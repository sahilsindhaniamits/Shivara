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
Route::get('/promotions', [CouponApiController::class, 'getPromotions']);
Route::post('/promotions/apply', [CouponApiController::class, 'applyPromotion']);

// Razorpay Magic Checkout - Shipping Info API
Route::post('/shipping-info', [ShippingInfoController::class, 'handle']);
Route::get('/shipping-info', [ShippingInfoController::class, 'handle']);

// Razorpay Magic Checkout - COD Review Order API
Route::post('/cod/review', [CodReviewController::class, 'handle']);
Route::get('/cod/review', [CodReviewController::class, 'handle']);

// Razorpay Webhook
Route::match(['get', 'post'], '/razorpay/webhook', [RazorpayWebhookController::class, 'handle']);
