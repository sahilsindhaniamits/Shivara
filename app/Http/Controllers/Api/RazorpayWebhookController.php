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
            case 'payment.authorized':
                // Magic Checkout sends this for COD orders
                $this->handlePaymentAuthorized($payload);
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

    /**
     * Handle payment.authorized - for COD orders via Magic Checkout
     * COD payments are in 'authorized' state (pending = COD payment to be collected on delivery)
     */
    private function handlePaymentAuthorized(array $payload)
    {
        $payment = $payload['payload']['payment']['entity'] ?? [];
        $orderId = $payment['order_id'] ?? null;
        $method = $payment['method'] ?? '';

        if ($orderId && $method === 'cod') {
            $order = \App\Models\Order::where('razorpay_order_id', $orderId)->first();
            if ($order && $order->payment_status === 'pending') {
                $order->update([
                    'payment_method' => 'cod',
                    'payment_status' => 'cod_pending',
                    'status' => 'confirmed',
                    'razorpay_payment_id' => $payment['id'] ?? null,
                ]);

                // Send confirmation email for COD orders
                $customerEmail = $order->address?->email ?? ($order->user?->email ?? null);
                if ($customerEmail) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($customerEmail)
                            ->send(new \App\Mail\OrderStatusMail($order->fresh(['items', 'address'])));
                    } catch (\Exception $e) {
                        Log::warning('COD order email failed: ' . $e->getMessage());
                    }
                }
            }
        }
    }
}
