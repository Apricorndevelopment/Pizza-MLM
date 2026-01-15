<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPackage extends Model
{
    protected $table = 'product-package';
    protected $fillable = ['product_name', 'product_image', 'description', 'mrp', 'gst', 'dp', 'pv','percentage' ];

    // public $timestamps = false;

    // public function details()
    // {
    //     return $this->hasMany(Package2Details::class, 'package2_id');
    // }

}
