<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelIncome extends Model
{
    protected $table = 'level_incomes';
    protected $fillable = [
        'order_id', // Added
        'vendor_id', // Added
        'admin_id',
        'user_id',
        'user_ulid',
        'from_user_id',
        'from_user_ulid',
        'from_user_name',
        'purchase_amount',
        'purchase_pv',
        'level',
        'amount',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
    
    public function package()
    {
        return $this->belongsTo(ProductPackage::class);
    }
}