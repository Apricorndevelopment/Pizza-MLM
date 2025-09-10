<?php

namespace App\Http\Controllers;

use App\Mail\UserRegisteredMail;
use App\Models\User;
use App\Models\Admin;
use App\Models\Gallery;
use App\Models\Package2Purchase;
use App\Models\PasswordOtp;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function auth()
    {
        return view('Auth.register');
    }

    public function loadMore(Request $request)
    {
        $page = (int) $request->get('page', 1); // First page = after first 3
        $perPage = 6;
        $initialDisplayCount = 3;

        // Skip initial 3, then apply pagination
        $skip = $initialDisplayCount + (($page - 1) * $perPage);

        $photos = Gallery::skip($skip)->take($perPage)->get();
        $totalPhotos = Gallery::count();

        $hasMore = $totalPhotos > $skip + $photos->count();

        if ($request->ajax()) {
            $html = '';
            foreach ($photos as $photo) {
                $html .= '<div class="col">';
                $html .= '<div class="card h-100 shadow-sm">';
                $html .= '<img src="' . asset('storage/photos/' . basename($photo->photo)) . '" alt="' . e($photo->title) . '" class="card-img-top img-fluid" style="height: 250px; object-fit: cover;">';
                $html .= '<div class="card-body">';
                $html .= '<h5 class="card-title">' . e($photo->title) . '</h5>';
                $html .= '</div></div></div>';
            }

            return response()->json([
                'html' => $html,
                'hasMore' => $hasMore,
                'loaded' => $photos->count(),
                'total' => $totalPhotos,
            ]);
        }

        return abort(404);
    }


    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:15',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[!@#$%^&*(),.?":{}|<>]/',
            ],
            'sponsor_id' => 'required|string|max:50|exists:users,ulid',
            'parent_id' => 'nullable|string|max:50|exists:users,ulid',
        ], [
            'password.regex' => 'Please use a strong password with at least one special character.',
        ]);

        $customUlid = 'AH' . rand(100000, 999999);

        while (User::where('ulid', $customUlid)->exists()) {
            $customUlid = 'AH' . rand(100000, 999999);
        }
        $plainPassword = $request->password;
        $user = User::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'sponsor_id' => $request->sponsor_id,
            'parent_id' => $request->parent_id,
            'ulid' => $customUlid,
            'password' => Hash::make($plainPassword),
            'role' => 'user',
            'status' => 'inactive',
        ]);

        Mail::to($user->email)->send(new UserRegisteredMail($user, $plainPassword));

        return view('Auth.congratulations', compact('user'));
    }

    public function login()
    {
        return view('Auth.login');
    }

    public function showLoginForm()
    {
        return view('Auth.login');
    }
    // public function logindetails(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $email = $request->email;
    //     $password = $request->password;

    //     if ($request->is('admin/login')) {
    //         if (Auth::attempt(['email' => $email, 'password' => $password, 'role' => 'admin'])) {
    //             $request->session()->regenerate();
    //             session(['admin_logged_in' => true]);
    //             return redirect()->route('admin.dashboard');
    //         } else {
    //             return back()->withErrors(['email' => 'Invalid admin credentials']);
    //         }
    //     }

    //     // User login
    //     if (Auth::attempt(['email' => $email, 'password' => $password])) {
    //         $request->session()->regenerate();

    //         if (Auth::user()->role === 'user') {
    //             return redirect()->route('user.dashboard');
    //         } else {
    //             Auth::logout();
    //             return back()->withErrors(['email' => 'Invalid user role']);
    //         }
    //     }

    //     return back()->withErrors(['email' => 'Invalid login credentials']);
    // }
    public function logindetails(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;

        if ($request->is('admin/login')) {
            if (Auth::attempt(['email' => $email, 'password' => $password, 'role' => 'admin'])) {
                $request->session()->regenerate();
                session(['admin_logged_in' => true]);
                return redirect()->route('admin.dashboard');
            } else {
                return back()->with('error', 'Login details are wrong.');
            }
        }

        // Check if input is email or ULID
        $fieldType = filter_var($email, FILTER_VALIDATE_EMAIL) ? 'email' : 'ulid';

        if (Auth::attempt([$fieldType => $email, 'password' => $password])) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'user') {
                if (Auth::user()->role === 'user') {
                    return redirect()->route('user.dashboard')
                        ->with('welcome_popup', true)
                        ->with('welcome_name', Auth::user()->name);
                }
            } else {
                Auth::logout();
                return back()->with('error', 'Login details are wrong.');
            }
        }


        return back()->with('error', 'Login details are wrong.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login')->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT',
        ]);
    }


    // For user Tree
    public function showTreeRecursive()
    {
        $user = Auth::user(); // Logged-in user

        $tree = $this->buildTree($user->ulid); // Use ULID as key

        $treeHtml = $this->renderTreeHtml($user, $tree);

        $breadcrumbs = [
            ['title' => 'Network', 'url' => route('user.view.userTree')],
            ['title' => 'Network Explorer', 'url' => route('user.view.userTree')]
        ];

        return view('user.network.viewuser', compact('user', 'treeHtml', 'breadcrumbs'));
    }

    private function buildTree($ulid)
    {
        $users = User::where('sponsor_id', $ulid)->get();

        return $users->map(function ($user) {
            $user->children = $this->buildTree($user->ulid); // Recursive by ULID
            $user->total_team = $this->countTotalTeam($user->ulid);
            return $user;
        });
    }

    private function renderTreeHtml($user, $children, $isRoot = true)
    {
        $html = $isRoot ? '<ul class="tree" style="padding-left:10px; margin:0; font-family:Arial, sans-serif;">' : '<ul class="nested" style="padding-left:0px; margin:6px 0 0 8px; border-left:1px dotted black;">';

        $hasChildren = $children->isNotEmpty();
        $icon = $hasChildren ? '<span style="border:1.3px solid black;padding:1px;font-size:10px">➖</span> <i class="fa-solid fa-folder-open text-primary"></i>' : '―<i class="fa-solid fa-folder text-primary"></i>';

        $html .= '<li class="my-1 list-unstyled">';

        // Add data-user-ulid attribute for easier selection
        $html .= '<span class="tree-node small lh-sm d-flex flex-wrap align-items-center" data-user-ulid="' . $user->ulid . '" onclick="toggleNode(this); loadUserDetails(\'' . $user->ulid . '\')">';

        $html .= '<span class="toggle-icon me-1">' . $icon . '</span>';

        $html .= '<span class="node-label fw-medium text-dark">';
        $html .= $user->name . ' ';
        $html .= '<span class="text-muted">(' . $user->ulid . ')</span>';
        $html .= ' | <span class="d-none d-md-inline">Total </span> Team:' . $user->total_team;
        $html .= '</span>';

        $html .= '</span>';

        if ($hasChildren) {
            $html .= '<ul class="nested">';
            foreach ($children as $child) {
                $html .= $this->renderTreeHtml($child, $child->children, false);
            }
            $html .= '</ul>';
        }

        $html .= '</li>';
        $html .= '</ul>';

        return $html;
    }

    public function getUserDetails($ulid)
    {
        $authUser = Auth::user();

        $user = User::where('ulid', $ulid)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Purchase Amount
        $purchaseAmount = Package2Purchase::where('user_id', $user->id)->sum('final_price');

        // Level Calculation (ULID-based)
        $level = $this->calculateLevel($authUser->ulid, $user->ulid);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'ulid' => $user->ulid,
            'email' => $user->email,
            'registered_date' => $user->created_at->format('d-m-Y, H:i A'),
            'activation_date' => $user->user_doa,
            'rank' => $user->current_rank,
            'status' => $user->status,
            'points_balance' => $user->points_balance,
            'loyalty_balance' => $user->loyalty_balance,
            'left_business' => $user->left_business,
            'right_business' => $user->right_business,
            'level' => $level,
            'purchase_amount' => $purchaseAmount,
        ]);
    }

    public function getUserDetailsAdmin($ulid)
    {
        $user = User::where('ulid', $ulid)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Purchase Amount
        $purchaseAmount = Package2Purchase::where('user_id', $user->id)->sum('final_price');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'ulid' => $user->ulid,
            'email' => $user->email,
            'registered_date' => $user->created_at->format('d-m-Y, H:i A'),
            'activation_date' => $user->user_doa,
            'rank' => $user->current_rank,
            'status' => $user->status,
            'points_balance' => $user->points_balance,
            'loyalty_balance' => $user->loyalty_balance,
            'left_business' => $user->left_business,
            'right_business' => $user->right_business,
            'purchase_amount' => $purchaseAmount,
        ]);
    }

    // Calculate the level of a target user in relation to the starting user (Auth user)

    private function calculateLevel($startUlid, $targetUlid, $level = 0)
    {
        // Base case: same user
        if ($startUlid === $targetUlid) {
            return $level;
        }

        // Find the target user
        $targetUser = User::where('ulid', $targetUlid)->first();

        if (!$targetUser || !$targetUser->sponsor_id) {
            return null; // Not in tree or no sponsor
        }

        // Move up one level using sponsor's ULID
        return $this->calculateLevel($startUlid, $targetUser->sponsor_id, $level + 1);
    }

    // for Admin Tree
    public function showUserTreeFromAdmin($adminId)
    {
        $admin = Admin::findOrFail($adminId);

        // Get top-level users sponsored by this admin's AUID
        $tree = $this->buildTreeFromAdmin($admin->auid);

        // Convert the tree to text format
        $treeText = $this->renderTreeTextFromAdmin($admin, $tree);

        return view('admin.user_tree', compact('admin', 'tree', 'treeText'));
    }

    private function buildTreeFromAdmin($sponsor_id)
    {
        $users = User::where('sponsor_id', $sponsor_id)->get();

        return $users->map(function ($user) {
            $user->children = $this->buildTreeFromAdmin($user->ulid);
            $user->total_team = $this->countTotalTeam($user->ulid);
            return $user;
        });
    }

    private function renderTreeTextFromAdmin($admin, $children, $isRoot = true)
    {
        $output = '└── 👑 Admin: ' . $admin->name . ' (' . $admin->auid . ')' . "\n";

        // foreach ($children as $index => $child) {
        //     $isLast = $index === count($children) - 1;
        //     $output .= $this->renderTreeTextStyled($child, $child->children ?? collect(), '', $isLast);
        // }

        // return $output;
        $html = $isRoot ? '<ul class="tree" style="padding-left:10px; margin:0; font-family:Arial, sans-serif;">' : '<ul class="nested" style="padding-left:10px; margin:6px 0 0 5px; border-left:1px solid #e0e0e0;">';

        foreach ($children as $user) {
            $hasChildren = $user->children->isNotEmpty();

            $html .= '<li>';

            if ($hasChildren) {
                $html .= '<span class="toggle-icon" onclick="toggleNode(this)">➕</span>';
            } else {
                $html .= '<span class="toggle-icon">📁</span>';
            }

            $html .= '<span class="node-label" onclick="loadUserDetails(\'' . $user->ulid . '\')">';
            $html .= '<span class="fw-medium text-dark">';
            $html .= $user->name;
            $html .= '<span class="text-muted">(' . $user->ulid . ')</span>';
            $html .= '|<span class="d-none d-md-inline">Total </span>Team:' . $user->total_team;
            $html .= '</span>';
            $html .= '</span>';

            if ($hasChildren) {
                $html .= $this->renderTreeTextFromAdmin($admin, $user->children, false);
            }

            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }

    private function renderTreeTextStyled($user, $children, $prefix = '', $isLast = true)
    {
        $branch = $isLast ? '└──' : '├──';
        $output = $prefix . $branch . ' 📁 ' . $user->name . ' (' . $user->ulid . ')' . "\n";

        $newPrefix = $prefix . ($isLast ? '    ' : '│   ');

        foreach ($children as $index => $child) {
            $childIsLast = $index === count($children) - 1;
            $output .= $this->renderTreeTextStyled($child, $child->children ?? collect(), $newPrefix, $childIsLast);
        }

        return $output;
    }

    private function countTotalTeam($ulid)
    {
        return User::where('sponsor_id', $ulid)->count();
    }


    public function sendEmailOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $otp = rand(100000, 999999);

        Session::put('email_otp', $otp);
        Session::put('email_to_verify', $request->email);

        Mail::raw("Your OTP is: $otp", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Your Email Verification OTP');
        });

        return response()->json(['status' => true, 'message' => 'OTP sent successfully.']);
    }

    public function showForgotForm()
    {
        return view('Auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        PasswordOtp::updateOrCreate(
            ['email' => $request->email],
            ['otp' => $otp, 'expires_at' => $expiresAt]
        );

        Mail::raw("Your OTP is: $otp", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Password Reset OTP');
        });

        return redirect()->route('password.reset')->with('email', $request->email);
    }

    public function showResetForm(Request $request)
    {
        $email = session('email');
        return view('Auth.reset-password', compact('email'));
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[!@#$%^&*(),.?":{}|<>]/', // ✅ Special character check
            ],
        ], [
            'password.regex' => 'Password must contain at least one special character.',
        ]);

        $record = PasswordOtp::where('email', $request->email)->first();

        if (!$record || $record->otp != $request->otp || Carbon::now()->gt($record->expires_at)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP']);
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete OTP after use
        $record->delete();

        return redirect()->route('auth.login')->with('success', 'Password reset successfully');
    }
}
