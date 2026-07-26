<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->throttleApi('60,1');
        // Exclude Razorpay callback URL from CSRF verification
        // Magic Checkout redirects POST to this URL after payment
        $middleware->validateCsrfTokens(except: [
            'payment/verify',
            'api/*',
            'razorpay-hooks/*',
        ]);
        // Add Permissions-Policy + CDN bypass headers globally
        // Razorpay Magic Checkout needs accelerometer/gyroscope for fraud detection
        $middleware->append(\App\Http\Middleware\BypassCdnChallenge::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
