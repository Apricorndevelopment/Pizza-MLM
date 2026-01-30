<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items'; // Explicitly define table name if needed
    protected $guarded = [];

    /**
     * Relationship: An Item belongs to an Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Relationship: An Item belongs to a Vendor (User).
     * This allows you to access $item->vendor if needed later.
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}