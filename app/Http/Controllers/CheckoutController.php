<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Display the checkout summary page.
     */
    public function index(): View|RedirectResponse
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $totalPrice = $cartItems->sum(fn (Cart $item): float => (float) $item->subtotal);

        return view('checkout.index', compact('cartItems', 'totalPrice'));
    }

    /**
     * Process the checkout request using DB::transaction.
     */
    public function process(Request $request): RedirectResponse
    {
        // Mendukung variasi nama field form (misal: phone/customer_phone/no_hp, address/shipping_address/alamat)
        $phone = $request->input('customer_phone') ?? $request->input('phone') ?? $request->input('no_hp') ?? $request->input('telepon');
        $address = $request->input('shipping_address') ?? $request->input('address') ?? $request->input('alamat') ?? $request->input('alamat_pengiriman');
        $paymentMethod = $request->input('payment_method') ?? $request->input('metode_pembayaran') ?? 'Transfer Bank Manual';
        $notes = $request->input('notes') ?? $request->input('catatan');

        $request->merge([
            'customer_phone' => $phone,
            'shipping_address' => $address,
            'payment_method' => $paymentMethod,
            'notes' => $notes,
        ]);

        // 1. Validasi data pengiriman & metode pembayaran
        $validated = $request->validate([
            'customer_phone' => ['required', 'string', 'min:8', 'max:25'],
            'shipping_address' => ['required', 'string', 'min:5'],
            'payment_method' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'customer_phone.required' => 'Nomor telepon wajib diisi.',
            'customer_phone.min' => 'Nomor telepon minimal 8 karakter.',
            'shipping_address.required' => 'Alamat pengiriman wajib diisi.',
            'shipping_address.min' => 'Alamat pengiriman minimal 5 karakter.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        // Ambil data keranjang milik user yang sedang login
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        // Validasi ketersediaan stok produk sebelum checkout
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->route('cart.index')->with(
                    'error',
                    "Stok produk '{$item->product->name}' tidak mencukupi (sisa {$item->product->stock} unit)."
                );
            }
        }

        // 2. Gunakan DB::transaction untuk menjamin integritas data
        $order = DB::transaction(function () use ($cartItems, $validated): Order {
            // Generate nomor pesanan unik
            do {
                $orderCode = 'ORD-'.date('Ymd').'-'.strtoupper(Str::random(6));
            } while (Order::where('order_code', $orderCode)->exists());

            // Hitung total harga
            $totalPrice = $cartItems->sum(fn (Cart $item): float => (float) $item->subtotal);

            // 3. Buat record baru di tabel orders dengan status pending
            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => Auth::id(),
                'total_price' => $totalPrice,
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
                'shipping_address' => $validated['shipping_address'],
                'customer_phone' => $validated['customer_phone'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // 4. Pindahkan semua data dari tabel carts ke tabel order_items
            // 5. Kurangi kolom stock pada tabel products sesuai jumlah barang yang dibeli
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->final_price,
                    'subtotal' => $item->subtotal,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            // 6. Hapus semua data keranjang milik user tersebut di tabel carts
            Cart::where('user_id', Auth::id())->delete();

            return $order;
        });

        // 7. Arahkan user ke halaman sukses
        return redirect()->route('checkout.success', $order->id)
            ->with('success', 'Pesanan Anda berhasil dibuat!');
    }

    /**
     * Display order success page (checkout-success.blade.php).
     */
    public function success(Order $order): View
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $order->load(['items.product', 'user']);

        // Gunakan view checkout-success jika ada, atau fallback ke checkout.success
        if (view()->exists('checkout-success')) {
            return view('checkout-success', compact('order'));
        }

        return view('checkout.success', compact('order'));
    }

    /**
     * Display order history for authenticated user.
     */
    public function orders(Request $request): View
    {
        $currentStatus = $request->query('status', 'all');

        $query = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->latest();

        if ($currentStatus && $currentStatus !== 'all') {
            if ($currentStatus === 'pending') {
                $query->where('status', 'pending');
            } elseif ($currentStatus === 'processing') {
                $query->whereIn('status', ['processing', 'dikemas']);
            } elseif ($currentStatus === 'shipped') {
                $query->whereIn('status', ['shipped', 'dikirim']);
            } elseif ($currentStatus === 'completed') {
                $query->whereIn('status', ['completed', 'selesai']);
            } else {
                $query->where('status', $currentStatus);
            }
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('orders.index', compact('orders', 'currentStatus'));
    }

    /**
     * Display order detail.
     */
    public function show(Order $order): View
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $order->load(['items.product', 'user']);

        return view('orders.show', compact('order'));
    }
}
