<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Order;
use App\Models\Package1;
use App\Models\ProductPackage;
use App\Models\ProductPackagePurchase;
use App\Models\PackageMonthlyDistribution;
use App\Models\Wallet1Transaction;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        // return view('admin.login');
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (
            Auth::guard('admin')->attempt($credentials)
        ) {
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['error' => 'Invalid credentials']);
    }

    public function dashboard()
    {
        // 1. User Statistics
        $totalUsers = User::count();
        $todayJoined = User::whereDate('created_at', Carbon::today())->count();
        $yesterdayJoined = User::whereDate('created_at', Carbon::yesterday())->count();
        $monthlyJoined = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $inactiveUsers = User::where('status', 'inactive')->count();

        // 2. Order Statistics
        $newPlacedOrders = Order::where('status', 'placed')->count();

        // Calculate Total Sales (Excluding cancelled orders)
        $totalSales = Order::where('status', '!=', 'rejected')->sum('total_amount');

        // 3. Recent Orders Table (Latest 6)
        $recentOrders = Order::with('user')->latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'todayJoined',
            'yesterdayJoined',
            'monthlyJoined',
            'inactiveUsers',
            'newPlacedOrders',
            'totalSales',
            'recentOrders'
        ));
    }

    public function toggleShopStatus(Request $request)
    {
        // Get the currently authenticated admin
        $admin = Auth::guard('admin')->user();

        // Toggle the status (if 1 make 0, if 0 make 1)
        $admin->isShopOpen = !$admin->isShopOpen;
        $admin->save();

        $statusMessage = $admin->isShopOpen ? 'Store is now Open!' : 'Store is now Closed.';

        return back()->with('success', $statusMessage);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        // return redirect()->route('admin.login');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Prevent browser back button cache
        return response()
            ->redirectToRoute('admin.login')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function profile()
    {
        $user = Auth::user();

        return view('admin.profiles.profile', ['user' => $user]);
    }

    public function edit()
    {
        $user = Auth::user();
        return view('admin.profiles.edit-profile', ['user' => $user]);
    }

    public function update(Request $request)
    {
        $authUser = Auth::user();
        $user = Admin::find($authUser->id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => [
                'nullable',
                'required_with:password,password_confirmation',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('The current password is incorrect.');
                    }
                }
            ],
            'password' => [
                'nullable',
                'required_with:current_password',
                'confirmed',
                'min:8',
                'different:current_password',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'password_confirmation' => 'required_with:password'
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];


        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::delete('public/' . $user->profile_picture);
            }

            // Define the directory path
            $directory = 'storage/profile-pictures';

            // Create the directory if it doesn't exist
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            // Get the file and extension
            $file = $request->file('profile_picture');
            $extension = $file->getClientOriginalExtension();

            // Create a unique filename
            $filename = uniqid() . '.' . $extension;

            // Move the file to the directory
            $file->move($directory, $filename);

            // Save the relative path to the database
            $user->profile_picture = 'profile-pictures/' . $filename;
        }

        if ($request->filled('current_password')) {

            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully!');
    }

    public function editPdf()
    {
        $breadcrumbs = [
            ['title' => 'Manage PDFs', 'url' => route('admin.pdf.edit')]
        ];

        return view('admin.edit-pdf', compact('breadcrumbs'));
    }

    public function updatePdf(Request $request)
    {
        $request->validate([
            'english_pdf' => 'nullable|file|mimes:pdf|max:102400',
            'hindi_pdf' => 'nullable|file|mimes:pdf|max:102400'
        ]);

        if ($request->hasFile('english_pdf')) {
            $englishPdf = $request->file('english_pdf');
            $englishPdf->move(public_path(), 'English-Geokranti.pdf');
        }

        if ($request->hasFile('hindi_pdf')) {
            $hindiPdf = $request->file('hindi_pdf');
            $hindiPdf->move(public_path(), 'Hindi-Geokranti.pdf');
        }

        return redirect()->route('admin.pdf.edit')
            ->with('success', 'PDF files updated successfully!');
    }

    public function managePhoto()
    {
        $photos = Gallery::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.addPhoto', compact('photos'));
    }

    public function addPhoto(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'photo' => 'required|mimes:jpeg,png,jpg,webp|max:5120'
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid() . '.' . $extension;
            $directory = 'storage/photos';

            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $file->move($directory, $filename);

            Gallery::create([
                'title' => $request->input('title'),
                'photo' =>  $filename
            ]);

            return redirect()->route('admin.photo.manage')->with('success', 'Photo uploaded successfully!');
        } else {
            return redirect()->back()->withErrors(['photo' => 'Photo upload failed. Please try again.']);
        }
    }

    public function deletePhoto($id)
    {
        $photo = Gallery::findOrFail($id);

        // Delete the photo file from storage
        $filePath = 'storage/photos/' . $photo->photo;
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // Delete the database record
        $photo->delete();

        return redirect()->route('admin.photo.manage')->with('success', 'Photo deleted successfully!');
    }

    public function viewmemeber(Request $request)
    {
        $status = $request->input('status', 'all');
        $ulid = $request->input('ulid');

        $member = User::when($status !== 'all', function ($query) use ($status) {
            return $query->where('status', $status);
        })->when($ulid, function ($query) use ($ulid) {
            return $query->where('ulid', 'LIKE', '%' . $ulid . '%');
        })
            ->paginate(10);

        // Append the search query and status filter to the pagination links
        $member->appends(['status' => $status, 'ulid' => $ulid]);

        return view('admin.members.viewmember', compact('member', 'status', 'ulid'));
    }

    public function editMember($id)
    {
        $member = User::findOrFail($id);
        return view('admin.members.editmember', compact('member'));
    }

    public function updateMember(Request $request, $id)
    {
        $member = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:15',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:8|confirmed'
        ]);

        $member->name = $request->name;
        $member->email = $request->email;
        $member->phone = $request->phone;
        $member->status = $request->status;
        $member->address = $request->address;
        $member->state = $request->state;

        if ($request->filled('password')) {
            $member->password = Hash::make($request->password);
        }

        $member->save();

        return redirect()->route('admin.viewmember')->with('success', 'Member updated successfully');
    }

    public function viewMemberDetails($id)
    {
        $member = User::findOrFail($id);
        return view('admin.members.view-details', compact('member'));
    }

    public function deleteMember($id)
    {
        DB::beginTransaction();
        try {
            $member = User::findOrFail($id);

            $sponsorIdOfDeletedMember = $member->sponsor_id;

            // Delete from all related tables
            DB::table('level_incomes')->where('user_id', $member->id)->delete();
            DB::table('login_activities')->where('user_id', $member->id)->delete();
            DB::table('loyalty_transactions')->where('user_id', $member->id)->delete();
            DB::table('maturity_monthly_deductions')->where('user_id', $member->id)->delete();
            DB::table('package2_purchases')->where('user_id', $member->id)->delete();
            DB::table('package_monthly_distributions')->where('user_id', $member->id)->delete();
            DB::table('package_transactions')->where('user_id', $member->id)->delete();
            DB::table('points_transactions')->where('user_id', $member->id)->delete();
            DB::table('royalty_rewards_income')->where('user_id', $member->id)->delete();
            DB::table('sales_stock')->where('user_id', $member->id)->delete();
            DB::table('user_package_inventories')->where('user_ulid', $member->ulid)->delete();

            // Update downline members to take on the sponsor of the deleted member
            User::where('sponsor_id', $member->ulid)->update(['sponsor_id' => $sponsorIdOfDeletedMember]);

            // Finally delete the user
            $member->forceDelete();

            DB::commit();

            return redirect()->route('admin.viewmember')->with('success', 'Member deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }


    public function networkSummary(Request $request)
    {
        $breadcrumbs = [
            ['title' => 'Network', 'url' => route('admin.network.summary')],
            ['title' => 'Network Summary', 'url' => route('admin.network.summary')]
        ];

        $admin = Auth::guard('admin')->user();

        // Get all downline users from admin's direct children
        $downlineUsers = $this->getAdminDownlineUsers($admin->auid);

        // Add level and purchase status to each user
        foreach ($downlineUsers as $user) {
            $user->level = $this->calculateLevelFromAdmin($admin->auid, $user->ulid);
        }

        // Get available designations for filter
        $designations = DB::table('percentage_rewards')
            ->pluck('rank')
            ->toArray();

        // Apply filters if requested
        if ($request->hasAny(['designation', 'status', 'start_date', 'end_date'])) {
            $downlineUsers = $this->applyFilters($downlineUsers, $request);
        }

        $downlineCollection = collect($downlineUsers)->sortBy([
            ['level', 'asc'],
            ['created_at', 'desc'],
        ]);

        // Paginate results (15 per page)
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        $currentItems = $downlineCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $paginatedUsers = new LengthAwarePaginator($currentItems, $downlineCollection->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'pageName' => 'page'
        ]);

        return view('admin.network-summary', compact('paginatedUsers', 'designations', 'breadcrumbs', 'admin'));
    }

    // Get all users in admin's downline (starting from direct children)
    private function getAdminDownlineUsers($adminAuid, &$results = [])
    {
        // Get direct children of admin
        $users = User::where('sponsor_id', $adminAuid)->get();

        foreach ($users as $user) {
            $results[] = $user; // Add current user
            $this->getDownlineUsers($user->ulid, $results); // Add children recursively
        }

        return $results;
    }

    // Get all users below a given ULID (recursive)
    private function getDownlineUsers($ulid, &$results = [])
    {
        $users = User::where('sponsor_id', $ulid)->get();

        foreach ($users as $user) {
            $results[] = $user; // Add current user
            $this->getDownlineUsers($user->ulid, $results); // Add children
        }

        return $results;
    }

    // Calculate the level of a target user relative to admin's AUID
    private function calculateLevelFromAdmin($adminAuid, $targetUlid, $level = 1)
    {
        if ($adminAuid === $targetUlid) {
            return 0; // This shouldn't happen as admin's AUID shouldn't match user ULID
        }

        $targetUser = User::where('ulid', $targetUlid)->first();

        if (!$targetUser || !$targetUser->sponsor_id) {
            return null;
        }

        if ($targetUser->sponsor_id === $adminAuid) {
            return $level;
        }

        return $this->calculateLevelFromAdmin($adminAuid, $targetUser->sponsor_id, $level + 1);
    }

    public function showTransferCouponsForm()
    {
        return view('admin.transfer-coupons');
    }

    public function transferCoupons(Request $request)
    {
        $request->validate([
            'coupon_quantity' => 'required|integer|min:1',
        ]);

        $quantity = $request->input('coupon_quantity');
        $count = 0;

        // Use chunk() to process users in groups of 200 to prevent memory errors
        User::chunk(200, function ($users) use ($quantity, &$count) {
            foreach ($users as $user) {
                // Check if user already has a coupon record
                $userCoupon = UserCoupon::where('user_id', $user->id)->first();

                if ($userCoupon) {
                    // Add to existing balance
                    $userCoupon->increment('coupon_quantity', $quantity);
                } else {
                    // Create new record
                    UserCoupon::create([
                        'user_id' => $user->id,
                        'user_ulid' => $user->ulid,
                        'coupon_quantity' => $quantity,
                        'coupon_value' => 10.00
                    ]);
                }
                $count++;
            }
        });

        return back()->with('success', "Successfully transferred $quantity coupons to all $count users!");
    }

    // Apply filters to user collection (same as user panel)
    private function applyFilters($users, $request)
    {
        // Filter by designation
        if ($request->filled('designation')) {
            $users = array_filter($users, function ($user) use ($request) {
                return $user->current_rank == $request->designation;
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $users = array_filter($users, function ($user) use ($request) {
                return $user->status == $request->status;
            });
        }

        // Filter by purchase status
        if ($request->filled('purchase_status')) {
            $users = array_filter($users, function ($user) use ($request) {
                return $user->purchase_status == $request->purchase_status;
            });
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $users = array_filter($users, function ($user) use ($request) {
                $userDate = $user->user_doa ?? $user->created_at;
                return $userDate >= $request->start_date && $userDate <= $request->end_date;
            });
        }

        return array_values($users); // Reset array keys
    }

    public function allIncomes(Request $request)
    {
        // 1. Direct Income Query
        $direct = DB::table('direct_income')
            ->join('users', 'direct_income.user_id', '=', 'users.id')
            ->select('users.name as user_name', 'users.ulid as user_ulid', 'direct_income.income_amount as amount', DB::raw("'Direct Income' as type"), 'direct_income.created_at');

        // 2. Level Income Query
        $level = DB::table('level_incomes')
            ->join('users', 'level_incomes.user_id', '=', 'users.id')
            ->select('users.name as user_name', 'users.ulid as user_ulid', 'level_incomes.amount as amount', DB::raw("'Level Income' as type"), 'level_incomes.created_at');

        // 3. Bonus Income Query
        $bonus = DB::table('bonus_income')
            ->join('users', 'bonus_income.user_id', '=', 'users.id')
            ->select('users.name as user_name', 'users.ulid as user_ulid', 'bonus_income.income_amount as amount', DB::raw("'Bonus Income' as type"), 'bonus_income.created_at');

        // 4. Rewards Income Query
        $reward = DB::table('rewards_incomes')
            ->join('users', 'rewards_incomes.user_id', '=', 'users.id')
            ->select('users.name as user_name', 'users.ulid as user_ulid', 'rewards_incomes.reward_amount as amount', DB::raw("'Reward Income' as type"), 'rewards_incomes.created_at');

        // 5. Repurchase Income Query
        $repurchase = DB::table('repurchase_incomes')
            ->join('users', 'repurchase_incomes.user_id', '=', 'users.id')
            ->select('users.name as user_name', 'users.ulid as user_ulid', 'repurchase_incomes.commission as amount', DB::raw("'Repurchase Income' as type"), 'repurchase_incomes.created_at');

        // 6. Cashback Income Query
        $cashback = DB::table('cashback_income')
            ->join('users', 'cashback_income.user_id', '=', 'users.id')
            ->select('users.name as user_name', 'users.ulid as user_ulid', 'cashback_income.income_amount as amount', DB::raw("'Cashback Income' as type"), 'cashback_income.created_at');

        // Combine all queries using UNION ALL inside a parent query
        $query = DB::table(DB::raw("({$direct->toSql()} UNION ALL {$level->toSql()} UNION ALL {$bonus->toSql()} UNION ALL {$reward->toSql()} UNION ALL {$repurchase->toSql()} UNION ALL {$cashback->toSql()}) as combined_incomes"))
            ->mergeBindings($direct)
            ->mergeBindings($level)
            ->mergeBindings($bonus)
            ->mergeBindings($reward)
            ->mergeBindings($repurchase)
            ->mergeBindings($cashback);

        // Search functionality (Search by Name or ULID)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_ulid', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%");
            });
        }

        // Filter by Income Type
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        // Sort by Date (Latest first) and Paginate
        $incomes = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.incomes.all-incomes', compact('incomes'));
    }

    public function paymentSettings()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.payment-settings', compact('admin'));
    }

    public function updatePaymentSettings(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        // Validation
        $request->validate([
            'upi_id' => 'required|max:255', // Treating as string generally, though your DB is int(50) currently
            'upi_qr' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        // Update UPI ID
        $admin->upi_id = $request->upi_id;

        // Handle QR Code Upload
        if ($request->hasFile('upi_qr')) {
            // Delete old QR code if it exists
            if ($admin->upi_qr) {
                $oldPath = public_path('storage/' . $admin->upi_qr);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            // Create directory if not exists
            $directory = 'storage/upi-qr';
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            // Upload new file
            $file = $request->file('upi_qr');
            $extension = $file->getClientOriginalExtension();
            $filename = 'upi_qr_' . uniqid() . '.' . $extension;

            $file->move($directory, $filename);

            // Save relative path to DB
            $admin->upi_qr = 'upi-qr/' . $filename;
        }

        $admin->save();

        return redirect()->route('admin.payment.settings')->with('success', 'Payment details updated successfully!');
    }
}
