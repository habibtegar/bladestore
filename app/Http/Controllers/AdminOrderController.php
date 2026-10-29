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
        $period = $request->query('period');

        $orders = Order::with(['user', 'items.product'])
            ->when($status && $status !== 'all', function ($query) use ($status): void {
                if (in_array($status, ['pending', 'belum_bayar'])) {
                    $query->whereIn('status', ['pending', 'belum_bayar']);
                } elseif (in_array($status, ['processing', 'dikemas'])) {
                    $query->whereIn('status', ['processing', 'dikemas']);
                } elseif (in_array($status, ['shipped', 'dikirim'])) {
                    $query->whereIn('status', ['shipped', 'dikirim']);
                } elseif (in_array($status, ['completed', 'selesai'])) {
                    $query->whereIn('status', ['completed', 'selesai']);
                } elseif (in_array($status, ['cancelled', 'dibatalkan'])) {
                    $query->whereIn('status', ['cancelled', 'dibatalkan']);
                } else {
                    $query->where('status', $status);
                }
            })
            ->when($period && $period !== 'all', function ($query) use ($period): void {
                if ($period === 'today') {
                    $query->whereDate('created_at', today());
                } elseif ($period === 'week') {
                    $query->where('created_at', '>=', now()->subDays(7));
                } elseif ($period === 'month') {
                    $query->where('created_at', '>=', now()->subDays(30));
                }
            })
            ->when($search, function ($query, $search): void {
                $query->where(function ($q) use ($search): void {
                    $q->where('order_code', 'like', "%{$search}%")
                        ->orWhere('tracking_number', 'like', "%{$search}%")
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
            'pending' => Order::whereIn('status', ['pending', 'belum_bayar'])->count(),
            'processing' => Order::whereIn('status', ['processing', 'dikemas'])->count(),
            'shipped' => Order::whereIn('status', ['shipped', 'dikirim'])->count(),
            'completed' => Order::whereIn('status', ['completed', 'selesai'])->count(),
            'cancelled' => Order::whereIn('status', ['cancelled', 'dibatalkan'])->count(),
        ];

        $totalRevenue = Order::whereIn('status', ['completed', 'selesai'])->sum('total_price');

        return view('admin.orders.index', compact('orders', 'status', 'search', 'period', 'statusCounts', 'totalRevenue'));
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
     * Update the status and/or tracking number of the specified order.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['nullable', 'string', 'in:pending,processing,shipped,completed,cancelled,belum_bayar,dikemas,dikirim,selesai,dibatalkan'],
            'tracking_number' => ['nullable', 'string', 'max:100'],
        ], [
            'status.in' => 'Status pesanan tidak valid.',
            'tracking_number.max' => 'Nomor resi maksimal 100 karakter.',
        ]);

        $newTrackingNumber = $request->filled('tracking_number') ? trim($request->input('tracking_number')) : null;
        $requestedStatus = $validated['status'] ?? null;
        $newStatus = $requestedStatus ?: $order->status;

        // Pembatasan: Matikan input nomor resi dan pengiriman jika pesanan masih 'Belum Bayar'
        if (in_array($order->status, ['pending', 'belum_bayar'])) {
            if (! empty($newTrackingNumber)) {
                return back()->with('error', 'Nomor resi tidak dapat diinput karena pesanan masih berstatus Belum Bayar. Input nomor resi hanya diizinkan setelah pembayaran diterima (status Dikemas).');
            }

            if (in_array($newStatus, ['shipped', 'dikirim'])) {
                return back()->with('error', 'Status pesanan tidak dapat diubah menjadi Dikirim karena pesanan belum dibayar.');
            }
        }

        // Otomatis ubah status pesanan menjadi "Dikirim" (shipped) saat nomor resi dimasukkan pada pesanan yang sudah dikemas
        if (! empty($newTrackingNumber)) {
            if (in_array($order->status, ['processing', 'dikemas']) || in_array($requestedStatus, ['processing', 'dikemas']) || empty($requestedStatus)) {
                $newStatus = 'shipped';
            }
        }

        $updateData = ['status' => $newStatus];
        if ($request->has('tracking_number')) {
            $updateData['tracking_number'] = $newTrackingNumber;
        }

        $order->update($updateData);
        $order->refresh();

        $feedback = "Pesanan #{$order->order_code} berhasil diperbarui.";
        if ($newTrackingNumber) {
            $feedback .= " Nomor resi: {$newTrackingNumber} tersimpan dan status menjadi '{$order->status_label}'.";
        } else {
            $feedback .= " Status pesanan saat ini: '{$order->status_label}'.";
        }

        return back()->with('success', $feedback);
    }

    /**
     * Dedicated action for updating shipping tracking receipt number.
     */
    public function updateTracking(Request $request, Order $order): RedirectResponse
    {
        return $this->updateStatus($request, $order);
    }
}
