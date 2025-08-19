<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyTransaction;
use App\Models\MoneyWithdrawl;
use App\Models\User;
use App\Models\PointsTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class WalletController extends Controller
{

    public function index()
    {
        // Recent transactions for both tabs
        // $pointsTransactions = PointsTransaction::with(['user', 'admin'])
        //                     ->latest()
        //                     ->take(10)
        //                     ->get();

        $pointsTransactions = PointsTransaction::with(['user', 'admin'])
            ->where('admin_id', 1)
            ->latest()
            ->take(10)
            ->get();

        $loyaltyTransactions = LoyaltyTransaction::with(['user', 'admin'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.wallet.viewwallet', compact('pointsTransactions', 'loyaltyTransactions'));
    }

    public function viewAllTransactions(Request $request)
    {
        $query = PointsTransaction::with(['user', 'admin'])
            ->latest();

        // Add search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('ulid', 'like', '%' . $search . '%');
            });
        }

        $pointsTransactions = $query->paginate(15);

        return view('admin.wallet.allTransaction', compact('pointsTransactions'));
    }

    public function getUserByUlid(Request $request)
    {
        $user = User::where('ulid', $request->ulid)->first(['name', 'email', 'points_balance', 'loyalty_balance']);

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
                'points_balance' => $user->points_balance,
                'loyalty_balance' => $user->loyalty_balance
            ]
        ]);
    }

    public function addPoints(Request $request)
    {
        $request->validate([
            'ulid' => 'required',
            'points' => 'required|numeric',
            'notes' => 'nullable|string'
        ]);

        $user = User::where('ulid', $request->ulid)->firstOrFail();

        $adminId = Auth::guard('admin')->id();

        PointsTransaction::create([
            'user_id' => $user->id,
            'points' => $request->points,
            'notes' => $request->notes,
            'admin_id' => $adminId,
            'balance' => $user->points_balance + $request->points
        ]);

        $user->increment('points_balance', $request->points);

        return redirect()->back()->with('success', 'Points transaction completed successfully.');
    }


    public function addLoyalty(Request $request)
    {
        $request->validate([
            'ulid' => 'required',
            'loyalty' => 'required|numeric',
            'notes' => 'nullable|string'
        ]);

        $user = User::where('ulid', $request->ulid)->firstOrFail();

        $adminId = Auth::guard('admin')->id();

        LoyaltyTransaction::create([
            'user_id' => $user->id,
            'loyalty' => $request->loyalty,
            'notes' => $request->notes,
            'admin_id' => $adminId
        ]);

        $user->increment('loyalty_balance', $request->loyalty);

        return redirect()->back()->with('success', 'Loyalty transaction completed successfully.');
    }

    
    // WithdrawalController.php
    public function withdrawPoints(Request $request)
    {
        $user = Auth::user();

        // Check if user has payment method set up
        if (!$user->account_no && !$user->upi_id) {
            return back()->with('error', 'Please add bank account or UPI ID in your profile first');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:500|max:' . $user->points_balance,
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

        return view('admin.wallet.withdrawl', compact('withdrawals','allWithdrawls'));
    }

    public function approveWithdrawlRequest($id)
    {
        $withdrawal = MoneyWithdrawl::findOrFail($id);

        // Deduct points from user
        $user = $withdrawal->user;
        $user->decrement('points_balance', $withdrawal->total_amount);
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





    //Transfer Points to Downline User in the user panel

    public function showTransferForm()
    {
        $user = Auth::user();
        // Get direct downline (level 1)
        $downlineUsers = User::where('sponsor_id', $user->ulid)->get();

         $breadcrumbs = [
            ['title' => 'Wallet', 'url' => route('user.transferPointsForm')],
            ['title' => 'Transfer Wallet', 'url' => route('user.transferPointsForm')]
        ];

        return view('user.transferPoints', compact('user', 'downlineUsers','breadcrumbs'));
    }

    public function searchDownlineUser(Request $request)
    {
        $request->validate(['ulid' => 'required|string']);

        $user = Auth::user();
        $downlineUser = User::where('ulid', $request->ulid)->first();

        if (!$downlineUser) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        // Check if user is in downline (recursive check)
        if (!$this->isInDownline($user->ulid, $downlineUser)) {
            return response()->json([
                'success' => false,
                'message' => 'This user is not in your downline. Please check the ULID and try again.'
            ]);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'name' => $downlineUser->name,
                'email' => $downlineUser->email,
                'points_balance' => $downlineUser->points_balance,
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

    public function transferPoints(Request $request)
    {
        $request->validate([
            'ulid' => 'required|string',
            'points' => 'required|numeric|min:1',
        ]);

        $sender = Auth::user();
        $receiver = User::where('ulid', $request->ulid)->firstOrFail();

        // Verify receiver is in sender's downline
        if (!$this->isInDownline($sender->ulid, $receiver)) {
            return back()->with('error', 'User is not in your downline');
        }

        // Check if sender has enough points
        if ($sender->points_balance < $request->points) {
            return back()->with('error', 'Insufficient points balance');
        }

        DB::transaction(function () use ($sender, $receiver, $request) {
            // Deduct from sender
            PointsTransaction::create([
                'user_id' => $sender->id,
                'points' => -$request->points,
                'notes' => 'Wallet Point Transfered to ' . $receiver->name . ' (' . $receiver->ulid . ')',
                'balance' => $sender->points_balance - $request->points
            ]);
            DB::table('users')
                ->where('id', $sender->id)
                ->decrement('points_balance', $request->points);
            // $sender->decrement('points_balance', $request->points);

            // Add to receiver
            PointsTransaction::create([
                'user_id' => $receiver->id,
                'points' => $request->points,
                'notes' => 'Recieved Wallet Point from ' . $sender->name . ' (' . $sender->ulid . ')',
                'balance' => $receiver->points_balance + $request->points
            ]);
            $receiver->increment('points_balance', $request->points);
        });

        return back()->with('success', 'Points transferred successfully');
    }
}
