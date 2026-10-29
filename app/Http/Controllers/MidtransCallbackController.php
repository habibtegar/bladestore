<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    /**
     * Handle incoming Midtrans Webhook Notification.
     */
    public function handle(Request $request): JsonResponse
    {
        $serverKey = (string) config('midtrans.server_key', '');
        $orderId = (string) $request->input('order_id', '');
        $statusCode = (string) $request->input('status_code', '');
        $grossAmount = (string) $request->input('gross_amount', '');
        $signatureKey = (string) $request->input('signature_key', '');
        $transactionStatus = (string) $request->input('transaction_status', '');
        $fraudStatus = (string) $request->input('fraud_status', '');

        if (empty($orderId)) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter order_id tidak ditemukan.',
            ], 400);
        }

        // Verifikasi keaslian webhook menggunakan signature key Midtrans
        // Formula: SHA512(order_id + status_code + gross_amount + server_key)
        if (! empty($serverKey) && $serverKey !== 'SB-Mid-server-demo-key' && ! empty($signatureKey)) {
            $expectedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);
            if (! hash_equals($expectedSignature, $signatureKey)) {
                Log::warning('Midtrans Webhook: Invalid signature key', [
                    'order_id' => $orderId,
                    'received' => $signatureKey,
                    'expected' => $expectedSignature,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Signature key tidak valid.',
                ], 403);
            }
        }

        $order = Order::where('order_code', $orderId)->first();

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => "Pesanan dengan nomor {$orderId} tidak ditemukan.",
            ], 404);
        }

        $newStatus = $order->status;

        // Pemetaan status transaksi Midtrans ke status pesanan database:
        // - settlement / capture (accept): 'processing' (Sedang Diproses / Dikemas)
        // - pending: 'pending' (Menunggu Pembayaran)
        // - deny / expire / cancel: 'cancelled' (Dibatalkan)
        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'challenge') {
                $newStatus = 'pending';
            } elseif ($fraudStatus === 'accept') {
                $newStatus = 'processing';
            }
        } elseif ($transactionStatus === 'settlement') {
            $newStatus = 'processing';
        } elseif ($transactionStatus === 'pending') {
            $newStatus = 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'], true)) {
            $newStatus = 'cancelled';
        }

        // Jangan mengubah pesanan yang sudah berstatus 'completed'
        if ($order->status !== 'completed') {
            $order->update([
                'status' => $newStatus,
            ]);
        }

        Log::info('Midtrans Webhook: Status pesanan berhasil diperbarui', [
            'order_id' => $order->id,
            'order_code' => $order->order_code,
            'transaction_status' => $transactionStatus,
            'status' => $order->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi Midtrans berhasil diproses.',
            'order_id' => $order->id,
            'order_code' => $order->order_code,
            'status' => $order->status,
        ]);
    }
}
