@extends('layouts.layout')
@section('title', 'Wallet Management')
@section('container')

<div class="min-h-screen bg-gray-50 px-3 sm:px-4 lg:px-6">
    <div class="max-w-6xl mx-auto">

        {{-- Success Alert --}}
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 p-4 border-l-4 border-green-500 shadow-sm flex items-center justify-between" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3 text-lg"></i>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- Main Card --}}
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            
            {{-- Tabs Header --}}
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px" aria-label="Tabs">
                    <button onclick="switchTab('wallet1')" id="tab-wallet1" 
                        class="w-1/2 py-3 px-1 text-center border-b-2 font-medium text-sm transition-colors border-blue-500 text-blue-600 bg-blue-50/50">
                        <i class="fas fa-wallet mr-2"></i> Wallet 1
                    </button>
                    <button onclick="switchTab('wallet2')" id="tab-wallet2" 
                        class="w-1/2 py-3 px-1 text-center border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-gift mr-2"></i> Wallet 2 (Rewards)
                    </button>
                </nav>
            </div>

            <div class="p-4">
                
                {{-- ==================== WALLET 1 TAB ==================== --}}
                <div id="content-wallet1" class="tab-content block">
                    
                    {{-- Transaction Form --}}
                    <form action="{{ route('admin.addWallet1') }}" method="POST" id="wallet1Form" class="mb-8">
                        @csrf
                        <input type="hidden" name="ulid" id="wallet1ULIDHidden">
                        
                        {{-- Search Section --}}
                        <div class="mb-4 max-w-2xl">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Find User</label>
                            <div class="flex gap-2">
                                <div class="relative flex-grow">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="wallet1ULID" 
                                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                        placeholder="Enter User ULID">
                                </div>
                                <button type="button" id="searchWallet1User" 
                                    class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                    Search
                                </button>
                            </div>
                        </div>

                        {{-- User Details Card (Ajax Loaded) --}}
                        <div id="wallet1UserDetails" class="hidden mb-4 bg-blue-50 rounded-xl p-5 border border-blue-100">
                            <h5 class="text-blue-800 font-bold mb-3 flex items-center">
                                <i class="fas fa-user-circle mr-2"></i> User Details
                            </h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-white p-3 rounded-lg border border-blue-100 shadow-sm">
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Name</p>
                                    <p class="text-gray-900 font-medium" id="wallet1UserName">--</p>
                                </div>
                                <div class="bg-white p-3 rounded-lg border border-blue-100 shadow-sm">
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Email</p>
                                    <p class="text-gray-900 font-medium break-all" id="wallet1UserEmail">--</p>
                                </div>
                                <div class="bg-white p-3 rounded-lg border border-blue-100 shadow-sm">
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Current Balance</p>
                                    <p class="text-blue-600 font-bold text-lg" id="wallet1UserBalance">0</p>
                                </div>
                            </div>
                            
                            {{-- Transaction Inputs --}}
                            <div class="mt-6 pt-6 border-t border-blue-200">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Amount</label>
                                        <div class="relative">
                                            <input type="number" id="wallet1Amount" name="wallet1"
                                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                                placeholder="0.00">
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Positive to add, negative (e.g., -50) to deduct.</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Notes (Optional)</label>
                                        <input type="text" id="wallet1Notes" name="notes"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                            placeholder="Reason for transaction...">
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-end">
                                    <button type="submit" class="px-6 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors shadow-md flex items-center">
                                        <i class="fas fa-paper-plane mr-2"></i> Process Transaction
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <hr class="border-gray-200 mb-6">

                    {{-- Filters --}}
                    <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-filter text-gray-500 mr-2"></i>
                            <h6 class="text-sm font-bold text-gray-700 uppercase">Filter Transactions</h6>
                        </div>
                        <form method="GET" action="{{ route('admin.wallet') }}">
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Search ULID</label>
                                    <input type="text" name="wallet1_ulid" value="{{ request('wallet1_ulid') }}" 
                                        class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="ULID">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Type</label>
                                    <select name="wallet1_type" class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">All</option>
                                        <option value="credit" {{ request('wallet1_type') == 'credit' ? 'selected' : '' }}>Credit (+)</option>
                                        <option value="debit" {{ request('wallet1_type') == 'debit' ? 'selected' : '' }}>Debit (-)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Start Date</label>
                                    <input type="date" name="wallet1_start_date" value="{{ request('wallet1_start_date') }}" 
                                        class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">End Date</label>
                                    <input type="date" name="wallet1_end_date" value="{{ request('wallet1_end_date') }}" 
                                        class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit" class="flex-1 bg-blue-600 text-white text-sm py-2 px-3 rounded-md hover:bg-blue-700 transition-colors">
                                        Apply
                                    </button>
                                    <a href="{{ route('admin.wallet') }}" class="flex-none bg-white border border-gray-300 text-gray-600 text-sm py-2 px-3 rounded-md hover:bg-gray-50 transition-colors" title="Reset">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($wallet1Transactions as $transaction)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $transaction->created_at->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex flex-col">
                                            <a href="javascript:void(0)" class="user-ulid-link text-blue-600 font-medium hover:underline" data-ulid="{{ optional($transaction->user)->ulid }}">
                                                {{ optional($transaction->user)->name }}
                                            </a>
                                            <span class="text-xs text-gray-500">{{ optional($transaction->user)->ulid }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $transaction->wallet1 >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->wallet1 >= 0 ? '+' : '' }}{{ $transaction->wallet1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $transaction->balance ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs break-words">
                                        {{ $transaction->notes ? $transaction->notes : 'N/A' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Wallet 1 Pagination --}}
                    <div class="mt-4 flex items-center justify-between border-t border-gray-200 pt-4">
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">{{ $wallet1Transactions->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $wallet1Transactions->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $wallet1Transactions->total() }}</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($wallet1Transactions->onFirstPage())
                                        <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-400 cursor-not-allowed">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left"></i>
                                        </span>
                                    @else
                                        <a href="{{ $wallet1Transactions->appends(request()->all())->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($wallet1Transactions->getUrlRange(1, $wallet1Transactions->lastPage()) as $page => $url)
                                        @if ($page == $wallet1Transactions->currentPage())
                                            <span aria-current="page" class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $wallet1Transactions->appends(request()->all())->url($page) }}" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($wallet1Transactions->hasMorePages())
                                        <a href="{{ $wallet1Transactions->appends(request()->all())->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Next</span>
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    @else
                                        <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-400 cursor-not-allowed">
                                            <span class="sr-only">Next</span>
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    @endif
                                </nav>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ==================== WALLET 2 TAB ==================== --}}
                <div id="content-wallet2" class="tab-content hidden">
                    
                     {{-- Transaction Form --}}
                     <form action="{{ route('admin.addWallet2') }}" method="POST" id="rewardsForm" class="mb-8">
                        @csrf
                        <input type="hidden" name="ulid" id="rewardsULIDHidden">
                        
                        {{-- Search Section --}}
                        <div class="mb-4 max-w-2xl">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Find User (Rewards)</label>
                            <div class="flex gap-2">
                                <div class="relative flex-grow">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="rewardsULID" 
                                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all"
                                        placeholder="Enter User ULID">
                                </div>
                                <button type="button" id="searchRewardsUser" 
                                    class="px-6 py-2.5 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors shadow-sm">
                                    Search
                                </button>
                            </div>
                        </div>

                        {{-- User Details Card (Ajax Loaded) --}}
                        <div id="rewardsUserDetails" class="hidden mb-4 bg-purple-50 rounded-xl p-5 border border-purple-100">
                            <h5 class="text-purple-800 font-bold mb-3 flex items-center">
                                <i class="fas fa-user-circle mr-2"></i> User Details
                            </h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-white p-3 rounded-lg border border-purple-100 shadow-sm">
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Name</p>
                                    <p class="text-gray-900 font-medium" id="rewardsUserName">--</p>
                                </div>
                                <div class="bg-white p-3 rounded-lg border border-purple-100 shadow-sm">
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Email</p>
                                    <p class="text-gray-900 font-medium break-all" id="rewardsUserEmail">--</p>
                                </div>
                                <div class="bg-white p-3 rounded-lg border border-purple-100 shadow-sm">
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Wallet 2 Balance</p>
                                    <p class="text-purple-600 font-bold text-lg" id="rewardsUserBalance">0</p>
                                </div>
                            </div>
                            
                            {{-- Transaction Inputs --}}
                            <div class="mt-6 pt-6 border-t border-purple-200">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Amount</label>
                                        <div class="relative">
                                            <input type="number" id="rewardsAmount" name="wallet2"
                                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all"
                                                placeholder="0.00">
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Positive to add, negative to deduct.</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Notes (Optional)</label>
                                        <input type="text" id="rewardsNotes" name="notes"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all"
                                            placeholder="Reason for transaction...">
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-end">
                                    <button type="submit" class="px-6 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors shadow-md flex items-center">
                                        <i class="fas fa-paper-plane mr-2"></i> Process Reward
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <hr class="border-gray-200 mb-6">

                    {{-- Wallet 2 Filters (Added) --}}
                    <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-filter text-gray-500 mr-2"></i>
                            <h6 class="text-sm font-bold text-gray-700 uppercase">Filter Wallet 2</h6>
                        </div>
                        <form method="GET" action="{{ route('admin.wallet') }}">
                            {{-- Hidden inputs to keep tab active logic if desired, or handle via JS --}}
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Search ULID</label>
                                    <input type="text" name="wallet2_ulid" value="{{ request('wallet2_ulid') }}" 
                                        class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500" placeholder="ULID">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Type</label>
                                    <select name="wallet2_type" class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                                        <option value="">All</option>
                                        <option value="credit" {{ request('wallet2_type') == 'credit' ? 'selected' : '' }}>Credit (+)</option>
                                        <option value="debit" {{ request('wallet2_type') == 'debit' ? 'selected' : '' }}>Debit (-)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Start Date</label>
                                    <input type="date" name="wallet2_start_date" value="{{ request('wallet2_start_date') }}" 
                                        class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">End Date</label>
                                    <input type="date" name="wallet2_end_date" value="{{ request('wallet2_end_date') }}" 
                                        class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit" class="flex-1 bg-purple-600 text-white text-sm py-2 px-3 rounded-md hover:bg-purple-700 transition-colors">
                                        Apply
                                    </button>
                                    <a href="{{ route('admin.wallet') }}" class="flex-none bg-white border border-gray-300 text-gray-600 text-sm py-2 px-3 rounded-md hover:bg-gray-50 transition-colors" title="Reset">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($wallet2Transactions as $transaction)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $transaction->created_at->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex flex-col">
                                            <span class="text-purple-600 font-medium">{{ optional($transaction->user)->name }}</span>
                                            <span class="text-xs text-gray-500">{{ optional($transaction->user)->ulid }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $transaction->wallet2 >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->wallet2 >= 0 ? '+' : '' }}{{ $transaction->wallet2 }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $transaction->balance ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs break-words">
                                        {{ $transaction->notes ? $transaction->notes : 'N/A' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Wallet 2 Pagination --}}
                    <div class="mt-4 flex items-center justify-between border-t border-gray-200 pt-4">
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">{{ $wallet2Transactions->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $wallet2Transactions->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $wallet2Transactions->total() }}</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($wallet2Transactions->onFirstPage())
                                        <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-400 cursor-not-allowed">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left"></i>
                                        </span>
                                    @else
                                        <a href="{{ $wallet2Transactions->appends(request()->all())->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($wallet2Transactions->getUrlRange(1, $wallet2Transactions->lastPage()) as $page => $url)
                                        @if ($page == $wallet2Transactions->currentPage())
                                            <span aria-current="page" class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $wallet2Transactions->appends(request()->all())->url($page) }}" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($wallet2Transactions->hasMorePages())
                                        <a href="{{ $wallet2Transactions->appends(request()->all())->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Next</span>
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    @else
                                        <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-400 cursor-not-allowed">
                                            <span class="sr-only">Next</span>
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    @endif
                                </nav>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // Simple Tab Switcher Logic
    function switchTab(tabId) {
        // Hide all contents
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('block'));
        
        // Reset styles for all tabs
        const btn1 = document.getElementById('tab-wallet1');
        const btn2 = document.getElementById('tab-wallet2');
        
        // Default inactive classes
        const inactiveClasses = ['border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'bg-transparent'];
        // Active classes
        const activeClasses = ['border-blue-500', 'text-blue-600', 'bg-blue-50/50'];

        // Reset
        btn1.classList.remove(...activeClasses);
        btn1.classList.add(...inactiveClasses);
        btn2.classList.remove(...activeClasses);
        btn2.classList.add(...inactiveClasses);

        // Activate specific tab
        document.getElementById('content-' + tabId).classList.remove('hidden');
        document.getElementById('content-' + tabId).classList.add('block');

        const activeBtn = document.getElementById('tab-' + tabId);
        activeBtn.classList.remove(...inactiveClasses);
        activeBtn.classList.add(...activeClasses);

        // Keep state in URL hash (optional)
        window.location.hash = tabId;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Check hash to open correct tab on load (e.g. after filter submit)
        if(window.location.hash === '#wallet2' || window.location.search.includes('wallet2_')) {
            switchTab('wallet2');
        } else {
            switchTab('wallet1');
        }

        // ================= Common Fetch Logic =================
        function fetchUserDetails(ulid, type) {
             const prefix = type === 'wallet1' ? 'wallet1' : 'rewards';
             
             fetch('/admin/get-user-by-ulid', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ ulid: ulid })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(prefix + 'UserName').textContent = data.user.name;
                    document.getElementById(prefix + 'UserEmail').textContent = data.user.email;
                    
                    const balance = type === 'wallet1' ? data.user.wallet1_balance : data.user.wallet2_balance;
                    document.getElementById(prefix + 'UserBalance').textContent = balance;
                    
                    // Remove hidden class
                    document.getElementById(prefix + 'UserDetails').classList.remove('hidden');
                } else {
                    alert('User not found');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function handleFormSubmit(e, type) {
            e.preventDefault();
            const prefix = type === 'wallet1' ? 'wallet1' : 'rewards';
            const ulid = document.getElementById(prefix + 'ULID').value;
            const amount = document.getElementById(prefix + 'Amount').value;
            
            document.getElementById(prefix + 'ULIDHidden').value = ulid;

            if (!amount) {
                alert('Please enter an amount');
                return;
            }

            const action = parseInt(amount) >= 0 ? 'add' : 'deduct';
            const absAmount = Math.abs(amount);
            
            if(confirm(`Are you sure you want to ${action} ${absAmount} money to user ${ulid}?`)) {
                e.target.submit();
            }
        }

        // ================= Wallet 1 Listeners =================
        
        // Link Clicks in Table
        document.querySelectorAll('.user-ulid-link').forEach(link => {
            link.addEventListener('click', function() {
                const ulid = this.getAttribute('data-ulid');
                if (ulid) {
                    document.getElementById('wallet1ULID').value = ulid;
                    // Trigger search
                    document.getElementById('searchWallet1User').click();
                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });

        document.getElementById('searchWallet1User').addEventListener('click', function() {
            const ulid = document.getElementById('wallet1ULID').value;
            if (ulid) fetchUserDetails(ulid, 'wallet1');
        });

        document.getElementById('wallet1Form').addEventListener('submit', function(e) {
            handleFormSubmit(e, 'wallet1');
        });

        // ================= Wallet 2 Listeners =================
        document.getElementById('searchRewardsUser').addEventListener('click', function() {
            const ulid = document.getElementById('rewardsULID').value;
            if (ulid) fetchUserDetails(ulid, 'rewards');
        });

        document.getElementById('rewardsForm').addEventListener('submit', function(e) {
            handleFormSubmit(e, 'rewards');
        });
    });
</script>
@endsection