<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Razorpay Webhook Handler
 * Receives events from Razorpay (payment captured, failed, etc.)
 */
class RazorpayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // GET request = URL validation by Razorpay
        if ($request->isMethod('get')) {
            return response()->json(['status' => 'ok', 'message' => 'Webhook endpoint active']);
        }

        $payload = $request->all();
        $event = $payload['event'] ?? '';

        Log::info('Razorpay Webhook: ' . $event, ['payload' => $payload]);

        switch ($event) {
            case 'payment.captured':
                $this->handlePaymentCaptured($payload);
                break;
            case 'payment.failed':
                $this->handlePaymentFailed($payload);
                break;
            case 'order.paid':
                $this->handleOrderPaid($payload);
                break;
        }

        return response()->json(['status' => 'ok']);
    }

    private function handlePaymentCaptured(array $payload)
    {
        $payment = $payload['payload']['payment']['entity'] ?? [];
        $orderId = $payment['order_id'] ?? null;

        if ($orderId) {
            $order = \App\Models\Order::where('razorpay_order_id', $orderId)->first();
            if ($order && $order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                    'razorpay_payment_id' => $payment['id'] ?? null,
                    'paid_at' => now(),
                ]);
            }
        }
    }

    private function handlePaymentFailed(array $payload)
    {
        $payment = $payload['payload']['payment']['entity'] ?? [];
        $orderId = $payment['order_id'] ?? null;

        if ($orderId) {
            $order = \App\Models\Order::where('razorpay_order_id', $orderId)->first();
            if ($order && $order->payment_status === 'pending') {
                $order->update(['payment_status' => 'failed']);
            }
        }
    }

    private function handleOrderPaid(array $payload)
    {
        // Same as payment.captured for most cases
        $this->handlePaymentCaptured($payload);
    }
}
