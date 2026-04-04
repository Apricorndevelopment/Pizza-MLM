<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\AutopoolEarningsHistory;
use App\Models\CashbackIncome;
use App\Models\BonusIncome;
use App\Models\DirectIncome;
use App\Models\LevelIncome;
use App\Models\MediaLibrary;
use App\Models\Wallet2Transaction;
use App\Models\MoneyWithdrawl;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PercentageIncome;
use App\Models\RepurchaseIncome;
use App\Models\RewardsIncome;
use App\Models\Wallet1Transaction;
use App\Models\RoyaltyRewardsIncome;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\UserGallery;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // 1. Calculate Incomes
        $directIncome = DirectIncome::where('user_id', $user->id)->sum('income_amount');
        $bonusIncome = BonusIncome::where('user_id', $user->id)->sum('income_amount');
        $repurchaseIncome = RepurchaseIncome::where('user_id', $user->id)->sum('commission');
        $levelIncome = LevelIncome::where('user_id', $user->id)->sum('amount');
        $cashbackIncome = CashbackIncome::where('user_id', $user->id)->sum('income_amount');
        $rewardIncome = RewardsIncome::where('user_id', $user->id)->sum('reward_amount');
        $autoPoolIncome = AutopoolEarningsHistory::where('user_id', $user->id)->sum('reward_amount');

        $totalIncome = $directIncome + $bonusIncome + $levelIncome + $cashbackIncome + $rewardIncome + $repurchaseIncome + $autoPoolIncome;

        // 2. Fetch Media (Audio & Video)
        $audios = MediaLibrary::where('type', 'audio')->latest()->get();
        $videos = MediaLibrary::where('type', 'video')->latest()->get();

        // 3. Fetch Coupon Quantity
        // Assuming user_coupons table has a 'coupon_quantity' column and one row per user, 
        // or multiple rows we sum up.
        $totalCoupons = UserCoupon::where('user_id', $user->id)->sum('coupon_quantity');

        // 4. NEW: Fetch Gallery Banners
        $galleries = UserGallery::latest()->get();

        return view('user.dashboard', compact(
            'levelIncome',
            'bonusIncome',
            'directIncome',
            'repurchaseIncome',
            'cashbackIncome',
            'rewardIncome',
            'autoPoolIncome',
            'totalIncome',
            'audios',
            'videos',
            'totalCoupons',
            'user',
            'galleries'
        ));
    }

    /**
     * Recursively get all downline User IDs (Integers) 
     * Search Condition: sponsor_id matches the parent's ULID
     */
    private function getAllDownlineIds($currentUlid)
    {
        // 1. Fetch direct downlines
        // We need 'id' to store in the list, and 'ulid' to continue the recursion
        $downlines = User::where('sponsor_id', $currentUlid)->get(['id', 'ulid']);

        // 2. Start a collection with the IDs found at this level
        $userIds = $downlines->pluck('id');

        // 3. Recurse: For each child, find their children using their ULID
        foreach ($downlines as $user) {
            $userIds = $userIds->merge($this->getAllDownlineIds($user->ulid));
        }

        return $userIds;
    }

    /**
     * Calculate total business for given ULIDs and optional date filter
     */
    private function getBusinessForUlids($ulids, $startDate = null, $endDate = null)
    {
        $query = Order::whereIn('user_id', $ulids);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->sum('total_amount');
    }

    /**
     * API endpoint to fetch sales chart data
     */
    public function getSalesChartData(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'monthly');

        $labels = [];
        $data = [];

        // ---------------------------------------------------------
        // STEP 1: Get the entire tree's IDs
        // ---------------------------------------------------------

        // Get all downline IDs using the recursive function
        $teamIds = $this->getAllDownlineIds($user->ulid);

        // Add the current user's ID (Self Business) to the list
        $teamIds->push($user->id);

        // ---------------------------------------------------------
        // STEP 2: Filter Logic (Same as before, using $teamIds)
        // ---------------------------------------------------------

        switch ($filter) {
            case 'daily': // Last 15 days
                $startDate = Carbon::now()->subDays(14)->startOfDay();
                $endDate = Carbon::now()->endOfDay();

                for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
                    $dayStart = $date->copy()->startOfDay();
                    $dayEnd = $date->copy()->endOfDay();

                    // Pass the collected IDs to the calculation function
                    $totalBusiness = $this->getBusinessForUlids($teamIds, $dayStart, $dayEnd);

                    $labels[] = $date->format('d M');
                    $data[] = $totalBusiness;
                }
                break;

            case 'weekly': // Last 8 weeks
                $startDate = Carbon::now()->subWeeks(7)->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();

                for ($weekStart = $startDate->copy(); $weekStart <= $endDate; $weekStart->addWeek()) {
                    $weekEnd = $weekStart->copy()->endOfWeek();

                    $totalBusiness = $this->getBusinessForUlids($teamIds, $weekStart, $weekEnd);

                    $labels[] = $weekStart->format('d M') . ' - ' . $weekEnd->format('d M');
                    $data[] = $totalBusiness;
                }
                break;

            case 'monthly': // Last 12 months
                $startDate = Carbon::now()->subMonths(11)->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();

                for ($month = $startDate->copy(); $month <= $endDate; $month->addMonth()) {
                    $monthStart = $month->copy()->startOfMonth();
                    $monthEnd = $month->copy()->endOfMonth();

                    $totalBusiness = $this->getBusinessForUlids($teamIds, $monthStart, $monthEnd);

                    $labels[] = $month->format('M Y');
                    $data[] = $totalBusiness;
                }
                break;

            case 'yearly': // Last 10 years
                $currentYear = Carbon::now()->year;

                for ($year = $currentYear - 9; $year <= $currentYear; $year++) {
                    $yearStart = Carbon::create($year, 1, 1)->startOfYear();
                    $yearEnd = Carbon::create($year, 12, 31)->endOfYear();

                    $totalBusiness = $this->getBusinessForUlids($teamIds, $yearStart, $yearEnd);

                    $labels[] = (string)$year;
                    $data[] = $totalBusiness;
                }
                break;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    public function profile()
    {
        // $user = Auth::user();
        // return view('user.profile', ['user' => $user]);
        $user = Auth::user();

        $showPasswordReminder = false;

        if ($user->password_updated_at) {
            $daysSincePasswordChange = now()->diffInDays($user->password_updated_at);

            if ($daysSincePasswordChange >= 15) {
                $showPasswordReminder = true;
            }
        }

        $breadcrumbs = [
            ['title' => 'Profile', 'url' => route('user.profile')]
        ];

        return view('user.profile', compact('user', 'showPasswordReminder', 'breadcrumbs'));
    }

    public function edit()
    {
        $user = Auth::user();
        $breadcrumbs = [
            ['title' => 'Profile', 'url' => route('user.profile')],
            ['title' => 'Edit Profile', 'url' => route('user.profile.edit')]
        ];

        return view('user.edit-profile', ['user' => $user, 'breadcrumbs' => $breadcrumbs]);
    }

    public function update(Request $request)
    {
        $authUser = Auth::user();
        $user = User::find($authUser->id);

        $validated = $request->validate([
            // Basic Information
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',

            // KYC Documents
            'adhar_no' => [
                'nullable',
                'string',
                'min:12',
                'max:12',
                function ($attribute, $value, $fail) {
                    if ($value && !preg_match('/^\d{12}$/', $value)) {
                        $fail('Aadhaar number must be exactly 12 digits.');
                    }
                }
            ],
            'pan_no' => [
                'nullable',
                'string',
                'min:10',
                'max:10',
                function ($attribute, $value, $fail) {
                    if ($value && !preg_match('/^[A-Z]{5}\d{4}[A-Z]$/', $value)) {
                        $fail('PAN number must be in valid format (e.g., ABCDE1234F).');
                    }
                }
            ],
            'adhar_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'adhar_back_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'pan_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',

            'nom_name' => 'nullable|string|max:255',
            'nom_relation' => 'nullable|string|max:100',

            'bank_name' => 'nullable|string|max:100',
            'account_no' => 'nullable|string|min:6|max:100',
            'ifsc_code' => [
                'nullable',
                'string',
                'min:4',
                'max:100',
                function ($attribute, $value, $fail) {
                    if ($value && !preg_match('/^[A-Z]{4}0[A-Z0-9]{6}$/', $value)) {
                        $fail('IFSC code must be in valid format (e.g., ABCD0123456).');
                    }
                }
            ],
            'upi_id' => 'nullable|string|max:100',
            'passbook_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',

            // Password Change
            'current_password' => [
                'nullable',
                'required_with:password',
                'string',
                'min:8',
                function ($attribute, $value, $fail) use ($user) {
                    if ($value && !Hash::check($value, $user->password)) {
                        $fail('The current password is incorrect.');
                    }
                }
            ],
            'password' => [
                'nullable',
                'required_with:current_password',
                'string',
                'min:8',
                'confirmed',
                'different:current_password',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).+$/'
            ],
        ], [
            // Custom Error Messages
            'name.required' => 'Full name is required',
            'email.required' => 'Email address is required',
            'email.unique' => 'This email is already taken',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'password.different' => 'New password must be different from current password',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number and one special character',
            'adhar_no.min' => 'Aadhaar number must be exactly 12 digits',
            'pan_no.min' => 'PAN number must be exactly 10 characters',
            'profile_picture.max' => 'Profile picture must be less than 5MB',
            'adhar_photo.max' => 'Aadhaar photo must be less than 5MB',
            'adhar_back_photo.max' => 'Aadhaar back photo must be less than 5MB',
            'pan_photo.max' => 'PAN photo must be less than 5MB',
            'passbook_photo.max' => 'Passbook photo must be less than 5MB',
        ]);

        // Update basic fields
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),

            'adhar_no' => $request->input('adhar_no'),
            'pan_no' => $request->input('pan_no'),
            'nom_name' => $request->input('nom_name'),
            'nom_relation' => $request->input('nom_relation'),
            'bank_name' => $request->input('bank_name'),
            'account_no' => $request->input('account_no'),
            'ifsc_code' => $request->input('ifsc_code'),
            'upi_id' => $request->input('upi_id'),
        ]);

        // Handle profile picture upload
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

        // Handle Aadhaar photo upload
        if ($request->hasFile('adhar_photo')) {
            // Delete old aadhaar photo if exists
            if ($user->adhar_photo) {
                Storage::delete('public/' . $user->adhar_photo);
            }

            // Define the directory path
            $directory = 'storage/aadhaar-documents';

            // Create the directory if it doesn't exist
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            // Get the file and extension
            $file = $request->file('adhar_photo');
            $extension = $file->getClientOriginalExtension();

            // Create a unique filename
            $filename = uniqid() . '.' . $extension;

            // Move the file to the directory
            $file->move($directory, $filename);

            // Save the relative path to the database
            $user->adhar_photo = 'aadhaar-documents/' . $filename;
        }

        // Handle Aadhaar back photo upload
        if ($request->hasFile('adhar_back_photo')) {
            // Delete old aadhaar back photo if exists
            if ($user->adhar_back_photo) {
                Storage::delete('public/' . $user->adhar_back_photo);
            }

            // Define the directory path
            $directory = 'storage/aadhaar-documents';

            // Create the directory if it doesn't exist
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            // Get the file and extension
            $file = $request->file('adhar_back_photo');
            $extension = $file->getClientOriginalExtension();

            // Create a unique filename
            $filename = uniqid() . '.' . $extension;

            // Move the file to the directory
            $file->move($directory, $filename);

            // Save the relative path to the database
            $user->adhar_back_photo = 'aadhaar-documents/' . $filename;
        }

        // Handle PAN photo upload
        if ($request->hasFile('pan_photo')) {
            // Delete old pan photo if exists
            if ($user->pan_photo) {
                Storage::delete('public/' . $user->pan_photo);
            }

            // Define the directory path
            $directory = 'storage/pan-documents';

            // Create the directory if it doesn't exist
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            // Get the file and extension
            $file = $request->file('pan_photo');
            $extension = $file->getClientOriginalExtension();

            // Create a unique filename
            $filename = uniqid() . '.' . $extension;

            // Move the file to the directory
            $file->move($directory, $filename);

            // Save the relative path to the database
            $user->pan_photo = 'pan-documents/' . $filename;
        }

        // Handle passbook photo upload
        if ($request->hasFile('passbook_photo')) {
            // Delete old passbook photo if exists
            if ($user->passbook_photo) {
                Storage::delete('public/' . $user->passbook_photo);
            }

            // Define the directory path
            $directory = 'storage/passbook-photos';

            // Create the directory if it doesn't exist
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            // Get the file and extension
            $file = $request->file('passbook_photo');
            $extension = $file->getClientOriginalExtension();

            // Create a unique filename
            $filename = uniqid() . '.' . $extension;

            // Move the file to the directory
            $file->move($directory, $filename);

            // Save the relative path to the database
            $user->passbook_photo = 'passbook-photos/' . $filename;
        }

        // Handle password change
        if ($request->filled('current_password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully!');
    }




    public function convertMatchingToNumber($value)
    {
        $value = strtolower(trim($value));
        if (str_contains($value, 'cr')) {
            return (float)str_replace('cr', '', $value) * 10000000;
        } elseif (str_contains($value, 'l')) {
            return (float)str_replace('l', '', $value) * 100000;
        } elseif (str_contains($value, 'k')) {
            return (float)str_replace('k', '', $value) * 1000;
        } else {
            return (float)$value;
        }
    }




    public function viewWallet(Request $request)
    {
        $wallet1 = Auth::user()->wallet1_balance;
        $wallet2 = Auth::user()->wallet2_balance;
        $percentageIncome = PercentageIncome::first();
        $user = Auth::user();

        // Withdrawals with pagination
        $withdrawalsQuery = MoneyWithdrawl::where('user_id', $user->id);
        $withdrawals = $withdrawalsQuery->latest()->paginate(5, ['*'], 'withdrawals_page');

        // Wallet1 Transactions with filters and pagination
        $wallet1Query = Wallet1Transaction::where('user_id', $user->id);

        // Apply filters if requested
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

        $wallet1Transactions = $wallet1Query->latest()->paginate(10, ['*'], 'wallet1_page');

        // Wallet2 Transactions (keep your existing logic)
        $wallet2Transactions = Wallet2Transaction::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();


        $withdrawalStatus = Admin::first()->is_withdrawal_open;

        return view('user.viewwallet', compact('wallet1', 'wallet2', 'wallet1Transactions', 'wallet2Transactions', 'withdrawals', 'percentageIncome', 'withdrawalStatus'));
    }

    public function vendorsList(Request $request)
    {
        // Fetch only active/approved vendors
        $query = Vendor::with('user')->where('status', 'vendor');

        // Handle Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('vendor_name', 'LIKE', "%{$search}%")
                    ->orWhere('company_name', 'LIKE', "%{$search}%")
                    ->orWhere('company_city', 'LIKE', "%{$search}%");
            });
        }

        $vendors = $query->latest()->paginate(12)->withQueryString();

        return view('user.vendors.index', compact('vendors'));
    }



    public function levelIncomeReport(Request $request)
    {
        // Get filter parameters from request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $incomesQuery = LevelIncome::where('user_id', Auth::id())
            ->with(['fromUser', 'package']);

        // Apply date filters
        $incomesQuery->when($startDate, function ($query) use ($startDate) {
            return $query->whereDate('created_at', '>=', $startDate);
        });

        $incomesQuery->when($endDate, function ($query) use ($endDate) {
            return $query->whereDate('created_at', '<=', $endDate);
        });

        $incomes = $incomesQuery->latest()->paginate(10);

        // Calculate totals based on filtered results
        $totalIncome = $incomesQuery->sum('amount');
        $totalRecords = $incomesQuery->count();

        $breadcrumbs = [
            ['title' => 'Incentives', 'url' => route('user.reports.level-income')],
            ['title' => 'Passive Income', 'url' => route('user.reports.level-income')]
        ];

        return view('user.rewards.level-income', compact('incomes', 'totalIncome', 'totalRecords', 'breadcrumbs', 'startDate', 'endDate'));
    }

    public function showUserRankRewards($ulid)
    {
        $user = User::where('ulid', $ulid)->firstOrFail();

        // Fetch rewards from RoyaltyRewardsIncome table
        $rewards = RoyaltyRewardsIncome::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($reward) {
                return [
                    'id'     => $reward->id,
                    'rank'   => $reward->rank,
                    'amount' => $reward->points,
                    'status' => $reward->status,
                    'date'   => $reward->created_at->format('d M Y'),
                ];
            });

        $currentRank = $user->current_rank;

        // Get all possible ranks from royalty_level_rewards
        $allRanks = DB::table('royalty_level_rewards')
            ->orderBy('sr_no')
            ->pluck('level')
            ->toArray();

        $breadcrumbs = [
            ['title' => 'Incentives', 'url' => route('user.rewards.rankRewards', $user->ulid)],
            ['title' => 'Reward Income', 'url' => route('user.rewards.rankRewards', $user->ulid)]
        ];

        return view('user.rewards.rankRewards', compact('user', 'rewards', 'currentRank', 'allRanks', 'breadcrumbs'));
    }

    // Claim reward
    public function claimReward($id)
    {
        $reward = RoyaltyRewardsIncome::findOrFail($id);
        $reward->status = 1;
        $reward->save();

        // Optional: Add points to user balance
        $user = User::find($reward->user_id);
        $user->increment('wallet1_balance', $reward->points);

        return redirect()->back()->with('success', 'Reward claimed successfully.');
    }

    // Reject reward
    public function rejectReward($id)
    {
        $reward = RoyaltyRewardsIncome::findOrFail($id);
        $reward->status = 2; // Rejected
        $reward->save();

        return redirect()->back()->with('success', 'Reward rejected.');
    }
}
