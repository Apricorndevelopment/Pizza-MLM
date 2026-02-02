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
     * Relationship: An Order belongs to a User (The Customer who bought the item).
     * Used in: ->with('user')
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: Get the Admin associated with the order.
     * Assumes 'admin_id' exists in the orders table.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
