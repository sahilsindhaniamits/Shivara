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

// Razorpay Webhook (accepts both GET for validation and POST for events)
Route::match(['get', 'post'], '/razorpay/webhook', [RazorpayWebhookController::class, 'handle']);
