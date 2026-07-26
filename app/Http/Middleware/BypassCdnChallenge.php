<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to:
 * 1. Allow Razorpay sensors (accelerometer, gyroscope) via Permissions-Policy
 * 2. Add CDN bypass headers for API routes
 *
 * Razorpay Magic Checkout uses device sensors for fraud detection.
 * Without the Permissions-Policy allowing these, Magic Checkout
 * falls back to Standard Checkout.
 */
class BypassCdnChallenge
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Allow Razorpay sensors — CRITICAL for Magic Checkout
        $response->headers->set('Permissions-Policy', 'accelerometer=*, gyroscope=*, magnetometer=*, payment=*');

        // API-specific headers
        if ($request->is('api/*') || $request->is('razorpay-hooks/*')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, private');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('CDN-Cache-Control', 'no-store');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        }

        return $response;
    }
}
