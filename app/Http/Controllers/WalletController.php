<?php

namespace App\Http\Controllers;

use App\Models\Wallet2Transaction;
use App\Models\MoneyWithdrawl;
use App\Models\PercentageIncome;
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

        $percentageIncome = PercentageIncome::first();

        // Calculate charges (5% each)
        $adminCharge = $validated['amount'] * ($percentageIncome->admin_charge / 100);
        $tdsCharge = $validated['amount'] * ($percentageIncome->tds_charge / 100);
        $creditedAmount = $validated['amount'] - $adminCharge - $tdsCharge;

        // Create withdrawal request
        MoneyWithdrawl::create([
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

    public function toggleWithdrawalStatus(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        // Toggle the status
        $admin->is_withdrawal_open = $request->status;
        $admin->save();

        return response()->json([
            'success' => true,
            'message' => 'Withdrawal status updated successfully.',
            'isOpen' => $admin->is_withdrawal_open
        ]);
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




    //Transfer Wallet1 to Downline User in the user panel

    public function showTransferForm()
    {
        $user = Auth::user();
        // Get direct downline (level 1)
        $downlineUsers = User::where('sponsor_id', $user->ulid)->get();

        $breadcrumbs = [
            ['title' => 'Wallet', 'url' => route('user.transferWallet1Form')],
            ['title' => 'Transfer Wallet', 'url' => route('user.transferWallet1Form')]
        ];

        return view('user.transferWallet1', compact('user', 'downlineUsers', 'breadcrumbs'));
    }

    public function searchDownlineUser(Request $request)
    {
        $request->validate([
            'ulid' => 'required|string',
            'wallet_type' => 'required|in:wallet1,wallet2' // Naya validation
        ]);

        $user = Auth::user();
        $downlineUser = User::where('ulid', $request->ulid)->first();

        if (!$downlineUser) {
            return response()->json(['success' => false, 'message' => 'User not found']);
        }

        if (!$this->isInDownline($user->ulid, $downlineUser)) {
            return response()->json(['success' => false, 'message' => 'User is not in your downline.']);
        }

        // Wallet type ke basis par balance pick karein
        $balance = ($request->wallet_type === 'wallet1')
            ? $downlineUser->wallet1_balance
            : $downlineUser->wallet2_balance;

        return response()->json([
            'success' => true,
            'user' => [
                'name' => $downlineUser->name,
                'email' => $downlineUser->email,
                'balance' => $balance, // Dynamic balance
                'ulid' => $downlineUser->ulid
            ]
        ]);
    }

    // Recursive function to check downline relationship
    private function isInDownline($sponsorUlid, $user)
    {
        if ($user->sponsor_id === $sponsorUlid) {
            return true;
        }

        if (!$user->sponsor_id) {
            return false;
        }

        $sponsor = User::where('ulid', $user->sponsor_id)->first();
        if (!$sponsor) {
            return false;
        }

        return $this->isInDownline($sponsorUlid, $sponsor);
    }

    public function transferWallet1(Request $request)
    {
        $request->validate([
            'ulid' => 'required|string',
            'wallet1' => 'required|numeric|min:1',
        ]);

        $sender = Auth::user();
        $receiver = User::where('ulid', $request->ulid)->firstOrFail();

        // Verify receiver is in sender's downline
        if (!$this->isInDownline($sender->ulid, $receiver)) {
            return back()->with('error', 'User is not in your downline');
        }

        // Check if sender has enough wallet1
        if ($sender->wallet1_balance < $request->wallet1) {
            return back()->with('error', 'Insufficient personal wallet balance');
        }

        DB::transaction(function () use ($sender, $receiver, $request) {
            // Deduct from sender
            Wallet1Transaction::create([
                'user_id' => $sender->id,
                'wallet1' => -$request->wallet1,
                'notes' => 'Wallet Point Transfered to ' . $receiver->name . ' (' . $receiver->ulid . ')',
                'balance' => $sender->wallet1_balance - $request->wallet1
            ]);
            DB::table('users')
                ->where('id', $sender->id)
                ->decrement('wallet1_balance', $request->wallet1);
            // $sender->decrement('wallet1_balance', $request->wallet1);

            // Add to receiver
            Wallet1Transaction::create([
                'user_id' => $receiver->id,
                'wallet1' => $request->wallet1,
                'notes' => 'Recieved Wallet Point from ' . $sender->name . ' (' . $sender->ulid . ')',
                'balance' => $receiver->wallet1_balance + $request->wallet1
            ]);
            $receiver->increment('wallet1_balance', $request->wallet1);
        });

        return back()->with('success', 'Money transferred successfully');
    }

    // Transfer Wallet 2 to Downline
    public function transferWallet2(Request $request)
    {
        $request->validate([
            'ulid' => 'required|string',
            'wallet2' => 'required|numeric|min:1',
        ]);

        $sender = Auth::user();
        $receiver = User::where('ulid', $request->ulid)->firstOrFail();

        // Verify receiver is in sender's downline
        if (!$this->isInDownline($sender->ulid, $receiver)) {
            return back()->with('error', 'User is not in your downline');
        }

        // Check if sender has enough wallet2
        if ($sender->wallet2_balance < $request->wallet2) {
            return back()->with('error', 'Insufficient Wallet 2 balance');
        }

        DB::transaction(function () use ($sender, $receiver, $request) {
            // Deduct from sender
            \App\Models\Wallet2Transaction::create([
                'user_id' => $sender->id,
                'user_ulid' => $sender->ulid,
                'wallet2' => -$request->wallet2,
                'notes' => 'Wallet 2 Transfer to ' . $receiver->name . ' (' . $receiver->ulid . ')',
                'balance' => $sender->wallet2_balance - $request->wallet2
            ]);

            DB::table('users')->where('id', $sender->id)->decrement('wallet2_balance', $request->wallet2);

            // Add to receiver
            \App\Models\Wallet2Transaction::create([
                'user_id' => $receiver->id,
                'user_ulid' => $receiver->ulid,
                'wallet2' => $request->wallet2,
                'notes' => 'Received Wallet 2 from ' . $sender->name . ' (' . $sender->ulid . ')',
                'balance' => $receiver->wallet2_balance + $request->wallet2
            ]);

            DB::table('users')->where('id', $receiver->id)->increment('wallet2_balance', $request->wallet2);
        });

        return back()->with('success', 'Wallet 2 transferred successfully');
    }
}
