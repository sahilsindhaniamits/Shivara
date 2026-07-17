<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Shipping Info API for Razorpay Magic Checkout.
 * Returns shipping serviceability, COD serviceability, shipping fees and COD fees.
 *
 * Razorpay calls this API server-to-server (no session/cookies available).
 * It sends: order_id (receipt), razorpay_order_id (without order_ prefix), contact, email, addresses[]
 *
 * Configure in Razorpay Dashboard > Magic Checkout > Shipping Setup:
 * URL: https://theshivara.com/api/shipping-info
 */
class ShippingInfoController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Razorpay Shipping Info API called', ['payload' => $request->all()]);

        $addresses = $request->input('addresses', []);
        $orderId = $request->input('order_id', ''); // This is the receipt field
        $razorpayOrderId = $request->input('razorpay_order_id', ''); // Without order_ prefix

        // Get order amount from Razorpay API (since this is server-to-server, no session available)
        $subtotal = 0;
        if ($razorpayOrderId) {
            try {
                $api = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));
                $fetchId = 'order_' . $razorpayOrderId;
                $rzpOrder = $api->order->fetch($fetchId);
                // line_items_total is the cart subtotal (before shipping/COD), amount includes shipping
                $subtotal = ($rzpOrder->line_items_total ?? $rzpOrder->amount ?? 0) / 100; // Convert paise to rupees
            } catch (\Exception $e) {
                Log::warning('Shipping Info: Failed to fetch Razorpay order', ['error' => $e->getMessage(), 'order_id' => $razorpayOrderId]);
                // Use amount from order if available, otherwise default to a value that qualifies for free shipping
                $subtotal = 999; // Default to qualify for free shipping
            }
        }

        $freeShippingThreshold = config('shivara.free_shipping_threshold', 299);
        $standardRate = config('shivara.standard_rate', 79);
        $codCharge = config('shivara.cod_charge', 49);

        // Determine shipping fee based on subtotal
        $shippingFee = $subtotal >= $freeShippingThreshold ? 0 : $standardRate;

        // Build response for each address
        $responseAddresses = [];
        foreach ($addresses as $addr) {
            $zipcode = $addr['zipcode'] ?? '';
            $id = $addr['id'] ?? '0';
            $country = $addr['country'] ?? 'in';

            // We deliver pan-India (all Indian pincodes serviceable)
            $serviceable = true;

            // COD available for all Indian addresses
            $codAvailable = true;

            $responseAddresses[] = [
                'id' => (string)$id,
                'zipcode' => (string)$zipcode,
                'country' => $country,
                'shipping_methods' => [
                    [
                        'id' => 'standard',
                        'name' => 'Standard Delivery',
                        'description' => config('shivara.standard_days', '5-7 business days'),
                        'serviceable' => $serviceable,
                        'shipping_fee' => (int)($shippingFee * 100), // in paise
                        'cod' => $codAvailable,
                        'cod_fee' => (int)($codCharge * 100), // ₹49 = 4900 paise (COD charge ₹50 as you mentioned)
                    ],
                ],
            ];
        }

        $response = [
            'addresses' => $responseAddresses,
        ];

        Log::info('Razorpay Shipping Info API response', ['response' => $response]);

        return response()->json($response);
    }
}
