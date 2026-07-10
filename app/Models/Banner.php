<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'image', 'mobile_image', 'link',
        'is_active', 'sort_order', 'start_date', 'end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Get full image URL (handles Hostinger /public/storage/ prefix)
     */
    public function getImageUrlAttribute(): string
    {
        if (!$this->image) return '';
        return str_starts_with($this->image, '/storage/') ? '/public' . $this->image : $this->image;
    }

    /**
     * Get full mobile image URL
     */
    public function getMobileImageUrlAttribute(): ?string
    {
        if (!$this->mobile_image) return null;
        return str_starts_with($this->mobile_image, '/storage/') ? '/public' . $this->mobile_image : $this->mobile_image;
    }
}
