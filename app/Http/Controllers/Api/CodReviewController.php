<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Razorpay Magic Checkout - Review COD Orders API
 * 
 * Called by Razorpay for manual review of COD orders.
 * Requires Basic Authentication.
 * 
 * Dashboard URL: https://theshivara.com/api/cod/review
 * Username: shivara_cod_review
 * Password: (set in .env as RAZORPAY_COD_REVIEW_PASSWORD)
 */
class CodReviewController extends Controller
{
    public function handle(Request $request)
    {
        // Verify Basic Auth
        $username = $request->getUser();
        $password = $request->getPassword();

        $validUsername = config('services.razorpay.cod_review_username', 'shivara_cod_review');
        $validPassword = config('services.razorpay.cod_review_password', '');

        if ($username !== $validUsername || $password !== $validPassword) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $orderId = $request->input('order_id');
        $action = $request->input('action'); // "approve" or "reject"

        if (!$orderId) {
            return response()->json(['error' => 'order_id is required'], 400);
        }

        // Find our internal order by Razorpay order ID
        $order = \App\Models\Order::where('razorpay_order_id', $orderId)->first();

        if ($action === 'approve') {
            // Approve the COD order
            if ($order) {
                $order->update(['status' => 'confirmed']);
                $order->timeline()->create([
                    'status' => 'confirmed',
                    'message' => 'COD order approved via Razorpay review',
                ]);
            }

            return response()->json([
                'order_id' => $orderId,
                'action' => 'approve',
                'status' => 'success',
            ]);
        } elseif ($action === 'reject') {
            // Reject/cancel the COD order
            if ($order) {
                $order->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                ]);
                $order->timeline()->create([
                    'status' => 'cancelled',
                    'message' => 'COD order rejected via Razorpay RTO review',
                ]);
            }

            return response()->json([
                'order_id' => $orderId,
                'action' => 'reject',
                'status' => 'success',
            ]);
        }

        return response()->json([
            'order_id' => $orderId,
            'action' => $action ?? 'unknown',
            'status' => 'success',
        ]);
    }
}
