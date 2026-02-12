@extends('layouts.layout')
@section('title', 'Dashboard')
@section('container')

    {{-- Header Section --}}
    <div class="mb-6 flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Welcome back, {{ Auth::guard('admin')->user()->name }}!</h2>
            <p class="text-gray-500 text-sm mt-1">Here's an overview of your platform's performance.</p>
        </div>

        <div>
            <form action="{{ route('admin.shop.toggle') }}" method="POST" id="shopStatusForm">
                @csrf
                {{-- Toggle Container --}}
                <label class="relative inline-flex items-center cursor-pointer group">
                    <input type="checkbox" name="isShopOpen" value="1" class="sr-only peer"
                        onchange="document.getElementById('shopStatusForm').submit()"
                        {{ Auth::guard('admin')->user()->isShopOpen ? 'checked' : '' }}>

                    {{-- Toggle Background --}}
                    <div
                        class="w-14 h-7 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-500 shadow-inner">
                    </div>

                    {{-- Status Text --}}
                    <span
                        class="ml-3 text-sm font-bold {{ Auth::guard('admin')->user()->isShopOpen ? 'text-emerald-600' : 'text-slate-500' }}">
                        {{ Auth::guard('admin')->user()->isShopOpen ? 'Shop is Open' : 'Shop is Closed' }}
                    </span>
                </label>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Revenue --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">₹{{ number_format($totalSales, 2) }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-wallet text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        {{-- Total Users --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Users</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalUsers) }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-gray-500">
                <span class="text-red-500 font-medium mr-2">● {{ $inactiveUsers }} Inactive</span>
            </div>
        </div>

        {{-- New Orders --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">New Orders</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $newPlacedOrders }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-amber-100 flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-amber-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-500">
                Requires Attention
            </div>
        </div>

        {{-- Monthly Joins --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Joined This Month</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $monthlyJoined }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <i class="fas fa-user-plus text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Joined Today & Yesterday --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">User Growth</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 rounded-lg bg-slate-50 border border-slate-100">
                    <p class="text-slate-500 text-xs uppercase font-bold tracking-wider">Joined Today</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">{{ $todayJoined }}</p>
                </div>
                <div class="p-4 rounded-lg bg-slate-50 border border-slate-100">
                    <p class="text-slate-500 text-xs uppercase font-bold tracking-wider">Joined Yesterday</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">{{ $yesterdayJoined }}</p>
                </div>
            </div>
        </div>

        {{-- Quick Actions (Optional Placeholder) --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-xl font-bold mb-2">Manage Platform</h3>
                <p class="text-blue-100 text-sm mb-4">Quickly access key areas of your admin panel.</p>
                <div class="flex gap-3">
                    <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-white text-blue-700 rounded-lg text-sm font-bold shadow-sm hover:bg-blue-50 transition">View Orders</a>
                    <a href="{{ route('admin.viewmember') }}" class="px-4 py-2 bg-blue-500 text-white border border-blue-400 rounded-lg text-sm font-bold hover:bg-blue-600 transition">View Users</a>
                </div>
            </div>
            <i class="fas fa-cogs absolute -bottom-4 -right-4 text-9xl text-white opacity-10"></i>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800">Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All Orders &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                    <tr>
                        <th class="py-3 px-6">Order ID</th>
                        <th class="py-3 px-6">Customer</th>
                        <th class="py-3 px-6">Amount</th>
                        <th class="py-3 px-6">Date</th>
                        <th class="py-3 px-6 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-6 font-medium text-gray-900">#{{ $order->order_id }}</td>
                            <td class="py-3 px-6">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-800">{{ $order->user->name ?? 'Guest' }}</span>
                                    <span class="text-xs text-gray-400">{{ $order->user->email ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-6 font-bold text-slate-700">₹{{ number_format($order->total_amount, 2) }}</td>
                            <td class="py-3 px-6 text-gray-500">{{ $order->created_at->format('d M, Y') }}</td>
                            <td class="py-3 px-6 text-right">
                                @if($order->status == 'delivered')
                                    <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2.5 py-1 rounded-full">Delivered</span>
                                @elseif($order->status == 'placed')
                                    <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full">New Order</span>
                                @elseif($order->status == 'cancelled' || $order->status == 'rejected')
                                    <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full">{{ ucfirst($order->status) }}</span>
                                @else
                                    <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p>No orders found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection