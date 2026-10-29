<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
    public function process(Request $request): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson() || $request->ajax()) {
            return $this->createSnapToken($request);
        }

        // Mendukung variasi nama field form (misal: phone/customer_phone/no_hp, address/shipping_address/alamat)
        $phone = $request->input('customer_phone') ?? $request->input('phone') ?? $request->input('no_hp') ?? $request->input('telepon');
        $address = $request->input('shipping_address') ?? $request->input('address') ?? $request->input('alamat') ?? $request->input('alamat_pengiriman');
        $paymentMethod = $request->input('payment_method') ?? $request->input('metode_pembayaran') ?? 'Midtrans Payment Gateway';
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

        // Ambil data keranjang milik user yang sedang login (saring berdasarkan produk terpilih jika ada)
        $selectedCartIds = $request->input('cart_ids') ?? $request->input('selected_cart_ids');
        if (is_string($selectedCartIds)) {
            $selectedCartIds = array_filter(explode(',', $selectedCartIds));
        }

        $cartQuery = Cart::with('product')->where('user_id', Auth::id());
        if (! empty($selectedCartIds) && is_array($selectedCartIds)) {
            $cartQuery->whereIn('id', $selectedCartIds);
        }
        $cartItems = $cartQuery->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Silakan pilih minimal satu produk di keranjang untuk checkout.');
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

            // 6. Hapus hanya produk yang di-checkout dari tabel carts
            $checkoutItemIds = $cartItems->pluck('id')->all();
            Cart::where('user_id', Auth::id())->whereIn('id', $checkoutItemIds)->delete();

            return $order;
        });

        // Request snap token jika server key tersedia
        $snapResult = $this->requestMidtransSnapToken($order);
        if ($snapResult['success'] && ! empty($snapResult['token'])) {
            $order->update(['snap_token' => $snapResult['token']]);
        }

        // 7. Arahkan user ke halaman sukses
        return redirect()->route('checkout.success', $order->id)
            ->with('success', 'Pesanan Anda berhasil dibuat!');
    }

    /**
     * Request Midtrans Snap Token from current user's cart.
     */
    public function createSnapToken(Request $request): JsonResponse
    {
        $phone = $request->input('customer_phone') ?? $request->input('phone') ?? $request->input('no_hp') ?? $request->input('telepon');
        $address = $request->input('shipping_address') ?? $request->input('address') ?? $request->input('alamat') ?? $request->input('alamat_pengiriman');
        $paymentMethod = $request->input('payment_method') ?? $request->input('metode_pembayaran') ?? 'Midtrans Payment Gateway';
        $notes = $request->input('notes') ?? $request->input('catatan');

        $request->merge([
            'customer_phone' => $phone,
            'shipping_address' => $address,
            'payment_method' => $paymentMethod,
            'notes' => $notes,
        ]);

        $validated = $request->validate([
            'customer_phone' => ['required', 'string', 'min:8', 'max:25'],
            'shipping_address' => ['required', 'string', 'min:5'],
            'payment_method' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:500'],
            'cart_ids' => ['nullable'],
        ], [
            'customer_phone.required' => 'Nomor telepon wajib diisi.',
            'customer_phone.min' => 'Nomor telepon minimal 8 karakter.',
            'shipping_address.required' => 'Alamat pengiriman wajib diisi.',
            'shipping_address.min' => 'Alamat pengiriman minimal 5 karakter.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        // Ambil data keranjang milik user yang sedang login (saring berdasarkan produk terpilih jika ada)
        $selectedCartIds = $request->input('cart_ids') ?? $request->input('selected_cart_ids');
        if (is_string($selectedCartIds)) {
            $selectedCartIds = array_filter(explode(',', $selectedCartIds));
        }

        $cartQuery = Cart::with('product')->where('user_id', Auth::id());
        if (! empty($selectedCartIds) && is_array($selectedCartIds)) {
            $cartQuery->whereIn('id', $selectedCartIds);
        }
        $cartItems = $cartQuery->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan pilih minimal satu produk di keranjang untuk checkout.',
            ], 422);
        }

        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok produk '{$item->product->name}' tidak mencukupi (sisa {$item->product->stock} unit).",
                ], 422);
            }
        }

        $order = DB::transaction(function () use ($cartItems, $validated): Order {
            do {
                $orderCode = 'ORD-'.date('Ymd').'-'.strtoupper(Str::random(6));
            } while (Order::where('order_code', $orderCode)->exists());

            $totalPrice = $cartItems->sum(fn (Cart $item): float => (float) $item->subtotal);

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

            // 6. Hapus hanya produk yang di-checkout dari tabel carts
            $checkoutItemIds = $cartItems->pluck('id')->all();
            Cart::where('user_id', Auth::id())->whereIn('id', $checkoutItemIds)->delete();

            return $order;
        });

        $snapResult = $this->requestMidtransSnapToken($order);

        if (! $snapResult['success']) {
            return response()->json([
                'success' => false,
                'message' => $snapResult['message'] ?? 'Gagal membuat transaksi di Midtrans.',
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'redirect_url' => route('checkout.success', $order->id),
            ], 422);
        }

        $order->update(['snap_token' => $snapResult['token']]);

        return response()->json([
            'success' => true,
            'snap_token' => $snapResult['token'],
            'redirect_url' => route('checkout.success', $order->id),
            'order_id' => $order->id,
            'order_code' => $order->order_code,
        ]);
    }

    /**
     * Request a Snap Token from the Midtrans API.
     *
     * @return array{success: bool, token?: string, redirect_url?: string, message?: string}
     */
    protected function requestMidtransSnapToken(Order $order): array
    {
        $serverKey = (string) config('midtrans.server_key', '');
        $order->loadMissing(['user', 'items.product']);
        $user = $order->user;

        $payload = [
            'transaction_details' => [
                'order_id' => $order->order_code,
                'gross_amount' => (int) round((float) $order->total_price),
            ],
            'customer_details' => [
                'first_name' => $user->name ?? 'Pelanggan',
                'email' => $user->email ?? 'pelanggan@example.com',
                'phone' => $order->customer_phone ?? '',
                'shipping_address' => [
                    'first_name' => $user->name ?? 'Pelanggan',
                    'address' => $order->shipping_address ?? '',
                    'phone' => $order->customer_phone ?? '',
                ],
            ],
            'item_details' => $order->items->map(function (OrderItem $item): array {
                return [
                    'id' => (string) $item->product_id,
                    'price' => (int) round((float) $item->price),
                    'quantity' => (int) $item->quantity,
                    'name' => mb_substr((string) ($item->product->name ?? 'Produk'), 0, 50),
                ];
            })->values()->all(),
        ];

        // Jika dalam testing/mock atau key dummy tanpa koneksi API nyata
        if (empty($serverKey)) {
            return [
                'success' => false,
                'message' => 'MIDTRANS_SERVER_KEY belum diatur di file .env.',
            ];
        }

        try {
            $response = Http::withBasicAuth($serverKey, '')
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->timeout(15)
                ->post(config('midtrans.api_url'), $payload);

            if ($response->successful()) {
                $token = (string) $response->json('token', '');
                $redirectUrl = (string) $response->json('redirect_url', '');

                return [
                    'success' => true,
                    'token' => $token,
                    'redirect_url' => $redirectUrl,
                ];
            }

            $errorMsg = $response->json('error_messages.0')
                ?? $response->json('message')
                ?? "Gagal menghubungi Midtrans (HTTP {$response->status()}).";

            Log::error('Midtrans Snap API Error', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            return [
                'success' => false,
                'message' => $errorMsg,
            ];
        } catch (\Throwable $e) {
            Log::error('Midtrans Exception: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat menghubungi payment gateway Midtrans.',
            ];
        }
    }

    /**
     * Display order success page (checkout-success.blade.php).
     */
    public function success(Order $order): View
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        // Sinkronkan status pesanan secara real-time dari Midtrans jika status masih pending
        if (! app()->environment('testing') && in_array($order->status, ['pending', 'belum_bayar']) && ! empty($order->snap_token)) {
            $this->queryAndSyncOrderWithMidtrans($order);
            $order->refresh();
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
        // Otomatis batalkan pesanan pending yang melebihi 24 jam (kadaluarsa)
        Order::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'belum_bayar'])
            ->where('created_at', '<=', now()->subHours(24))
            ->update(['status' => 'cancelled']);

        // Sinkronkan status pesanan pending aktif secara real-time dari Midtrans API
        if (! app()->environment('testing')) {
            $pendingOrders = Order::where('user_id', Auth::id())
                ->whereIn('status', ['pending', 'belum_bayar'])
                ->whereNotNull('snap_token')
                ->latest()
                ->limit(5)
                ->get();

            foreach ($pendingOrders as $pendingOrder) {
                $this->queryAndSyncOrderWithMidtrans($pendingOrder);
            }
        }

        $currentStatus = $request->query('status', 'all');

        $query = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->latest();

        if ($currentStatus && $currentStatus !== 'all') {
            if ($currentStatus === 'pending' || $currentStatus === 'belum_bayar') {
                $query->whereIn('status', ['pending', 'belum_bayar']);
            } elseif ($currentStatus === 'processing' || $currentStatus === 'dikemas') {
                $query->whereIn('status', ['processing', 'dikemas']);
            } elseif ($currentStatus === 'shipped' || $currentStatus === 'dikirim') {
                $query->whereIn('status', ['shipped', 'dikirim']);
            } elseif ($currentStatus === 'completed' || $currentStatus === 'selesai') {
                $query->whereIn('status', ['completed', 'selesai']);
            } else {
                $query->where('status', $currentStatus);
            }
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('orders.index', compact('orders', 'currentStatus'));
    }

    /**
     * Get or create Midtrans Snap Token for an existing order.
     */
    public function getOrderSnapToken(Order $order): JsonResponse
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses tidak diizinkan.',
            ], 403);
        }

        // Cek apakah pesanan sudah kadaluarsa (lebih dari 24 jam)
        if ($order->isExpired()) {
            if (! in_array($order->status, ['cancelled', 'dibatalkan'])) {
                $order->update(['status' => 'cancelled']);
            }

            return response()->json([
                'success' => false,
                'message' => 'Batas waktu pembayaran pesanan ini telah kadaluarsa (24 jam). Pesanan telah dibatalkan.',
                'is_expired' => true,
            ], 422);
        }

        if (! in_array($order->status, ['pending', 'belum_bayar'])) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini sudah tidak berstatus Belum Bayar.',
            ], 422);
        }

        // Jika snap_token sudah tersimpan sebelumnya di database, gunakan kembali
        if (! empty($order->snap_token)) {
            return response()->json([
                'success' => true,
                'snap_token' => $order->snap_token,
                'order_code' => $order->order_code,
            ]);
        }

        // Jika belum ada snap_token, minta token ke Midtrans API
        $snapResult = $this->requestMidtransSnapToken($order);

        if (! $snapResult['success'] || empty($snapResult['token'])) {
            return response()->json([
                'success' => false,
                'message' => $snapResult['message'] ?? 'Gagal membuat token pembayaran Midtrans.',
            ], 422);
        }

        $order->update(['snap_token' => $snapResult['token']]);

        return response()->json([
            'success' => true,
            'snap_token' => $snapResult['token'],
            'order_code' => $order->order_code,
        ]);
    }

    /**
     * Display order detail.
     */
    public function show(Order $order): View
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        // Otomatis batalkan pesanan pending yang melebihi 24 jam
        if (in_array($order->status, ['pending', 'belum_bayar']) && $order->created_at->diffInHours(now()) >= 24) {
            $order->update(['status' => 'cancelled']);
        }

        // Sinkronkan status pesanan aktif jika masih pending
        if (! app()->environment('testing') && in_array($order->status, ['pending', 'belum_bayar']) && ! empty($order->snap_token)) {
            $this->queryAndSyncOrderWithMidtrans($order);
            $order->refresh();
        }

        $order->load(['items.product', 'user']);

        return view('orders.show', compact('order'));
    }

    /**
     * Check and sync order status directly from Midtrans API.
     */
    public function syncStatus(Request $request, Order $order): JsonResponse
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses tidak diizinkan.',
            ], 403);
        }

        $wasUpdated = false;

        // Cek jika client mengirimkan callback payload Snap hasil pembayaran sukses
        $snapStatus = (string) ($request->input('transaction_status') ?? $request->input('snap_result.transaction_status') ?? '');
        $snapStatusCode = (string) ($request->input('status_code') ?? $request->input('snap_result.status_code') ?? '');
        $snapOrderId = (string) ($request->input('order_id') ?? $request->input('snap_result.order_id') ?? '');

        if (! empty($snapOrderId) && $snapOrderId === $order->order_code) {
            if ($snapStatus === 'settlement' || ($snapStatus === 'capture' && ($request->input('fraud_status') ?? 'accept') === 'accept') || $snapStatusCode === '200') {
                if (in_array($order->status, ['pending', 'belum_bayar'])) {
                    $order->update(['status' => 'processing']);
                    $wasUpdated = true;
                }
            }
        }

        // Sinkronkan juga langsung dengan Midtrans API server-to-server
        $apiUpdated = $this->queryAndSyncOrderWithMidtrans($order);
        if ($apiUpdated) {
            $wasUpdated = true;
        }

        $order->refresh();

        return response()->json([
            'success' => true,
            'message' => $wasUpdated ? 'Status pesanan berhasil disinkronkan dengan Midtrans.' : 'Status pesanan sudah sesuai.',
            'order_code' => $order->order_code,
            'status' => $order->status,
            'status_label' => $order->status_label,
            'can_be_paid' => $order->canBePaid(),
            'updated' => $wasUpdated,
        ]);
    }

    /**
     * Helper to query Midtrans Status API and update order status in DB.
     */
    public function queryAndSyncOrderWithMidtrans(Order $order): bool
    {
        $serverKey = (string) config('midtrans.server_key', '');
        if (empty($serverKey) || $serverKey === 'SB-Mid-server-demo-key') {
            return false;
        }

        $statusBaseUrl = config('midtrans.is_production', false)
            ? 'https://api.midtrans.com/v2'
            : 'https://api.sandbox.midtrans.com/v2';

        try {
            $response = Http::withBasicAuth($serverKey, '')
                ->withHeaders(['Accept' => 'application/json'])
                ->timeout(5)
                ->get("{$statusBaseUrl}/{$order->order_code}/status");

            if ($response->successful()) {
                $transactionStatus = (string) $response->json('transaction_status', '');
                $fraudStatus = (string) $response->json('fraud_status', '');

                $newStatus = $order->status;
                if ($transactionStatus === 'settlement') {
                    $newStatus = 'processing';
                } elseif ($transactionStatus === 'capture') {
                    $newStatus = ($fraudStatus === 'challenge') ? 'pending' : 'processing';
                } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'], true)) {
                    $newStatus = 'cancelled';
                } elseif ($transactionStatus === 'pending') {
                    $newStatus = 'pending';
                }

                if ($newStatus !== $order->status) {
                    $order->update(['status' => $newStatus]);

                    return true;
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Midtrans Status Check Error for order {$order->order_code}: ".$e->getMessage());
        }

        return false;
    }
}
