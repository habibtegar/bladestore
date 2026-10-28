<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display the authenticated user's shopping cart.
     */
    public function index(): View
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $totalPrice = $cartItems->sum(fn (Cart $item): float => (float) $item->subtotal);

        return view('cart.index', compact('cartItems', 'totalPrice'));
    }

    /**
     * Add a product to the shopping cart.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        $existingQty = $cart ? $cart->quantity : 0;
        $requestedQty = (int) $validated['quantity'];
        $newQty = $existingQty + $requestedQty;

        if ($newQty > $product->stock) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi. Sisa stok: '.$product->stock.' unit.',
                ], 422);
            }

            return back()->with('error', 'Stok tidak mencukupi. Sisa stok: '.$product->stock.' unit.');
        }

        if ($cart) {
            $cart->update(['quantity' => $newQty]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $requestedQty,
            ]);
        }

        if ($request->expectsJson() || $request->ajax()) {
            $totalCount = (int) Cart::where('user_id', Auth::id())->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Berhasil ditambahkan ke keranjang',
                'cart_count' => $totalCount,
            ]);
        }

        return redirect()->route('cart.index')->with('cart_added', 'Produk berhasil ditambahkan ke keranjang');
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request, Cart $cart): RedirectResponse
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $quantity = (int) $validated['quantity'];

        if ($quantity > $cart->product->stock) {
            return back()->with('error', 'Jumlah melebihi stok yang tersedia ('.$cart->product->stock.').');
        }

        $cart->update(['quantity' => $quantity]);

        return redirect()->route('cart.index')->with('success', 'Jumlah barang berhasil diperbarui.');
    }

    /**
     * Remove an item from the shopping cart.
     */
    public function destroy(Cart $cart): RedirectResponse
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Barang berhasil dihapus dari keranjang.');
    }
}
