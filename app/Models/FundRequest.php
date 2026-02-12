<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'transaction_id',
        'sender_upi_id',
        'payment_method',
        'receipt_image',
        'status',
        'admin_remark'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
