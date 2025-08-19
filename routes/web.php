<?php

use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PackageAssignmentController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\UserController;
use App\Models\Package2;
use App\Models\Package2Details;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-mail', function () {
    \Illuminate\Support\Facades\Mail::raw('This is a test email.', function ($message) {
        $message->to('developerapricorn1234@gmail.com')->subject('Test Mail');
    });

    return "Mail sent!";
});
Route::get('register', [AuthController::class, 'auth'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

Route::post('/login', [AuthController::class, 'logindetails'])->name('auth.login');
// User dashboard
Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard')->middleware('auth');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
// Route::middleware(['auth'])->group(function () {

//     Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
//     Route::get('/profile/edit', [UserController::class, 'edit'])->name('user.profile.edit');
//     Route::put('/profile/update', [UserController::class, 'update'])->name('user.profile.update');

//     Route::post('/purchase-package', [UserController::class, 'purchasePackage'])->name('user.purchase-package');
//     Route::get('/my-packages', [PackageAssignmentController::class, 'viewUserPackage'])->name('user.packages');
//     Route::get('/user/viewuser', [AuthController::class, 'showTreeRecursive'])->name('user.view');

//     Route::get('/get-package-rates/{packageId}', function ($packageId) {
//         $rates = Package2Details::where('package2_id', $packageId)->get();
//         return response()->json($rates);
//     });
//     Route::get('/get-package-price/{packageId}', function ($packageId) {
//         $package = Package2::findOrFail($packageId);
//         return response()->json([
//             'price' => $package->price,
//             'user_balance' => Auth::check() ? Auth::user()->points_balance : 0
//         ]);
//     });
//     Route::get('/package2-purchase', [UserController::class, 'showPurchaseForm'])->name('package2.purchase');
//     Route::post('/package2-purchase', [UserController::class, 'processPurchase'])->name('package2.process-purchase');
// });

Route::middleware(['auth'])->group(function () {

    Route::middleware(['auth', 'password.age'])->group(function () {

        // Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
        Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::get('/profile/edit', [UserController::class, 'edit'])->name('user.profile.edit');
        Route::put('/profile/update', [UserController::class, 'update'])->name('user.profile.update');
    });


    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('user.profile.edit');
    Route::put('/profile/update', [UserController::class, 'update'])->name('user.profile.update');

    Route::post('/purchase-package', [UserController::class, 'purchasePackage'])->name('user.purchase-package');

    Route::get('/package2-purchase', [UserController::class, 'showPurchaseForm'])->name('package2.purchase');
    Route::post('/package2-purchase', [UserController::class, 'processPurchase'])->name('package2.process-purchase');
    Route::get('/user/my-packages', [PackageAssignmentController::class, 'viewUserPackage'])->name('user.packages');
    Route::get('/user/activation-package', [PackageAssignmentController::class, 'viewActivationPackage'])->name('user.activation.package');
    Route::get('/user/viewuser', [AuthController::class, 'showTreeRecursive'])->name('user.view.userTree');
    Route::get('/user/network-summary', [ContactController::class, 'networkSummary'])->name('user.network.summary');
    Route::get('/user/direct-team', [ContactController::class, 'directTeam'])->name('user.direct.team');

    Route::get('/user/commissions/level1', [UserController::class, 'level1Commissions'])->name('user.commissions.level1');
    Route::get('/user/commissions/level2', [UserController::class, 'level2Commissions'])->name('user.commissions.level2');
    Route::get('/user/reports/level-income', [UserController::class, 'levelIncomeReport'])->name('user.reports.level-income');

    Route::get('/user/user-rewards/{ulid}', [UserController::class, 'showUserRankRewards'])->name('user.rewards.rankRewards');
    Route::post('/user/reward/claim/{id}', [UserController::class, 'claimReward'])->name('user.rank.claimReward');
    Route::post('/user/reward/reject/{id}', [UserController::class, 'rejectReward'])->name('user.rank.rejectReward');

    Route::get('/user/my-yearly-profits', [UserController::class, 'showUserYearlyProfits'])->name('user.yearly.profits');
    Route::get('/user/my-monthly-profits', [UserController::class, 'showUserMonthlyProfits'])->name('user.monthly.profits');

    Route::get('/get-package-rates/{packageId}', function ($packageId) {
        $rates = Package2Details::where('package2_id', $packageId)->get();
        return response()->json($rates);
    });
    Route::get('/get-package-price/{packageId}', function ($packageId) {
        $package = Package2::findOrFail($packageId);
        return response()->json([
            'price' => $package->price,
            'user_balance' => Auth::check() ? Auth::user()->points_balance : 0
        ]);
    });
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user/transfer-points', [WalletController::class, 'showTransferForm'])->name('user.transferPointsForm');
    Route::post('/user/search-downline', [WalletController::class, 'searchDownlineUser'])->name('user.search.downline');
    Route::post('/user/transfer-points', [WalletController::class, 'transferPoints'])->name('user.transfer.points');

    Route::middleware(['diamond'])->group(function() {
        Route::get('/user/stock-transfer', [StockController::class, 'showUserTransferForm'])->name('user.stock.form');
        Route::post('/user/stock-transfer/search-user', [StockController::class, 'searchUserInUserSide'])->name('user.stock.search-user');
        Route::post('/user/stock-transfer', [StockController::class, 'transferStockUserPanel'])->name('user.stock.transfer');
        Route::get('/user/stock/transfer-by-coupon', [StockController::class, 'showCouponTransferForm'])->name('user.stock.coupon-transfer');
        Route::post('/user/stock/validate-coupon', [StockController::class, 'validateCoupon'])->name('user.stock.validate-coupon');
        Route::post('/user/stock/transfer-by-coupon', [StockController::class, 'transferStockByCoupon'])->name('user.stock.transfer-by-coupon');
    });
    Route::get('/user/viewStock', [StockController::class, 'stockTransferHistory'])->name('user.viewStock');
    Route::get('/user/allStocks', [StockController::class, 'viewUserStocks'])->name('user.allStocks');
});


Route::get('/get-user-details/{ulid}', [AuthController::class, 'getUserDetails']);
Route::get('/admin/get-user-details/{ulid}', [AuthController::class, 'getUserDetails']);

Route::middleware(['auth'])->group(function () {
    Route::post('/user/fetch-sub-users', [AuthController::class, 'fetchSubUsers'])->name('user.subusers');
});

Route::get('adminlogin', [AdminController::class, 'index'])->name('admin.login');
Route::post('adminlogin', [AdminController::class, 'login'])->name('admin.login.post');

// Admin Protected Routes
Route::middleware(['auth:admin'])->group(function () {
    Route::get('admindashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('adminlogout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::get('/admin/profile/edit', [AdminController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/admin/profile/update', [AdminController::class, 'update'])->name('admin.profile.update');

    Route::get('/profit-distribution', [AdminController::class, 'showFormForProfitDistribution'])->name('admin.profit.distribution');
    Route::post('/profit-distribution', [AdminController::class, 'distributeYearlyProfit'])->name('admin.profit.distribute');
    Route::get('/admin/view-yearly-distribution', [AdminController::class, 'viewYearlyDistribution'])->name('admin.view.distribution');
    Route::get('/admin/view-monthly-distribution', [AdminController::class, 'viewMonthlyDistributions'])->name('admin.view.monthlyDistribution');

    Route::get('/admin/viewmember', [AdminController::class, 'viewmemeber'])->name('admin.viewmember');
    Route::get('/admin/viewmember/{id}', [AdminController::class, 'viewMemberDetails'])->name('admin.viewmemberdetails');
    Route::get('/admin/editmember/{id}', [AdminController::class, 'editMember'])->name('admin.editmember');
    Route::put('/admin/update-member/{id}', [AdminController::class, 'updateMember'])->name('admin.updatemember');
    Route::delete('/admin/delete-member/{id}', [AdminController::class, 'deleteMember'])->name('admin.deletemember');
    Route::get('/admin/user-tree/{adminId}', [AuthController::class, 'showUserTreeFromAdmin'])->name('admin.user.tree');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('products', ProductController::class);
    });

    Route::prefix('admin')->group(function () {
        Route::get('/wallet', [WalletController::class, 'index'])->name('admin.wallet');
        Route::get('/wallet-transactions', [WalletController::class, 'viewAllTransactions'])->name('admin.wallet-transactions');
        Route::post('/get-user-by-ulid', [WalletController::class, 'getUserByUlid']);
        Route::post('/add-points', [WalletController::class, 'addPoints'])->name('admin.addPoints');
        Route::post('/add-loyalty', [WalletController::class, 'addLoyalty'])->name('admin.addLoyalty');

        Route::get('/stock-transfer', [StockController::class, 'showTransferForm'])->name('admin.stock.form');
        Route::post('/stock-transfer/search-user', [StockController::class, 'searchUser'])->name('admin.stock.search-user');
        Route::post('/stock-transfer', [StockController::class, 'transferStock'])->name('admin.stock.transfer');
        Route::get('/view-stock', [StockController::class, 'viewAdminStock'])->name('admin.viewStock');

        Route::get('/withdrawals', [WalletController::class, 'viewWithdrawlRequest'])->name('admin.withdrawls.index');
        Route::post('/withdrawals/{id}/approve', [WalletController::class, 'approveWithdrawlRequest'])->name('admin.withdrawls.approve');
        Route::post('/withdrawals/{id}/reject', [WalletController::class, 'rejectWithdrawlRequest'])->name('admin.withdrawls.reject');

        Route::get('/package', [PackageController::class, 'package'])->name('admin.package');
        Route::get('/packages/package1/create', [PackageController::class, 'createPackage1'])->name('admin.package1.create');
        Route::get('/packages/package2/create', [PackageController::class, 'createPackage2'])->name('admin.package2.create');
        Route::post('/packages/package1', [PackageController::class, 'storePackage1'])->name('admin.package1.store');
        Route::post('/packages/package2', [PackageController::class, 'storePackage2'])->name('admin.package2.store');

        Route::get('/packages/package1/{id}/edit', [PackageController::class, 'editPackage1'])->name('admin.package1.edit');
        Route::put('/packages/package1/{id}', [PackageController::class, 'updatePackage1'])->name('admin.package1.update');
        Route::delete('/packages/package1/{id}', [PackageController::class, 'destroyPackage1'])->name('admin.package1.destroy');

        Route::get('/packages/package2/{id}/edit', [PackageController::class, 'editPackage2'])->name('admin.package2.edit');
        Route::put('/packages/package2/{id}', [PackageController::class, 'updatePackage2'])->name('admin.package2.update');
        Route::delete('/packages/package2/{id}', [PackageController::class, 'destroyPackage2'])->name('admin.package2.destroy');

        Route::prefix('admin/packages')->name('admin.packages.')->group(function () {
            Route::get('/assign', [PackageAssignmentController::class, 'index'])->name('assign');
            Route::post('/search', [PackageAssignmentController::class, 'search'])->name('search');
            Route::post('/assign', [PackageAssignmentController::class, 'assignPackage'])->name('assign.submit');
        });
    });
});


Route::middleware(['auth'])->group(function () {
    Route::get('/user/viewwallet', [UserController::class, 'viewWallet'])->name('user.viewwallet');
    Route::post('/user/withdraw/points', [WalletController::class, 'withdrawPoints'])->name('user.withdraw.points');
});

Route::get('/check-sponsor/{id}', function ($id) {
    $sponsor = User::where('ulid', $id)->first();

    if ($sponsor) {
        return response()->json([
            'exists' => true,
            'name' => $sponsor->name ?? $sponsor->name
        ]);
    } else {
        return response()->json(['exists' => false]);
    }
})->name('check.sponsor');

Route::get('/check-parent/{id}', function ($id) {
    $parent = User::where('ulid', $id)->first();

    if ($parent) {
        return response()->json([
            'exists' => true,
            'name' => $parent->name ?? $parent->name
        ]);
    } else {
        return response()->json(['exists' => false]);
    }
})->name('check.parent');

Route::post('/check-email', function (\Illuminate\Http\Request $request) {
    $exists = User::where('email', $request->email)->exists();
    return response()->json(['exists' => $exists]);
})->name('check.email');

Route::post('/send-email-otp', [AuthController::class, 'sendEmailOtp'])->name('send.email.otp');

Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');

// Handle email submit (send OTP)
Route::post('/forgot-password', [AuthController::class, 'sendOtp'])->name('password.email');

// Show OTP + new password form
Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('password.reset');

// Handle OTP + password submission
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');
