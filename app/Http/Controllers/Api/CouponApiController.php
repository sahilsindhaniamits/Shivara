<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * API endpoints for Razorpay Magic Checkout coupon integration.
 * These are called by Razorpay SERVER-TO-SERVER (no session, no cookies).
 *
 * Razorpay sends:
 * - order_id: The receipt field set when creating the Razorpay order
 * - razorpay_order_id: The Razorpay order ID (without "order_" prefix)
 * - contact: Customer phone (e.g. "+919000090000")
 * - email: Customer email
 *
 * Configure in Razorpay Dashboard > Magic Checkout > Coupon Settings:
 * - URL for get promotions: https://theshivara.com/api/promotions
 * - URL for apply promotions: https://theshivara.com/api/promotions/apply
 */
class CouponApiController extends Controller
{
    /**
     * GET /api/promotions - Returns available coupons for display in Magic Checkout
     *
     * Razorpay expects response format:
     * { "promotions": [ { "code": "...", "summary": "...", "description": "..." }, ... ] }
     */
    public function getPromotions(Request $request)
    {
        Log::info('Razorpay Get Promotions API called', ['payload' => $request->all()]);

        $coupons = Coupon::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')->orWhereColumn('usage_count', '<', 'usage_limit');
            })
            ->get();

        $promotions = $coupons->map(function ($coupon) {
            return [
                'code' => $coupon->code,
                'summary' => $coupon->description ?: ($coupon->type === 'percentage' ? $coupon->value . '% OFF' : '₹' . number_format($coupon->value) . ' OFF'),
                'description' => $this->buildDescription($coupon),
            ];
        });

        $response = [
            'promotions' => $promotions->values()->toArray(),
        ];

        Log::info('Razorpay Get Promotions response', ['count' => $promotions->count()]);

        return response()->json($response);
    }

    /**
     * POST /api/promotions/apply - Validates and applies a coupon code
     *
     * Razorpay sends: { "order_id": "receipt_value", "contact": "+91...", "email": "...", "code": "SAVE10" }
     *
     * Razorpay expects success response:
     * { "promotion": { "reference_id": "...", "code": "...", "value": 50000, "value_type": "fixed_amount", "description": "..." } }
     *
     * Razorpay expects error response:
     * { "error": { "code": "...", "description": "...", "reason": "..." } }
     */
    public function applyPromotion(Request $request)
    {
        Log::info('Razorpay Apply Promotion API called', ['payload' => $request->all()]);

        $code = strtoupper(trim($request->input('code', '')));
        $orderId = $request->input('order_id', ''); // This is the receipt field
        $razorpayOrderId = $request->input('razorpay_order_id', '');

        if (!$code) {
            return response()->json([
                'error' => [
                    'code' => 'INVALID_COUPON',
                    'description' => 'Please enter a coupon code.',
                    'reason' => 'empty_code',
                ],
            ], 400);
        }

        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon || !$coupon->isValid()) {
            return response()->json([
                'error' => [
                    'code' => 'INVALID_COUPON',
                    'description' => 'Invalid or expired coupon code.',
                    'reason' => 'invalid_code',
                ],
            ], 400);
        }

        // Get order amount from Razorpay API (no session available - server-to-server call)
        $orderAmount = 0;
        if ($razorpayOrderId) {
            try {
                $api = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));
                $fetchId = 'order_' . $razorpayOrderId;
                $rzpOrder = $api->order->fetch($fetchId);
                $orderAmount = ($rzpOrder->line_items_total ?? $rzpOrder->amount ?? 0) / 100; // paise to rupees
            } catch (\Exception $e) {
                Log::warning('Apply Promotion: Failed to fetch Razorpay order', ['error' => $e->getMessage()]);
            }
        }

        // Fallback: try to get from order_id (receipt) - look up in our orders table
        if ($orderAmount <= 0 && $orderId) {
            $localOrder = \App\Models\Order::where('order_number', $orderId)->first();
            if ($localOrder) {
                $orderAmount = $localOrder->subtotal;
            }
        }

        // If we still can't determine amount, use a high default so coupons aren't blocked
        if ($orderAmount <= 0) {
            $orderAmount = 99999;
        }

        // Check minimum order amount
        if ($coupon->min_order_amount && $orderAmount < $coupon->min_order_amount) {
            return response()->json([
                'error' => [
                    'code' => 'MIN_ORDER_NOT_MET',
                    'description' => 'Minimum order amount is ₹' . number_format($coupon->min_order_amount) . '.',
                    'reason' => 'min_order_not_met',
                ],
            ], 400);
        }

        // Calculate discount
        $discount = $coupon->calculateDiscount($orderAmount);

        $response = [
            'promotion' => [
                'reference_id' => 'coupon_' . $coupon->id,
                'code' => $coupon->code,
                'type' => 'coupon',
                'value' => (int)($discount * 100), // in paise
                'value_type' => 'fixed_amount', // Always return fixed amount (already calculated)
                'description' => $coupon->description ?: ($coupon->type === 'percentage' ? $coupon->value . '% OFF applied!' : '₹' . number_format($coupon->value) . ' OFF applied!'),
            ],
        ];

        Log::info('Razorpay Apply Promotion success', ['code' => $code, 'discount_paise' => (int)($discount * 100)]);

        return response()->json($response);
    }

    private function buildDescription(Coupon $coupon): string
    {
        $desc = $coupon->type === 'percentage'
            ? "Get {$coupon->value}% off"
            : "Get ₹" . number_format($coupon->value) . " off";

        if ($coupon->min_order_amount) {
            $desc .= " on orders above ₹" . number_format($coupon->min_order_amount);
        }
        if ($coupon->max_discount) {
            $desc .= " (max ₹" . number_format($coupon->max_discount) . ")";
        }
        return $desc;
    }
}
