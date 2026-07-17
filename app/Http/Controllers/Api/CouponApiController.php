<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

/**
 * API endpoints for Razorpay Magic Checkout coupon integration.
 * These are called by Razorpay to display/apply promotions during checkout.
 * 
 * Razorpay Dashboard > Magic Checkout > Coupon Settings:
 * - URL for get promotions: https://theshivara.com/api/promotions
 * - URL for apply promotions: https://theshivara.com/api/promotions/apply
 */
class CouponApiController extends Controller
{
    /**
     * GET /api/promotions - Returns available coupons for display in Magic Checkout
     * 
     * Razorpay sends: order_id, contact (phone) as query params
     * Expected response: { "promotions": [ { "code", "summary", "description", "tnc" } ] }
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

        // Razorpay Magic Checkout expects flat response with "promotions" key
        return response()->json([
            'promotions' => $promotions->values(),
        ]);
    }

    /**
     * POST /api/promotions/apply - Validates and applies a coupon
     * 
     * Razorpay sends: { "code": "COUPONCODE", "order_id": "order_xxx", "order_amount": 94900 }
     * order_amount is in paise (e.g., 94900 = ₹949)
     * 
     * Expected success response: { "promotion_applied": true, "discount": 9490, "code": "SHIVARA10", "description": "..." }
     * Expected failure response: { "promotion_applied": false, "error_message": "..." }
     */
    public function applyPromotion(Request $request)
    {
        $code = strtoupper(trim($request->input('code', '')));
        $orderAmountPaise = (int) $request->input('order_amount', 0);
        $orderAmount = $orderAmountPaise / 100; // Convert paise to rupees

        if (!$code) {
            return response()->json([
                'promotion_applied' => false,
                'error_message' => 'Please enter a coupon code.',
            ]);
        }

        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon || !$coupon->isValid()) {
            return response()->json([
                'promotion_applied' => false,
                'error_message' => 'Invalid or expired coupon code.',
            ]);
        }

        if ($coupon->min_order_amount && $orderAmount < $coupon->min_order_amount) {
            return response()->json([
                'promotion_applied' => false,
                'error_message' => 'Minimum order amount is ₹' . number_format($coupon->min_order_amount) . '.',
            ]);
        }

        $discount = $coupon->calculateDiscount($orderAmount);
        $discountPaise = (int) round($discount * 100); // Return discount in paise

        return response()->json([
            'promotion_applied' => true,
            'discount' => $discountPaise,
            'code' => $coupon->code,
            'description' => $coupon->description ?: ($coupon->type === 'percentage' ? $coupon->value . '% OFF applied!' : '₹' . $coupon->value . ' OFF applied!'),
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
