<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\MaturityMonthlyDeduction;
use App\Models\Package1;
use App\Models\Package2;
use App\Models\Package2Details;
use App\Models\Package2Purchase;
use App\Models\User;
use App\Models\PackageTransaction;
use App\Models\PointsTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageAssignmentController extends Controller
{
    public function index()
    {
        return view('admin.packagesassign');
    }

    public function search(Request $request)
    {
        $request->validate(['ulid' => 'required|string']);

        $user = User::where('ulid', $request->ulid)->first();

        if (!$user) {
            return back()->with('error', 'User not found with this ULID');
        }

        $packages = Package1::all();

        return view('admin.packagesassign', compact('user', 'packages'));
    }

    public function assignPackage(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:package1,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $package = Package1::findOrFail($request->package_id);

        // Calculate final price
        $discountAmount = $package->discount_per ? ($package->price * $package->discount_per) / 100 : 0;
        $finalPrice = $package->price - $discountAmount;

        PackageTransaction::create([
            'user_id' => $user->id,
            'package1_id' => $package->id,
            'ulid' => $user->ulid,
            'package_name' => $package->package_name,
            'price' => $package->price,
            'discount_percentage' => $package->discount_per,
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice,
        ]);

        $user->update([
            'status' => 'active',
            'user_doa' => now()
        ]);

        return redirect()->route('admin.packages.assign')
            ->with('success', 'Package assigned successfully!');
    }

    public function viewUserPackagePurchases()
    {
        $purchases = Package2Purchase::with(['user', 'package2', 'rateDetail'])
            ->orderBy('purchased_at', 'desc')
            ->paginate(10);

        return view('admin.package-purchases', compact('purchases'));
    }



    // For the User's Dashboard

    public function viewUserPackage()
    {
        $userId = Auth::id();

        // Get all package transactions for this user with package details
        $regularPackages = Package2Purchase::with(['package2', 'rateDetail'])
            ->where('user_id', $userId)
            ->where('maturity', 0)
            ->latest()
            ->get();

        $breadcrumbs = [
            ['title' => 'Package', 'url' => route('user.packages')],
            ['title' => 'Invoices', 'url' => route('user.packages')]
        ];

        return view('user.packages', compact('regularPackages', 'breadcrumbs'));
    }

    public function viewUserMaturityPackage()
    {
        $userId = Auth::id();

        $maturityPackages = Package2Purchase::with(['package2', 'rateDetail','maturityMonthlyDeductions'])
            ->where('user_id', $userId)
            ->where('maturity', 1)
            ->where('payout_processed', 0)
            ->latest()
            ->get();

        $breadcrumbs = [
            ['title' => 'Package', 'url' => route('user.maturity.packages')],
            ['title' => 'Maturity Package', 'url' => route('user.maturity.packages')]
        ];

        return view('user.maturity-package', compact('maturityPackages', 'breadcrumbs'));
    }

    public function payDeduction($id)
    {
        $deduction = MaturityMonthlyDeduction::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

       $totalDue = $deduction->total_deduction;

        if (Auth::user()->points_balance < $totalDue) {
            return redirect()->back()->with('error', 'Insufficient balance to make this payment.');
        }

        $user = User::find(Auth::id());

        DB::transaction(function () use ($deduction, $totalDue,$user) {
            // Deduct from user balance
            $user->decrement('points_balance', $totalDue);

            // Record points transaction
            PointsTransaction::create([
                'user_id' => Auth::id(),
                'user_ulid' => Auth::user()->ulid,
                'points' => -$totalDue,
                'notes' => 'Payment for maturity package monthly deduction - ' . $deduction->deduction_month,
                'admin_id' => null,
            ]);

            // Mark deduction as paid
            $deduction->update(['status' => 'paid']);
        });

        return redirect()->back()->with('success', 'Payment completed successfully!');
    }


    public function showInvoice($id)
    {
        $transaction = Package2Purchase::findOrFail($id);
        $breadcrumbs = [
            ['title' => 'Package', 'url' => route('user.packages')],
            ['title' => 'Invoices', 'url' => route('user.packages')],
            ['title' => 'View Invoice', 'url' => '#'],
        ];

        return view('user.viewInvoice', compact('transaction', 'breadcrumbs'));
    }

    public function showMaturityInvoice($id)
    {
        $transaction = Package2Purchase::findOrFail($id);
        $breadcrumbs = [
            ['title' => 'Package', 'url' => route('user.packages')],
            ['title' => 'Monthly Invoice', 'url' => route('user.maturity.packages')],
            ['title' => 'View Invoice', 'url' => '#'],
        ];

        return view('user.viewMaturityInvoice', compact('transaction', 'breadcrumbs'));
    }

    public function showEndorseForm($id)
    {
        $maturityPackage = Package2Purchase::with('package2')->findOrFail($id);

        // Check if the package belongs to the authenticated user and is a maturity package
        if ($maturityPackage->user_id !== Auth::id() || $maturityPackage->maturity != 1) {
            return redirect()->route('user.packages')->with('error', 'Invalid package for endorsement');
        }

        // Get regular packages (maturity = 0)
        $regularPackages = Package2::where('maturity', 0)->get();

        $breadcrumbs = [
            ['title' => 'Packages', 'url' => route('user.packages')],
            ['title' => 'Monthly Invoice', 'url' => route('user.maturity.packages')],
            ['title' => 'Endorse Package', 'url' => route('user.packages.endorse', $id)]
        ];

        return view('user.endorse-package', compact('maturityPackage', 'regularPackages', 'breadcrumbs'));
    }

    public function processEndorsement(Request $request)
    {
        $request->validate([
            'maturity_package_id' => 'required|exists:package2_purchases,id',
            'regular_package_id' => 'required|exists:package2,id',
            'package_detail_id' => 'required|exists:package2_details,id',
        ]);

        DB::beginTransaction();
        try {
            $maturityPackage = Package2Purchase::findOrFail($request->maturity_package_id);
            $maturityPackageDetails = Package2::findOrFail($maturityPackage->package2_id);
            $regularPackage = Package2::findOrFail($request->regular_package_id);
            $rateDetail = Package2Details::findOrFail($request->package_detail_id);

            // Check authorization
            if ($maturityPackage->user_id !== Auth::id() || $maturityPackage->maturity != 1) {
                return redirect()->back()->with('error', 'Unauthorized action');
            }

            $newQuantity = floor($maturityPackage->quantity * $maturityPackageDetails->package_quantity);

            $maturityPackage->update([
                'package2_id' => $regularPackage->id,
                'package2_detail_id' => $rateDetail->id,
                'package_name' => $regularPackage->package_name,
                'quantity' => $newQuantity,
                'rate' => $rateDetail->rate,
                'capital' => $rateDetail->capital,
                'time' => $rateDetail->time,
                'profit_share' => $rateDetail->profit_share,
                'maturity' => 0, // Set as regular package
                'endorsed' => 1, // Mark as endorsed
                // Generate new invoice and bed numbers
                'invoice_no' => $this->getNextInvoiceNumber(),
                'bed_no' => $this->getNextBedNumber(),
                'purchased_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('user.packages')->with('success', 'Package endorsed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to process endorsement: ' . $e->getMessage());
        }
    }

     private function getNextInvoiceNumber()
    {
        $datePart = now()->format('Ymd');
        $prefix = "INV-{$datePart}-";
        $last = Package2Purchase::where('invoice_no', 'like', "{$prefix}%")
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
        $last = Package2Purchase::where('bed_no', 'like', "{$prefix}%")
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


    public function viewActivationPackage()
    {
        $userId = Auth::id();
        $package = PackageTransaction::where('user_id', $userId)
            ->latest()
            ->first();

        $breadcrumbs = [
            ['title' => 'Shopping Card', 'url' => route('user.activation.package')],
        ];
        return view('user.activation-package', compact('package', 'breadcrumbs'));
    }
}
