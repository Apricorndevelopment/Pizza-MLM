<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPackage extends Model
{
    protected $table = 'product-package';
    
    // 'manage_stock' aur 'stock_quantity' add kiya
    protected $fillable = [
        'product_name', 'product_image', 'description', 'max_coupon_usage', 
        'mrp', 'gst', 'dp', 'pv', 'profit', 'isVeg', 'is_package_product', 
        'capping', 'manage_stock', 'stock_quantity','product_cost',
    ];

    // Check if product is in stock
    public function getIsInStockAttribute()
    {
        if (!$this->manage_stock) {
            return true;
        }
        return $this->stock_quantity > 0;
    }
}