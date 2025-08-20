<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PackageTransaction;
use App\Models\Product;
use App\Models\User;
use App\Models\StockTransfer;
use App\Models\UserPackageInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function showTransferForm()
    {
        $products = Product::all();
        $stockTransfers = StockTransfer::with(['receiver', 'product'])
            ->where('sender_type', 'admin')
            ->orWhere('receiver_ulid', Auth::user()->ulid)
            ->get();

        return view('admin.stock.stockTransfer', compact('products', 'stockTransfers'));
    }

    public function searchUser(Request $request)
    {
        $request->validate(['ulid' => 'required|string']);

        $user = User::where('ulid', $request->ulid)->first();

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
                'ulid' => $user->ulid
            ]
        ]);
    }

    public function transferStock(Request $request)
    {
        $request->validate([
            'receiver_ulid' => 'required|string',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'location' => 'nullable|string'
        ]);

        // Get the receiver's current balance for this product and location
        $currentInventory = UserPackageInventory::where([
            'user_ulid' => $request->receiver_ulid,
            'product_id' => $request->product_id,
            'location' => $request->location
        ])->first();

        $currentBalance = $currentInventory ? $currentInventory->quantity : 0;
        $receiverBalance = $currentBalance + $request->quantity;

        // Create stock transfer record with receiver balance
        StockTransfer::create([
            'sender_type' => 'admin',
            'sender_id' => auth('admin')->id(),
            'receiver_ulid' => $request->receiver_ulid,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'sender_balance' => 0,
            'receiver_balance' => $receiverBalance, 
            'notes' => $request->notes,
            'from_location' => $request->location,
            'to_location' => $request->location,
            'status' => 'completed'
        ]);

        // Update or create user inventory
        UserPackageInventory::updateOrCreate(
            [
                'user_ulid' => $request->receiver_ulid,
                'product_id' => $request->product_id,
                'location' => $request->location
            ],
            [
                'quantity' => DB::raw("quantity + {$request->quantity}")
            ]
        );

        return back()->with('success', 'Stock transferred successfully');
    }

    public function viewAdminStock()
    {
        $stocks = UserPackageInventory::with('product', 'user')
            ->where('quantity', '>', 0)
            ->get();

        return view('admin.stock.viewStock', compact('stocks'));
    }





    //User stock transfer methods

    public function showUserTransferForm()
    {
        $user = Auth::user();
        $products = UserPackageInventory::with('product')
            ->where('user_ulid', $user->ulid)
            ->where('quantity', '>', 0)
            ->get()
            ->pluck('product')
            ->filter();

        $breadcrumbs = [
            ['title' => 'Manage Stock', 'url' => route('user.stock.form')],
            ['title' => 'Transfer Stock', 'url' => route('user.stock.form')]
        ];

        return view('user.stock.stockTransfer', compact('products', 'breadcrumbs'));
    }

    public function searchUserInUserSide(Request $request)
    {
        $request->validate(['ulid' => 'required|string']);

        $user = User::where('ulid', $request->ulid)
            ->where('ulid', '!=', Auth::user()->ulid)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found or cannot transfer to yourself'
            ]);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'ulid' => $user->ulid
            ]
        ]);
    }

    public function transferStockUserPanel(Request $request)
    {
        $request->validate([
            'receiver_ulid' => 'required|string',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'to_location' => 'required|string'
        ]);

        $user = Auth::user();

        // Check inventory
        $inventory = UserPackageInventory::where([
            'user_ulid' => $user->ulid,
            'product_id' => $request->product_id
        ])->first();

        if (!$inventory || $inventory->quantity < $request->quantity) {
            return back()->with('error', 'Insufficient stock for transfer');
        }

        $from_location = $inventory->location;

        DB::transaction(function () use ($user, $request, $from_location) {
            // Deduct from sender
            UserPackageInventory::where([
                'user_ulid' => $user->ulid,
                'product_id' => $request->product_id
            ])->decrement('quantity', $request->quantity);

            // Add to receiver
            UserPackageInventory::updateOrCreate(
                [
                    'user_ulid' => $request->receiver_ulid,
                    'product_id' => $request->product_id
                ],
                [
                    'quantity' => DB::raw("quantity + {$request->quantity}"),
                    'location' => $request->to_location
                ]
            );

            // Record transfer
            StockTransfer::create([
                'sender_type' => 'user',
                'sender_id' => $user->ulid,
                'receiver_ulid' => $request->receiver_ulid,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'notes' => $request->notes,
                'from_location' => $from_location,
                'to_location' => $request->to_location,
                'status' => 'completed'
            ]);
        });

        return back()->with('success', 'Stock transferred successfully');
    }

    public function stockTransferHistory()
    {
        $user = Auth::user();

        $transfers = StockTransfer::with(['receiver', 'product'])
            ->where('sender_type', 'user')
            ->orwhere('sender_id', $user->ulid)
            ->orWhere('receiver_ulid', $user->ulid)
            ->latest()
            ->paginate(10);

        $breadcrumbs = [
            ['title' => 'Manage Stock', 'url' => route('user.viewStock')],
            ['title' => 'View Stock History', 'url' => route('user.viewStock')]
        ];

        return view('user.stock.stockHistory', compact('transfers', 'breadcrumbs'));
    }

    public function showCouponTransferForm()
    {
        $user = Auth::user();
        $products = UserPackageInventory::with('product')
            ->where('user_ulid', $user->ulid)
            ->where('quantity', '>', 0)
            ->get()
            ->pluck('product')
            ->filter();

        $breadcrumbs = [
            ['title' => 'Manage Stock', 'url' => route('user.stock.coupon-transfer')],
            ['title' => 'Coupon Stock Transfer', 'url' => route('user.stock.coupon-transfer')]
        ];
        return view('user.stock.couponStock', compact('products', 'breadcrumbs'));
    }

    public function validateCoupon(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);

        $coupon = strtoupper(trim($request->coupon_code));
        $pattern = '/^GEO(\d+)PQ(\d+)([A-Za-z]*)$/i';

        if (!preg_match($pattern, $coupon, $matches)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon format. Correct format: GEO{user_id}PQ{quantity}'
            ]);
        }

        $userId = $matches[1];
        $quantity = $matches[2];

        // Validate user ID is numeric and exists
        if (!is_numeric($userId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid user ID in coupon'
            ]);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        if (!is_numeric($quantity)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid quantity in coupon'
            ]);
        }

        if ($user->id == Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot transfer to yourself'
            ]);
        }

        $package = PackageTransaction::where('user_id', $user->id)
            ->latest()
            ->first();

        if ($package && $package->status == 'delivered') {
            return response()->json([
                'success' => false,
                'message' => 'Package already delivered'
            ]);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'name' => $user->name,
                'ulid' => $user->ulid
            ],
            'quantity' => (int)$quantity,
            'coupon_code' => $coupon
        ]);
    }

    public function transferStockByCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
            'receiver_ulid' => 'required|string',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'to_location' => 'required|string'
        ]);

        $user = Auth::user();

        // Verify coupon format again
        $coupon = $request->coupon_code;
        if (!preg_match('/^GEO\d+PQ\d+/i', $coupon)) {
            return back()->with('error', 'Invalid coupon format');
        }

        // Check inventory
        $inventory = UserPackageInventory::where([
            'user_ulid' => $user->ulid,
            'product_id' => $request->product_id
        ])->first();

        if (!$inventory || $inventory->quantity < $request->quantity) {
            return back()->with('error', 'Insufficient stock for transfer');
        }

        $from_location = $inventory->location;

        DB::transaction(function () use ($user, $request, $from_location) {
            // Deduct from sender
            UserPackageInventory::where([
                'user_ulid' => $user->ulid,
                'product_id' => $request->product_id
            ])->decrement('quantity', $request->quantity);

            // Add to receiver
            UserPackageInventory::updateOrCreate(
                [
                    'user_ulid' => $request->receiver_ulid,
                    'product_id' => $request->product_id
                ],
                [
                    'quantity' => DB::raw("quantity + {$request->quantity}"),
                    'location' => $request->to_location
                ]
            );

            // Record transfer
            StockTransfer::create([
                'sender_type' => 'user',
                'sender_id' => $user->ulid,
                'receiver_ulid' => $request->receiver_ulid,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'notes' => $request->notes,
                'from_location' => $from_location,
                'to_location' => $request->to_location,
                'status' => 'completed'
            ]);
            PackageTransaction::where('user_id', function ($query) use ($request) {
                $query->select('id')
                    ->from('users')
                    ->where('ulid', $request->receiver_ulid);
            })
                ->latest()
                ->limit(1)
                ->update(['status' => 'delivered']);
        });

        return back()->with('success', 'Stock transferred successfully using coupon');
    }



    public function viewUserStocks()
    {
        $stocks = UserPackageInventory::with('product', 'user')
            ->where('quantity', '>', 0)
            ->get();

        $breadcrumbs = [
            ['title' => 'Manage Stock', 'url' => route('user.allStocks')],
            ['title' => 'Coupon Stock Transfer', 'url' => route('user.allStocks')]
        ];
        return view('user.stock.viewAllStock', compact('stocks'));
    }
}
