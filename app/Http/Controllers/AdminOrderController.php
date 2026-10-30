<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

        $totalRevenue = Order::paid()->sum('total_price');

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

        $newTrackingNumber = $request->filled('tracking_number') ? trim((string) $request->input('tracking_number')) : null;
        $requestedStatus = $validated['status'] ?? null;
        $newStatus = $requestedStatus ?: $order->status;

        // Validasi Alur Transisi Status & Resi
        if (! $order->isCod()) {
            // Untuk pesanan online (Non-COD):
            // Tidak boleh input resi atau ubah status ke 'Dikirim' jika belum bayar
            if (in_array($order->status, ['pending', 'belum_bayar'])) {
                if (! empty($newTrackingNumber)) {
                    return back()->with('error', 'Nomor resi tidak dapat diinput karena pesanan online masih berstatus Belum Bayar. Pembayaran harus lunas terlebih dahulu.');
                }

                if (in_array($newStatus, ['shipped', 'dikirim'])) {
                    return back()->with('error', 'Status pesanan tidak dapat diubah menjadi Dikirim karena pembayaran online belum lunas.');
                }
            }
        } else {
            // Untuk pesanan COD:
            // Alur yang jelas: Menunggu Diproses -> Dikemas -> Dikirim -> Selesai
            // Jika masih 'pending' (Menunggu Diproses), tidak boleh langsung lompat ke 'Dikirim' tanpa dikemas terlebih dahulu
            if (in_array($order->status, ['pending', 'belum_bayar']) && in_array($newStatus, ['shipped', 'dikirim'])) {
                return back()->with('error', 'Pesanan COD harus diproses ke status Dikemas terlebih dahulu sebelum diubah menjadi Dikirim.');
            }
        }

        // Jika nomor resi diinput manual oleh admin pada pesanan yang sedang dikemas, otomatis ubah status menjadi "Dikirim"
        if (! empty($newTrackingNumber)) {
            if (in_array($order->status, ['processing', 'dikemas']) || in_array($requestedStatus, ['processing', 'dikemas']) || empty($requestedStatus)) {
                $newStatus = 'shipped';
            }
        }

        // Ketentuan: Nomor resi simulasi hanya dibuat ketika pesanan benar-benar diubah menjadi Dikirim
        if (in_array($newStatus, ['shipped', 'dikirim'])) {
            if (empty($newTrackingNumber) && empty($order->tracking_number)) {
                $newTrackingNumber = 'BLADE-'.date('Ymd').'-'.strtoupper(Str::random(6));
            }
        }

        $updateData = ['status' => $newStatus];
        if ($newTrackingNumber !== null) {
            $updateData['tracking_number'] = $newTrackingNumber;
        } elseif ($request->has('tracking_number') && empty($newTrackingNumber) && ! in_array($newStatus, ['shipped', 'dikirim'])) {
            $updateData['tracking_number'] = null;
        }

        // Sinkronisasi status pembayaran berdasarkan alur:
        if (in_array($newStatus, ['completed', 'selesai'])) {
            // 7. Ketika pesanan selesai diterima dan pembayaran COD sudah dilakukan, ubah status pembayaran menjadi Sudah Dibayar
            $updateData['payment_status'] = 'paid';
        } elseif (in_array($newStatus, ['cancelled', 'dibatalkan'])) {
            // 8. Pesanan COD yang dibatalkan tidak boleh dianggap lunas
            $updateData['payment_status'] = 'cancelled';
        } elseif ($order->isCod()) {
            // Pesanan COD selama pending, processing, shipped belum dibayar
            $updateData['payment_status'] = 'unpaid';
        } elseif (in_array($newStatus, ['processing', 'dikemas', 'shipped', 'dikirim'])) {
            // Pesanan online yang sudah diproses berarti sudah bayar
            $updateData['payment_status'] = 'paid';
        } else {
            $updateData['payment_status'] = 'unpaid';
        }

        $order->update($updateData);
        $order->refresh();

        $feedback = "Pesanan #{$order->order_code} berhasil diperbarui.";
        if (! empty($order->tracking_number)) {
            $feedback .= " Nomor resi: {$order->tracking_number} tersimpan dan status menjadi '{$order->status_label}'.";
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
