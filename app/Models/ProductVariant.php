<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'name', 'mrp', 'selling_price', 'stock', 'sku', 'weight', 'is_active',
    ];

    protected $casts = [
        'mrp' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
