<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Shipping Info API for Razorpay Magic Checkout.
 * Returns shipping serviceability, fees, and COD availability for given addresses.
 *
 * Configure this URL in Razorpay Dashboard > Magic Checkout > Shipping Setup:
 * URL: https://theshivara.com/api/shipping-info
 */
class ShippingInfoController extends Controller
{
    public function handle(Request $request)
    {
        $addresses = $request->input('addresses', []);
        $orderId = $request->input('order_id', '');

        // Calculate cart subtotal from Razorpay order if available
        $razorpayOrderId = $request->input('razorpay_order_id', '');
        $subtotal = 0;

        // Try to get the order amount from session or Razorpay
        if ($razorpayOrderId) {
            try {
                $api = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));
                // Razorpay passes the order ID without 'order_' prefix in some cases
                $fetchId = str_starts_with($razorpayOrderId, 'order_') ? $razorpayOrderId : ('order_' . $razorpayOrderId);
                $rzpOrder = $api->order->fetch($fetchId);
                $subtotal = ($rzpOrder->line_items_total ?? $rzpOrder->amount ?? 0) / 100;
            } catch (\Exception $e) {
                // Fallback to session data
                $checkoutData = session('razorpay_checkout', []);
                $subtotal = $checkoutData['subtotal'] ?? 0;
            }
        }

        // If subtotal is still 0, try session
        if ($subtotal <= 0) {
            $checkoutData = session('razorpay_checkout', []);
            $subtotal = $checkoutData['subtotal'] ?? 0;
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

            // We deliver to all Indian pincodes (pan-India delivery)
            $serviceable = true;

            // COD available for all serviceable addresses
            $codAvailable = true;

            $responseAddresses[] = [
                'id' => $id,
                'zipcode' => $zipcode,
                'country' => $addr['country'] ?? 'IN',
                'shipping_methods' => [
                    [
                        'id' => 'standard',
                        'name' => 'Standard Delivery',
                        'description' => config('shivara.standard_days', '5-7 business days'),
                        'serviceable' => $serviceable,
                        'shipping_fee' => (int)($shippingFee * 100), // in paise
                        'cod' => $codAvailable,
                        'cod_fee' => $codAvailable ? (int)($codCharge * 100) : 0, // in paise
                    ],
                ],
            ];
        }

        return response()->json([
            'addresses' => $responseAddresses,
        ]);
    }
}
