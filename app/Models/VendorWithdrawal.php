<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorWithdrawal extends Model
{
    use HasFactory;

    protected $table = 'vendor_withdrawals';

    protected $fillable = [
        'user_id',
        'vendor_id',
        'user_ulid',
        'total_amount',
        'vendor_charge',
        'credited_amount',
        'status',
        'payment_method',
        'admin_remark'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}