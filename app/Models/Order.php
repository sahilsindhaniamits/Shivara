<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'address_id', 'status', 'payment_status',
        'payment_method', 'subtotal', 'discount', 'shipping_charge', 'tax_amount',
        'total_amount', 'coupon_id', 'coupon_code', 'razorpay_order_id',
        'razorpay_payment_id', 'razorpay_signature', 'shipping_method',
        'tracking_number', 'tracking_url', 'courier_name', 'awb_number',
        'paid_at', 'shipped_at', 'delivered_at', 'cancelled_at', 'notes', 'admin_notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping_charge' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function timeline()
    {
        return $this->hasMany(OrderTimeline::class)->orderBy('created_at', 'desc');
    }

    public function returnRequest()
    {
        return $this->hasOne(ReturnRequest::class);
    }

    public static function generateOrderNumber(): string
    {
        $lastOrder = static::orderBy('id', 'desc')->first();

        if ($lastOrder && preg_match('/^SHV(\d+)$/', $lastOrder->order_number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        return 'SHV' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
