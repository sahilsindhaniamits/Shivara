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
Route::match(['get', 'post'], '/shipping-info', function (\Illuminate\Http\Request $request) {
    // Log full request to see what Razorpay sends
    \Log::info('shipping-info request', $request->all());

    $addresses = $request->input('addresses', []);
    $responseAddresses = [];

    // Razorpay sends order_id — look up the amount from our database
    // We store it in a simple file-based approach since cache/session won't work
    // for server-to-server calls
    $shippingFee = (int)(config('shivara.standard_rate', 50) * 100); // Default ₹50
    $orderId = $request->input('order_id');

    if ($orderId) {
        // Try reading from our stored file
        $cacheFile = storage_path('app/rzp_orders/' . md5($orderId) . '.txt');
        if (file_exists($cacheFile)) {
            $subtotal = (float) file_get_contents($cacheFile);
            if ($subtotal >= config('shivara.free_shipping_threshold', 999)) {
                $shippingFee = 0;
            }
        } else {
            // Fallback: try Razorpay API
            try {
                $api = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));
                $order = $api->order->fetch($orderId);
                $subtotal = ($order['amount'] ?? 0) / 100;
                if ($subtotal >= config('shivara.free_shipping_threshold', 999)) {
                    $shippingFee = 0;
                }
            } catch (\Exception $e) {
                // Default: charge shipping
            }
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
            'cod_fee' => 5000,
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
