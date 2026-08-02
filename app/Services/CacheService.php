<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Centralized cache management for storefront.
 * Call these methods from admin controllers when data is updated.
 */
class CacheService
{
    /**
     * Clear all storefront caches (call after any admin product/category/banner change)
     */
    public static function clearStorefront(): void
    {
        Cache::forget('storefront_categories');
        Cache::forget('storefront_banners');
        Cache::forget('home_featured_products');
        Cache::forget('popup_coupons');
    }

    /**
     * Clear product-related caches
     */
    public static function clearProducts(): void
    {
        Cache::forget('home_featured_products');
    }

    /**
     * Clear category caches
     */
    public static function clearCategories(): void
    {
        Cache::forget('storefront_categories');
    }

    /**
     * Clear banner caches
     */
    public static function clearBanners(): void
    {
        Cache::forget('storefront_banners');
    }

    /**
     * Clear coupon/popup caches
     */
    public static function clearCoupons(): void
    {
        Cache::forget('popup_coupons');
    }

    /**
     * Clear admin dashboard stats cache
     */
    public static function clearDashboard(): void
    {
        Cache::forget('admin_dashboard_stats');
    }

    /**
     * Clear everything
     */
    public static function clearAll(): void
    {
        static::clearStorefront();
        static::clearDashboard();
        \App\Models\Setting::clearCache();
    }
}
