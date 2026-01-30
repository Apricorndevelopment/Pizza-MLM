<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Recommended to add this
use Illuminate\Database\Eloquent\Model;

class OrderRejection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'user_id',
        'reason',
    ];

    /**
     * Relationship: An order rejection belongs to an Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship: An order rejection belongs to a User (Admin/Vendor).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}