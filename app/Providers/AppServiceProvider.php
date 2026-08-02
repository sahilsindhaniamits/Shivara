<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-clear caches when models are updated by admin
        $this->registerCacheBusters();
    }

    /**
     * Register model event listeners to bust caches when admin changes data.
     * This ensures the storefront always shows fresh data without manual cache clearing.
     */
    private function registerCacheBusters(): void
    {
        // Product changes → clear home page featured products cache
        \App\Models\Product::saved(function () {
            Cache::forget('home_featured_products');
            Cache::forget('admin_dashboard_stats');
        });
        \App\Models\Product::deleted(function () {
            Cache::forget('home_featured_products');
            Cache::forget('admin_dashboard_stats');
        });

        // Category changes → clear categories cache
        \App\Models\Category::saved(function () {
            Cache::forget('storefront_categories');
        });
        \App\Models\Category::deleted(function () {
            Cache::forget('storefront_categories');
        });

        // Banner changes → clear banners cache
        \App\Models\Banner::saved(function () {
            Cache::forget('storefront_banners');
        });
        \App\Models\Banner::deleted(function () {
            Cache::forget('storefront_banners');
        });

        // Coupon changes → clear popup coupons cache
        \App\Models\Coupon::saved(function () {
            Cache::forget('popup_coupons');
        });
        \App\Models\Coupon::deleted(function () {
            Cache::forget('popup_coupons');
        });

        // Order status changes → clear dashboard stats
        \App\Models\Order::saved(function () {
            Cache::forget('admin_dashboard_stats');
        });
    }
}
