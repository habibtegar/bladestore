<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the main admin dashboard overview.
     */
    public function index(): View
    {
        $today = Carbon::today();

        // 1. Core KPIs
        $totalRevenue = (float) Order::paid()->sum('total_price');
        $revenueToday = (float) Order::paid()->whereDate('created_at', $today)->sum('total_price');
        $totalOrdersCount = (int) Order::count();
        $pendingOrdersCount = (int) Order::whereIn('status', ['pending', 'belum_bayar'])->count();
        $processingOrdersCount = (int) Order::whereIn('status', ['processing', 'dikemas'])->count();
        $totalProductsCount = (int) Product::count();

        // 2. Revenue Trend (Last 7 Days) for Dashboard Chart
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $recentPaidOrders = Order::paid()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get(['created_at', 'total_price']);

        $dailyTotals = $recentPaidOrders
            ->groupBy(fn (Order $order): string => $order->created_at ? $order->created_at->format('Y-m-d') : '')
            ->map(fn ($group): float => (float) $group->sum('total_price'));

        $chartLabels = [];
        $chartData = [];

        $period = CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $chartLabels[] = $date->translatedFormat('d M');
            $chartData[] = (float) ($dailyTotals->get($dateString, 0));
        }

        // 3. Recent Orders (Latest 6)
        $recentOrders = Order::with(['user', 'items.product'])
            ->latest('created_at')
            ->latest('id')
            ->take(6)
            ->get();

        // 4. Low Stock Products (Stock <= 5)
        $lowStockProducts = Product::where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();
        $lowStockCount = Product::where('stock', '<=', 5)->count();

        // 5. Status counts for quick distribution pill
        $completedOrdersCount = Order::whereIn('status', ['completed', 'selesai'])->count();
        $shippedOrdersCount = Order::whereIn('status', ['shipped', 'dikirim'])->count();

        $lastUpdated = Carbon::now()->translatedFormat('d M Y, H:i').' WIB';

        return view('admin.dashboard.index', compact(
            'totalRevenue',
            'revenueToday',
            'totalOrdersCount',
            'pendingOrdersCount',
            'processingOrdersCount',
            'totalProductsCount',
            'chartLabels',
            'chartData',
            'recentOrders',
            'lowStockProducts',
            'lowStockCount',
            'completedOrdersCount',
            'shippedOrdersCount',
            'lastUpdated'
        ));
    }
}
