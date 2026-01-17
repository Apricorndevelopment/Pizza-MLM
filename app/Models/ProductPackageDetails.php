<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPackageDetails extends Model
{
    protected $table = 'package2_details';
    protected $fillable = ['package2_id', 'rate', 'time', 'capital', 'profit_share'];

     public function package2()
    {
        return $this->belongsTo(ProductPackage::class, 'package2_id');
    }

}