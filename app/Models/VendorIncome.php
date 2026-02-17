<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorIncome extends Model
{
    protected $table = 'vendor_incomes';

    protected $fillable = [
        'order_id', // Added
        'vendor_id', // Added
        'admin_id',
        'user_id',
        'user_ulid',
        'from_vendor_name',
        'from_vendor_ulid',
        'purchase_amount',
        'purchase_pv',
        'income_amount',
        'percentage',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}