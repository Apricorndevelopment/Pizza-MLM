<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function getOrdersByUserId(int $userId)
    {
        // Eager load items to solve N+1 query problem
        return Order::with(['items.vendor'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);
    }

    public function getOrderDetails(int $orderId, int $userId)
    {
        return Order::with('items')
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->firstOrFail();
    }
}
