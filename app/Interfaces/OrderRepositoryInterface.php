<?php

namespace App\Interfaces;

interface OrderRepositoryInterface 
{
    public function getOrdersByUserId(int $userId);
    public function getOrderDetails(int $orderId, int $userId);
}