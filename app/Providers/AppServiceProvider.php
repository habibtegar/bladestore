<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // View Composer untuk membagikan data keranjang pengguna yang sedang login ke layout & sidebar
        View::composer(['layouts.app', 'cart.partials.offcanvas'], function ($view): void {
            if (Auth::check()) {
                $navCartItems = Cart::with('product')
                    ->where('user_id', Auth::id())
                    ->get();
                $cartTotalCount = (int) $navCartItems->sum('quantity');
                $navCartTotal = (float) $navCartItems->sum(fn (Cart $item): float => (float) $item->subtotal);
            } else {
                $navCartItems = collect();
                $cartTotalCount = 0;
                $navCartTotal = 0.0;
            }

            $view->with(compact('navCartItems', 'cartTotalCount', 'navCartTotal'));
        });
    }
}
