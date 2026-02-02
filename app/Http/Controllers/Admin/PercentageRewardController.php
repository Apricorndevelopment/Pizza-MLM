<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PercentageReward;

class PercentageRewardController extends Controller
{
    public function index()
    {
        // Order by Serial Number (sr_no)
        $rewards = PercentageReward::orderBy('sr_no', 'asc')->get();
        return view('admin.percentage_rewards.index', compact('rewards'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sr_no'       => 'required|integer|unique:percentage_rewards,sr_no',
            'achievement' => 'required|integer',
            'reward'      => 'required|integer',
            'rank'        => 'required|string|max:255',
        ]);

        PercentageReward::create($request->all());

        return redirect()->back()->with('success', 'Reward added successfully!');
    }

    public function update(Request $request, $id)
    {
        $reward = PercentageReward::findOrFail($id);

        $request->validate([
            // Unique check ignores current record ID
            'sr_no'       => 'required|integer|unique:percentage_rewards,sr_no,' . $reward->id,
            'achievement' => 'required|integer',
            'reward'      => 'required|integer',
            'rank'        => 'required|string|max:255',
        ]);

        $reward->update($request->all());

        return redirect()->back()->with('success', 'Reward updated successfully!');
    }

    public function destroy($id)
    {
        PercentageReward::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Reward deleted successfully!');
    }
}
