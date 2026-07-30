<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CouponApiController;
use App\Http\Controllers\Api\RazorpayWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| These routes are loaded by RouteServiceProvider and prefixed with /api
|--------------------------------------------------------------------------
*/

// Razorpay Magic Checkout - Coupon Promotions API
Route::get('/promotions', [CouponApiController::class, 'getPromotions']);
Route::post('/promotions/apply', [CouponApiController::class, 'applyPromotion']);

// Razorpay Magic Checkout - Shipping Info API
// Returns serviceability, COD availability, shipping fee for given addresses
// Razorpay adds this shipping_fee to the order amount and displays it in popup
Route::match(['get', 'post'], '/shipping-info', function (\Illuminate\Http\Request $request) {
    $addresses = $request->input('addresses', []);
    $orderId = $request->input('order_id');
    $responseAddresses = [];

    // Determine shipping fee based on order amount (line_items_total)
    // Order amount from Razorpay = product subtotal (no shipping included)
    $shippingFee = (int)(config('shivara.standard_rate', 50) * 100); // Default ₹50 in paise
    if ($orderId) {
        try {
            $api = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $order = $api->order->fetch($orderId);
            $orderAmount = ($order['amount'] ?? 0) / 100;
            if ($orderAmount >= config('shivara.free_shipping_threshold', 999)) {
                $shippingFee = 0; // Free shipping above threshold
            }
        } catch (\Exception $e) {
            // Default: charge shipping
        }
    }

    foreach ($addresses as $address) {
        $zipcode = $address['zipcode'] ?? ($address['pincode'] ?? '');
        $serviceable = (bool) preg_match('/^[1-9]\d{5}$/', $zipcode);
        $responseAddresses[] = [
            'zipcode' => $zipcode,
            'state' => $address['state'] ?? '',
            'city' => $address['city'] ?? '',
            'country' => $address['country'] ?? 'IN',
            'serviceable' => $serviceable,
            'cod' => $serviceable,
            'cod_fee' => 5000, // ₹50 in paise
            'shipping_fee' => $shippingFee,
        ];
    }
    return response()->json(['addresses' => $responseAddresses]);
});

// Razorpay Magic Checkout - COD Order Review API
// Razorpay calls this to check if a COD order should be approved or rejected
// Uses Basic Authentication (configure username/password in Razorpay Dashboard)
Route::match(['get', 'post'], '/cod/review', function (\Illuminate\Http\Request $request) {
    // Basic auth verification
    $username = config('services.razorpay.cod_review_username', 'shivara');
    $password = config('services.razorpay.cod_review_password', 'shivara_cod_2024');

    $authHeader = $request->header('Authorization');
    if ($authHeader) {
        $credentials = base64_decode(str_replace('Basic ', '', $authHeader));
        [$providedUser, $providedPass] = explode(':', $credentials, 2);
        if ($providedUser !== $username || $providedPass !== $password) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    // Get order details from Razorpay's request
    $orderId = $request->input('order_id');
    $paymentId = $request->input('payment_id');

    // Auto-approve all COD orders (you can add custom logic here)
    // Return "approve" to accept, "reject" to decline
    return response()->json([
        'action' => 'approve', // or 'reject'
    ]);
});

// Razorpay Webhook (accepts both GET for validation and POST for events)
Route::match(['get', 'post'], '/razorpay/webhook', [RazorpayWebhookController::class, 'handle']);
