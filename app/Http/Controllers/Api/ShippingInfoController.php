<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Razorpay Magic Checkout - Shipping Info API
 * 
 * Called by Razorpay to get shipping serviceability, COD serviceability,
 * shipping fees and COD fees for customer addresses.
 * 
 * Dashboard URL: https://theshivara.com/api/shipping-info
 */
class ShippingInfoController extends Controller
{
    public function handle(Request $request)
    {
        $addresses = $request->input('addresses', []);
        $orderId = $request->input('order_id');

        // Get order amount from Razorpay order if available
        $orderAmount = 0;
        if ($orderId) {
            try {
                $api = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));
                $rzpOrder = $api->order->fetch($orderId);
                $orderAmount = ($rzpOrder['amount'] ?? 0) / 100; // Convert paise to rupees
            } catch (\Exception $e) {
                // If we can't fetch order, use 0 (will charge shipping)
            }
        }

        $freeShippingThreshold = config('shivara.free_shipping_threshold', 999);
        $standardRate = config('shivara.standard_rate', 50);
        $codCharge = config('shivara.cod_charge', 49);

        // Calculate shipping fee based on order amount
        $shippingFee = $orderAmount >= $freeShippingThreshold ? 0 : $standardRate;

        $responseAddresses = [];

        foreach ($addresses as $address) {
            $zipcode = $address['zipcode'] ?? ($address['pincode'] ?? '');
            
            // All India pincodes are serviceable (6 digits starting with 1-9)
            $serviceable = (bool) preg_match('/^[1-9]\d{5}$/', $zipcode);

            $responseAddresses[] = [
                'zipcode' => $zipcode,
                'state' => $address['state'] ?? '',
                'city' => $address['city'] ?? '',
                'country' => $address['country'] ?? 'IN',
                'serviceable' => $serviceable,
                'cod' => $serviceable, // COD available where shipping is serviceable
                'cod_fee' => 0, // No extra COD fee (already included in product price)
                'shipping_fee' => $serviceable ? (int)($shippingFee * 100) : 0, // In paise
                'delivery_time' => [
                    'min_days' => 5,
                    'max_days' => 7,
                ],
            ];
        }

        return response()->json([
            'addresses' => $responseAddresses,
        ]);
    }
}
