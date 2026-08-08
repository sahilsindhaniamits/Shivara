<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VelocityShipping
{
    private string $baseUrl = 'https://shazam.velocity.in';

    /**
     * Get auth token (cached for 23 hours)
     */
    public function getToken(): ?string
    {
        return Cache::remember('velocity_token', 82800, function () {
            $response = Http::post($this->baseUrl . '/custom/api/v1/auth-token', [
                'username' => config('services.velocity.username'),
                'password' => config('services.velocity.password'),
            ]);

            if ($response->successful()) {
                return $response->json('token');
            }

            Log::error('Velocity auth failed: ' . $response->body());
            return null;
        });
    }

    /**
     * Build the standard order payload for Velocity
     */
    private function buildPayload(\App\Models\Order $order): array
    {
        $address = $order->address;

        $items = $order->items->map(function ($item) {
            return [
                'name' => $item->product_name,
                'sku' => 'SKU-' . $item->product_id,
                'units' => $item->quantity,
                'selling_price' => (float) $item->price,
                'discount' => 0,
                'tax' => 0,
            ];
        })->toArray();

        return [
            'order_id' => $order->order_number,
            'order_date' => $order->created_at->format('Y-m-d H:i'),
            'billing_customer_name' => $address->full_name,
            'billing_last_name' => '',
            'billing_address' => $address->address_line1 . ($address->address_line2 ? ', ' . $address->address_line2 : ''),
            'billing_city' => $address->city,
            'billing_pincode' => $address->pincode,
            'billing_state' => $address->state,
            'billing_country' => 'India',
            'billing_email' => $address->email ?? '',
            'billing_phone' => $address->phone,
            'shipping_is_billing' => true,
            'order_items' => $items,
            'payment_method' => $order->payment_method === 'cod' ? 'COD' : 'PREPAID',
            'sub_total' => (float) $order->subtotal,
            'cod_collectible' => $order->payment_method === 'cod' ? (float) $order->total_amount : 0,
            'length' => 25,
            'breadth' => 20,
            'height' => 10,
            'weight' => 0.5,
            'pickup_location' => config('services.velocity.pickup_location', 'Shivara Warehouse'),
            'warehouse_id' => config('services.velocity.warehouse_id'),
        ];
    }

    /**
     * Push order to Velocity "New" section (no courier assignment).
     * Called automatically when a new order is placed.
     */
    public function pushOrder(\App\Models\Order $order): array
    {
        $token = $this->getToken();
        if (!$token) {
            return ['success' => false, 'error' => 'Failed to authenticate with Velocity Shipping'];
        }

        $address = $order->address;
        if (!$address) {
            return ['success' => false, 'error' => 'Order has no delivery address'];
        }

        $payload = $this->buildPayload($order);

        $response = Http::withHeaders([
            'Authorization' => $token,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/custom/api/v1/forward-order', $payload);

        $data = $response->json();

        if ($response->successful() && isset($data['payload'])) {
            $p = $data['payload'];
            if (!empty($p['order_created']) || !empty($p['shipment_id'])) {
                return [
                    'success' => true,
                    'shipment_id' => $p['shipment_id'] ?? '',
                    'order_id' => $p['order_id'] ?? '',
                ];
            }
        }

        // If order already exists, that's fine — it's already in Velocity
        if (stripos(json_encode($data), 'order already exists') !== false) {
            return ['success' => true, 'shipment_id' => '', 'order_id' => ''];
        }

        Log::error('Velocity push order failed', ['response' => $data, 'order' => $order->order_number]);
        return ['success' => false, 'error' => $data['message'] ?? $data['error'] ?? json_encode($data) ?? 'Push failed'];
    }

    /**
     * Ship order via orchestration (auto-assigns courier + AWB).
     * Called when admin clicks "Ship with Velocity" button.
     */
    public function createShipment(\App\Models\Order $order): array
    {
        $token = $this->getToken();
        if (!$token) {
            return ['success' => false, 'error' => 'Failed to authenticate with Velocity Shipping'];
        }

        $address = $order->address;
        if (!$address) {
            return ['success' => false, 'error' => 'Order has no delivery address'];
        }

        $payload = $this->buildPayload($order);

        // Use orchestration endpoint (creates order + auto-assigns courier + generates AWB)
        $response = Http::withHeaders([
            'Authorization' => $token,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/custom/api/v1/forward-order-orchestration', $payload);

        $data = $response->json();

        if ($response->successful() && isset($data['payload']['awb_code'])) {
            return [
                'success' => true,
                'awb_code' => $data['payload']['awb_code'],
                'courier_name' => $data['payload']['courier_name'] ?? '',
                'shipment_id' => $data['payload']['shipment_id'] ?? '',
                'order_id' => $data['payload']['order_id'] ?? '',
                'label_url' => $data['payload']['label_url'] ?? null,
                'charges' => $data['payload']['charges'] ?? null,
            ];
        }

        // If "Order already exists" — try with a suffixed order_id for orchestration
        $errorMsg = json_encode($data);
        if (stripos($errorMsg, 'order already exists') !== false) {
            return [
                'success' => false,
                'error' => 'Order already exists in Velocity. Please assign courier from Velocity Dashboard → Orders → New.',
            ];
        }

        Log::error('Velocity create shipment failed', ['response' => $data, 'order' => $order->order_number]);
        return ['success' => false, 'error' => $data['message'] ?? $data['error'] ?? $errorMsg ?? 'Shipment creation failed'];
    }

    /**
     * Track shipment by AWB
     */
    public function track(string $awb): array
    {
        $token = $this->getToken();
        if (!$token) return ['success' => false, 'error' => 'Auth failed'];

        $response = Http::withHeaders([
            'Authorization' => $token,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/custom/api/v1/order-tracking', [
            'awbs' => [$awb],
        ]);

        $data = $response->json();

        if ($response->successful() && isset($data['result'][$awb])) {
            $tracking = $data['result'][$awb]['tracking_data'] ?? [];
            return [
                'success' => true,
                'status' => $tracking['shipment_status'] ?? 'unknown',
                'track_url' => $tracking['track_url'] ?? null,
                'activities' => $tracking['shipment_track_activities'] ?? [],
            ];
        }

        return ['success' => false, 'error' => 'Tracking not found'];
    }

    /**
     * Cancel an order in Velocity by order_id (used before re-creating with orchestration)
     */
    public function cancelByOrderId(string $orderId): array
    {
        $token = $this->getToken();
        if (!$token) return ['success' => false, 'error' => 'Auth failed'];

        $response = Http::withHeaders([
            'Authorization' => $token,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/custom/api/v1/cancel-order', [
            'order_ids' => [$orderId],
        ]);

        $data = $response->json();
        Log::info('Velocity cancel by order_id', ['order_id' => $orderId, 'response' => $data]);

        return [
            'success' => $response->successful(),
            'message' => $data['message'] ?? 'Cancel attempted',
        ];
    }

    /**
     * Cancel shipment by AWB
     */
    public function cancel(string $awb): array
    {
        $token = $this->getToken();
        if (!$token) return ['success' => false, 'error' => 'Auth failed'];

        $response = Http::withHeaders([
            'Authorization' => $token,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/custom/api/v1/cancel-order', [
            'awbs' => [$awb],
        ]);

        return [
            'success' => $response->successful(),
            'message' => $response->json('message') ?? 'Cancellation initiated',
        ];
    }

    /**
     * Check serviceability between two pincodes
     */
    public function checkServiceability(string $fromPin, string $toPin, string $paymentMode = 'prepaid'): array
    {
        $token = $this->getToken();
        if (!$token) return ['success' => false, 'error' => 'Auth failed'];

        $response = Http::withHeaders([
            'Authorization' => $token,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/custom/api/v1/serviceability', [
            'from' => $fromPin,
            'to' => $toPin,
            'payment_mode' => $paymentMode,
            'shipment_type' => 'forward',
        ]);

        $data = $response->json();

        if ($response->successful() && $data['status'] === 'SUCCESS') {
            return [
                'success' => true,
                'serviceable' => !empty($data['result']['serviceability_results']),
                'carriers' => $data['result']['serviceability_results'] ?? [],
                'zone' => $data['result']['zone'] ?? null,
            ];
        }

        return ['success' => false, 'error' => 'Serviceability check failed'];
    }
}
