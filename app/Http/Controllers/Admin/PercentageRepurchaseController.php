<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PercentageRepurchaseIncome;

class PercentageRepurchaseController extends Controller
{
    // List all levels
    public function index()
    {
        $levels = PercentageRepurchaseIncome::orderBy('id', 'asc')->get();
        return view('admin.percentage_repurchase.index', compact('levels'));
    }

    // Store New Level
    public function store(Request $request)
    {
        $request->validate([
            'level'      => 'required|numeric|unique:percentage_repurchase_incomes,level',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        PercentageRepurchaseIncome::create([
            'level'      => $request->level,
            'percentage' => $request->percentage,
        ]);

        return redirect()->back()->with('success', 'Level Percentage added successfully!');
    }

    // Update Existing Level
    public function update(Request $request, $id)
    {
        $level = PercentageRepurchaseIncome::findOrFail($id);

        $request->validate([
            // 'unique' rule ignores the current ID so you can save without changing the name
            'level'      => 'required|numeric|unique:percentage_repurchase_incomes,level,' . $level->id,
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        $level->update([
            'level'      => $request->level,
            'percentage' => $request->percentage,
        ]);

        return redirect()->back()->with('success', 'Level updated successfully!');
    }

    // Delete Level
    public function destroy($id)
    {
        $level = PercentageRepurchaseIncome::findOrFail($id);
        $level->delete();

        return redirect()->back()->with('success', 'Level deleted successfully!');
    }
}