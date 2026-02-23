@extends('layouts.layout')
@section('title', 'Vendor Orders Overview')

@section('container')
    <div class="min-h-screen bg-slate-50 px-2 sm:px-3 lg:px-6">

        {{-- Header & Filters --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-xl font-bold text-[#064E3B] flex items-center gap-2">
                    <i class="fas fa-store"></i> Vendor Orders Overview
                </h1>
                <p class="text-slate-500 text-sm mt-1">Monitor all orders placed across different vendor stores.</p>
            </div>

            {{-- Filter Form --}}
            <form action="{{ route('admin.vendor_orders') }}" method="GET" class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
                {{-- Status Filter --}}
                <div class="relative">
                    <select name="status" class="block w-full sm:w-40 pl-3 pr-8 py-2.5 border-none rounded-xl bg-white shadow-sm ring-1 ring-slate-200 focus:ring-2 focus:ring-[#059669] focus:outline-none transition-all text-sm font-semibold text-slate-700 cursor-pointer">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="placed" {{ request('status') == 'placed' ? 'selected' : '' }}>Placed</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                {{-- Search Bar --}}
                <div class="relative group w-full sm:w-80">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 text-lg group-focus-within:text-emerald-600 transition-colors"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="block w-full pl-10 pr-3 py-2.5 border-none rounded-xl bg-white shadow-sm ring-1 ring-slate-200 placeholder-slate-400 focus:ring-2 focus:ring-[#059669] focus:outline-none transition-all text-sm"
                        placeholder="Search ID, Company or Customer..." autocomplete="off">
                </div>

                <button type="submit" class="bg-gradient-to-br from-teal-600 to-emerald-700 text-white font-bold rounded-xl px-6 py-2.5 shadow-md hover:scale-105 transition-transform duration-200 flex-shrink-0">
                    Filter
                </button>
                @if(request()->has('search') || request()->has('status'))
                    <a href="{{ route('admin.vendor_orders') }}" class="bg-slate-200 text-slate-700 font-bold rounded-xl px-6 py-2.5 shadow-sm hover:bg-slate-300 transition-colors duration-200 flex items-center justify-center flex-shrink-0">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        {{-- Orders Container --}}
        <div class="space-y-3 min-h-[300px]">
            @forelse ($orders as $order)
                <div class="order-card bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                    
                    {{-- Main Card Body --}}
                    <div class="p-3">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">

                            {{-- Col 1: Order Info (2 cols) --}}
                            <div class="md:col-span-2">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-semibold bg-[#ECFDF5] text-[#065F46] border border-emerald-100/50">
                                        #{{ $order->order_id }}
                                    </span>
                                </div>
                                <div class="text-xs text-slate-500 font-medium flex items-center">
                                    <i class="mdi mdi-calendar-clock mr-1.5"></i>
                                    {{ $order->created_at->format('M d, Y h:i A') }}
                                </div>
                            </div>

                            {{-- Col 2: Vendor Info (3 cols) --}}
                            <div class="md:col-span-3 md:border-l md:border-slate-100 md:pl-6">
                                <p class="text-[10px] uppercase tracking-wider font-bold text-orange-500 mb-1 flex items-center gap-1"><i class="mdi mdi-storefront"></i> Vendor</p>
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-xs">
                                        {{ substr($order->vendor->company_name ?? 'V', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800 truncate" title="{{ $order->vendor->company_name ?? 'Unknown Company' }}">
                                            {{ $order->vendor->company_name ?? 'Unknown Company' }}
                                        </p>
                                        <p class="text-[10px] text-slate-400">Owner: {{ $order->vendor->user->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Col 3: Customer Info (3 cols) --}}
                            <div class="md:col-span-3 md:border-l md:border-slate-100 md:pl-6">
                                <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mb-1 flex items-center gap-1"><i class="mdi mdi-account"></i> Customer</p>
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 px-3 py-1 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs">
                                        {{ substr($order->user->name ?? 'G', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ $order->user->name ?? 'Guest' }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $order->user->email ?? 'No Email' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Col 4: Amount & Status (4 cols) --}}
                            <div class="md:col-span-4 md:border-l md:border-slate-100 md:pl-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div>
                                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mb-0.5">Total Amount</p>
                                    <p class="text-xl font-bold text-[#064E3B]">₹{{ number_format($order->total_amount, 2) }}</p>
                                </div>
                                
                                <div class="flex items-center gap-3 w-full sm:w-auto">
                                    {{-- READ-ONLY STATUS BADGE --}}
                                    @php
                                        $statusClass = match($order->status) {
                                            'placed' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'accepted' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'delivered' => 'bg-green-100 text-green-800 border-green-200',
                                            'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                            default => 'bg-slate-100 text-slate-800 border-slate-200'
                                        };
                                        $statusIcon = match($order->status) {
                                            'placed' => 'mdi-clock-outline',
                                            'accepted' => 'mdi-thumb-up-outline',
                                            'delivered' => 'mdi-check-all',
                                            'rejected' => 'mdi-close-circle-outline',
                                            default => 'mdi-circle-outline'
                                        };
                                    @endphp
                                    <div class="px-3 py-1.5 rounded-lg border {{ $statusClass }} text-xs font-bold uppercase tracking-wide flex items-center justify-center gap-1.5 min-w-[100px] text-center">
                                        <i class="mdi {{ $statusIcon }}"></i> {{ $order->status }}
                                    </div>

                                    <button type="button" onclick="toggleDetails('details-{{ $order->id }}')" class="inline-flex items-center justify-center p-2 bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-lg border border-slate-200 transition-colors duration-200" title="View Items">
                                        <i id="icon-details-{{ $order->id }}" class="fas fa-chevron-down transition-transform duration-300"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Collapsible Item Details --}}
                    <div id="details-{{ $order->id }}" class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out opacity-0 bg-slate-50/50">
                        <div class="p-3 border-t border-slate-100">
                            
                            {{-- Delivery Details --}}
                            <div class="mb-3 bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                                <h6 class="text-[11px] uppercase tracking-widest font-bold text-slate-400 mb-3 flex items-center gap-2">
                                    <i class="fas fa-truck text-lg text-emerald-500"></i> Delivery Details
                                </h6>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div>
                                        <span class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider">Phone Number</span>
                                        <span class="font-semibold text-slate-800 text-sm">{{ $order->phone_number ?? ($order->user->phone ?? 'N/A') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider">Delivery Address</span>
                                        <span class="font-semibold text-slate-800 text-sm">{{ $order->address ?? ($order->user->address ?? 'N/A') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider">Location/Landmark</span>
                                        <span class="font-semibold text-slate-800 text-sm">{{ $order->location ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <h6 class="text-[11px] uppercase tracking-widest font-bold text-slate-400 mb-3 flex items-center gap-2">
                                <i class="fas fa-basket text-lg"></i> Ordered Items
                            </h6>
                            <div class="overflow-x-auto bg-white rounded-xl border border-slate-200">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50 text-xs text-slate-500 border-b border-slate-200">
                                            <th class="py-3 px-4 font-semibold">Product</th>
                                            <th class="py-3 px-4 font-semibold text-right">Price</th>
                                            <th class="py-3 px-4 font-semibold text-center">Qty</th>
                                            <th class="py-3 px-4 font-semibold text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
                                        @foreach ($order->items as $item)
                                            <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50 transition-colors">
                                                <td class="py-3 px-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="h-10 w-10 rounded-lg bg-white border border-slate-200 overflow-hidden flex-shrink-0">
                                                            @if ($item->product_image)
                                                                <img src="{{ asset($item->product_image) }}" class="h-full w-full object-cover">
                                                            @else
                                                                <div class="h-full w-full flex items-center justify-center text-slate-300">
                                                                    <i class="mdi mdi-image"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <span class="font-bold text-slate-700">{{ $item->product_name }}</span>
                                                    </div>
                                                </td>
                                                <td class="py-3 px-4 text-slate-600 font-medium text-right">₹{{ number_format($item->price, 2) }}</td>
                                                <td class="py-3 px-4 text-center">
                                                    <span class="inline-block px-2.5 py-1 rounded bg-slate-100 border border-slate-200 text-xs font-bold text-slate-700">{{ $item->quantity }}</span>
                                                </td>
                                                <td class="py-3 px-4 text-right font-bold text-[#064E3B]">₹{{ number_format($item->subtotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            {{-- Optional: Rejection Reason Alert if status is rejected --}}
                            @if($order->status === 'rejected')
                                @php 
                                    $rejection = \App\Models\OrderRejection::where('order_id', $order->id)->first();
                                @endphp
                                @if($rejection)
                                    <div class="mt-4 bg-red-50 text-red-800 p-3 rounded-xl border border-red-200 text-sm flex items-start gap-2">
                                        <i class="fas fa-exclamation-circle text-red-500 text-lg mt-0.5"></i>
                                        <div>
                                            <strong class="block mb-0.5">Reason for Rejection:</strong>
                                            {{ $rejection->reason }}
                                        </div>
                                    </div>
                                @endif
                            @endif

                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                    <div class="h-20 w-20 rounded-full bg-[#ECFDF5] flex items-center justify-center mb-4">
                        <i class="mdi mdi-store-off text-4xl text-[#059669]"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">No Vendor Orders Found</h3>
                    <p class="text-slate-500 text-sm mt-1 mb-6 text-center max-w-xs">There are no orders matching your current filters.</p>
                    <a href="{{ route('admin.vendor_orders') }}" class="text-teal-600 hover:text-teal-800 font-bold hover:underline">Clear Filters</a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($orders->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>

    {{-- JS for Collapsing Details --}}
    <script>
        function toggleDetails(id) {
            const element = document.getElementById(id);
            const icon = document.getElementById('icon-' + id.replace('details-', ''));
            if (!element) return;

            if (element.style.maxHeight) {
                element.style.maxHeight = null;
                element.classList.remove('opacity-100');
                element.classList.add('opacity-0');
                icon.style.transform = 'rotate(0deg)';
            } else {
                element.style.maxHeight = element.scrollHeight + "px";
                element.classList.remove('opacity-0');
                element.classList.add('opacity-100');
                icon.style.transform = 'rotate(180deg)';
            }
        }
    </script>
@endsection