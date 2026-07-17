<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

/**
 * API endpoints for Razorpay Magic Checkout coupon integration.
 * These are called by Razorpay to display/apply promotions during checkout.
 */
class CouponApiController extends Controller
{
    /**
     * GET /api/promotions - Returns available coupons for display
     * Called by Razorpay "URL for get promotions"
     */
    public function getPromotions(Request $request)
    {
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
                'summary' => $coupon->description ?: ($coupon->type === 'percentage' ? $coupon->value . '% OFF' : '₹' . $coupon->value . ' OFF'),
                'description' => $this->buildDescription($coupon),
                'tnc' => $this->buildTnc($coupon),
            ];
        });

        return response()->json([
            'success' => true,
            'promotions' => $promotions->values(),
        ]);
    }

    /**
     * POST /api/promotions/apply - Validates and applies a coupon
     * Called by Razorpay "URL for apply promotions"
     *
     * Expected request from Razorpay:
     * { "order_id": "receipt_value", "contact": "+919000090000", "email": "...", "code": "500OFF" }
     *
     * Expected response format:
     * { "promotion": { "reference_id": "...", "code": "...", "value": 50000 (paise), "value_type": "fixed_amount", "description": "..." } }
     */
    public function applyPromotion(Request $request)
    {
        $code = strtoupper($request->input('code', ''));
        $orderAmount = (float) $request->input('order_amount', 0) / 100; // Razorpay sends in paise

        // If order_amount not provided, try to get from order_id (receipt) lookup
        if ($orderAmount <= 0) {
            $checkoutData = session('razorpay_checkout', []);
            $orderAmount = $checkoutData['subtotal'] ?? 0;
        }

        if (!$code) {
            return response()->json(['success' => false, 'message' => 'Please enter a coupon code.'], 400);
        }

        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon || !$coupon->isValid()) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired coupon code.'], 400);
        }

        if ($coupon->min_order_amount && $orderAmount < $coupon->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum order amount is ₹' . number_format($coupon->min_order_amount) . '.',
            ], 400);
        }

        $discount = $coupon->calculateDiscount($orderAmount);

        // Return in Razorpay's expected format
        return response()->json([
            'success' => true,
            'promotion' => [
                'reference_id' => 'coupon_' . $coupon->id,
                'code' => $coupon->code,
                'type' => 'coupon',
                'value' => (int)($discount * 100), // in paise
                'value_type' => $coupon->type === 'percentage' ? 'percentage' : 'fixed_amount',
                'description' => $coupon->description ?: ($coupon->type === 'percentage' ? $coupon->value . '% OFF applied!' : '₹' . $coupon->value . ' OFF applied!'),
            ],
        ]);
    }

    private function buildDescription(Coupon $coupon): string
    {
        $desc = $coupon->type === 'percentage'
            ? "Get {$coupon->value}% off"
            : "Get ₹{$coupon->value} off";

        if ($coupon->min_order_amount) {
            $desc .= " on orders above ₹" . number_format($coupon->min_order_amount);
        }
        if ($coupon->max_discount) {
            $desc .= " (max ₹" . number_format($coupon->max_discount) . ")";
        }
        return $desc;
    }

    private function buildTnc(Coupon $coupon): string
    {
        $tnc = [];
        if ($coupon->min_order_amount) $tnc[] = "Min order: ₹" . number_format($coupon->min_order_amount);
        if ($coupon->max_discount) $tnc[] = "Max discount: ₹" . number_format($coupon->max_discount);
        if ($coupon->end_date) $tnc[] = "Valid till: " . $coupon->end_date->format('d M Y');
        if ($coupon->usage_limit) $tnc[] = "Limited usage";
        return implode(' | ', $tnc) ?: 'No restrictions';
    }
}
