<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of all orders, sorted by latest.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $orders = Order::with(['user', 'items.product'])
            ->when($status, function ($query, $status): void {
                $query->where('status', $status);
            })
            ->when($search, function ($query, $search): void {
                $query->where(function ($q) use ($search): void {
                    $q->where('order_code', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($uq) use ($search): void {
                            $uq->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('created_at')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        // Count summaries for status badges/tabs
        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        $totalRevenue = Order::where('status', 'completed')->sum('total_price');

        return view('admin.orders.index', compact('orders', 'status', 'search', 'statusCounts', 'totalRevenue'));
    }

    /**
     * Display the specified order details with order_items and products.
     */
    public function show(Order $order): View
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the status of the specified order.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,processing,completed,cancelled'],
        ], [
            'status.required' => 'Pilih status pesanan terlebih dahulu.',
            'status.in' => 'Status pesanan tidak valid.',
        ]);

        $previousStatus = $order->status;
        $newStatus = $validated['status'];

        $order->update([
            'status' => $newStatus,
        ]);

        return back()->with(
            'success',
            "Status pesanan #{$order->order_code} berhasil diperbarui dari '{$previousStatus}' menjadi '{$newStatus}'."
        );
    }
}
