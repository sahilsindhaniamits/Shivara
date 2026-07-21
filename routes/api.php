<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CouponApiController;
use App\Http\Controllers\Api\RazorpayWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Razorpay Magic Checkout - Coupon Promotions API
Route::get('/promotions', [CouponApiController::class, 'getPromotions']);
Route::post('/promotions/apply', [CouponApiController::class, 'applyPromotion']);

// Razorpay Webhook
Route::match(['get', 'post'], '/razorpay/webhook', [RazorpayWebhookController::class, 'handle']);

// Razorpay Magic Checkout - Shipping Info API
Route::match(['get', 'post'], '/shipping-info', function (\Illuminate\Http\Request $request) {
    $addresses = $request->input('addresses', []);

    $responseAddresses = [];
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
            'cod_fee' => 0,
            'shipping_fee' => 0,
        ];
    }
    return response()->json(['addresses' => $responseAddresses]);
});

// COD Review API
Route::match(['get', 'post'], '/cod/review', function (\Illuminate\Http\Request $request) {
    return response()->json(['status' => 'success', 'action' => $request->input('action', 'approve')]);
});
