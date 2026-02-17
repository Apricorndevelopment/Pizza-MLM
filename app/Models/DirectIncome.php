<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DirectIncome extends Model
{
    // Table name (change if needed)
    protected $table = 'direct_income';

    public $timestamps = true;

    // Mass assignable fields
    protected $fillable = [
        'order_id', // Added
        'vendor_id', // Added
        'admin_id',
        'user_id',
        'user_ulid',
        'from_name',
        'from_ulid',
        'purchase_amount',
        'purchase_pv',
        'income_amount',
        'percentage',
    ];

    // Cast fields to correct types
    protected $casts = [
        'user_id'          => 'integer',
        'purchase_amount' => 'decimal:2',
        'purchase_pv'      => 'integer',
        'income_amount'   => 'decimal:2',
        'percentage'       => 'decimal:2',
    ];

    /**
     * Income belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Income generated from another user (by ULID)
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_ulid', 'ulid');
    }
}
