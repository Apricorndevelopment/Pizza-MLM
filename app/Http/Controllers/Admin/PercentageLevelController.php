<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PercentageLevelIncome;

class PercentageLevelController extends Controller
{
    // List all levels
    public function index()
    {
        $levels = PercentageLevelIncome::orderBy('id', 'asc')->get();
        return view('admin.percentage_level.index', compact('levels'));
    }

    // Show Create Form
    public function create()
    {
        // Show existing levels below the form for reference
        $levels = PercentageLevelIncome::orderBy('level', 'asc')->get();
        return view('admin.percentage_level.create', compact('levels'));
    }

    // Store New Level
    public function store(Request $request)
    {
        $request->validate([
            'level'      => 'required|string|max:50|unique:percentage_level_incomes,level',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        PercentageLevelIncome::create([
            'level'      => $request->level,
            'percentage' => $request->percentage,
        ]);

        return redirect()->back()->with('success', 'Level Percentage added successfully!');
    }

    // Show Edit Form
    public function edit($id)
    {
        $level = PercentageLevelIncome::findOrFail($id);
        return view('admin.percentage_level.edit', compact('level'));
    }

    // Update Existing Level
    public function update(Request $request, $id)
    {
        $level = PercentageLevelIncome::findOrFail($id);

        $request->validate([
            // 'unique' rule ignores the current ID so you can save without changing the name
            'level'      => 'required|string|max:50|unique:percentage_level_incomes,level,' . $level->id,
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        $level->update([
            'level'      => $request->level,
            'percentage' => $request->percentage,
        ]);

        return redirect()->route('admin.percentage.index')->with('success', 'Level updated successfully!');
    }

    // Delete Level
    public function destroy($id)
    {
        $level = PercentageLevelIncome::findOrFail($id);
        $level->delete();

        return redirect()->back()->with('success', 'Level deleted successfully!');
    }
}