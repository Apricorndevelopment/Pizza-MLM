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
        'price',
        'mrp',
        'gst',
        'dp',
        'pv',          // Admin Only
        'percentage',  // Admin Only
        'status',
        'isVeg',
        'max_coupon_usage' // From previous step
    ];
   
    public function inventories()
    {
        return $this->hasMany(UserPackageInventory::class, 'product_id');
    }

    // Relation to Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}