<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoTestimonial extends Model
{
    protected $fillable = [
        'customer_name', 'video_url', 'video_type', 'thumbnail',
        'product_id', 'rating', 'is_verified', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get embeddable video URL
     */
    public function getEmbedUrlAttribute(): string
    {
        if ($this->video_type === 'youtube') {
            // Extract YouTube ID from various URL formats
            preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $this->video_url, $matches);
            return $matches[1] ?? $this->video_url;
        }
        if ($this->video_type === 'instagram') {
            // Instagram reels embed format: add /embed/ to the reel URL
            $url = rtrim($this->video_url, '/');
            if (!str_contains($url, '/embed')) {
                return $url . '/embed';
            }
            return $url;
        }
        return $this->video_url;
    }

    /**
     * Get thumbnail URL (uploaded or YouTube auto-thumbnail)
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return str_starts_with($this->thumbnail, '/storage/') ? '/public' . $this->thumbnail : $this->thumbnail;
        }
        // Auto-generate YouTube thumbnail
        if ($this->video_type === 'youtube') {
            return 'https://img.youtube.com/vi/' . $this->embed_url . '/maxresdefault.jpg';
        }
        return 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=300&h=400&fit=crop';
    }
}
