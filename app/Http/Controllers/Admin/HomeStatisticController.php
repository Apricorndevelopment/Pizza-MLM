<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeStatistic;

class HomeStatisticController extends Controller
{
    public function index()
    {
        // Order by sort_order so you can control display sequence
        $stats = HomeStatistic::orderBy('sort_order', 'asc')->get();
        return view('admin.home-statistics.index', compact('stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        HomeStatistic::create($request->all());

        return back()->with('success', 'Statistic added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        $stat = HomeStatistic::findOrFail($id);
        $stat->update($request->all());

        return back()->with('success', 'Statistic updated successfully!');
    }

    public function destroy($id)
    {
        HomeStatistic::destroy($id);
        return back()->with('success', 'Statistic deleted successfully!');
    }
}