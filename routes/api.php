<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CouponApiController;
use App\Http\Controllers\Api\RazorpayWebhookController;
use App\Http\Controllers\Api\ShippingInfoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| These routes are loaded by RouteServiceProvider and prefixed with /api
|--------------------------------------------------------------------------
*/

// Razorpay Magic Checkout - Coupon Promotions API
// Set these URLs in Razorpay Dashboard > Magic Checkout > Coupon Settings:
// - URL for get promotions: https://theshivara.com/api/promotions
// - URL for apply promotions: https://theshivara.com/api/promotions/apply
Route::match(['get', 'post'], '/promotions', [CouponApiController::class, 'getPromotions']);
Route::match(['get', 'post'], '/promotions/apply', [CouponApiController::class, 'applyPromotion']);

// Razorpay Magic Checkout - Shipping Info API
// Set this URL in Razorpay Dashboard > Magic Checkout > Shipping Setup:
// - URL for shipping info: https://theshivara.com/api/shipping-info
Route::match(['get', 'post'], '/shipping-info', [ShippingInfoController::class, 'handle']);

// Razorpay Webhook (accepts both GET for validation and POST for events)
Route::match(['get', 'post'], '/razorpay/webhook', [RazorpayWebhookController::class, 'handle']);
