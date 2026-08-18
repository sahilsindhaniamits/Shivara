<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CacheHeaders
{
    /**
     * Add browser caching headers for guest visitors on GET requests.
     * This significantly reduces server load for repeat visits.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only cache GET requests for guest users (not logged in, no cart activity)
        if ($request->isMethod('GET') && !auth()->check() && !session()->has('cart')) {
            $response->headers->set('Cache-Control', 'public, max-age=60, s-maxage=120');
        }

        return $response;
    }
}
