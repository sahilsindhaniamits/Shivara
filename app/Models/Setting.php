<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value with caching (avoids DB query on every page load)
     * Settings are cached for 1 hour and cleared when updated.
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            return static::where('key', $key)->value('value') ?? $default;
        }) ?? $default;
    }

    /**
     * Set a setting value and clear its cache
     */
    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
        Cache::forget("setting_{$key}");
    }

    /**
     * Get all settings at once (useful for admin panel)
     */
    public static function getAllCached(): array
    {
        return Cache::remember('all_settings', 3600, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        $settings = static::pluck('key');
        foreach ($settings as $key) {
            Cache::forget("setting_{$key}");
        }
        Cache::forget('all_settings');
    }
}
