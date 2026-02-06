<?php

use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\PercentageIncomeController;
use App\Http\Controllers\Admin\PercentageLevelController;
use App\Http\Controllers\Admin\PercentageRewardController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\LoginActivityController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\user\ComplaintController;
use App\Http\Controllers\User\UserIncomeController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\Vendor\VendorOrderController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorProductController;
use App\Http\Middleware\IsVendor;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/gallery/load-more', [AuthController::class, 'loadMore'])->name('gallery.load-more');
Route::get('/news/load-more', [AuthController::class, 'loadMoreNews'])->name('news.load-more');

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

Route::get('/dashboard/sales-data', [UserController::class, 'getSalesChartData'])->name('dashboard.sales.data');

Route::middleware(['auth'])->group(function () {

    Route::middleware(['auth', 'password.age'])->group(function () {
        // Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
        Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::get('/profile/edit', [UserController::class, 'edit'])->name('user.profile.edit');
        Route::put('/profile/update', [UserController::class, 'update'])->name('user.profile.update');
    });

    // "Become a Vendor" पेज दिखाने और खरीदने का रूट
    Route::get('/become-vendor', [VendorController::class, 'showPurchasePage'])->name('user.become_vendor');
    Route::post('/purchase-vendor', [VendorController::class, 'processPurchase'])->name('user.purchase_vendor');

    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/user/profile/edit', [UserController::class, 'edit'])->name('user.profile.edit');
    Route::put('/user/profile/update', [UserController::class, 'update'])->name('user.profile.update');


    Route::get('/package2-purchase', [UserController::class, 'showPurchaseForm'])->name('package2.purchase');
    Route::post('/package2-purchase', [UserController::class, 'processPurchase'])->name('package2.process-purchase');
    
    Route::get('/user/viewuser', [AuthController::class, 'showTreeRecursive'])->name('user.view.userTree');
    Route::get('/user/network-summary', [ContactController::class, 'networkSummary'])->name('user.network.summary');
    Route::get('/user/direct-team', [ContactController::class, 'directTeam'])->name('user.direct.team');

   
    Route::prefix('user')->group(function () {
        // Other routes...
        Route::get('/login-activity', [LoginActivityController::class, 'index'])->name('user.login-activity');
        Route::delete('/login-activity/{id}', [LoginActivityController::class, 'destroy'])->name('user.login-activity.destroy');
        Route::post('/login-activity/logout-all', [LoginActivityController::class, 'logoutAllDevices'])->name('user.login-activity.logout-all');

        Route::get('/complaints', [ComplaintController::class, 'index'])->name('user.complaints.index');
        Route::post('/complaints', [ComplaintController::class, 'store'])->name('user.complaints.store');

        // Income Reports
        Route::get('/income/direct', [UserIncomeController::class, 'directIncome'])->name('user.income.direct');
        Route::get('/income/bonus', [UserIncomeController::class, 'bonusIncome'])->name('user.income.bonus');
        Route::get('/income/level', [UserIncomeController::class, 'levelIncome'])->name('user.income.level');
        Route::get('/income/reward', [UserIncomeController::class, 'rewardIncome'])->name('user.income.reward');
        Route::get('/income/repurchase', [UserIncomeController::class, 'repurchaseIncome'])->name('user.income.repurchase');
    });

});


// === VENDOR ROUTES (Protected by 'is_vendor') ===
Route::middleware(['auth', IsVendor::class])->group(function () {
    // वेंडर का अपना डैशबोर्ड
    Route::get('/vendor-dashboard', [VendorController::class, 'dashboard'])->name('vendor.dashboard');

    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::resource('products', VendorProductController::class);
    });

    Route::get('/orders', [VendorOrderController::class, 'index'])->name('vendor.orders.index');
    Route::get('/orders/{id}', [VendorOrderController::class, 'show'])->name('vendor.orders.show');
    Route::post('/orders/update-status', [VendorOrderController::class, 'updateStatus'])->name('vendor.orders.updateStatus');
    Route::post('/toggle-shop-status', [VendorController::class, 'toggleShopStatus'])->name('vendor.toggleShopStatus');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user/transfer-wallet1', [WalletController::class, 'showTransferForm'])->name('user.transferWallet1Form');
    Route::post('/user/search-downline', [WalletController::class, 'searchDownlineUser'])->name('user.search.downline');
    Route::post('/user/transfer-wallet1', [WalletController::class, 'transferWallet1'])->name('user.transfer.wallet1');

  
    Route::get('/my-orders', [UserOrderController::class, 'index'])->name('user.orders.index');
    Route::prefix('user/shop')->name('user.shop.')->group(function () {
        Route::get('/', [ShopController::class, 'index'])->name('index');
        Route::post('/purchase', [ShopController::class, 'purchase'])->name('purchase');
    });
});


Route::get('/get-user-details/{ulid}', [AuthController::class, 'getUserDetails']);
Route::get('/admin/get-user-details/{ulid}', [AuthController::class, 'getUserDetailsAdmin']);


Route::get('adminlogin', [AdminController::class, 'index'])->name('admin.login');
Route::post('adminlogin', [AdminController::class, 'login'])->name('admin.login.post');

// Admin Protected Routes
Route::middleware(['auth:admin'])->group(function () {
    Route::get('admindashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('adminlogout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::get('/admin/profile/edit', [AdminController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/admin/profile/update', [AdminController::class, 'update'])->name('admin.profile.update');


    Route::get('/admin/viewmember', [AdminController::class, 'viewmemeber'])->name('admin.viewmember');
    Route::get('/admin/viewmember/{id}', [AdminController::class, 'viewMemberDetails'])->name('admin.viewmemberdetails');
    Route::get('/admin/editmember/{id}', [AdminController::class, 'editMember'])->name('admin.editmember');
    Route::put('/admin/update-member/{id}', [AdminController::class, 'updateMember'])->name('admin.updatemember');
    Route::delete('/admin/delete-member/{id}', [AdminController::class, 'deleteMember'])->name('admin.deletemember');
    Route::get('/admin/network/summary', [AdminController::class, 'networkSummary'])->name('admin.network.summary');
    Route::get('/admin/user-tree/{adminId}', [AuthController::class, 'showUserTreeFromAdmin'])->name('admin.user.tree');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('products', ProductController::class);
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('faq', FaqController::class);
    });

    Route::prefix('admin')->group(function () {
        Route::get('/wallet', [WalletController::class, 'index'])->name('admin.wallet');
        Route::get('/wallet-transactions', [WalletController::class, 'viewAllTransactions'])->name('admin.wallet-transactions');
        Route::post('/get-user-by-ulid', [WalletController::class, 'getUserByUlid']);
        Route::post('/add-wallet1', [WalletController::class, 'addWallet1'])->name('admin.addWallet1');
        Route::post('/add-wallet2', [WalletController::class, 'addWallet2'])->name('admin.addWallet2');


        Route::get('/withdrawals', [WalletController::class, 'viewWithdrawlRequest'])->name('admin.withdrawls.index');
        Route::post('/withdrawals/{id}/approve', [WalletController::class, 'approveWithdrawlRequest'])->name('admin.withdrawls.approve');
        Route::post('/withdrawals/{id}/reject', [WalletController::class, 'rejectWithdrawlRequest'])->name('admin.withdrawls.reject');

        Route::get('/package', [PackageController::class, 'package'])->name('admin.package');
        Route::get('/packages/package1/create', [PackageController::class, 'createPackage1'])->name('admin.package1.create');
        Route::get('/packages/package2/create', [PackageController::class, 'createProductPackage'])->name('admin.package2.create');
        Route::post('/packages/package1', [PackageController::class, 'storePackage1'])->name('admin.package1.store');
        Route::post('/packages/package2', [PackageController::class, 'storeProductPackage'])->name('admin.package2.store');

        Route::get('/packages/package1/{id}/edit', [PackageController::class, 'editPackage1'])->name('admin.package1.edit');
        Route::put('/packages/package1/{id}', [PackageController::class, 'updatePackage1'])->name('admin.package1.update');
        Route::delete('/packages/package1/{id}', [PackageController::class, 'destroyPackage1'])->name('admin.package1.destroy');

        Route::get('/packages/package2/{id}/edit', [PackageController::class, 'editProductPackage'])->name('admin.package2.edit');
        Route::put('/packages/package2/{id}', [PackageController::class, 'updateProductPackage'])->name('admin.package2.update');
        Route::delete('/packages/package2/{id}', [PackageController::class, 'destroyProductPackage'])->name('admin.package2.destroy');

        Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');

        // Update Status (Admin Override)
        Route::post('/orders/update-status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    });
    // Percentage Level Routes
    Route::group(['prefix' => 'admin'], function () {

        // List
        Route::get('/percentage-levels', [PercentageLevelController::class, 'index'])
            ->name('admin.percentage.index');

        // Create
        Route::get('/percentage-level/add', [PercentageLevelController::class, 'create'])
            ->name('admin.percentage.create');
        Route::post('/percentage-level/store', [PercentageLevelController::class, 'store'])
            ->name('admin.percentage.store');

        // Edit
        Route::get('/percentage-level/edit/{id}', [PercentageLevelController::class, 'edit'])
            ->name('admin.percentage.edit');
        Route::put('/percentage-level/update/{id}', [PercentageLevelController::class, 'update'])
            ->name('admin.percentage.update');

        // Delete
        Route::delete('/percentage-level/delete/{id}', [PercentageLevelController::class, 'destroy'])
            ->name('admin.percentage.destroy');


        Route::get('/percentage-income', [PercentageIncomeController::class, 'index'])->name('admin.income.index');
        Route::put('/percentage-income/update/{id}', [PercentageIncomeController::class, 'update'])->name('admin.income.update');


        // percentage reward 
        Route::get('/percentage-rewards', [PercentageRewardController::class, 'index'])->name('admin.rewards.index');
        Route::post('/percentage-rewards/store', [PercentageRewardController::class, 'store'])->name('admin.rewards.store');
        Route::put('/percentage-rewards/update/{id}', [PercentageRewardController::class, 'update'])->name('admin.rewards.update');
        Route::delete('/percentage-rewards/delete/{id}', [PercentageRewardController::class, 'destroy'])->name('admin.rewards.destroy');

        Route::post('/admin/toggle-shop', [AdminController::class, 'toggleShopStatus'])->name('admin.shop.toggle');
    });

    // Admin Complaints Routes
    Route::get('admin/complaints', [AdminComplaintController::class, 'index'])->name('admin.complaints.index');
    // Update Complaint (Reply & Status Change)
    Route::put('admin/complaints/{id}', [AdminComplaintController::class, 'update'])->name('admin.complaints.store');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/user/viewwallet', [UserController::class, 'viewWallet'])->name('user.viewwallet');
    Route::post('/user/withdraw/wallet1', [WalletController::class, 'withdrawWallet1'])->name('user.withdraw.wallet1');
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
