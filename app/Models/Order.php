<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = []; // Allows mass assignment for all fields

    /**
     * Relationship: An Order has many Order Items.
     * Used in: Order::whereHas('items', ...) and $order->items->sum(...)
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Relationship: An Order belongs to a User (Customer).
     * Used in: ->with(..., 'user')
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}