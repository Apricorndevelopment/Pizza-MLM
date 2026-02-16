@extends('layouts.layout')
@section('title', 'All User Incomes Ledger')

@section('container')
<div class="container mx-auto px-3">
    
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm">
                <i class="bi bi-cash-stack text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Income Ledger</h2>
                <p class="text-sm text-gray-500">Real-time combined view of all user earnings.</p>
            </div>
        </div>
    </div>

    {{-- Filters & Search Section --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <form method="GET" action="{{ route('admin.incomes.all') }}" class="flex flex-col md:flex-row gap-4">
            
            <div class="flex-1 relative">
                <i class="bi bi-search absolute left-3 top-3 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Search by User Name or ULID..." 
                    class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
            </div>

            <div class="w-full md:w-64">
                <select name="type" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Income Types</option>
                    <option value="Direct Income" {{ request('type') == 'Direct Income' ? 'selected' : '' }}>Direct Income</option>
                    <option value="Level Income" {{ request('type') == 'Level Income' ? 'selected' : '' }}>Level Income</option>
                    <option value="Bonus Income" {{ request('type') == 'Bonus Income' ? 'selected' : '' }}>Bonus Income</option>
                    <option value="Reward Income" {{ request('type') == 'Reward Income' ? 'selected' : '' }}>Reward Income</option>
                    <option value="Repurchase Income" {{ request('type') == 'Repurchase Income' ? 'selected' : '' }}>Repurchase Income</option>
                    <option value="Cashback Income" {{ request('type') == 'Cashback Income' ? 'selected' : '' }}>Cashback Income</option>
                </select>
            </div>

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition-colors shadow-sm whitespace-nowrap">
                <i class="bi bi-funnel mr-1"></i> Filter
            </button>

            @if(request()->has('search') || request()->has('type'))
                <a href="{{ route('admin.incomes.all') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-2 rounded-lg font-medium transition-colors whitespace-nowrap text-center">
                    Clear
                </a>
            @endif
        </form>
    </div>

    {{-- Main Data Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider font-bold">
                    <tr>
                        <th class="px-6 py-4 border-b border-gray-100">Sr No.</th>
                        <th class="px-6 py-4 border-b border-gray-100">User Details</th>
                        <th class="px-6 py-4 border-b border-gray-100">Income Type</th>
                        <th class="px-6 py-4 border-b border-gray-100 text-right">Amount</th>
                        <th class="px-6 py-4 border-b border-gray-100 text-right">Date & Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($incomes as $index => $income)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $incomes->firstItem() + $index }}
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800">{{ $income->user_name }}</span>
                                    <span class="text-xs font-mono text-gray-500 mt-0.5">#{{ $income->user_ulid }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                @php
                                    // Assign different colors based on type for better UI
                                    $badgeClass = 'bg-gray-100 text-gray-700 border-gray-200';
                                    if(str_contains($income->type, 'Direct')) $badgeClass = 'bg-green-50 text-green-700 border-green-200';
                                    elseif(str_contains($income->type, 'Level')) $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                    elseif(str_contains($income->type, 'Bonus')) $badgeClass = 'bg-purple-50 text-purple-700 border-purple-200';
                                    elseif(str_contains($income->type, 'Reward')) $badgeClass = 'bg-orange-50 text-orange-700 border-orange-200';
                                    elseif(str_contains($income->type, 'Repurchase')) $badgeClass = 'bg-teal-50 text-teal-700 border-teal-200';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wide border {{ $badgeClass }}">
                                    {{ $income->type }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <span class="text-base font-extrabold text-green-600">
                                    + ₹{{ number_format($income->amount, 2) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex flex-col items-end">
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ \Carbon\Carbon::parse($income->created_at)->format('d M, Y') }}
                                    </span>
                                    <span class="text-xs text-gray-400 mt-0.5">
                                        {{ \Carbon\Carbon::parse($income->created_at)->format('h:i A') }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <i class="bi bi-inbox text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">No income records found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($incomes->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-white">
                {{ $incomes->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection