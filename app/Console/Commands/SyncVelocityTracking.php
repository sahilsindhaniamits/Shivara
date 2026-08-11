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
     * Fetch order status from Velocity using /shipments API (Order Details endpoint)
     * This endpoint supports searching by order display ID and returns tracking details
     */
    private function fetchOrderStatus(VelocityShipping $velocity, string $token, string $orderId): ?array
    {
        // Use /shipments API with search parameter to find our order
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => $token,
            'Content-Type' => 'application/json',
        ])->post('https://shazam.velocity.in/custom/api/v1/shipments', [
            'page' => ['number' => '1'],
            'per_page' => '5',
            'search' => $orderId,
        ]);

        $data = $response->json();
        $this->line("    /shipments [{$response->status()}]: " . mb_substr(json_encode($data), 0, 500));

        if ($response->successful() && isset($data['data']) && is_array($data['data'])) {
            foreach ($data['data'] as $shipment) {
                $attrs = $shipment['attributes'] ?? $shipment;
                $trackingNumber = $attrs['tracking_number'] ?? null;
                $status = $attrs['status'] ?? '';
                $carrier = $attrs['carrier']['name'] ?? ($attrs['carrier_name'] ?? null);

                // Check if this shipment has an AWB/tracking number assigned
                if ($trackingNumber) {
                    Log::info("Velocity sync found AWB for {$orderId}", [
                        'awb' => $trackingNumber,
                        'courier' => $carrier,
                        'status' => $status,
                    ]);
                    return [
                        'awb_code' => $trackingNumber,
                        'courier_name' => $carrier ?? 'Velocity Courier',
                        'status' => $status,
                    ];
                }
            }
        }

        return null;
    }
}
