<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Package1;
use App\Models\Package2;
use App\Models\Package2Purchase;
use App\Models\PackageMonthlyDistribution;
use App\Models\PointsTransaction;
use App\Models\User;
use App\Models\Wallet;
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

    public function viewmemeber()
    {
        $member = User::Paginate(10);
        return view('admin.members.viewmember', compact('member'));
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
            // Delete from all related tables
            DB::table('level_incomes')->where('user_id', $member->id)->delete();
            DB::table('loyalty_transactions')->where('user_id', $member->id)->delete();
            DB::table('package2_purchases')->where('user_id', $member->id)->delete();
            DB::table('package_monthly_distributions')->where('user_id', $member->id)->delete();
            DB::table('package_transactions')->where('user_id', $member->id)->delete();
            DB::table('points_transactions')->where('user_id', $member->id)->delete();

            // Update any reference fields
            User::where('sponsor_id', $member->ulid)->update(['sponsor_id' => null]);
            User::where('parent_id', $member->ulid)->update(['parent_id' => null]);

            // Finally delete the user
            $member->forceDelete();

            DB::commit();

            return redirect()->route('admin.viewmember')->with('success', 'Member deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Delete failed: ' . $e->getMessage());
        }
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
                        'notes' => "₹$perUserAmount received for $year yearly profit as $levels->level ($levels->profit%)",
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
                        'notes' => "₹$userAmount received for $year yearly package profit share ($profitSharePercentage%) based on package value ₹{$buyer['purchase']->final_price} and duration factor {$buyer['weight']}/$totalWeight",
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
