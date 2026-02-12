<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    // Match the exact table name
    protected $table = 'coupons';

    // Match the exact column names
    protected $fillable = [
        'coupon_qyt',
        'coupon_price',
    ];
}
