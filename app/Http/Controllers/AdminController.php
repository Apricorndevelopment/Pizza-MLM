<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Gallery;
use App\Models\Package1;
use App\Models\Package2;
use App\Models\Package2Purchase;
use App\Models\PackageMonthlyDistribution;
use App\Models\PointsTransaction;
use App\Models\User;
use App\Models\Wallet;
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
        $package1Count = Package1::count();
        $package2Count = Package2::count();
        $userCount = User::count();
        $businessCount = Package2Purchase::sum('final_price');

        return view('admin.dashboard', compact('package1Count', 'package2Count', 'userCount', 'businessCount'));
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
            User::where('parent_id', $member->ulid)->update(['parent_id' => $sponsorIdOfDeletedMember]);

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

            // Check if user has purchases (paid/unpaid status)
            $hasPurchases = Package2Purchase::where('user_id', $user->id)
                ->exists();
            $user->purchase_status = $hasPurchases ? 'paid' : 'unpaid';

            // Calculate total purchases if user has any
            if ($hasPurchases) {
                $user->total_purchases = Package2Purchase::where('user_id', $user->id)
                    ->sum('final_price');
            } else {
                $user->total_purchases = 0;
            }
        }

        // Get available designations for filter
        $designations = DB::table('royalty_level_rewards')
            ->pluck('level')
            ->toArray();

        // Apply filters if requested
        if ($request->hasAny(['designation', 'status', 'purchase_status', 'start_date', 'end_date'])) {
            $downlineUsers = $this->applyFilters($downlineUsers, $request);
        }

        // Paginate results (15 per page)
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        $currentItems = array_slice($downlineUsers, ($currentPage - 1) * $perPage, $perPage);
        $paginatedUsers = new LengthAwarePaginator($currentItems, count($downlineUsers), $perPage, $currentPage, [
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


    public function showFormForProfitDistribution()
    {
        $royaltyLevels = DB::table('royalty_level_rewards')
            ->whereNotNull('profit')
            ->orderBy('sr_no')
            ->get();
        return view('admin.profit-distribution', compact('royaltyLevels'));
    }

    public function distributeYearlyProfit(Request $request)
    {
        $validated = $request->validate([
            'profit' => 'required|numeric|min:0',
            'expenditure' => 'required|numeric|min:0',
            'profit_share' => 'required|numeric|min:0|max:100', // Only for package buyers
        ]);

        // Calculate final profit
        $finalProfit = $validated['profit'] - $validated['expenditure'];
        $year = now()->year;

        // Process royalty level rewards distribution (using their own percentages)
        $this->distributeToRoyaltyLevels($finalProfit, $year);

        // Process package buyers distribution (using form's profit_share)
        $this->distributeToPackageBuyers($finalProfit, $validated['profit_share'], $year);

        DB::table('yearly_royalty_distribution')->insert([
            'profit' => $validated['profit'],
            'expenditure' => $validated['expenditure'],
            'final_profit' => $finalProfit,
            'year' => $year,
            'profit_share' => $validated['profit_share'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Profit distributed successfully!');
    }

    protected function distributeToRoyaltyLevels($finalProfit, $year)
    {
        $royaltyLevels = DB::table('royalty_level_rewards')
            ->whereNotNull('profit')
            ->orderBy('sr_no')
            ->get();

        foreach ($royaltyLevels as $levels) {
            if (empty($levels->level) || empty($levels->profit)) {
                continue;
            }

            // Calculate amount using level's own profit percentage
            $levelAmount = $finalProfit * ($levels->profit / 100);

            $users = User::where('current_rank', $levels->level)->get();
            $userCount = $users->count();

            if ($userCount > 0) {
                $perUserAmount = $levelAmount / $userCount;

                foreach ($users as $user) {
                    $user->increment('points_balance', $perUserAmount);

                    PointsTransaction::create([
                        'user_id' => $user->id,
                        'user_ulid' => $user->ulid,
                        'points' => $perUserAmount,
                        'notes' => "₹$perUserAmount received for $year yearly profit as $levels->level",
                        'admin_id' => Auth::id()
                    ]);
                }
            }
        }
    }

    protected function distributeToPackageBuyers($finalProfit, $profitSharePercentage, $year)
    {
        $packageBuyers = Package2Purchase::where('profit_share', 1)
            ->with('user')
            ->get();

        $totalAmount = $finalProfit * ($profitSharePercentage / 100);

        // Calculate weighted amounts based on package price and duration
        $totalWeight = 0;
        $buyersData = [];

        $currentDate = now();
        $totalMonths = 12;

        foreach ($packageBuyers as $purchase) {
            // Calculate duration factor (months since purchase)
            $monthsSincePurchase = $purchase->created_at->diffInMonths($currentDate);
            $durationRatio = min(1, $monthsSincePurchase / $totalMonths);

            $weight = $purchase->final_price * $durationRatio;
            $totalWeight += $weight;

            $buyersData[] = [
                'user' => $purchase->user,
                'weight' => $weight,
                'purchase' => $purchase,
            ];
        }

        if ($totalWeight > 0) {
            foreach ($buyersData as $buyer) {
                $userAmount = ($buyer['weight'] / $totalWeight) * $totalAmount;

                if ($userAmount > 0) {
                    $user = $buyer['user'];
                    $user->increment('points_balance', $userAmount);

                    PointsTransaction::create([
                        'user_id' => $user->id,
                        'user_ulid' => $user->ulid,
                        'points' => $userAmount,
                        'notes' => "₹$userAmount received for $year yearly package profit share",
                        'admin_id' => Auth::id()
                    ]);
                }
            }
        }
    }

    public function viewYearlyDistribution()
    {
        $distributions = DB::table('yearly_royalty_distribution')
            ->orderBy('year', 'desc')
            ->paginate(10);

        return view('admin.view-yearly-distribution', compact('distributions'));
    }

    public function viewMonthlyDistributions()
    {
        $distributions = PackageMonthlyDistribution::with(['user', 'packagePurchase'])
            ->orderBy('distribution_date', 'desc')
            ->paginate(20);

        return view('admin.view-monthly-distribution', compact('distributions'));
    }
}
