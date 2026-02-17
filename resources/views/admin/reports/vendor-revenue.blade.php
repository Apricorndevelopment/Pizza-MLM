@extends('layouts.layout')
@section('title', 'Vendor Revenue & Profit Report')

@section('container')
<div class="min-h-screen bg-slate-50 font-sans">
    <div class="max-w-7xl mx-auto px-2 sm:px-3 lg:px-6">
        
        {{-- Header & Filters --}}
        <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-3">
                    <span class="p-2 bg-indigo-100 rounded-lg text-indigo-600 shadow-sm">
                        <i class="bi bi-shop text-xl"></i>
                    </span>
                    Vendor Performance & Profit Report
                </h1>
                <p class="text-sm text-slate-500 mt-1">Track individual vendor sales, distributions, and net admin profit.</p>
            </div>

            <form method="GET" action="{{ route('admin.vendor_revenue.report') }}" class="flex flex-wrap items-end gap-3 bg-white p-3 rounded-xl shadow-sm border border-slate-200">

                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                 <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Search Vendor</label>
                    <div class="relative">
                        <i class="bi bi-search absolute left-3 top-2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Name or Company..." class="pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none w-48">
                    </div>
                </div>

                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-1.5 rounded-lg text-sm font-semibold transition-colors shadow-sm">
                    Filter
                </button>
                @if($startDate || $endDate || $search)
                    <a href="{{ route('admin.vendor_revenue.report') }}" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-1.5 rounded-lg text-sm font-semibold transition-colors border border-red-200">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        {{-- Main Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-bold">
                        <tr>
                            <th class="px-2 py-3 border-b border-slate-100">Vendor Details</th>
                            <th class="px-2 py-3 border-b border-slate-100">Orders<br><span class="text-[9px] text-slate-400">(Delivered)</span></th>
                            <th class="px-2 py-3 border-b border-slate-100">Total Revenue</th>
                            <th class="px-2 py-3 border-b border-slate-100">Vendor Share<br><span class="text-[9px] text-slate-400">(70%)</span></th>
                            <th class="px-2 py-3 border-b border-slate-100">Network Income<br><span class="text-[9px] text-slate-400">(Distributed)</span></th>
                            <th class="px-2 py-3 border-b border-slate-100 text-emerald-600">Net Admin Profit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($vendors as $vendor)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                
                                {{-- Vendor Details Column --}}
                                <td class="px-2 py-3">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800 text-sm">
                                            {{ $vendor->company_name ?? 'No Company Name' }}
                                        </span>
                                        <div class="flex items-center text-xs text-slate-500 mt-1">
                                            <i class="bi bi-person-fill mr-1"></i> {{ $vendor->vendor_name }}
                                        </div>
                                        <div class="flex items-start text-[11px] text-slate-400 mt-1 max-w-[200px] leading-tight">
                                            <i class="bi bi-geo-alt-fill mr-1 mt-0.5"></i> 
                                            {{ $vendor->company_address ?? $vendor->user_address ?? 'Address not provided' }}
                                        </div>
                                    </div>
                                </td>

                                {{-- Orders Count --}}
                                <td class="px-2 py-3">
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-bold text-sm">
                                        {{ $vendor->total_orders }}
                                    </span>
                                </td>

                                {{-- Total Revenue --}}
                                <td class="px-2 py-3 ">
                                    <span class="text-sm font-bold text-slate-800">
                                        ₹{{ number_format($vendor->total_revenue, 2) }}
                                    </span>
                                </td>

                                {{-- Vendor Payout --}}
                                <td class="px-2 py-3 ">
                                    <span class="text-sm font-semibold text-orange-500">
                                        - ₹{{ number_format($vendor->vendor_payout, 2) }}
                                    </span>
                                </td>

                                {{-- Distributed Income --}}
                                <td class="px-2 py-3 ">
                                    <span class="text-sm font-semibold text-red-500">
                                        - ₹{{ number_format($vendor->total_distributed_incomes, 2) }}
                                    </span>
                                </td>

                                {{-- Net Profit --}}
                                <td class="px-2 py-3 bg-emerald-50/30">
                                    <span class="text-base font-black {{ $vendor->net_profit >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                        ₹{{ number_format($vendor->net_profit, 2) }}
                                    </span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                            <i class="bi bi-shop-window text-2xl text-slate-400"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">No vendors found matching your criteria.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($vendors->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-white">
                    {{ $vendors->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection