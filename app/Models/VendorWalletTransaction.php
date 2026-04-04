<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorWalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'vendor_wallet_transactions';

    protected $fillable = [
        'user_id',
        'user_ulid',
        'amount',
        'notes',
        'balance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}