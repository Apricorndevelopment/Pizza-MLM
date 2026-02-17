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
                <p class="text-sm text-slate-500 mt-1">Analyze admin sales, distributed incomes, and net profit.</p>
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
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-1.5 rounded-lg text-sm font-semibold transition-colors shadow-sm">
                    Filter
                </button>
                @if($startDate || $endDate)
                    <a href="{{ route('admin.revenue.report') }}" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-1.5 rounded-lg text-sm font-semibold transition-colors border border-red-200">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        {{-- Top Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Revenue Card --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 text-blue-50 opacity-50 group-hover:scale-110 transition-transform duration-500">
                    <i class="bi bi-cart-check-fill" style="font-size: 8rem;"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Total Admin Revenue</p>
                    <h2 class="text-3xl font-black text-blue-600 mb-1">₹{{ number_format($totalRevenue, 2) }}</h2>
                    <p class="text-xs text-slate-400 font-medium">From <span class="font-bold text-slate-600">{{ $totalOrdersCount }}</span> delivered orders</p>
                </div>
            </div>

            {{-- Expenses Card --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 text-red-50 opacity-50 group-hover:scale-110 transition-transform duration-500">
                    <i class="bi bi-share-fill" style="font-size: 8rem;"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Total Distributed</p>
                    <h2 class="text-3xl font-black text-red-500 mb-1">- ₹{{ number_format($totalExpenses, 2) }}</h2>
                    <p class="text-xs text-slate-400 font-medium">Incomes + Rewards payouts</p>
                </div>
            </div>

            {{-- Net Profit Card --}}
            <div class="bg-slate-900 rounded-2xl p-6 shadow-lg relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 text-emerald-500 opacity-20 group-hover:scale-110 transition-transform duration-500">
                    <i class="bi bi-wallet-fill" style="font-size: 8rem;"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-sm font-bold text-emerald-400 uppercase tracking-wider mb-1">Net Admin Profit</p>
                    <h2 class="text-4xl font-black text-white mb-1">₹{{ number_format($netProfit, 2) }}</h2>
                    @php
                        $margin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;
                    @endphp
                    <p class="text-xs text-slate-300 font-medium">Profit Margin: <span class="font-bold text-emerald-400">{{ number_format($margin, 1) }}%</span></p>
                </div>
            </div>
        </div>

        {{-- Detailed Breakdown --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
                <h3 class="font-bold text-slate-800">Distribution Breakdown</h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex p-3 items-center justify-center"><i class="bi bi-person-check-fill"></i></div>
                            <span class="font-semibold text-slate-700">Direct Income</span>
                        </div>
                        <span class="font-bold text-slate-900">₹{{ number_format($directIncome, 2) }}</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 p-3 flex items-center justify-center"><i class="bi bi-diagram-3-fill"></i></div>
                            <span class="font-semibold text-slate-700">Level Income</span>
                        </div>
                        <span class="font-bold text-slate-900">₹{{ number_format($levelIncome, 2) }}</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 p-3 flex items-center justify-center"><i class="bi bi-gift-fill"></i></div>
                            <span class="font-semibold text-slate-700">Bonus Income</span>
                        </div>
                        <span class="font-bold text-slate-900">₹{{ number_format($bonusIncome, 2) }}</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-600 p-3 flex items-center justify-center"><i class="bi bi-arrow-repeat"></i></div>
                            <span class="font-semibold text-slate-700">Repurchase Income</span>
                        </div>
                        <span class="font-bold text-slate-900">₹{{ number_format($repurchaseIncome, 2) }}</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 p-3 flex items-center justify-center"><i class="bi bi-cash-coin"></i></div>
                            <span class="font-semibold text-slate-700">Cashback Income</span>
                        </div>
                        <span class="font-bold text-slate-900">₹{{ number_format($cashbackIncome, 2) }}</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-amber-50 rounded-xl border border-amber-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-amber-200 text-amber-700 p-3 flex items-center justify-center"><i class="bi bi-trophy-fill"></i></div>
                            <span class="font-semibold text-amber-900">Rewards (Global)</span>
                        </div>
                        <span class="font-bold text-amber-900">₹{{ number_format($totalRewards, 2) }}</span>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection