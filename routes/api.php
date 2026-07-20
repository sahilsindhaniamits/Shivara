<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CouponApiController;
use App\Http\Controllers\Api\RazorpayWebhookController;
use App\Http\Controllers\Api\ShippingInfoController;
use App\Http\Controllers\Api\CodReviewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| These routes are loaded by RouteServiceProvider and prefixed with /api
|--------------------------------------------------------------------------
*/

// Razorpay Magic Checkout - Coupon Promotions API
Route::withoutMiddleware('throttle:api')->group(function () {
    Route::get('/promotions', [CouponApiController::class, 'getPromotions']);
    Route::post('/promotions/apply', [CouponApiController::class, 'applyPromotion']);
});

// Razorpay Magic Checkout - Shipping Info API
// Set in Dashboard > Magic Checkout > Shipping Info URL: https://theshivara.com/api/shipping-info
Route::withoutMiddleware('throttle:api')->group(function () {
    Route::post('/shipping-info', [ShippingInfoController::class, 'handle']);
    Route::get('/shipping-info', [ShippingInfoController::class, 'handle']);
});

// Razorpay Magic Checkout - COD Review Order API
// Set in Dashboard > Magic Checkout > RTO Reduction > Manual Review COD Orders
// URL: https://theshivara.com/api/cod/review
// Username: shivara_cod_review
// Password: (your chosen password)
Route::withoutMiddleware('throttle:api')->group(function () {
    Route::post('/cod/review', [CodReviewController::class, 'handle']);
    Route::get('/cod/review', [CodReviewController::class, 'handle']);
});

// Razorpay Webhook (accepts both GET for validation and POST for events)
Route::withoutMiddleware('throttle:api')->group(function () {
    Route::match(['get', 'post'], '/razorpay/webhook', [RazorpayWebhookController::class, 'handle']);
});
