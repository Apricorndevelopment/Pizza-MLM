<?php

namespace App\Http\Controllers;

use App\Models\BonusIncome;
use App\Models\Commission;
use App\Models\DirectIncome;
use App\Models\LevelIncome;
use App\Models\Wallet2Transaction;
use App\Models\MoneyWithdrawl;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Package1;
use App\Models\ProductPackage;
use App\Models\ProductPackageDetails;
use App\Models\ProductPackagePurchase;
use App\Models\PackageMonthlyDistribution;
use App\Models\PackageTransaction;
use App\Models\RepurchaseIncome;
use App\Models\RewardsIncome;
use App\Models\Wallet1Transaction;
use App\Models\RoyaltyRewardsIncome;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $directIncome = DirectIncome::where('user_id', $user->id)->sum('income_amount');

        $repurchaseIncome = RepurchaseIncome::where('user_id', $user->id)->sum('commission');

        $levelIncome = LevelIncome::where('user_id', $user->id)->sum('amount');

        $bonusIncome = BonusIncome::where('user_id', $user->id)->sum('income_amount');

        $rewardIncome = RewardsIncome::where('user_id', $user->id)->sum('reward_amount');

        $totalIncome = $directIncome + $levelIncome + $bonusIncome + $rewardIncome + $repurchaseIncome;

        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('user.dashboard')]
        ];

        return view('user.dashboard', compact('breadcrumbs', 'levelIncome', 'directIncome', 'repurchaseIncome', 'bonusIncome', 'rewardIncome', 'totalIncome'));
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
            'state' => 'nullable|string|max:100',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

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
            'adhar_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pan_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

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
            'passbook_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

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
            'profile_picture.max' => 'Profile picture must be less than 2MB',
            'adhar_photo.max' => 'Aadhaar photo must be less than 2MB',
            'pan_photo.max' => 'PAN photo must be less than 2MB',
            'passbook_photo.max' => 'Passbook photo must be less than 2MB',
        ]);

        // Update basic fields
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
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


    //Package purchasing for the Activation
    public function purchasePackage(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:package1,id'
        ]);

        $user = Auth::user();
        $package = Package1::findOrFail($request->package_id);

        $discountAmount = $package->discount_per ? ($package->price * $package->discount_per) / 100 : 0;
        $totalCost = $package->price;

        if ($user->wallet1_balance < $totalCost) {
            return back()->with('error', 'Insufficient balance to purchase this package');
        }

        $couponCode = 'GEO' . $user->id . 'PQ' . $package->package_quantity;
        // dd($user->id);
        PackageTransaction::create([
            'user_id' => $user->id,
            'package1_id' => $package->id,
            'ulid' => $user->ulid,
            'package_name' => $package->package_name,
            'price' => $package->price,
            'discount_percentage' => $package->discount_per,
            'discount_amount' => $discountAmount,
            'quantity' => $package->package_quantity,
            'final_price' => $totalCost,
            'coupon_code' => $couponCode,
            'status' => 'pending',
            'transaction_date' => now(),
        ]);

        if ($user->status == 'inactive') {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['status' => 'active', 'user_doa' => now()]);
        }

        // Deduct points directly from DB
        DB::table('users')
            ->where('id', $user->id)
            ->decrement('wallet1_balance', $totalCost);

        Wallet1Transaction::create([
            'user_id' => $user->id,
            'user_ulid' => $user->ulid,
            'points' => -$totalCost,
            'notes' => 'Deducted for purchasing package: ' . $package->package_name,
        ]);

        return redirect()->route('user.dashboard')->with([
            'success' => 'Package purchased successfully!',
            'coupon_code' => $couponCode // Pass coupon code to show in success message
        ]);
    }


    //User side package purchasing after Activation

    public function showPurchaseForm()
    {
        $packages = ProductPackage::with('details')->get();
        $breadcrumbs = [
            ['title' => 'Package', 'url' => route('package2.purchase')],
            ['title' => 'Buy Package', 'url' => route('package2.purchase')]
        ];
        return view('user.package2-purchase', compact('packages', 'breadcrumbs'));
    }

    public function processPurchase(Request $request)
    {
        $request->validate([
            'package2_id' => 'required|exists:package2,id',
            'package2_detail_id' => 'required|exists:package2_details,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $package = ProductPackage::findOrFail($request->package2_id);
        $rateDetail = ProductPackageDetails::findOrFail($request->package2_detail_id);

        $finalPrice = $package->price * $request->quantity;

        // Check user's balance
        if ($user->wallet1_balance < $finalPrice) {
            return redirect()->back()->with('error', 'Insufficient balance to purchase this package');
        }

        DB::beginTransaction();
        try {
            $invoiceNumber = $this->getNextInvoiceNumber();
            $bedNumber = $this->getNextBedNumber();

            // Create package purchase record
            $purchase = ProductPackagePurchase::create([
                'user_id' => $user->id,
                'ulid' => $user->ulid,
                'package2_id' => $package->id,
                'package2_detail_id' => $rateDetail->id,
                'package_name' => $package->package_name,
                'quantity' => $request->quantity,
                'rate' => $rateDetail->rate,
                'capital' => $rateDetail->capital,
                'time' => $rateDetail->time,
                'profit_share' => $rateDetail->profit_share,
                'final_price' => $finalPrice,
                'maturity' => $package->maturity, // Store maturity from package
                'endorsed' => 0,
                'invoice_no' => $invoiceNumber,
                'bed_no' => $bedNumber,
                'purchased_at' => now(),
            ]);

            // Deduct from user's balance
            DB::table('users')
                ->where('id', $user->id)
                ->decrement('wallet1_balance', $finalPrice);

            // Record points transaction
            Wallet1Transaction::create([
                'user_id' => $user->id,
                'user_ulid' => $user->ulid,
                'points' => -$finalPrice,
                'notes' => 'Purchased package: ' . $package->package_name . ' (Quantity: ' . $request->quantity . ' units)',
                'admin_id' => null
            ]);

            // Process sponsor commissions
            $this->processSponsorCommissions($user, $finalPrice, $package);

            // For calculating the rank of the user based on the total business volume
            $this->checkAndRewardUser($user->sponsor_id);

            DB::commit();

            return redirect()->back()->with('success', 'Package purchased successfully! Total Quantity: ' . $request->quantity . ' units');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to process your request: ' . $e->getMessage());
        }
    }

    private function getNextInvoiceNumber()
    {
        $datePart = now()->format('Ymd');
        $prefix = "INV-{$datePart}-";
        $last = ProductPackagePurchase::where('invoice_no', 'like', "{$prefix}%")
            ->orderBy('invoice_no', 'desc')
            ->first();

        if (!$last) {
            $nextNumber = 1;
        } else {
            $lastNumber = intval(substr($last->invoice_no, -5));
            $nextNumber = $lastNumber + 1;
        }
        return $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
    private function getNextBedNumber()
    {
        $datePart = now()->format('Ymd');
        $prefix = "GEOBED-{$datePart}-";
        $last = ProductPackagePurchase::where('bed_no', 'like', "{$prefix}%")
            ->orderBy('bed_no', 'desc')
            ->first();

        if (!$last) {
            $nextNumber = 1;
        } else {
            $lastNumber = intval(substr($last->bed_no, -5));
            $nextNumber = $lastNumber + 1;
        }
        return $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }


    protected function processSponsorCommissions($user, $amount, $package)
    {
        $hasParent = false;

        // First check parent_id - if exists, only parent gets 3%
        if ($user->parent_id) {
            $parent = User::where('ulid', $user->parent_id)->first();
            if ($parent && $parent->status == 'active') {
                // Check if parent is eligible (has purchases and within 3 months)
                if ($this->isUserEligibleForCommission($parent)) {
                    $hasParent = true;
                    $commission = $amount * 0.03;
                    $parent->increment('wallet1_balance', $commission);

                    Commission::create([
                        'user_id' => $parent->id,
                        'from_ulid' => $user->ulid,
                        'from_name' => $user->name,
                        'purchase_amount' => $amount,
                        'commission' => $commission,
                        'level' => 1
                    ]);
                }
            }
        }

        // Process sponsor levels only if no active parent exists
        if (!$hasParent && $user->sponsor_id) {
            $sponsorL1 = User::where('ulid', $user->sponsor_id)->first();
            if ($sponsorL1 && $sponsorL1->status == 'active' && $this->isUserEligibleForCommission($sponsorL1)) {
                $commissionL1 = $amount * 0.03;
                $sponsorL1->increment('wallet1_balance', $commissionL1);
                Commission::create([
                    'user_id' => $sponsorL1->id,
                    'from_ulid' => $user->ulid,
                    'from_name' => $user->name,
                    'purchase_amount' => $amount,
                    'commission' => $commissionL1,
                    'level' => 1
                ]);
            }
        }

        // Process L2 commission with additional conditions
        if ($user->sponsor_id) {
            $sponsorL1 = User::where('ulid', $user->sponsor_id)->first();
            if ($sponsorL1 && $sponsorL1->sponsor_id) {
                $sponsorL2 = User::where('ulid', $sponsorL1->sponsor_id)->first();
                if ($sponsorL2 && $this->hasPurchasedPackage($sponsorL2)) {
                    $downlineCount = User::where('sponsor_id', $sponsorL2->ulid)
                        ->where('status', 'active')
                        ->count();

                    if ($downlineCount >= 2 && $sponsorL2->status == 'active' && $this->isUserEligibleForCommission($sponsorL2)) {
                        $commissionL2 = $amount * 0.01;
                        $sponsorL2->increment('wallet1_balance', $commissionL2);
                        Commission::create([
                            'user_id' => $sponsorL2->id,
                            'from_ulid' => $user->ulid,
                            'from_name' => $user->name,
                            'purchase_amount' => $amount,
                            'commission' => $commissionL2,
                            'level' => 2
                        ]);
                    }

                    // Process L3 commission with additional conditions
                    if ($sponsorL2->sponsor_id) {
                        $sponsorL3 = User::where('ulid', $sponsorL2->sponsor_id)->first();
                        if ($sponsorL3 && $this->hasPurchasedPackage($sponsorL3)) {
                            $downlineCountL2 = User::where('sponsor_id', $sponsorL3->ulid)
                                ->where('status', 'active')
                                ->count();

                            if ($downlineCountL2 >= 3 && $sponsorL3->status == 'active' && $this->isUserEligibleForCommission($sponsorL3)) {
                                $commissionL3 = $amount * 0.01;
                                $sponsorL3->increment('wallet1_balance', $commissionL3);
                                Commission::create([
                                    'user_id' => $sponsorL3->id,
                                    'from_ulid' => $user->ulid,
                                    'from_name' => $user->name,
                                    'purchase_amount' => $amount,
                                    'commission' => $commissionL3,
                                    'level' => 3
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    protected function hasPurchasedPackage($user)
    {
        return DB::table('package2_purchases')
            ->where('user_id', $user->id)
            ->exists();
    }

    protected function isUserEligibleForCommission($user)
    {
        // Check if user is within first 3 months
        if (empty($user->user_doa)) {
            return false;
        }

        // Check if 3 months have passed since activation
        $threeMonthsLater = Carbon::parse($user->user_doa)->addMonths(3);
        if (now()->gt($threeMonthsLater)) {
            return false;
        }

        // Check if user has received less than 10,000 in total commissions
        $totalCommissions = Commission::where('user_id', $user->id)
            ->sum('commission');

        return $totalCommissions < 10000;
    }


    //Calculating the rank of the user based on the total business volume
    public function checkAndRewardUser($userUlid)
    {
        $directLegs = User::where('sponsor_id', $userUlid)->get();

        $legsBusiness = [];
        foreach ($directLegs as $leg) {
            $legsBusiness[$leg->ulid] = $this->getTotalBusiness($leg->ulid);
        }

        if (empty($legsBusiness)) return;

        $strongLegUlid = array_search(max($legsBusiness), $legsBusiness);
        $strongLegBusiness = $legsBusiness[$strongLegUlid];
        $weakerLegsBusiness = array_sum($legsBusiness) - $strongLegBusiness;
        $matchingBusiness = $weakerLegsBusiness;

        $leftBusiness = 0;
        $rightBusiness = 0;

        if ($directLegs->count() >= 2) {
            $leftBusiness = $strongLegBusiness;
            $rightBusiness = $weakerLegsBusiness;
        } elseif ($directLegs->count() == 1) {
            $leftBusiness = $legsBusiness[$directLegs[0]->ulid] ?? 0;
        }

        $user = User::where('ulid', $userUlid)->first();
        $user->update([
            'left_business' => $leftBusiness,
            'right_business' => $rightBusiness,
        ]);

        // Process rewards for current user
        $this->processUserRewards($user, $matchingBusiness, $strongLegBusiness);

        // Update business and ranks for all upline parents
        $this->updateUplineBusinessAndRanks($user);
    }

    protected function processUserRewards($user, $matchingBusiness, $strongLegBusiness)
    {
        $rewards = DB::table('royalty_level_rewards')
            ->orderBy('sr_no')
            ->get();

        $givenRewards = RoyaltyRewardsIncome::where('user_id', $user->id)
            ->orderBy('id')
            ->get();

        $totalRequiredBusiness = 0;
        $highestAchievedRank = $user->current_rank;
        $lastClaimedBusiness = 0;

        foreach ($rewards as $reward) {
            $requiredBusiness = $this->convertMatchingToNumber($reward->matching);

            $existingReward = $givenRewards->where('rank', $reward->level)->first();

            if ($existingReward) {
                if ($existingReward->status == 1) {
                    $lastClaimedBusiness = $requiredBusiness;
                    $totalRequiredBusiness = $lastClaimedBusiness;
                }
                continue;
            }

            $totalRequiredBusiness = $lastClaimedBusiness + $requiredBusiness;

            if ($matchingBusiness >= $totalRequiredBusiness && $strongLegBusiness >= $totalRequiredBusiness) {
                RoyaltyRewardsIncome::create([
                    'user_id'   => $user->id,
                    'user_ulid' => $user->ulid,
                    'points'    => $this->convertMatchingToNumber($reward->reward),
                    'rank'      => $reward->level,
                    'status'    => 0
                ]);

                $highestAchievedRank = $reward->level;
                $givenRewards->push((object)[
                    'rank' => $reward->level,
                    'status' => 0
                ]);
            }
        }

        if ($user->current_rank != $highestAchievedRank) {
            $user->update(['current_rank' => $highestAchievedRank]);
        }
    }

    protected function updateUplineBusinessAndRanks(User $user)
    {
        $currentUser = $user;
        $processedUsers = [];

        // Traverse up the sponsorship tree
        while ($currentUser->sponsor_id && !in_array($currentUser->sponsor_id, $processedUsers)) {
            $sponsor = User::where('ulid', $currentUser->sponsor_id)->first();
            if (!$sponsor) break;

            // Get all direct legs of the sponsor
            $directLegs = User::where('sponsor_id', $sponsor->ulid)->get();

            $legsBusiness = [];
            foreach ($directLegs as $leg) {
                $legsBusiness[$leg->ulid] = $this->getTotalBusiness($leg->ulid);
            }

            if (!empty($legsBusiness)) {
                $strongLegUlid = array_search(max($legsBusiness), $legsBusiness);
                $strongLegBusiness = $legsBusiness[$strongLegUlid];
                $weakerLegsBusiness = array_sum($legsBusiness) - $strongLegBusiness;
                $matchingBusiness = $weakerLegsBusiness;

                $leftBusiness = 0;
                $rightBusiness = 0;

                if ($directLegs->count() >= 2) {
                    $leftBusiness = $strongLegBusiness;
                    $rightBusiness = $weakerLegsBusiness;
                } elseif ($directLegs->count() == 1) {
                    $leftBusiness = $legsBusiness[$directLegs[0]->ulid] ?? 0;
                }

                $sponsor->update([
                    'left_business' => $leftBusiness,
                    'right_business' => $rightBusiness,
                ]);

                // Process rewards for each upline sponsor
                $this->processUserRewards($sponsor, $matchingBusiness, $strongLegBusiness);
            }

            $processedUsers[] = $sponsor->ulid;
            $currentUser = $sponsor;
        }
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

    public function getTotalBusiness($ulid)
    {
        $user = User::where('ulid', $ulid)->first();

        if (!$user) return 0;

        // Sum own purchases
        $ownBusiness = ProductPackagePurchase::where('ulid', $ulid)->sum('final_price');

        // Get direct downline
        $downlines = User::where('sponsor_id', $ulid)->get();

        $totalBusiness = $ownBusiness;

        foreach ($downlines as $downline) {
            $totalBusiness += $this->getTotalBusiness($downline->ulid);
        }

        return $totalBusiness;
    }




    public function viewWallet(Request $request)
    {
        $wallet1 = Auth::user()->wallet1_balance;
        $wallet2 = Auth::user()->wallet2_balance;
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

        $breadcrumbs = [
            ['title' => 'Wallet', 'url' => route('user.viewwallet')],
            ['title' => 'Manage Wallet', 'url' => route('user.viewwallet')]
        ];

        return view('user.viewwallet', compact('wallet1', 'wallet2', 'wallet1Transactions', 'wallet2Transactions', 'withdrawals', 'breadcrumbs'));
    }

    // public function level1Commissions(Request $request)
    // {
    //     // Get filter parameters from request
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');

    //     $commissions = Commission::where('user_id', Auth::id())
    //         ->where('level', 1)
    //         ->when($startDate, function ($query) use ($startDate) {
    //             return $query->whereDate('created_at', '>=', $startDate);
    //         })
    //         ->when($endDate, function ($query) use ($endDate) {
    //             return $query->whereDate('created_at', '<=', $endDate);
    //         })
    //         ->latest()
    //         ->paginate(10); // 
    //     $breadcrumbs = [
    //         ['title' => 'Incentives', 'url' => route('user.commissions.level1')],
    //         ['title' => 'Direct Commissions', 'url' => route('user.commissions.level1')]
    //     ];

    //     return view('user.rewards.directcommission', compact('commissions', 'breadcrumbs', 'startDate', 'endDate'));
    // }

    // public function level2Commissions(Request $request)
    // {
    //     // Get filter parameters from request
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');

    //     $commissions = Commission::where('user_id', Auth::id())
    //         ->whereIn('level', [2, 3])
    //         ->when($startDate, function ($query) use ($startDate) {
    //             return $query->whereDate('created_at', '>=', $startDate);
    //         })
    //         ->when($endDate, function ($query) use ($endDate) {
    //             return $query->whereDate('created_at', '<=', $endDate);
    //         })
    //         ->latest()
    //         ->paginate(10); // Changed from take(10) to paginate(10)

    //     $breadcrumbs = [
    //         ['title' => 'Incentives', 'url' => route('user.commissions.level2')],
    //         ['title' => 'Network Bonus', 'url' => route('user.commissions.level2')]
    //     ];

    //     return view('user.rewards.networkcommission', compact('commissions', 'breadcrumbs', 'startDate', 'endDate'));
    // }

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


    public function showUserYearlyProfits()
    {
        $user = Auth::user();

        // Get rank-based profits
        $rankProfits = Wallet1Transaction::where('user_id', $user->id)
            ->where('notes', 'like', '%yearly profit as %')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($transaction) {
                preg_match('/₹(\d+) received for (\d{4}) yearly profit as (.+) \((\d+)%\)/', $transaction->notes, $matches);
                return [
                    'type' => 'rank',
                    'amount' => $transaction->points,
                    'year' => $matches[2] ?? null,
                    'rank' => $matches[3] ?? null,
                    'percentage' => $matches[4] ?? null,
                    'date' => $transaction->created_at->format('d M Y'),
                ];
            });

        // Combine and sort by year
        $allProfits = $rankProfits
            ->sortByDesc('year')
            ->groupBy('year');

        // Get user's packages eligible for profit share
        $eligiblePackages = ProductPackagePurchase::where('user_id', $user->id)
            ->where('profit_share', 1)
            ->get();

        $breadcrumbs = [
            ['title' => 'Incentives', 'url' => route('user.yearly.profits')],
            ['title' => 'Royalty Income', 'url' => route('user.yearly.profits')]
        ];
        return view('user.rewards.yearlyProfits', compact('user', 'allProfits', 'eligiblePackages', 'breadcrumbs'));
    }

    public function showUserMonthlyProfits(Request $request)
    {
        $user = Auth::user();

        // Get filter parameters from request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $distributionsQuery = PackageMonthlyDistribution::with(['user', 'packagePurchase'])
            ->where('user_id', $user->id);

        // Apply date filters
        $distributionsQuery->when($startDate, function ($query) use ($startDate) {
            return $query->whereDate('distribution_date', '>=', $startDate);
        });

        $distributionsQuery->when($endDate, function ($query) use ($endDate) {
            return $query->whereDate('distribution_date', '<=', $endDate);
        });

        $distributions = $distributionsQuery->orderBy('distribution_date', 'desc')
            ->paginate(10);

        // Calculate total based on filtered results
        $totalAmount = $distributionsQuery->sum('distributed_amount');

        $breadcrumbs = [
            ['title' => 'Package', 'url' => route('user.monthly.profits')],
            ['title' => 'Monthly Income', 'url' => route('user.monthly.profits')]
        ];

        return view('user.rewards.view-monthlyProfits', compact('distributions', 'breadcrumbs', 'totalAmount', 'startDate', 'endDate'));
    }
}
