<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminRevenueController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Guest Authentication Routes
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/demo-login', [AuthController::class, 'demoLogin'])->name('demo.login');
    Route::get('/demo-admin-login', [AuthController::class, 'demoAdminLogin'])->name('demo.admin-login');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes (Cart & Checkout)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function (): void {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Cart Management
    Route::prefix('cart')->name('cart.')->group(function (): void {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/', [CartController::class, 'store'])->name('store');
        Route::match(['patch', 'post'], '/{cart}', [CartController::class, 'update'])->name('update');
        Route::post('/update-quantity', [CartController::class, 'updateQuantity'])->name('update-quantity');
        Route::get('/sidebar', [CartController::class, 'sidebarHtml'])->name('sidebar');
        Route::delete('/{cart}', [CartController::class, 'destroy'])->name('destroy');
    });

    // Checkout
    Route::prefix('checkout')->name('checkout.')->group(function (): void {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/', [CheckoutController::class, 'process'])->name('process');
        Route::post('/process', [CheckoutController::class, 'process']);
        Route::post('/snap-token', [CheckoutController::class, 'createSnapToken'])->name('snap-token');
        Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
    });
    Route::get('/checkout-success/{order}', [CheckoutController::class, 'success']);

    // Customer Profile & Address Management
    Route::prefix('profile')->name('profile.')->group(function (): void {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::match(['put', 'patch'], '/', [ProfileController::class, 'update'])->name('update');
        Route::match(['put', 'patch'], '/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    });

    Route::prefix('addresses')->name('addresses.')->group(function (): void {
        Route::post('/', [AddressController::class, 'store'])->name('store');
        Route::match(['put', 'patch'], '/{address}', [AddressController::class, 'update'])->name('update');
        Route::delete('/{address}', [AddressController::class, 'destroy'])->name('destroy');
        Route::match(['post', 'patch'], '/{address}/primary', [AddressController::class, 'setPrimary'])->name('set-primary');
    });

    // Order History & Details (Customer)
    Route::prefix('orders')->name('orders.')->group(function (): void {
        Route::get('/', [CheckoutController::class, 'orders'])->name('index');
        Route::match(['get', 'post'], '/{order}/snap-token', [CheckoutController::class, 'getOrderSnapToken'])->name('snap-token');
        Route::match(['get', 'post'], '/{order}/sync-status', [CheckoutController::class, 'syncStatus'])->name('sync-status');
        Route::get('/{order}', [CheckoutController::class, 'show'])->name('show');
    });

    // Admin Suite Management (Orders, Revenue & Products - Protected with 'admin' middleware)
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function (): void {
        Route::redirect('/', '/admin/dashboard');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/revenue', [AdminRevenueController::class, 'index'])->name('revenue.index');
        Route::get('/revenue/export', [AdminRevenueController::class, 'exportCsv'])->name('revenue.export');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::match(['patch', 'put', 'post'], '/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::match(['patch', 'put', 'post'], '/orders/{order}/tracking', [AdminOrderController::class, 'updateTracking'])->name('orders.update-tracking');
        Route::resource('products', AdminProductController::class)->except(['show']);
    });
});
