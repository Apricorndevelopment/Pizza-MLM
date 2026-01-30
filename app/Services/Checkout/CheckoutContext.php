<?php

namespace App\Services\Checkout;

use App\Models\User;

class CheckoutContext
{
    public User $user;
    public array $requestData;
    public array $cartItems = []; // Enriched items with model data
    
    // Financials
    public float $totalDp = 0;
    public float $discountAmount = 0;
    public float $payableAmount = 0;
    
    // Payments
    public float $wallet2Deduction = 0;
    public float $wallet1Deduction = 0;
    public int $couponsUsed = 0;

    public function __construct(User $user, array $requestData)
    {
        $this->user = $user;
        $this->requestData = $requestData;
    }
}