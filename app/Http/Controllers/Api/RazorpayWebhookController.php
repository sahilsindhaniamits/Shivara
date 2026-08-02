<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Razorpay Webhook Handler
 * Receives events from Razorpay (payment captured, failed, etc.)
 * Verifies webhook signature to prevent forged requests.
 */
class RazorpayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // GET request = URL validation by Razorpay
        if ($request->isMethod('get')) {
            return response()->json(['status' => 'ok', 'message' => 'Webhook endpoint active']);
        }

        // Verify webhook signature (CRITICAL: prevents forged payment events)
        $webhookSecret = config('services.razorpay.webhook_secret');
        if ($webhookSecret) {
            $signature = $request->header('X-Razorpay-Signature');
            if (!$signature) {
                Log::warning('Razorpay Webhook: Missing signature header');
                return response()->json(['status' => 'error', 'message' => 'Missing signature'], 401);
            }

            $payload = $request->getContent();
            $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);

            if (!hash_equals($expectedSignature, $signature)) {
                Log::warning('Razorpay Webhook: Invalid signature', [
                    'ip' => $request->ip(),
                    'expected' => substr($expectedSignature, 0, 10) . '...',
                    'received' => substr($signature, 0, 10) . '...',
                ]);
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 401);
            }
        } else {
            Log::warning('Razorpay Webhook: No webhook secret configured - signature not verified!');
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
                    'status' => $order->status === 'pending' ? 'confirmed' : $order->status,
                    'razorpay_payment_id' => $payment['id'] ?? null,
                    'paid_at' => now(),
                ]);
                Log::info('Webhook: Order confirmed via payment.captured', ['order' => $order->order_number]);
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

                // Rollback stock for failed payments
                $this->rollbackStock($order);

                Log::info('Webhook: Payment failed, stock rolled back', ['order' => $order->order_number]);
            }
        }
    }

    private function handleOrderPaid(array $payload)
    {
        // Same as payment.captured for most cases
        $this->handlePaymentCaptured($payload);
    }

    /**
     * Rollback stock when a payment fails or order is abandoned
     */
    private function rollbackStock(\App\Models\Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->variant_id) {
                $variant = \App\Models\ProductVariant::find($item->variant_id);
                if ($variant) {
                    $variant->increment('stock', $item->quantity);
                }
            } elseif ($item->product_id) {
                $product = \App\Models\Product::find($item->product_id);
                if ($product && !is_null($product->stock)) {
                    $product->increment('stock', $item->quantity);
                }
            }
        }
    }
}
