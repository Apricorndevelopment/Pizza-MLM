<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashbackIncome extends Model
{
    // Table name (change if different)
    protected $table = 'cashback_income';

    // If created_at is INT, Laravel timestamps still work
    public $timestamps = true;

    // Mass assignable fields
    protected $fillable = [
        'order_id', // Added
        'vendor_id', // Added
        'admin_id',
        'user_id',
        'user_ulid',
        'purchase_amount',
        'purchase_pv',
        'percentage',
        'income_amount',
    ];

    // Cast columns to proper data types
    protected $casts = [
        'user_id'         => 'integer',
        'purchase_amount' => 'decimal:2',
        'purchase_pv'     => 'integer',
        'percentage'      => 'decimal:2',
        'income_amount'   => 'decimal:2',
    ];

    /**
     * Relationship: Income belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
