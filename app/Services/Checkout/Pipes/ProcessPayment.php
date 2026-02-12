<?php

namespace App\Services\Checkout\Pipes;

use App\Services\Checkout\CheckoutContext;
use Closure;

class ProcessPayment
{
    public function handle(CheckoutContext $context, Closure $next)
    {
        $wallet2Request = (int) $context->requestData['wallet2_usage'];

        // Wallet 2 Logic
        if ($wallet2Request > 0) {
            if ($wallet2Request > $context->user->wallet2_balance) {
                throw new \Exception('Insufficient Wallet 2 balance.');
            }
            if ($wallet2Request > $context->payableAmount) {
                throw new \Exception('Wallet 2 usage cannot exceed payable amount.');
            }

            $context->wallet2Deduction = $wallet2Request;
            $context->payableAmount -= $wallet2Request;
        }

        // Wallet 1 Logic (Remaining Balance)
        $context->wallet1Deduction = $context->payableAmount;

        if ($context->user->wallet1_balance < $context->wallet1Deduction) {
            throw new \Exception('Insufficient Main Wallet (Wallet 1) balance.');
        }

        return $next($context);
    }
}