<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['user_id', 'session_id', 'product_id', 'variant_id', 'quantity'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function getLineTotalAttribute(): float
    {
        $price = $this->variant ? $this->variant->selling_price : $this->product->selling_price;
        return $price * $this->quantity;
    }
}
