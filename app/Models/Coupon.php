<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'description', 'type', 'value', 'min_order_amount',
        'max_discount', 'usage_limit', 'usage_count', 'per_user_limit',
        'is_active', 'show_as_popup', 'auto_apply', 'start_date', 'end_date',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'is_active' => 'boolean',
        'show_as_popup' => 'boolean',
        'auto_apply' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->start_date && now()->lt($this->start_date)) return false;
        if ($this->end_date && now()->gt($this->end_date)) return false;
        if ($this->usage_limit !== null && $this->usage_count >= $this->usage_limit) return false;
        return true;
    }

    /**
     * Get the best auto-apply coupon for a given subtotal
     * Optimized: filters min_order_amount at DB level instead of fetching all
     */
    public static function getBestAutoApply(float $subtotal): ?self
    {
        $coupons = static::where('is_active', true)
            ->where('auto_apply', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')->orWhereColumn('usage_count', '<', 'usage_limit');
            })
            ->where(function ($q) use ($subtotal) {
                $q->whereNull('min_order_amount')->orWhere('min_order_amount', '<=', $subtotal);
            })
            ->get();

        if ($coupons->isEmpty()) return null;

        return $coupons->sortByDesc(fn($coupon) => $coupon->calculateDiscount($subtotal))->first();
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->min_order_amount && $subtotal < $this->min_order_amount) {
            return 0;
        }

        $discount = match ($this->type) {
            'percentage' => $subtotal * ($this->value / 100),
            'flat' => $this->value,
            'free_shipping' => 0,
            default => 0,
        };

        if ($this->max_discount && $discount > $this->max_discount) {
            $discount = $this->max_discount;
        }

        return min($discount, $subtotal);
    }
}
