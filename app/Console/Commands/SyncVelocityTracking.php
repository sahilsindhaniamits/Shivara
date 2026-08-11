<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\VelocityShipping;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncVelocityTracking extends Command
{
    protected $signature = 'velocity:sync-tracking';
    protected $description = 'Fetch tracking/AWB info from Velocity for orders that have been shipped there';

    public function handle()
    {
        $velocity = new VelocityShipping();

        // Clear any stale cached token first
        \Illuminate\Support\Facades\Cache::forget('velocity_token');

        $token = $velocity->getToken();

        if (!$token) {
            $this->error('Failed to authenticate with Velocity. Check VELOCITY_USERNAME and VELOCITY_PASSWORD in .env');
            return 1;
        }

        $this->info("Authenticated with Velocity successfully.");

        // Get all confirmed/processing orders without tracking number (these are in Velocity "New" or "Ready to Ship")
        $orders = Order::whereIn('status', ['confirmed', 'processing'])
            ->whereNull('tracking_number')
            ->where('created_at', '>=', now()->subDays(30))
            ->get();

        $this->info("Checking {$orders->count()} orders against Velocity...");

        $updated = 0;

        foreach ($orders as $order) {
            try {
                $this->line("  Checking: {$order->order_number}...");
                // Use Velocity's order tracking by order_id
                $result = $this->fetchOrderStatus($velocity, $token, $order->order_number);

                if ($result && !empty($result['awb_code'])) {
                    $order->update([
                        'status' => 'shipped',
                        'shipped_at' => now(),
                        'tracking_number' => $result['awb_code'],
                        'awb_number' => $result['awb_code'],
                        'courier_name' => $result['courier_name'] ?? 'Velocity',
                        'tracking_url' => 'https://shipfastt.in/track/' . $result['awb_code'],
                    ]);

                    $order->timeline()->create([
                        'status' => 'shipped',
                        'message' => 'Auto-synced from Velocity — ' . ($result['courier_name'] ?? 'Courier') . ' — AWB: ' . $result['awb_code'],
                    ]);

                    // Send shipped email
                    $customerEmail = $order->address?->email ?? ($order->user?->email ?? null);
                    if ($customerEmail) {
                        try {
                            \Illuminate\Support\Facades\Mail::to($customerEmail)->send(new \App\Mail\OrderStatusMail($order->fresh(['items', 'address'])));
                        } catch (\Exception $e) {
                            Log::warning('Shipped email failed for ' . $order->order_number . ': ' . $e->getMessage());
                        }
                    }

                    $updated++;
                    $this->line("  ✓ {$order->order_number} → AWB: {$result['awb_code']} ({$result['courier_name']})");
                }
            } catch (\Exception $e) {
                Log::warning("Velocity sync failed for {$order->order_number}: " . $e->getMessage());
            }
        }

        // Also check shipped orders for delivery status
        $shippedOrders = Order::where('status', 'shipped')
            ->whereNotNull('tracking_number')
            ->whereNull('delivered_at')
            ->where('created_at', '>=', now()->subDays(60))
            ->get();

        $delivered = 0;
        foreach ($shippedOrders as $order) {
            try {
                $trackResult = $velocity->track($order->tracking_number);
                if ($trackResult['success'] && strtolower($trackResult['status'] ?? '') === 'delivered') {
                    $order->update([
                        'status' => 'delivered',
                        'delivered_at' => now(),
                        'payment_status' => 'paid',
                        'paid_at' => $order->paid_at ?? now(),
                    ]);
                    $order->timeline()->create([
                        'status' => 'delivered',
                        'message' => 'Delivered — auto-synced from Velocity tracking',
                    ]);
                    $delivered++;
                    $this->line("  ✓ {$order->order_number} → Delivered");
                }
            } catch (\Exception $e) {
                // Skip silently
            }
        }

        $this->info("Done! Updated: {$updated} shipped, {$delivered} delivered.");
        return 0;
    }

    /**
     * Fetch order status from Velocity by order_id
     * Tries multiple API approaches since Velocity docs are limited
     */
    private function fetchOrderStatus(VelocityShipping $velocity, string $token, string $orderId): ?array
    {
        // Approach 1: Try /order-tracking with order_ids
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => $token,
            'Content-Type' => 'application/json',
        ])->post('https://shazam.velocity.in/custom/api/v1/order-tracking', [
            'order_ids' => [$orderId],
        ]);

        $data = $response->json();
        Log::info("Velocity API /order-tracking for {$orderId}", ['status' => $response->status(), 'body' => json_encode($data)]);
        $this->line("    /order-tracking [{$response->status()}]: " . json_encode($data));

        if ($response->successful() && isset($data['result'][$orderId])) {
            $orderData = $data['result'][$orderId];
            $trackingData = $orderData['tracking_data'] ?? $orderData;

            $awb = $trackingData['awb_code'] ?? ($orderData['awb_code'] ?? null);
            $courier = $trackingData['courier_name'] ?? ($orderData['courier_name'] ?? null);

            if ($awb) {
                return [
                    'awb_code' => $awb,
                    'courier_name' => $courier ?? 'Velocity Courier',
                    'status' => $trackingData['shipment_status'] ?? '',
                ];
            }
        }

        // Approach 2: Try /order-tracking with awb field (some APIs use this)
        // Maybe the result key is different - check all keys in result
        if ($response->successful() && isset($data['result']) && is_array($data['result'])) {
            foreach ($data['result'] as $key => $val) {
                $this->line("    Found result key: {$key}");
                $trackingData = $val['tracking_data'] ?? $val;
                $awb = $trackingData['awb_code'] ?? ($val['awb_code'] ?? null);
                $courier = $trackingData['courier_name'] ?? ($val['courier_name'] ?? null);
                if ($awb) {
                    return [
                        'awb_code' => $awb,
                        'courier_name' => $courier ?? 'Velocity Courier',
                        'status' => $trackingData['shipment_status'] ?? '',
                    ];
                }
            }
        }

        // Approach 3: Try /order-status endpoint
        $response2 = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => $token,
            'Content-Type' => 'application/json',
        ])->post('https://shazam.velocity.in/custom/api/v1/order-status', [
            'order_id' => $orderId,
        ]);

        $data2 = $response2->json();
        $this->line("    /order-status [{$response2->status()}]: " . json_encode($data2));

        if ($response2->successful() && $data2) {
            $awb = $data2['awb_code'] ?? ($data2['payload']['awb_code'] ?? ($data2['data']['awb_code'] ?? null));
            $courier = $data2['courier_name'] ?? ($data2['payload']['courier_name'] ?? ($data2['data']['courier_name'] ?? null));
            if ($awb) {
                return [
                    'awb_code' => $awb,
                    'courier_name' => $courier ?? 'Velocity Courier',
                    'status' => $data2['status'] ?? '',
                ];
            }
        }

        // Approach 4: Try /shipment-details endpoint
        $response3 = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => $token,
            'Content-Type' => 'application/json',
        ])->post('https://shazam.velocity.in/custom/api/v1/shipment-details', [
            'order_id' => $orderId,
        ]);

        $data3 = $response3->json();
        $this->line("    /shipment-details [{$response3->status()}]: " . json_encode($data3));

        if ($response3->successful() && $data3) {
            $awb = $data3['awb_code'] ?? ($data3['payload']['awb_code'] ?? ($data3['data']['awb_code'] ?? null));
            $courier = $data3['courier_name'] ?? ($data3['payload']['courier_name'] ?? ($data3['data']['courier_name'] ?? null));
            if ($awb) {
                return [
                    'awb_code' => $awb,
                    'courier_name' => $courier ?? 'Velocity Courier',
                    'status' => $data3['status'] ?? '',
                ];
            }
        }

        return null;
    }
}
