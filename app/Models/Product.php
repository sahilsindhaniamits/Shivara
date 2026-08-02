<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'short_description', 'ingredients',
        'how_to_use', 'benefits', 'mrp', 'selling_price', 'cost_price',
        'sku', 'hsn_code', 'gst_rate', 'weight', 'stock', 'low_stock_alert',
        'is_active', 'is_featured', 'meta_title', 'meta_description', 'category_id', 'banners', 'return_policy',
    ];

    protected $casts = [
        'mrp' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'gst_rate' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'banners' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true)->withDefault(function () {
            return null;
        });
    }

    public function getPrimaryImageUrlAttribute()
    {
        $image = $this->primaryImage;
        if ($image && $image->url) {
            return str_starts_with($image->url, '/storage/') ? '/public' . $image->url : $image->url;
        }
        // Fallback to first image
        $first = $this->images()->orderBy('sort_order')->first();
        if ($first && $first->url) {
            return str_starts_with($first->url, '/storage/') ? '/public' . $first->url : $first->url;
        }
        return null;
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class)->orderBy('sort_order');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->mrp <= 0) return 0;
        return (int) round((($this->mrp - $this->selling_price) / $this->mrp) * 100);
    }

    /**
     * Check if product is in stock (null stock = unlimited)
     */
    public function getInStockAttribute(): bool
    {
        if (is_null($this->stock)) return true; // null = unlimited
        return $this->stock > 0;
    }

    public function getAverageRatingAttribute(): float
    {
        // Use pre-loaded aggregate if available (from withAvg)
        if (isset($this->attributes['reviews_avg_rating'])) {
            return round((float) $this->attributes['reviews_avg_rating'], 1);
        }
        // Use loaded relation if available to avoid N+1
        if ($this->relationLoaded('reviews')) {
            $reviews = $this->getRelation('reviews');
            return $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 0;
        }
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewCountAttribute(): int
    {
        // Use pre-loaded count if available (from withCount)
        if (isset($this->attributes['reviews_count'])) {
            return (int) $this->attributes['reviews_count'];
        }
        // Use loaded relation if available to avoid N+1
        if ($this->relationLoaded('reviews')) {
            return $this->getRelation('reviews')->count();
        }
        return $this->reviews()->count();
    }
}
