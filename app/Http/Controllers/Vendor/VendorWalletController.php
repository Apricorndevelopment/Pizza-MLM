<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorWalletTransaction;
use App\Models\VendorWithdrawal;
use App\Models\PercentageIncome;
use App\Models\Admin;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;

class VendorWalletController extends Controller
{
    public function vendorWallet(Request $request)
    {
        $user = Auth::user();
        $vendorWalletBalance = $user->vendor_wallet_balance;
        $percentageIncome = PercentageIncome::first();
        
        // Check if global withdrawal is open (assuming you use the same admin setting)
        $withdrawalStatus = Admin::first()->is_withdrawal_open;

        // 1. Withdrawals with pagination
        $withdrawals = VendorWithdrawal::where('user_id', $user->id)
            ->latest()
            ->paginate(5, ['*'], 'withdrawals_page');

        // 2. Wallet Transactions with filters and pagination
        $walletQuery = VendorWalletTransaction::where('user_id', $user->id);

        if ($request->has('wallet_type') && !empty($request->wallet_type)) {
            if ($request->wallet_type === 'credit') {
                $walletQuery->where('amount', '>=', 0);
            } elseif ($request->wallet_type === 'debit') {
                $walletQuery->where('amount', '<', 0);
            }
        }

        if ($request->has('start_date') && !empty($request->start_date)) {
            $walletQuery->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && !empty($request->end_date)) {
            $walletQuery->whereDate('created_at', '<=', $request->end_date);
        }

        $walletTransactions = $walletQuery->latest()->paginate(10, ['*'], 'wallet_page');

        return view('vendor.wallet.index', compact(
            'vendorWalletBalance', 'walletTransactions', 'withdrawals', 'percentageIncome', 'withdrawalStatus'
        ));
    }

    public function withdraw(Request $request)
    {
        $user = Auth::user();
        $vendor_id = Vendor::where('user_id', $user->id)->value('id');

        // Check if user has payment method set up
        if (!$user->account_no && !$user->upi_id) {
            return back()->with('error', 'Please add bank account or UPI ID in your profile first');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:500|max:' . $user->vendor_wallet_balance,
            'payment_method' => 'required|in:bank,upi'
        ]);

        $percentageIncome = PercentageIncome::first();

        // Calculate vendor specific charge
        $chargePercent = $percentageIncome->vendor_withdraw_charge ?? 0;
        $adminCharge = $validated['amount'] * ($chargePercent / 100);
        $creditedAmount = $validated['amount'] - $adminCharge;

        // Create withdrawal request
        VendorWithdrawal::create([
            'user_id' => $user->id,
            'vendor_id' => $vendor_id,
            'user_ulid' => $user->ulid,
            'total_amount' => $validated['amount'],
            'vendor_charge' => $adminCharge,
            'credited_amount' => $creditedAmount,
            'status' => 'pending',
            'payment_method' => $validated['payment_method'],
        ]);

        // Deduct from Vendor Wallet
        $user->vendor_wallet_balance -= $validated['amount'];
        $user->save();

        // Create a Debit Transaction Record
        VendorWalletTransaction::create([
            'user_id' => $user->id,
            'user_ulid' => $user->ulid,
            'amount' => -$validated['amount'], // Negative for debit
            'notes' => 'Withdrawal Request Submitted',
            'balance' => $user->vendor_wallet_balance
        ]);

        return redirect()->back()->with('success', 'Vendor withdrawal request submitted successfully!');
    }


    // Admin Side Functionality 
    public function vendorWithdrawalRequests()
    {
        // Fetch pending requests with User and Vendor relationship
        $withdrawals = VendorWithdrawal::with(['user', 'vendor'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Fetch processed (approved/rejected) requests
        $allWithdrawls = VendorWithdrawal::with(['user', 'vendor'])
            ->where('status', '!=', 'pending')
            ->latest()
            ->paginate(10);

        return view('admin.wallet.vendor_withdrawals', compact('withdrawals', 'allWithdrawls'));
    }

    public function approveVendorWithdrawal($id)
    {
        $withdrawal = VendorWithdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Request is already processed.');
        }

        // ==========================================
        // IMPORTANT LOGIC CHECK
        // ==========================================
        // अगर आपने Withdrawal Request सबमिट करते समय ही vendor_wallet_balance से 
        // पैसे काट लिए थे (जैसा पिछले कोड में था), तो यहाँ दोबारा decrement मत करें। 
        // अगर नहीं काटे थे, तो नीचे की 2 लाइनें Un-comment कर लें:
        // 
        // $user = $withdrawal->user;
        // $user->decrement('vendor_wallet_balance', $withdrawal->total_amount);
        // ==========================================

        $withdrawal->status = 'approved';
        $withdrawal->save();

        return back()->with('success', 'Vendor Withdrawal approved successfully');
    }

    public function rejectVendorWithdrawal($id)
    {
        $withdrawal = VendorWithdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Request is already processed.');
        }

        $user = $withdrawal->user;

        // ==========================================
        // REFUND LOGIC
        // ==========================================
        // चूँकि रिक्वेस्ट करते समय पैसे कट गए थे, तो रिजेक्ट होने पर वापस वेंडर के वॉलेट में डालने होंगे:
        $user->increment('vendor_wallet_balance', $withdrawal->total_amount);

        // Create a refund transaction record for the vendor
        VendorWalletTransaction::create([
            'user_id'   => $user->id,
            'user_ulid' => $user->ulid,
            'amount'    => $withdrawal->total_amount, // Refund (+ amount)
            'notes'     => 'Refund: Withdrawal Request Rejected',
            'balance'   => $user->vendor_wallet_balance,
        ]);

        $withdrawal->status = 'rejected';
        $withdrawal->save();

        return back()->with('success', 'Vendor Withdrawal rejected and amount refunded.');
    }
}