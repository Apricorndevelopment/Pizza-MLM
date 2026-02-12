<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'vendor_id',
        'vendor_user_id',
        'product_name',
        'product_image',
        'description',
        'profit',
        'mrp',
        'gst',
        'dp',
        'pv',          // Admin Only
        'percentage',  // Admin Only
        'status',
        'isVeg',
        'max_coupon_usage',
        'manage_stock',
        'stock_quantity',
    ];

    public function getIsInStockAttribute()
    {
        if (!$this->manage_stock) {
            return true; // Always in stock if management is disabled
        }
        return $this->stock_quantity > 0;
    }
    // Relation to Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}