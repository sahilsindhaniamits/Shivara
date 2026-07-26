<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to add headers that bypass CDN browser challenge
 * for API routes called by Razorpay Magic Checkout (server-to-server).
 *
 * Hostinger CDN / Cloudflare "Checking your browser" challenge blocks
 * automated server-to-server requests. This middleware ensures API
 * responses include headers that instruct CDN to not cache or challenge.
 */
class BypassCdnChallenge
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Set headers to prevent CDN caching and challenge
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('CDN-Cache-Control', 'no-store');
        $response->headers->set('Cloudflare-CDN-Cache-Control', 'no-store');
        $response->headers->set('X-Robots-Tag', 'noindex');

        // Add CORS headers for Razorpay server calls
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

        return $response;
    }
}
