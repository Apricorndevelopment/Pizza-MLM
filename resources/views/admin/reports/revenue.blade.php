@extends('layouts.layout')
@section('title', 'Admin Revenue & Profit Report')

@section('container')
<div class="min-h-screen bg-slate-50 font-sans">
    <div class="max-w-7xl mx-auto px-2 sm:px-3 lg:px-6">
        
        {{-- Header & Filters --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-3">
                    <span class="p-2 bg-emerald-100 rounded-lg text-emerald-600 shadow-sm">
                        <i class="bi bi-graph-up-arrow text-xl"></i>
                    </span>
                    Revenue & Profit Report
                </h1>
                <p class="text-sm text-slate-500 mt-1">Accurate analysis of sales, costs, and net earnings.</p>
            </div>

            <form method="GET" action="{{ route('admin.revenue.report') }}" class="flex flex-wrap items-end gap-3 bg-white p-3 rounded-xl shadow-sm border border-slate-200">
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-1.5 rounded-lg text-sm font-semibold transition-colors">Filter</button>
                @if($startDate || $endDate)
                    <a href="{{ route('admin.revenue.report') }}" class="bg-red-50 text-red-600 px-4 py-1.5 rounded-lg text-sm font-semibold border border-red-200">Clear</a>
                @endif
            </form>
        </div>

        {{-- 4 Main Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
            {{-- 1. Revenue --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Total Revenue</p>
                <h2 class="text-2xl font-black text-blue-600">₹{{ number_format($totalRevenue, 2) }}</h2>
                <div class="mt-2 text-[10px] text-slate-500 font-bold bg-blue-50 inline-block px-2 py-0.5 rounded">{{ $totalOrdersCount }} Orders</div>
            </div>

            {{-- 2. Product Cost --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Product Cost</p>
                <h2 class="text-2xl font-black text-orange-500">₹{{ number_format($totalActualProductCost, 2) }}</h2>
                <div class="mt-2 text-[10px] text-slate-500 font-bold bg-orange-50 inline-block px-2 py-0.5 rounded">Actual Landing Price</div>
            </div>

            {{-- 3. MLM Distribution --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Network Income Distributed</p>
                <h2 class="text-2xl font-black text-red-500">₹{{ number_format($totalDistributedToOrders + $totalRewards, 2) }}</h2>
                <div class="mt-2 text-[10px] text-slate-500 font-bold bg-red-50 inline-block px-2 py-0.5 rounded">Incomes + Rewards</div>
            </div>

            {{-- 4. Final Net Profit --}}
            <div class="bg-slate-900 rounded-2xl p-6 shadow-lg border border-slate-800">
                <p class="text-xs font-bold text-emerald-400 uppercase mb-2">Net Admin Profit</p>
                <h2 class="text-2xl font-black text-white">₹{{ number_format($netProfit, 2) }}</h2>
                @php $margin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0; @endphp
                <div class="mt-2 text-[10px] text-emerald-400 font-bold bg-white/10 inline-block px-2 py-0.5 rounded">{{ number_format($margin, 1) }}% Margin</div>
            </div>
        </div>

        {{-- Detailed Breakdown (Style kept as before) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
                    <h3 class="font-bold text-slate-800">Income Distribution Breakdown</h3>
                </div>
                <div class="p-6 space-y-4">
                    {{-- Loop through distribution types as you had --}}
                    @php
                        $items = [
                            ['label' => 'Direct Income', 'val' => $directIncome, 'icon' => 'bi-person-check', 'color' => 'blue'],
                            ['label' => 'Level Income', 'val' => $levelIncome, 'icon' => 'bi-diagram-3', 'color' => 'purple'],
                            ['label' => 'Bonus Income', 'val' => $bonusIncome, 'icon' => 'bi-gift', 'color' => 'orange'],
                            ['label' => 'Repurchase Income', 'val' => $repurchaseIncome, 'icon' => 'bi-arrow-repeat', 'color' => 'teal'],
                            ['label' => 'Cashback Income', 'val' => $cashbackIncome, 'icon' => 'bi-cash-coin', 'color' => 'indigo'],
                            ['label' => 'Global Rewards', 'val' => $totalRewards, 'icon' => 'bi-trophy', 'color' => 'amber'],
                        ];
                    @endphp
                    @foreach($items as $item)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-{{ $item['color'] }}-100 text-{{ $item['color'] }}-600 flex items-center justify-center text-sm"><i class="bi {{ $item['icon'] }}"></i></div>
                                <span class="text-sm font-semibold text-slate-600">{{ $item['label'] }}</span>
                            </div>
                            <span class="font-bold text-slate-900">₹{{ number_format($item['val'], 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
                    <h3 class="font-bold text-slate-800">Final Financial Summary</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-5">
                        <div class="flex justify-between border-b border-dashed border-slate-200 pb-3">
                            <span class="text-sm text-slate-500">Gross Sales Revenue</span>
                            <span class="font-bold text-slate-800">₹{{ number_format($totalRevenue, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-dashed border-slate-200 pb-3">
                            <span class="text-sm text-slate-500">(-) Total Product Cost</span>
                            <span class="font-bold text-red-500">₹{{ number_format($totalActualProductCost, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-dashed border-slate-200 pb-3">
                            <span class="text-sm text-slate-500">(-) Total MLM Payouts</span>
                            <span class="font-bold text-red-500">₹{{ number_format($totalDistributedToOrders + $totalRewards, 2) }}</span>
                        </div>
                        <div class="p-4 bg-emerald-600 rounded-xl text-white flex justify-between items-center shadow-md">
                            <span class="font-bold uppercase tracking-widest text-xs">Net Admin Profit</span>
                            <span class="text-2xl font-black">₹{{ number_format($netProfit, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection