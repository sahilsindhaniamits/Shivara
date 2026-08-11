<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VelocityWebhookController extends Controller
{
    /**
     * Handle Velocity shipping webhook
     * Velocity calls this when order status changes (courier assigned, shipped, delivered, etc.)
     *
     * Configure this URL in Velocity Dashboard:
     * https://theshivara.com/api/velocity/webhook
     */
    public function handle(Request $request)
    {
        $data = $request->all();
        Log::info('Velocity webhook received', $data);

        // Try to find order by order_id field
        $orderId = $data['order_id'] ?? ($data['order_number'] ?? ($data['client_order_id'] ?? null));
        $awb = $data['awb'] ?? ($data['awb_code'] ?? ($data['awb_number'] ?? ($data['shipment_awb'] ?? null)));
        $courier = $data['courier_name'] ?? ($data['courier'] ?? ($data['carrier_name'] ?? null));
        $status = $data['status'] ?? ($data['shipment_status'] ?? ($data['current_status'] ?? ''));

        if (!$orderId && !$awb) {
            Log::warning('Velocity webhook: no order_id or awb in payload');
            return response()->json(['status' => 'error', 'message' => 'Missing order_id or awb'], 400);
        }

        // Find the order
        $order = null;
        if ($orderId) {
            $order = Order::where('order_number', $orderId)->first();
        }
        if (!$order && $awb) {
            $order = Order::where('tracking_number', $awb)->orWhere('awb_number', $awb)->first();
        }

        if (!$order) {
            Log::warning('Velocity webhook: order not found', ['order_id' => $orderId, 'awb' => $awb]);
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        // Update order based on status
        $statusLower = strtolower($status);

        if ($awb && !$order->tracking_number) {
            // AWB assigned — mark as shipped
            $order->update([
                'status' => 'shipped',
                'shipped_at' => now(),
                'tracking_number' => $awb,
                'awb_number' => $awb,
                'courier_name' => $courier ?? $order->courier_name,
                'tracking_url' => 'https://shipfastt.in/track/' . $awb,
            ]);

            $order->timeline()->create([
                'status' => 'shipped',
                'message' => 'Shipped via ' . ($courier ?? 'Velocity') . ' — AWB: ' . $awb . ' (auto-synced)',
            ]);

            // Send shipped email
            $customerEmail = $order->address?->email ?? ($order->user?->email ?? null);
            if ($customerEmail) {
                try {
                    \Illuminate\Support\Facades\Mail::to($customerEmail)->send(new \App\Mail\OrderStatusMail($order->fresh(['items', 'address'])));
                } catch (\Exception $e) {
                    Log::warning('Velocity webhook shipped email failed: ' . $e->getMessage());
                }
            }

            Log::info("Velocity webhook: Order {$order->order_number} marked as shipped, AWB: {$awb}");
        } elseif (in_array($statusLower, ['delivered', 'dl', 'pod'])) {
            // Delivered
            $order->update([
                'status' => 'delivered',
                'delivered_at' => now(),
                'payment_status' => 'paid',
                'paid_at' => $order->paid_at ?? now(),
            ]);
            $order->timeline()->create([
                'status' => 'delivered',
                'message' => 'Delivered — confirmed via Velocity webhook',
            ]);
            Log::info("Velocity webhook: Order {$order->order_number} delivered");
        } elseif (in_array($statusLower, ['rto', 'rto_initiated', 'returned'])) {
            // RTO
            $order->update(['status' => 'returned']);
            $order->timeline()->create([
                'status' => 'returned',
                'message' => 'RTO initiated via Velocity',
            ]);
            Log::info("Velocity webhook: Order {$order->order_number} RTO");
        }

        return response()->json(['status' => 'success', 'message' => 'Webhook processed']);
    }
}
