<?php

namespace App\Http\Controllers;

use App\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOrderController extends Controller
{
    private OrderRepositoryInterface $orderRepository;

    // Dependency Injection via Constructor
    public function __construct(OrderRepositoryInterface $orderRepository) 
    {
        $this->orderRepository = $orderRepository;
    }

    public function index() 
    {
        $orders = $this->orderRepository->getOrdersByUserId(Auth::id());
        return view('user.orders.index', compact('orders'));
    }
}