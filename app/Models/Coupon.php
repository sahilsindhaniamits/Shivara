<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'description', 'type', 'value', 'min_order_amount',
        'max_discount', 'usage_limit', 'usage_count', 'per_user_limit',
        'is_active', 'show_as_popup', 'start_date', 'end_date',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'is_active' => 'boolean',
        'show_as_popup' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isValid(): bool
    {
        return $this->is_active
            && now()->between($this->start_date, $this->end_date)
            && ($this->usage_limit === null || $this->usage_count < $this->usage_limit);
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
