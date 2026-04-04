<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Imported Log Facade
use App\Models\FundRequest;
use App\Models\Admin;
use App\Models\User;
use App\Models\Wallet1Transaction;

class FundRequestController extends Controller
{
    // ================= USER METHODS =================

    // Step 1: Show Add Money Page (Form + Admin QR)
    public function showAddMoneyForm()
    {
        Log::info('FundRequestController@showAddMoneyForm: Entering method.');

        // Fetch Admin's UPI details
        Log::info('FundRequestController@showAddMoneyForm: Fetching first Admin record.');
        $admin = Admin::first();
        Log::info('FundRequestController@showAddMoneyForm: Admin record fetched.', ['admin_id' => $admin ? $admin->id : 'null']);

        // Fetch user's history
        $userId = Auth::id();
        Log::info('FundRequestController@showAddMoneyForm: Fetching fund request history for user.', ['user_id' => $userId]);

        $history = FundRequest::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        Log::info('FundRequestController@showAddMoneyForm: History fetched.', ['count' => $history->count()]);

        Log::info('FundRequestController@showAddMoneyForm: Returning view user.wallet.add-money.');
        return view('user.wallet.add-money', compact('admin', 'history'));
    }

    // Step 2: Handle User Submission
    public function storeFundRequest(Request $request)
    {
        Log::info('FundRequestController@storeFundRequest: Entering method.');
        Log::info('FundRequestController@storeFundRequest: Request Data received.', $request->except('receipt_image'));

        Log::info('FundRequestController@storeFundRequest: Validating request data.');
        try {
            $request->validate([
                'amount' => 'required|numeric|min:1',
                'transaction_id' => 'required|string|unique:fund_requests,transaction_id',
                'sender_upi_id' => 'required|string',
                'payment_method' => 'required|string',
                'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120'
            ],[
                'transaction_id.unique' => 'The transaction ID has already been used in another request. Please double-check and try again.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // This will log the exact error message (e.g., "The transaction id has already been taken.")
            Log::error('Validation Failed Details:', $e->errors());
            throw $e; // Re-throw to allow Laravel to handle the redirect
        }
        Log::info('FundRequestController@storeFundRequest: Validation passed.');

        $imagePath = null;
        Log::info('FundRequestController@storeFundRequest: Checking for receipt image file.');

        if ($request->hasFile('receipt_image')) {
            Log::info('FundRequestController@storeFundRequest: Receipt image found. Processing upload.');
            $file = $request->file('receipt_image');
            $filename = 'receipt_' . time() . '.' . $file->getClientOriginalExtension();

            Log::info('FundRequestController@storeFundRequest: Moving file to storage/receipts.', ['filename' => $filename]);
            $file->move(public_path('storage/receipts'), $filename);

            $imagePath = 'receipts/' . $filename;
            Log::info('FundRequestController@storeFundRequest: File moved successfully.', ['path' => $imagePath]);
        } else {
            Log::info('FundRequestController@storeFundRequest: No receipt image provided.');
        }

        Log::info('FundRequestController@storeFundRequest: Creating FundRequest database record.');
        $fundRequest = FundRequest::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'transaction_id' => $request->transaction_id,
            'sender_upi_id' => $request->sender_upi_id,
            'payment_method' => $request->payment_method,
            'receipt_image' => $imagePath,
            'status' => 'pending'
        ]);
        Log::info('FundRequestController@storeFundRequest: Record created successfully.', ['id' => $fundRequest->id]);

        Log::info('FundRequestController@storeFundRequest: Redirecting back with success message.');
        return redirect()->back()->with('success', 'Fund request submitted successfully! Waiting for approval.');
    }

    // ================= ADMIN METHODS =================

    // Step 3: Show Requests to Admin
    public function listRequests(Request $request)
    {
        Log::info('FundRequestController@listRequests: Entering method.');

        $status = $request->query('status', 'pending');
        Log::info('FundRequestController@listRequests: Filtering by status.', ['status' => $status]);

        Log::info('FundRequestController@listRequests: Querying FundRequests with user relation.');
        $requests = FundRequest::with('user')
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        Log::info('FundRequestController@listRequests: Query executed.', ['count' => $requests->count(), 'total' => $requests->total()]);

        Log::info('FundRequestController@listRequests: Returning view admin.wallet.fund-requests.');
        return view('admin.wallet.fund-requests', compact('requests', 'status'));
    }

    // Step 4: Approve or Reject Request
    public function updateStatus(Request $request, $id)
    {
        Log::info('FundRequestController@updateStatus: Entering method.', ['id' => $id]);
        Log::info('FundRequestController@updateStatus: Request Data.', $request->all());

        Log::info('FundRequestController@updateStatus: Validating action and remark.');
        $request->validate([
            'action' => 'required|in:approve,reject',
            'remark' => 'nullable|string'
        ]);
        Log::info('FundRequestController@updateStatus: Validation passed.');

        Log::info('FundRequestController@updateStatus: Finding FundRequest by ID.');
        $fundRequest = FundRequest::findOrFail($id);
        Log::info('FundRequestController@updateStatus: FundRequest found.', ['current_status' => $fundRequest->status]);

        if ($fundRequest->status !== 'pending') {
            Log::warning('FundRequestController@updateStatus: Request already processed.', ['id' => $id, 'status' => $fundRequest->status]);
            return back()->with('error', 'This request has already been processed.');
        }

        if ($request->action === 'reject') {
            Log::info('FundRequestController@updateStatus: Action is REJECT.');

            $fundRequest->update([
                'status' => 'rejected',
                'admin_remark' => $request->remark
            ]);
            Log::info('FundRequestController@updateStatus: FundRequest status updated to rejected.');

            return back()->with('success', 'Request rejected.');
        }

        // --- APPROVAL LOGIC ---
        Log::info('FundRequestController@updateStatus: Action is APPROVE. Starting Transaction.');
        DB::beginTransaction();
        try {
            // 1. Update Request Status
            Log::info('FundRequestController@updateStatus: Updating status to approved.');
            $fundRequest->update([
                'status' => 'approved',
                'admin_remark' => 'Approved by Admin'
            ]);

            // 2. Add Money to User Wallet
            Log::info('FundRequestController@updateStatus: Finding User to credit wallet.', ['user_id' => $fundRequest->user_id]);
            $user = User::find($fundRequest->user_id);

            if ($user) {
                Log::info('FundRequestController@updateStatus: Incrementing user wallet1_balance.', ['amount' => $fundRequest->amount]);
                $user->increment('wallet1_balance', $fundRequest->amount);
                Log::info('FundRequestController@updateStatus: User wallet incremented.');
            } else {
                Log::error('FundRequestController@updateStatus: User not found!', ['user_id' => $fundRequest->user_id]);
                throw new \Exception('User not found');
            }

            // 3. Log Transaction
            Log::info('FundRequestController@updateStatus: Creating Wallet1Transaction record.');
            Wallet1Transaction::create([
                'user_id' => $user->id,
                'user_ulid' => $user->ulid,
                'wallet1' => $fundRequest->amount, // Positive value for Credit
                'balance' => $user->wallet1_balance,
                'notes' => "Fund Added via Request (Txn: {$fundRequest->transaction_id})",
                'admin_id' => Auth::guard('admin')->id()
            ]);
            Log::info('FundRequestController@updateStatus: Wallet1Transaction created.');

            DB::commit();
            Log::info('FundRequestController@updateStatus: Database Transaction Committed.');

            return back()->with('success', 'Request approved and money added to user wallet.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('FundRequestController@updateStatus: Exception occurred! Rolling back transaction.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
