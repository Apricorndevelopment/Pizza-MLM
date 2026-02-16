<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PercentageIncome;

class PercentageIncomeController extends Controller
{
    /**
     * Display the income configurations.
     */
    public function index()
    {
        // Fetch all records
        $incomes = PercentageIncome::orderBy('id', 'asc')->get();
        return view('admin.percentage_income.index', compact('incomes'));
    }

    /**
     * Update the specified configuration.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'direct_income'   => 'required|integer|min:0',
            'bonus_income'   => 'required|integer|min:0',
            'vendor_income'   => 'required|integer|min:0',
            'cashback_income'    => 'required|integer|min:0',
            'personal_wallet' => 'required|integer|min:0',
            'second_wallet'   => 'required|integer|min:0',
        ]);

        $income = PercentageIncome::findOrFail($id);

        $income->update([
            'direct_income'   => $request->direct_income,
            'bonus_income'   => $request->bonus_income,
            'vendor_income'   => $request->vendor_income,
            'cashback_income'    => $request->cashback_income,
            'personal_wallet' => $request->personal_wallet,
            'second_wallet'   => $request->second_wallet,
        ]);

        return redirect()->back()->with('success', 'Configuration updated successfully!');
    }
}
