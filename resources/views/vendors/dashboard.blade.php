@extends('vendorlayouts.layout')
@section('title', 'Vendor Dashboard')

@section('container')
    <div class="min-h-screen bg-slate-50/50 py-8 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header Section --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                        Welcome back, <span class="text-emerald-700">{{ Auth::user()->name }}!</span>
                    </h1>
                    <p class="text-slate-500 text-sm mt-1">Here is your sales overview.</p>
                </div>

                {{-- SHOP STATUS TOGGLE --}}
                <div class="bg-white px-5 py-2.5 rounded-full shadow-sm border border-slate-100 flex items-center gap-3">
                    <form id="shopStatusForm" class="flex items-center gap-3 m-0">
                        @csrf
                        <span id="statusLabel" class="text-sm font-bold {{ $isShopOpen ? 'text-emerald-600' : 'text-red-500' }} flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full {{ $isShopOpen ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}"></span>
                            {{ $isShopOpen ? 'Shop Open' : 'Shop Closed' }}
                        </span>

                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="status" id="shopStatusToggle" value="1" class="sr-only peer" {{ $isShopOpen ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </form>
                </div>
            </div>

            {{-- 1. SALES STATISTICS GRID --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
                
                {{-- Today's Sales --}}
                <div class="bg-white rounded-2xl p-2 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-2">
                        <div class="px-3.5 py-2 bg-indigo-50 rounded-xl text-indigo-600">
                            <i class="fas fa-calendar-day text-xl"></i>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs font-bold tracking-wider">Today's Sales</p>
                            <h3 class="text-xl font-bold text-slate-900">₹{{ number_format($todaySales, 2) }}</h3>
                        </div>
                    </div>
                </div>

                {{-- Yesterday's Sales --}}
                <div class="bg-white rounded-2xl p-2 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-2">
                        <div class="px-3.5 py-2 bg-purple-50 rounded-xl text-purple-600">
                            <i class="fas fa-history text-xl"></i>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs font-bold tracking-wider">Yesterday's Sales</p>
                            <h3 class="text-xl font-bold text-slate-900">₹{{ number_format($yesterdaySales, 2) }}</h3>
                        </div>
                    </div>
                </div>

                {{-- Monthly Sales --}}
                <div class="bg-white rounded-2xl p-2 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-2">
                        <div class="px-3.5 py-2 bg-emerald-50 rounded-xl text-emerald-600">
                            <i class="fas fa-calendar-alt text-xl"></i>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs font-bold tracking-wider">This Month</p>
                            <h3 class="text-xl font-bold text-slate-900">₹{{ number_format($monthlySales, 2) }}</h3>
                        </div>
                    </div>
                </div>

                {{-- Total Revenue --}}
                <div class="bg-white rounded-2xl p-2 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-2">
                        <div class="px-3.5 py-2 bg-teal-50 rounded-xl text-teal-600">
                            <i class="fas fa-wallet text-xl"></i>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs font-bold tracking-wider">Total Sales</p>
                            <h3 class="text-xl font-bold text-slate-900">₹{{ number_format($totalSales, 2) }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-2 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-2">
                        <div class="px-3.5 py-2 bg-teal-50 rounded-xl text-teal-600">
                            <i class="fas fa-wallet text-xl"></i>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs font-bold tracking-wider">Total Payout</p>
                            <h3 class="text-xl font-bold text-slate-900">₹{{ number_format($totalPayout, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. OPERATIONAL STATS GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
                
                {{-- Pending Orders Card --}}
                <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl p-3 shadow-lg text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex justify-between items-center mb-4">
                            <div class="p-2 bg-white/20 rounded-lg">
                                <i class="fas fa-bell text-2xl"></i>
                            </div>
                            <span class="text-xs font-bold bg-white/20 px-3.5 py-2 rounded-lg">Action Needed</span>
                        </div>
                        <h2 class="text-4xl font-bold mb-1">{{ $placedOrdersCount }}</h2>
                        <p class="text-white/90 font-medium">New Placed Orders</p>
                    </div>
                    {{-- Decor --}}
                    <div class="absolute -right-6 -bottom-6 text-white/10">
                        <i class="fas fa-clipboard-list text-9xl"></i>
                    </div>
                </div>

                {{-- Active Products Card --}}
                <div class="bg-white rounded-2xl p-3 shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-1">Active Products</p>
                        <h2 class="text-3xl font-bold text-slate-900">{{ $activeProducts }}</h2>
                        <a href="{{ route('vendor.products.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 mt-2 inline-block">Manage Products &rarr;</a>
                    </div>
                    <div class="h-16 w-16 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 text-2xl">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- RECENT SALES TABLE --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col">
                        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white">
                            <h2 class="text-lg font-bold text-slate-800">Recent Transactions</h2>
                        </div>
                        <div class="overflow-x-auto flex-1">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-50/50">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Time</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($recentSales as $sale)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="h-9 w-9 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-xs mr-3 flex-shrink-0">
                                                        {{ substr($sale->user_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-semibold text-slate-900">{{ $sale->user_name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-slate-900">{{ $sale->product_name }}</div>
                                                <div class="text-xs text-slate-500">Qty: {{ $sale->quantity }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-sm font-bold text-emerald-600">₹{{ number_format($sale->price, 2) }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span class="text-xs font-medium text-slate-400">
                                                    {{ \Carbon\Carbon::parse($sale->created_at)->diffForHumans() }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                                <p class="text-sm">No recent sales found.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- LOW STOCK ALERTS --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col">
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2 bg-white">
                            <i class="fas fa-exclamation-triangle text-red-500"></i>
                            <h2 class="text-lg font-bold text-slate-800">Low Stock Alert</h2>
                        </div>
                        
                        <div class="flex-1 overflow-y-auto max-h-[400px]">
                            @forelse($lowStockProducts as $product)
                                <div class="px-6 py-4 border-b border-slate-50 hover:bg-slate-50/50 transition-colors flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <h4 class="text-sm font-semibold text-slate-900 line-clamp-1">{{ $product->product_name }}</h4>
                                            <p class="text-xs text-slate-500">ID: #{{ $product->id }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-100">
                                        {{ $product->stock_quantity }} left
                                    </span>
                                </div>
                            @empty
                                <div class="px-6 py-12 text-center">
                                    <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-check text-emerald-500 text-xl"></i>
                                    </div>
                                    <h3 class="text-slate-900 font-medium text-sm">All Good!</h3>
                                    <p class="text-slate-500 text-xs mt-1">Inventory levels look healthy.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- FIXED AJAX SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('shopStatusToggle');
            const label = document.getElementById('statusLabel');

            toggle.addEventListener('change', function() {
                const isChecked = this.checked;
                const status = isChecked ? 1 : 0;

                // 1. Optimistic UI Update (Update text immediately)
                if(isChecked) {
                    label.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Shop Open';
                    label.classList.remove('text-red-500');
                    label.classList.add('text-emerald-600');
                } else {
                    label.innerHTML = '<span class="w-2 h-2 rounded-full bg-red-500"></span> Shop Closed';
                    label.classList.remove('text-emerald-600');
                    label.classList.add('text-red-500');
                }

                // 2. Perform Fetch Request expecting JSON
                fetch("{{ route('vendor.toggleShopStatus') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json' // Explicitly tell Laravel we want JSON
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(response => {
                    // Check if response is ok
                    if (!response.ok) { throw new Error('Network response was not ok'); }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        // Server returned { success: false }
                        alert('Failed to update status: ' + (data.message || 'Unknown error'));
                        toggle.checked = !isChecked; // Revert UI
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Something went wrong. Please check console.');
                    toggle.checked = !isChecked; // Revert UI
                });
            });
        });
    </script>
@endsection