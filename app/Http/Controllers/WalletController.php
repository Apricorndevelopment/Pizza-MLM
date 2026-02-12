<?php

namespace App\Http\Controllers;

use App\Models\Wallet2Transaction;
use App\Models\MoneyWithdrawl;
use App\Models\User;
use App\Models\Wallet1Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class WalletController extends Controller
{

    public function index(Request $request)
    {
        // --- Wallet 1 Logic ---
        $wallet1Query = Wallet1Transaction::with(['user', 'admin'])
            ->where('admin_id', 1);

        // Wallet 1 Filters
        if ($request->has('wallet1_ulid') && !empty($request->wallet1_ulid)) {
            $wallet1Query->whereHas('user', function ($q) use ($request) {
                $q->where('ulid', 'like', '%' . $request->wallet1_ulid . '%');
            });
        }

        if ($request->has('wallet1_type') && !empty($request->wallet1_type)) {
            if ($request->wallet1_type === 'credit') {
                $wallet1Query->where('wallet1', '>=', 0);
            } elseif ($request->wallet1_type === 'debit') {
                $wallet1Query->where('wallet1', '<', 0);
            }
        }

        if ($request->has('wallet1_start_date') && !empty($request->wallet1_start_date)) {
            $wallet1Query->whereDate('created_at', '>=', $request->wallet1_start_date);
        }

        if ($request->has('wallet1_end_date') && !empty($request->wallet1_end_date)) {
            $wallet1Query->whereDate('created_at', '<=', $request->wallet1_end_date);
        }

        // Pagination for Wallet 1 (Page Name: wallet1_page)
        $wallet1Transactions = $wallet1Query->latest()->paginate(10, ['*'], 'wallet1_page');


        // --- Wallet 2 Logic ---
        $wallet2Query = Wallet2Transaction::with(['user', 'admin'])->where('admin_id', 1); // Uncomment if Wallet 2 also needs admin_id check

        // Wallet 2 Filters
        if ($request->has('wallet2_ulid') && !empty($request->wallet2_ulid)) {
            $wallet2Query->whereHas('user', function ($q) use ($request) {
                $q->where('ulid', 'like', '%' . $request->wallet2_ulid . '%');
            });
        }

        if ($request->has('wallet2_type') && !empty($request->wallet2_type)) {
            if ($request->wallet2_type === 'credit') {
                $wallet2Query->where('wallet2', '>=', 0);
            } elseif ($request->wallet2_type === 'debit') {
                $wallet2Query->where('wallet2', '<', 0);
            }
        }

        if ($request->has('wallet2_start_date') && !empty($request->wallet2_start_date)) {
            $wallet2Query->whereDate('created_at', '>=', $request->wallet2_start_date);
        }

        if ($request->has('wallet2_end_date') && !empty($request->wallet2_end_date)) {
            $wallet2Query->whereDate('created_at', '<=', $request->wallet2_end_date);
        }

        // Pagination for Wallet 2 (Page Name: wallet2_page)
        $wallet2Transactions = $wallet2Query->latest()->paginate(10, ['*'], 'wallet2_page');

        return view('admin.wallet.viewwallet', compact('wallet1Transactions', 'wallet2Transactions'));
    }

    public function viewAllTransactions(Request $request)
    {
        $query = Wallet1Transaction::with(['user', 'admin'])
            ->latest();

        // Add search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('ulid', 'like', '%' . $search . '%');
            });
        }
        $wallet1Transactions = $query->paginate(15);
        if ($request->ajax()) {
            return response()->json($wallet1Transactions);
        }

        return view('admin.wallet.allTransaction', compact('wallet1Transactions'));
    }

    public function getUserByUlid(Request $request)
    {
        $user = User::where('ulid', $request->ulid)->first(['name', 'email', 'wallet1_balance', 'wallet2_balance']);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'wallet1_balance' => $user->wallet1_balance,
                'wallet2_balance' => $user->wallet2_balance
            ]
        ]);
    }

    public function addWallet1(Request $request)
    {
        $request->validate([
            'ulid' => 'required',
            'wallet1' => 'required|numeric',
            'notes' => 'nullable|string'
        ]);

        $user = User::where('ulid', $request->ulid)->firstOrFail();

        $adminId = Auth::guard('admin')->id();

        Wallet1Transaction::create([
            'user_id' => $user->id,
            'user_ulid' => $user->ulid,
            'wallet1' => $request->wallet1,
            'notes' => $request->notes,
            'admin_id' => $adminId,
            'balance' => $user->wallet1_balance + $request->wallet1
        ]);

        $user->increment('wallet1_balance', $request->wallet1);

        return redirect()->back()->with('success', 'Wallet1 transaction completed successfully.');
    }


    public function addWallet2(Request $request)
    {
        $request->validate([
            'ulid' => 'required',
            'wallet2' => 'required|numeric',
            'notes' => 'nullable|string'
        ]);

        $user = User::where('ulid', $request->ulid)->firstOrFail();

        $adminId = Auth::guard('admin')->id();

        Wallet2Transaction::create([
            'user_id' => $user->id,
            'user_ulid' => $user->ulid,
            'wallet2' => $request->wallet2,
            'notes' => $request->notes,
            'admin_id' => $adminId,
            'balance' => $user->wallet2_balance + $request->wallet2
        ]);

        $user->increment('wallet2_balance', $request->wallet2);

        return redirect()->back()->with('success', 'Wallet2 transaction completed successfully.');
    }


    // WithdrawalController.php
    public function withdrawWallet1(Request $request)
    {
        $user = Auth::user();

        // Check if user has payment method set up
        if (!$user->account_no && !$user->upi_id) {
            return back()->with('error', 'Please add bank account or UPI ID in your profile first');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:500|max:' . $user->wallet1_balance,
            'payment_method' => 'required|in:bank,upi'
        ]);

        // Calculate charges (5% each)
        $adminCharge = $validated['amount'] * 0.05;
        $tdsCharge = $validated['amount'] * 0.05;
        $creditedAmount = $validated['amount'] - $adminCharge - $tdsCharge;

        // Create withdrawal request
        $withdrawal = MoneyWithdrawl::create([
            'user_id' => $user->id,
            'user_ulid' => $user->ulid,
            'total_amount' => $validated['amount'],
            'admin_charge' => $adminCharge,
            'tds_charge' => $tdsCharge,
            'credited_amount' => $creditedAmount,
            'status' => 'pending',
            'payment_method' => $validated['payment_method'],
        ]);

        return redirect()->route('user.viewwallet')->with('success', 'Withdrawal request submitted successfully!');
    }

    public function viewUserWithdrawals()
    {

        return view('user.viewwallet', compact('withdrawals'));
    }

    public function viewWithdrawlRequest()
    {
        $withdrawals = MoneyWithdrawl::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $allWithdrawls = MoneyWithdrawl::with('user')
            ->where('status', '!=', 'pending')
            ->latest()
            ->paginate(10);

        return view('admin.wallet.withdrawl', compact('withdrawals', 'allWithdrawls'));
    }

    public function approveWithdrawlRequest($id)
    {
        $withdrawal = MoneyWithdrawl::findOrFail($id);

        // Deduct wallet1 from user
        $user = $withdrawal->user;
        $user->decrement('wallet1_balance', $withdrawal->total_amount);
        $user->save();

        // Update withdrawal status
        $withdrawal->status = 'approved';
        $withdrawal->save();

        return back()->with('success', 'Withdrawal approved successfully');
    }

    public function rejectWithdrawlRequest($id)
    {
        $withdrawal = MoneyWithdrawl::findOrFail($id);
        $withdrawal->status = 'rejected';
        $withdrawal->save();

        return back()->with('success', 'Withdrawal rejected');
    }

    
}