<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    protected $fillable = [
        'sender_type',
        'sender_id', 
        'receiver_ulid',
        'product_id',
        'quantity',
        'from_location',
        'to_location',
        'sender_balance',
        'receiver_balance',
        'notes',
        'status',
    ];

    public function sender()
    {
        return $this->morphTo();
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_ulid', 'ulid');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}