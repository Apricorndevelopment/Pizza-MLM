@extends('userlayouts.layouts') 
@section('title', 'My Orders')

@section('container')
{{-- Custom Scrollbar for a premium feel --}}
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

<div class="min-h-screen bg-gray-50/50 py-10">
    <div class="container mx-auto px-4 max-w-5xl">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                    <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#ECFDF5] text-teal-700">
                        <i class="bi bi-box-seam"></i>
                    </span>
                    My Orders
                </h1>
                <p class="text-gray-500 mt-2 text-sm font-medium ml-1">
                    Manage and track your recent purchases.
                </p>
            </div>

            {{-- Modern Search Filter --}}
            <div class="w-full md:w-auto relative group z-10">
                <div class="flex items-center bg-white rounded-2xl shadow-sm border border-gray-200 px-4 py-3 focus-within:ring-4 focus-within:ring-[#ECFDF5] focus-within:border-teal-500 transition-all duration-300 w-full md:w-96">
                    <i class="bi bi-search text-gray-400 group-focus-within:text-teal-500 transition-colors"></i>
                    <input type="text" 
                           id="orderSearchInput" 
                           onkeyup="filterOrders()" 
                           class="w-full border-none bg-transparent focus:ring-0 text-sm ml-3 placeholder-gray-400 text-gray-700 font-medium h-full outline-none"
                           placeholder="Filter by Order ID, Status, or Amount...">
                </div>
            </div>
        </div>

        @if($orders->count() > 0)
            <div class="space-y-5" id="ordersListContainer">
                @foreach($orders as $order)
                    
                    {{-- ORDER CARD --}}
                    <div class="order-card group bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:border-[#ECFDF5] transition-all duration-300 overflow-hidden relative"
                         data-search-term="#{{ $order->order_id }} {{ $order->status }} {{ $order->total_amount }}">
                        
                        {{-- Hover Accent Line --}}
                        <div class="absolute top-0 left-0 w-1 h-full bg-[#ECFDF5] group-hover:bg-teal-400 transition-all duration-300"></div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                                
                                {{-- Section 1: ID & Status --}}
                                <div class="lg:col-span-4">
                                    <div class="flex items-center gap-1 mb-2">
                                        <span class="bg-[#ECFDF5] text-teal-800 font-semibold px-3 py-1.5 rounded-lg text-sm tracking-wide border border-teal-100/50">
                                            #{{ $order->order_id }}
                                        </span>
                                        <span class="text-gray-400 text-xs font-medium flex items-center gap-1">
                                            <i class="bi bi-clock"></i> {{ $order->created_at->format('M d • h:i A') }}
                                        </span>
                                    </div>
                                    
                                    {{-- Dynamic Status Badge --}}
                                    @php
                                        $statusConfig = match($order->status) {
                                            'placed' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-100', 'icon' => 'bi-hourglass-split'],
                                            'accepted' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-100', 'icon' => 'bi-check2-circle'],
                                            'delivered' => ['bg' => 'bg-[#ECFDF5]', 'text' => 'text-teal-700', 'border' => 'border-teal-100', 'icon' => 'bi-box-seam-fill'],
                                            'rejected' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-100', 'icon' => 'bi-x-circle-fill'],
                                            default => ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'border' => 'border-gray-100', 'icon' => 'bi-circle'],
                                        };
                                    @endphp
                                    <div class="mt-3">
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold border {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }} uppercase tracking-wider">
                                            <i class="bi {{ $statusConfig['icon'] }}"></i> {{ $order->status }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Section 2: Payment Info --}}
                                <div class="lg:col-span-5 lg:border-l lg:border-r border-gray-50 lg:px-8">
                                    <div class="flex justify-between items-end mb-1">
                                        <span class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Total Amount</span>
                                    </div>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-1xl font-black text-gray-800 tracking-tight">₹{{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                    
                                    {{-- Payment Breakdown Pill --}}
                                    <div class="flex flex-wrap gap-2 mt-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded bg-gray-50 border border-gray-100 text-[10px] text-gray-600 font-semibold">
                                            Main: ₹{{ $order->wallet1_deducted }}
                                        </span>
                                        @if($order->wallet2_deducted > 0)
                                            <span class="inline-flex items-center px-2 py-1 rounded bg-[#ECFDF5] border border-teal-100 text-[10px] text-teal-700 font-semibold">
                                                Bonus: -₹{{ $order->wallet2_deducted }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Section 3: Action --}}
                                <div class="lg:col-span-3 flex justify-end">
                                    <button onclick="toggleDetails('details-{{ $order->id }}')" 
                                            class="w-full lg:w-auto group/btn relative flex items-center justify-center gap-2 px-4 py-2 bg-white text-gray-700 hover:text-teal-700 font-bold rounded-xl border border-gray-200 hover:border-teal-200 hover:bg-[#ECFDF5] transition-all duration-200">
                                        <span class="text-sm">View Items</span>
                                        <i id="icon-details-{{ $order->id }}" class="bi bi-chevron-down transition-transform duration-300 text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- COLLAPSIBLE DETAILS --}}
                        <div id="details-{{ $order->id }}" class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out opacity-0 bg-[#FAFAFA]">
                            <div class="p-6 border-t border-gray-100">
                                <h6 class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <i class="bi bi-basket3-fill"></i> Order Summary
                                </h6>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($order->items as $item)
                                        <div class="flex items-start p-3 bg-white rounded-xl border border-gray-100 shadow-sm hover:border-teal-100 transition-colors">
                                            {{-- Product Image --}}
                                            <div class="h-14 w-14 rounded-lg bg-gray-50 border border-gray-100 overflow-hidden flex-shrink-0 relative group-hover:shadow-inner">
                                                @if($item->product_image)
                                                    <img src="{{ asset($item->product_image) }}" class="h-full w-full object-cover">
                                                @else
                                                    <div class="h-full w-full flex items-center justify-center text-gray-300"><i class="bi bi-image"></i></div>
                                                @endif
                                            </div>

                                            {{-- Item Info --}}
                                            <div class="ml-4 flex-grow">
                                                <h4 class="text-sm font-bold text-gray-800 line-clamp-1" title="{{ $item->product_name }}">
                                                    {{ $item->product_name }}
                                                </h4>
                                                <div class="flex justify-between items-end mt-1">
                                                    <span class="text-xs text-gray-500 font-medium bg-gray-100 px-2 py-0.5 rounded">
                                                        Qty: {{ $item->quantity }}
                                                    </span>
                                                    <span class="text-sm font-bold text-teal-700">₹{{ $item->subtotal }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- No Results (Client Side) --}}
            <div id="noResultsMessage" class="hidden flex-col items-center justify-center py-20 bg-white rounded-3xl border border-dashed border-gray-200">
                <div class="bg-[#ECFDF5] p-5 rounded-full mb-4 animate-pulse">
                    <i class="bi bi-search text-3xl text-teal-600"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800">No Orders Found</h3>
                <p class="text-gray-500 text-sm mt-1">Try adjusting your filters.</p>
                <button onclick="document.getElementById('orderSearchInput').value=''; filterOrders()" class="mt-4 text-teal-600 font-bold text-sm hover:underline">Clear Search</button>
            </div>

            {{-- Pagination --}}
            <div class="mt-10" id="paginationContainer">
                {{ $orders->appends(request()->query())->links('pagination::tailwind') }}
            </div>

        @else
            {{-- Empty State (Server Side) --}}
            <div class="flex flex-col items-center justify-center py-24 bg-white rounded-3xl border border-dashed border-gray-200 shadow-sm">
                <div class="bg-[#ECFDF5] p-8 rounded-full mb-6">
                    <i class="bi bi-bag-heart text-5xl text-teal-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">No Orders Yet</h3>
                <p class="text-gray-500 mt-2 max-w-xs text-center text-sm">Looks like you haven't placed an order yet. Discover our best products today!</p>
                <a href="{{ route('user.shop.index') }}" 
                   class="mt-8 px-8 py-3 bg-teal-600 text-white rounded-full font-bold shadow-lg shadow-teal-200 hover:bg-teal-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    Start Shopping
                </a>
            </div>
        @endif
    </div>
</div>

<script>
    // 1. COLLAPSE LOGIC
    function toggleDetails(id) {
        const element = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        
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

    // 2. SEARCH LOGIC
    function filterOrders() {
        const input = document.getElementById('orderSearchInput');
        const filter = input.value.toLowerCase();
        const container = document.getElementById('ordersListContainer');
        const cards = container.getElementsByClassName('order-card');
        const noResults = document.getElementById('noResultsMessage');
        const pagination = document.getElementById('paginationContainer');
        
        let visibleCount = 0;

        for (let i = 0; i < cards.length; i++) {
            const card = cards[i];
            const searchText = (card.getAttribute('data-search-term') + " " + card.innerText).toLowerCase();
            
            if (searchText.indexOf(filter) > -1) {
                card.style.display = ""; 
                visibleCount++;
            } else {
                card.style.display = "none";
            }
        }

        if (visibleCount === 0) {
            noResults.classList.remove('hidden');
            noResults.classList.add('flex');
            if(pagination) pagination.style.display = 'none';
        } else {
            noResults.classList.add('hidden');
            noResults.classList.remove('flex');
            if(pagination) pagination.style.display = '';
        }
    }
</script>
@endsection