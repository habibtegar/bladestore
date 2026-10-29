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
        if (! Auth::check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan masuk terlebih dahulu untuk menambahkan produk ke keranjang.',
                    'redirect' => route('login'),
                ], 401);
            }

            return redirect()->guest(route('login'));
        }

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
            $navCartItems = Cart::with('product')
                ->where('user_id', Auth::id())
                ->get();
            $cartTotalCount = (int) $navCartItems->sum('quantity');
            $navCartTotal = (float) $navCartItems->sum(fn (Cart $item): float => (float) $item->subtotal);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil ditambahkan ke keranjang',
                'cart_count' => $cartTotalCount,
                'total_price' => $navCartTotal,
                'formatted_cart_total' => 'Rp '.number_format($navCartTotal, 0, ',', '.'),
                'sidebar_html' => view('cart.partials.offcanvas', compact('navCartItems', 'cartTotalCount', 'navCartTotal'))->render(),
            ]);
        }

        return redirect()->route('cart.index')->with('cart_added', 'Produk berhasil ditambahkan ke keranjang');
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request, Cart $cart): RedirectResponse|JsonResponse
    {
        if ($cart->user_id !== Auth::id()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses tidak diizinkan.',
                ], 403);
            }

            abort(403, 'Akses tidak diizinkan.');
        }

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'product_id' => ['nullable', 'exists:products,id'],
        ]);

        $quantity = (int) $validated['quantity'];
        $product = $cart->product;
        $stock = (int) ($product ? $product->stock : 0);

        if ($quantity > $stock) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah melebihi stok yang tersedia ('.$stock.' unit).',
                    'available_stock' => $stock,
                    'current_quantity' => $cart->quantity,
                ], 422);
            }

            return back()->with('error', 'Jumlah melebihi stok yang tersedia ('.$stock.').');
        }

        $cart->update(['quantity' => $quantity]);
        $cart->refresh();

        if ($request->expectsJson() || $request->ajax()) {
            $userCartItems = Cart::with('product')
                ->where('user_id', Auth::id())
                ->get();

            $totalPrice = (float) $userCartItems->sum(fn (Cart $item): float => (float) $item->subtotal);
            $totalQuantity = (int) $userCartItems->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Kuantitas produk berhasil diperbarui.',
                'item' => [
                    'id' => $cart->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'stock' => $stock,
                    'subtotal' => (float) $cart->subtotal,
                    'formatted_subtotal' => $cart->formatted_subtotal,
                ],
                'total_price' => $totalPrice,
                'formatted_total_price' => 'Rp '.number_format($totalPrice, 0, ',', '.'),
                'total_quantity' => $totalQuantity,
                'cart_count' => $totalQuantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Jumlah barang berhasil diperbarui.');
    }

    /**
     * Update cart quantity by cart_id or product_id via direct request.
     */
    public function updateQuantity(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'cart_id' => ['nullable', 'exists:carts,id'],
            'product_id' => ['nullable', 'exists:products,id'],
        ]);

        $cart = null;
        if (! empty($validated['cart_id'])) {
            $cart = Cart::where('user_id', Auth::id())->where('id', $validated['cart_id'])->first();
        } elseif (! empty($validated['product_id'])) {
            $cart = Cart::where('user_id', Auth::id())->where('product_id', $validated['product_id'])->first();
        }

        if (! $cart) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item keranjang tidak ditemukan.',
                ], 404);
            }

            return back()->with('error', 'Item keranjang tidak ditemukan.');
        }

        return $this->update($request, $cart);
    }

    /**
     * Remove an item from the shopping cart.
     */
    public function destroy(Request $request, Cart $cart): RedirectResponse|JsonResponse
    {
        if ($cart->user_id !== Auth::id()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses tidak diizinkan.',
                ], 403);
            }

            abort(403, 'Akses tidak diizinkan.');
        }

        $cart->delete();

        if ($request->expectsJson() || $request->ajax()) {
            $navCartItems = Cart::with('product')
                ->where('user_id', Auth::id())
                ->get();
            $cartTotalCount = (int) $navCartItems->sum('quantity');
            $navCartTotal = (float) $navCartItems->sum(fn (Cart $item): float => (float) $item->subtotal);

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil dihapus dari keranjang.',
                'cart_count' => $cartTotalCount,
                'total_price' => $navCartTotal,
                'formatted_cart_total' => 'Rp '.number_format($navCartTotal, 0, ',', '.'),
                'sidebar_html' => view('cart.partials.offcanvas', compact('navCartItems', 'cartTotalCount', 'navCartTotal'))->render(),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Barang berhasil dihapus dari keranjang.');
    }

    /**
     * Get updated sidebar HTML.
     */
    public function sidebarHtml(Request $request): JsonResponse
    {
        $navCartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();
        $cartTotalCount = (int) $navCartItems->sum('quantity');
        $navCartTotal = (float) $navCartItems->sum(fn (Cart $item): float => (float) $item->subtotal);

        return response()->json([
            'success' => true,
            'cart_count' => $cartTotalCount,
            'total_price' => $navCartTotal,
            'formatted_cart_total' => 'Rp '.number_format($navCartTotal, 0, ',', '.'),
            'sidebar_html' => view('cart.partials.offcanvas', compact('navCartItems', 'cartTotalCount', 'navCartTotal'))->render(),
        ]);
    }
}
