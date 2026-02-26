@extends('vendorlayouts.layout')
@section('title', 'All Orders')

@section('container')

    {{-- Script for PDF Generation --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8">

        {{-- Alerts --}}
        @if (session('success'))
            <div
                class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-xl"></i>
                    <div>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900"><i
                        class="fas fa-times"></i></button>
            </div>
        @endif

        @if (session('error'))
            <div
                class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-xl"></i>
                    <div>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900"><i
                        class="fas fa-times"></i></button>
            </div>
        @endif

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-[#064E3B]">All Orders</h1>
                <p class="text-slate-500 text-sm mt-1">Overview of all customer orders across the platform.</p>
            </div>

            {{-- Search Bar (Client Side) --}}
            <div class="w-full md:w-auto relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i
                        class="mdi mdi-magnify text-slate-400 text-lg group-focus-within:text-emerald-600 transition-colors"></i>
                </div>
                <input type="text" id="adminSearchInput" onkeyup="filterAdminOrders()"
                    class="block w-full md:w-96 pl-10 pr-3 py-2.5 border-none rounded-xl bg-white shadow-sm ring-1 ring-slate-200 placeholder-slate-400 focus:ring-2 focus:ring-[#059669] focus:outline-none transition-all text-sm"
                    placeholder="Search Order ID, Customer, or Status...">
            </div>
        </div>

        {{-- Orders List --}}
        @if ($orders->count() > 0)
            <div class="space-y-4" id="ordersContainer">
                @foreach ($orders as $order)
                    {{-- Data Attribute for Search --}}
                    <div class="order-card bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow duration-300 relative"
                        data-search="#{{ $order->order_id }} {{ strtolower($order->status) }} {{ strtolower($order->user->name ?? '') }}">

                        {{-- Main Card Body --}}
                        <div class="px-4 md:p-4">
                            <div class="flex flex-col md:flex-row gap-6 md:items-center">

                                {{-- Col 1: Order ID & Date --}}
                                <div class="w-full md:w-2/12">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-bold bg-[#ECFDF5] text-[#065F46] border border-emerald-100/50">
                                            #{{ $order->order_id }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-slate-500 font-medium flex items-center">
                                        <i class="mdi mdi-calendar-clock mr-1.5"></i>
                                        {{ $order->created_at->format('M d, Y h:i A') }}
                                    </div>
                                </div>

                                {{-- Col 2: Customer Info --}}
                                <div class="w-full md:w-3/12 md:border-l md:border-slate-100 md:pl-6">
                                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mb-1">Customer
                                    </p>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs">
                                            {{ substr($order->user->name ?? 'G', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800">
                                                {{ $order->user->name }}({{ $order->user->ulid }})</p>
                                            <p class="text-[10px] text-slate-400">{{ $order->user->email ?? 'No Email' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Col 3: Total Amount --}}
                                <div class="w-full md:w-2/12 md:border-l md:border-slate-100 md:pl-6">
                                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mb-0.5">Total
                                        Amount</p>
                                    <p class="text-xl font-bold text-[#064E3B]">
                                        ₹{{ number_format($order->total_amount, 2) }}</p>
                                </div>

                                {{-- Col 4: Status & Actions --}}
                                <div
                                    class="w-full md:w-5/12 flex flex-col sm:flex-row items-center md:justify-end gap-3 md:pl-6">

                                    {{-- LOGIC: LOCK STATUS IF DELIVERED OR REJECTED --}}
                                    @if ($order->status === 'delivered' || $order->status === 'rejected')
                                        <div
                                            class="w-full sm:w-auto px-3 py-2 rounded-lg bg-slate-100 border border-slate-200 text-slate-500 text-xs font-bold uppercase cursor-not-allowed flex items-center justify-center gap-2">
                                            @if ($order->status === 'delivered')
                                                <i class="fas fa-check-circle text-green-500"></i>
                                            @else
                                                <i class="fas fa-times-circle text-red-500"></i>
                                            @endif
                                            {{ $order->status }}
                                        </div>
                                    @else
                                        {{-- UPDATE STATUS FORM --}}
                                        <form id="statusForm-{{ $order->id }}"
                                            action="{{ route('vendor.orders.updateStatus') }}" method="POST"
                                            class="w-full sm:w-auto flex-grow">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">

                                            {{-- Hidden OTP input that will be filled by the modal --}}
                                            <input type="hidden" name="delivery_otp" id="otp-input-{{ $order->id }}">

                                            <div class="relative group">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="mdi mdi-list-status text-slate-400"></i>
                                                </div>
                                                <select name="status" id="statusSelect-{{ $order->id }}"
                                                    onchange="handleStatusChange(this, '{{ $order->id }}')"
                                                    class="appearance-none block w-full pl-9 pr-8 py-2 text-xs font-bold bg-white border border-slate-200 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#059669] focus:border-transparent cursor-pointer shadow-sm hover:border-slate-300 transition-colors uppercase">
                                                    @if ($order->status === 'placed')
                                                        <option value="placed" selected disabled>Placed (Action Required)
                                                        </option>
                                                        <option value="accepted" class="text-blue-600 font-bold">Accept
                                                            Order</option>
                                                        <option value="rejected" class="text-red-600 font-bold">Reject Order
                                                        </option>
                                                    @elseif($order->status === 'accepted')
                                                        <option value="accepted" selected disabled>Accepted (Processing)
                                                        </option>
                                                        <option value="delivered" class="text-green-600 font-bold">Mark
                                                            Delivered</option>
                                                    @endif
                                                </select>
                                                <div
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <i class="mdi mdi-chevron-down text-slate-400 text-xs"></i>
                                                </div>
                                            </div>
                                        </form>
                                    @endif

                                    {{-- INVOICE BUTTON (Visible only if status is 'delivered') --}}
                                    @if ($order->status == 'delivered')
                                        <button type="button" onclick="openInvoiceModal('{{ $order->id }}')"
                                            class="w-full lg:w-auto flex items-center justify-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-bold rounded-lg border border-indigo-100 transition-all duration-200">
                                            <i class="mdi mdi-receipt text-sm"></i> <span class="text-xs">Invoice</span>
                                        </button>
                                    @endif

                                    {{-- View Items Button --}}
                                    <button type="button" onclick="toggleDetails('details-{{ $order->id }}')"
                                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-[#ECFDF5] hover:bg-emerald-100 text-[#059669] text-xs font-bold rounded-lg border border-[#10B981] transition-colors duration-200 gap-1 group cursor-pointer whitespace-nowrap">
                                        <span>Details</span>
                                        <i id="icon-details-{{ $order->id }}"
                                            class="mdi mdi-chevron-down transition-transform duration-300"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Collapsible Details Section --}}
                        <div id="details-{{ $order->id }}"
                            class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out opacity-0 bg-slate-50/50">
                            <div class="p-2.5 sm:p-6 border-t border-slate-100">
                                {{-- NAYA: Delivery Details Box --}}
                                <div class="mb-3 bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                                    <h6
                                        class="text-[11px] uppercase tracking-widest font-bold text-slate-400 mb-2 flex items-center gap-2">
                                        Delivery Details
                                    </h6>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                        <div>
                                            <span
                                                class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider">Phone
                                                Number</span>
                                            <span
                                                class="font-semibold text-slate-800 text-sm">{{ $order->phone_number ?? ($order->user->phone ?? 'N/A') }}</span>
                                        </div>
                                        <div>
                                            <span
                                                class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider">Delivery
                                                Address</span>
                                            <span
                                                class="font-semibold text-slate-800 text-sm">{{ $order->address ?? ($order->user->address ?? 'N/A') }}</span>
                                        </div>
                                        <div>
                                            <span
                                                class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider">Location/Landmark</span>
                                            <span
                                                class="font-semibold text-slate-800 text-sm">{{ $order->location ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <h6
                                    class="text-[11px] uppercase tracking-widest font-bold text-slate-400 mb-4 flex items-center gap-2">
                                    <i class="mdi mdi-basket-outline text-lg"></i> Order Items
                                </h6>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="text-xs text-slate-400 border-b border-slate-200">
                                                <th class="pb-3 font-semibold pl-1">Product</th>
                                                <th class="pb-3 font-semibold">Price</th>
                                                <th class="pb-3 font-semibold text-center">Qty</th>
                                                <th class="pb-3 font-semibold text-right pr-1">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm">
                                            @foreach ($order->items as $item)
                                                <tr class="group/row hover:bg-white transition-colors">
                                                    <td class="py-3 pl-1 border-b border-slate-100">
                                                        <div class="flex items-center gap-3">
                                                            <div
                                                                class="h-9 w-9 rounded-lg bg-white border border-slate-200 overflow-hidden flex-shrink-0">
                                                                @if ($item->product_image)
                                                                    <img src="{{ asset($item->product_image) }}"
                                                                        class="h-full w-full object-cover">
                                                                @else
                                                                    <div
                                                                        class="h-full w-full flex items-center justify-center text-slate-300">
                                                                        <i class="mdi mdi-image"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <span
                                                                class="font-bold text-slate-700">{{ $item->product_name }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="py-3 border-b border-slate-100 text-slate-600 font-medium">
                                                        ₹{{ number_format($item->price, 2) }}</td>
                                                    <td class="py-3 border-b border-slate-100 text-center">
                                                        <span
                                                            class="inline-block px-2 py-0.5 rounded bg-white border border-slate-200 text-xs font-bold text-slate-600">{{ $item->quantity }}</span>
                                                    </td>
                                                    <td
                                                        class="py-3 pr-1 border-b border-slate-100 text-right font-bold text-[#064E3B]">
                                                        ₹{{ number_format($item->subtotal, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- EXACT INVOICE DESIGN IMPLEMENTATION (Fully Responsive) --}}
                        <div id="invoice-data-{{ $order->id }}" class="hidden">
                            <div class="p-2.5 sm:p-6 bg-white" style="font-family: sans-serif; color: #333;">

                                {{-- 1. Invoice Header: Logo & Platform Name --}}
                                <div class="flex justify-between items-center mb-6 pb-6 border-b border-gray-200">
                                    <div class="flex items-center gap-2.5 sm:gap-3.5">
                                        {{-- Platform Logo --}}
                                        <div
                                            class="w-10 sm:w-14 h-10 sm:h-14 bg-white rounded-lg border border-gray-100 shadow-sm p-1 flex items-center justify-center overflow-hidden">
                                            <img src="{{ asset('images/smartsave.png') }}" alt="Logo"
                                                class="w-full h-full object-contain">
                                        </div>
                                        <div>
                                            <h1
                                                class="text-xl sm:text-2xl font-black text-teal-800 tracking-tight m-0 leading-none uppercase">
                                                ZIDDI GROUP</h1>
                                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">
                                                SmartSave24 Platform</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <h2
                                            class="text-2xl sm:text-3xl font-bold text-gray-200 uppercase tracking-widest m-0 leading-none">
                                            BILL</h2>
                                        <p class="text-[8px] sm:text-sm font-bold text-gray-700 mt-1">
                                            #{{ $order->order_id }}</p>
                                    </div>
                                </div>

                                {{-- 2. Billing & Seller Information --}}
                                @php
                                    // Determine if order belongs to a vendor or admin
                                    $firstItem = $order->items->first();
                                    $isVendorOrder = $firstItem && $firstItem->vendor_id && $firstItem->vendor;
                                @endphp

                                <div class="flex justify-between mb-8 gap-8">
                                    {{-- Billed To (Customer Details) --}}
                                    <div class="w-28 sm:w-1/2">
                                        <p
                                            class="text-[8px] sm:text-[10px] font-bold text-teal-600 bg-teal-50 inline-block px-2 py-0.5 rounded uppercase tracking-widest mb-3">
                                            Billed To</p>
                                        <p class="font-extrabold text-gray-900 text-xs sm:text-lg leading-tight">
                                            {{ $order->user->name ?? 'Guest' }}</p>
                                        <p class="text-[8px] sm:text-sm text-gray-600 mt-1">
                                            {{ $order->user->email ?? 'N/A' }}</p>
                                        <p class="text-[8px] sm:text-sm text-gray-600">{{ $order->user->phone ?? 'N/A' }}
                                        </p>
                                        <p class="text-[8px] sm:text-sm text-gray-600 mt-1 max-w-[250px] leading-snug">
                                            {{ $order->user->address ?? 'Address not provided' }}
                                        </p>
                                    </div>

                                    {{-- Sold By (Vendor / Admin Details) --}}
                                    <div class="w-28 sm:w-1/2 text-right">
                                        <p
                                            class="text-[8px] sm:text-[10px] font-bold text-gray-500 bg-gray-100 inline-block px-2 py-0.5 rounded uppercase tracking-widest mb-3">
                                            Sold By</p>

                                        @if ($isVendorOrder)
                                            <p class="font-extrabold text-gray-900 text-xs sm:text-lg leading-tight">
                                                {{ $firstItem->vendor->company_name ?? 'Vendor Store' }}</p>
                                            <p class="text-[8px] sm:text-sm font-bold text-teal-600 mt-0.5">Owner:
                                                {{ $firstItem->vendor->user->name ?? 'Vendor' }}</p>
                                            <p
                                                class="text-[8px] sm:text-sm text-gray-600 mt-1 max-w-[250px] ml-auto leading-snug">
                                                {{ $firstItem->vendor->company_address ?? 'Address not provided' }}<br>
                                                {{ $firstItem->vendor->company_city ?? '' }}
                                                {{ $firstItem->vendor->company_state ?? '' }}
                                            </p>
                                            <p class="text-[8px] sm:text-sm text-gray-500 mt-1">
                                                {{ $firstItem->vendor->user->email ?? '' }}</p>
                                        @else
                                            <p class="font-extrabold text-gray-900 text-xs sm:text-lg leading-tight">Ziddi
                                                Group Official</p>
                                            <p class="text-[8px] sm:text-sm font-bold text-teal-600 mt-0.5">SmartSave24
                                                Admin</p>
                                            <p
                                                class="text-[8px] sm:text-sm text-gray-600 mt-1 max-w-[250px] ml-auto leading-snug">
                                                Main GT Road, V.P.O Rai<br>Sonipat, Haryana
                                            </p>
                                            <p class="text-[8px] sm:text-sm text-gray-500 mt-1">support@smartsave24.com</p>
                                        @endif

                                        <div class="mt-4">
                                            <p
                                                class="text-[8px] font-semibold sm:font-bold text-gray-800 bg-gray-50 inline-block px-2.5 sm:px-4.5 py-1.5 rounded border border-gray-100">
                                                Date: {{ $order->created_at->format('d M, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- 3. Items Table --}}
                                <table class="w-full mb-6" style="width: 100%; border-collapse: collapse;">
                                    <thead>
                                        <tr class="bg-gray-100 border-b-2 border-gray-200">
                                            <th
                                                class="text-left p-1.5 sm:p-3.5 text-xs font-extrabold text-gray-600 sm:uppercase">
                                                Item Description</th>
                                            <th
                                                class="text-center p-1.5 sm:p-3.5 text-xs font-extrabold text-gray-600 sm:uppercase">
                                                Qty</th>
                                            <th
                                                class="text-right p-1.5 sm:p-3.5 text-xs font-extrabold text-gray-600 sm:uppercase">
                                                Price</th>
                                            <th
                                                class="text-right p-1.5 sm:p-3.5 text-xs font-extrabold text-gray-600 sm:uppercase">
                                                Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($order->items as $item)
                                            <tr>
                                                <td
                                                    class="p-1.5 sm:p-3.5 text-xs sm:text-sm font-semibold sm:font-bold text-gray-800">
                                                    {{ $item->product_name }}</td>
                                                <td
                                                    class="p-1.5 sm:p-3.5 text-xs sm:text-sm font-medium text-gray-600 text-center">
                                                    {{ $item->quantity }}</td>
                                                <td
                                                    class="p-1.5 sm:p-3.5 text-xs sm:text-sm font-medium text-gray-600 text-right">
                                                    ₹{{ number_format($item->price, 2) }}</td>
                                                <td
                                                    class="p-1.5 sm:p-3.5 text-xs sm:text-sm font-black text-teal-700 text-right">
                                                    ₹{{ number_format($item->subtotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                {{-- 4. Totals Calculation --}}
                                <div class="flex justify-end">
                                    <div
                                        class="w-full sm:w-1/2 bg-gray-50 rounded-xl p-2.5 sm:p-4.5 border border-gray-100">
                                        <div class="flex justify-between py-2 border-b border-gray-200/60">
                                            <span class="text-sm font-medium text-gray-600">Subtotal</span>
                                            <span
                                                class="text-sm font-bold text-gray-800">₹{{ number_format($order->total_amount, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-gray-200/60">
                                            <span class="text-sm font-medium text-gray-600">Personal Wallet Used</span>
                                            <span
                                                class="text-sm font-bold text-gray-800">₹{{ number_format($order->wallet1_deducted, 2) }}</span>
                                        </div>
                                        @if ($order->wallet2_deducted > 0)
                                            <div class="flex justify-between py-2 border-b border-gray-200/60">
                                                <span class="text-sm font-medium text-gray-600">Second Wallet Used</span>
                                                <span
                                                    class="text-sm font-bold text-gray-800">₹{{ number_format($order->wallet2_deducted, 2) }}</span>
                                            </div>
                                        @endif
                                        @if ($order->coupons_used > 0)
                                            <div class="flex justify-between py-2 border-b border-gray-200/60">
                                                <span class="text-sm font-medium text-gray-600">Coupon Discount</span>
                                                <span class="text-sm font-bold text-orange-500">-
                                                    ₹{{ number_format($order->coupons_used * 10) }}</span>
                                            </div>
                                        @endif

                                        <div class="flex justify-between py-3 mt-1">
                                            <span class="text-sm sm:text-base font-extrabold text-gray-900 uppercase">Total
                                                Paid</span>
                                            <span
                                                class="text-lg sm:text-xl font-black text-teal-700">₹{{ number_format($order->wallet1_deducted + $order->wallet2_deducted, 2) }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- 5. Footer --}}
                                <div class="mt-12 pt-8 border-t-2 border-dashed border-gray-200 text-center">
                                    <p class="text-sm sm:text-base font-black text-gray-800">Thank you for shopping with
                                        Ziddi Group!</p>
                                    <p class="text-xs font-medium text-gray-500 mt-1">For support, please contact your
                                        seller or reach out to Ziddi Group Helpdesk.</p>
                                    <p class="text-[10px] text-gray-400 mt-4 uppercase tracking-widest">This is a computer
                                        generated invoice</p>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach

                {{-- INVOICE MODAL (Exactly matching User panel modal structure) --}}
                <div id="invoiceModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
                    aria-modal="true">
                    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
                        onclick="closeInvoiceModal()"></div>
                    <div class="fixed inset-0 z-10 w-screen p-2 overflow-y-auto">
                        <div class="flex min-h-screen items-center justify-center text-center sm:p-2">
                            <div
                                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-2xl">
                                <div
                                    class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center border-b border-gray-200">
                                    <h3 class="text-lg font-bold leading-6 text-gray-900">Invoice Preview</h3>
                                    <button type="button" onclick="closeInvoiceModal()"
                                        class="text-gray-400 hover:text-gray-500 focus:outline-none rounded-full p-1 hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-x-lg text-lg"></i>
                                    </button>
                                </div>
                                <div class="bg-gray-200 p-1 flex justify-center">
                                    <div id="modalInvoiceContent" class="bg-white shadow-lg w-full max-w-[210mm]">
                                    </div>
                                </div>
                                <div
                                    class="bg-white px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-200 gap-2">
                                    <button type="button" onclick="downloadPDF()"
                                        class="inline-flex w-full justify-center rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-teal-700 sm:ml-3 sm:w-auto gap-2 items-center transition-colors">
                                        <i class="bi bi-download"></i> Download PDF
                                    </button>
                                    <button type="button" onclick="closeInvoiceModal()"
                                        class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>{{-- EXACT INVOICE DESIGN IMPLEMENTATION (Fully Responsive) --}}
                <div id="invoice-data-{{ $order->id }}" class="hidden">
                    <div class="p-2.5 sm:p-6 bg-white" style="font-family: sans-serif; color: #333;">

                        {{-- 1. Invoice Header: Logo & Platform Name --}}
                        <div class="flex justify-between items-center mb-6 pb-6 border-b border-gray-200">
                            <div class="flex items-center gap-2.5 sm:gap-3.5">
                                {{-- Platform Logo --}}
                                <div
                                    class="w-10 sm:w-14 h-10 sm:h-14 bg-white rounded-lg border border-gray-100 shadow-sm p-1 flex items-center justify-center overflow-hidden">
                                    <img src="{{ asset('images/smartsave.png') }}" alt="Logo"
                                        class="w-full h-full object-contain">
                                </div>
                                <div>
                                    <h1
                                        class="text-xl sm:text-2xl font-black text-teal-800 tracking-tight m-0 leading-none uppercase">
                                        ZIDDI GROUP</h1>
                                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">
                                        SmartSave24 Platform</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <h2
                                    class="text-2xl sm:text-3xl font-bold text-gray-200 uppercase tracking-widest m-0 leading-none">
                                    BILL</h2>
                                <p class="text-[8px] sm:text-sm font-bold text-gray-700 mt-1">#{{ $order->order_id }}</p>
                            </div>
                        </div>

                        {{-- 2. Billing & Seller Information --}}
                        @php
                            // Determine if order belongs to a vendor or admin
                            $firstItem = $order->items->first();
                            $isVendorOrder = $firstItem && $firstItem->vendor_id && $firstItem->vendor;
                        @endphp

                        <div class="flex justify-between mb-8 gap-8">
                            {{-- Billed To (Customer Details) --}}
                            <div class="w-28 sm:w-1/2">
                                <p
                                    class="text-[8px] sm:text-[10px] font-bold text-teal-600 bg-teal-50 inline-block px-2 py-0.5 rounded uppercase tracking-widest mb-3">
                                    Billed To</p>
                                <p class="font-extrabold text-gray-900 text-xs sm:text-lg leading-tight">
                                    {{ $order->user->name ?? 'Guest' }}</p>
                                <p class="text-[8px] sm:text-sm text-gray-600 mt-1">{{ $order->user->email ?? 'N/A' }}</p>
                                <p class="text-[8px] sm:text-sm text-gray-600">{{ $order->user->phone ?? 'N/A' }}</p>
                                <p class="text-[8px] sm:text-sm text-gray-600 mt-1 max-w-[250px] leading-snug">
                                    {{ $order->user->address ?? 'Address not provided' }}
                                </p>
                            </div>

                            {{-- Sold By (Vendor / Admin Details) --}}
                            <div class="w-28 sm:w-1/2 text-right">
                                <p
                                    class="text-[8px] sm:text-[10px] font-bold text-gray-500 bg-gray-100 inline-block px-2 py-0.5 rounded uppercase tracking-widest mb-3">
                                    Sold By</p>

                                @if ($isVendorOrder)
                                    <p class="font-extrabold text-gray-900 text-xs sm:text-lg leading-tight">
                                        {{ $firstItem->vendor->company_name ?? 'Vendor Store' }}</p>
                                    <p class="text-[8px] sm:text-sm font-bold text-teal-600 mt-0.5">Owner:
                                        {{ $firstItem->vendor->user->name ?? 'Vendor' }}</p>
                                    <p class="text-[8px] sm:text-sm text-gray-600 mt-1 max-w-[250px] ml-auto leading-snug">
                                        {{ $firstItem->vendor->company_address ?? 'Address not provided' }}<br>
                                        {{ $firstItem->vendor->company_city ?? '' }}
                                        {{ $firstItem->vendor->company_state ?? '' }}
                                    </p>
                                    <p class="text-[8px] sm:text-sm text-gray-500 mt-1">
                                        {{ $firstItem->vendor->user->email ?? '' }}</p>
                                @else
                                    <p class="font-extrabold text-gray-900 text-xs sm:text-lg leading-tight">Ziddi Group
                                        Official</p>
                                    <p class="text-[8px] sm:text-sm font-bold text-teal-600 mt-0.5">SmartSave24 Admin</p>
                                    <p class="text-[8px] sm:text-sm text-gray-600 mt-1 max-w-[250px] ml-auto leading-snug">
                                        Main GT Road, V.P.O Rai<br>Sonipat, Haryana
                                    </p>
                                    <p class="text-[8px] sm:text-sm text-gray-500 mt-1">support@smartsave24.com</p>
                                @endif

                                <div class="mt-4">
                                    <p
                                        class="text-[8px] font-semibold sm:font-bold text-gray-800 bg-gray-50 inline-block px-2.5 sm:px-4.5 py-1.5 rounded border border-gray-100">
                                        Date: {{ $order->created_at->format('d M, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Items Table --}}
                        <table class="w-full mb-6" style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr class="bg-gray-100 border-b-2 border-gray-200">
                                    <th class="text-left p-1.5 sm:p-3.5 text-xs font-extrabold text-gray-600 sm:uppercase">
                                        Item Description</th>
                                    <th
                                        class="text-center p-1.5 sm:p-3.5 text-xs font-extrabold text-gray-600 sm:uppercase">
                                        Qty</th>
                                    <th
                                        class="text-right p-1.5 sm:p-3.5 text-xs font-extrabold text-gray-600 sm:uppercase">
                                        Price</th>
                                    <th
                                        class="text-right p-1.5 sm:p-3.5 text-xs font-extrabold text-gray-600 sm:uppercase">
                                        Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td
                                            class="p-1.5 sm:p-3.5 text-xs sm:text-sm font-semibold sm:font-bold text-gray-800">
                                            {{ $item->product_name }}</td>
                                        <td
                                            class="p-1.5 sm:p-3.5 text-xs sm:text-sm font-medium text-gray-600 text-center">
                                            {{ $item->quantity }}</td>
                                        <td class="p-1.5 sm:p-3.5 text-xs sm:text-sm font-medium text-gray-600 text-right">
                                            ₹{{ number_format($item->price, 2) }}</td>
                                        <td class="p-1.5 sm:p-3.5 text-xs sm:text-sm font-black text-teal-700 text-right">
                                            ₹{{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- 4. Totals Calculation --}}
                        <div class="flex justify-end">
                            <div class="w-full sm:w-1/2 bg-gray-50 rounded-xl p-2.5 sm:p-4.5 border border-gray-100">
                                <div class="flex justify-between py-2 border-b border-gray-200/60">
                                    <span class="text-sm font-medium text-gray-600">Subtotal</span>
                                    <span
                                        class="text-sm font-bold text-gray-800">₹{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200/60">
                                    <span class="text-sm font-medium text-gray-600">Personal Wallet Used</span>
                                    <span
                                        class="text-sm font-bold text-gray-800">₹{{ number_format($order->wallet1_deducted, 2) }}</span>
                                </div>
                                @if ($order->wallet2_deducted > 0)
                                    <div class="flex justify-between py-2 border-b border-gray-200/60">
                                        <span class="text-sm font-medium text-gray-600">Second Wallet Used</span>
                                        <span
                                            class="text-sm font-bold text-gray-800">₹{{ number_format($order->wallet2_deducted, 2) }}</span>
                                    </div>
                                @endif
                                @if ($order->coupons_used > 0)
                                    <div class="flex justify-between py-2 border-b border-gray-200/60">
                                        <span class="text-sm font-medium text-gray-600">Coupon Discount</span>
                                        <span class="text-sm font-bold text-orange-500">-
                                            ₹{{ number_format($order->coupons_used * 10) }}</span>
                                    </div>
                                @endif

                                <div class="flex justify-between py-3 mt-1">
                                    <span class="text-sm sm:text-base font-extrabold text-gray-900 uppercase">Total
                                        Paid</span>
                                    <span
                                        class="text-lg sm:text-xl font-black text-teal-700">₹{{ number_format($order->wallet1_deducted + $order->wallet2_deducted, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- 5. Footer --}}
                        <div class="mt-12 pt-8 border-t-2 border-dashed border-gray-200 text-center">
                            <p class="text-sm sm:text-base font-black text-gray-800">Thank you for shopping with Ziddi
                                Group!</p>
                            <p class="text-xs font-medium text-gray-500 mt-1">For support, please contact your seller or
                                reach out to Ziddi Group Helpdesk.</p>
                            <p class="text-[10px] text-gray-400 mt-4 uppercase tracking-widest">This is a computer
                                generated invoice</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- No Results Message --}}
            <div id="noResultsMessage"
                class="hidden flex-col items-center justify-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                <div class="h-20 w-20 rounded-full bg-[#ECFDF5] flex items-center justify-center mb-4">
                    <i class="mdi mdi-magnify-remove-outline text-4xl text-[#059669]"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">No Orders Found</h3>
                <p class="text-slate-500 text-sm mt-1">Try adjusting your search criteria.</p>
            </div>

            {{-- Pagination --}}
            <div id="paginationContainer" class="mt-6">
                {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @else
            {{-- Empty State --}}
            <div
                class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                <div class="h-20 w-20 rounded-full bg-[#ECFDF5] flex items-center justify-center mb-4">
                    <i class="mdi mdi-clipboard-text-off-outline text-4xl text-[#059669]"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">No Orders Found</h3>
                <p class="text-slate-500 text-sm mt-1 mb-6 text-center max-w-xs">There are no orders available at the
                    moment.</p>
            </div>
        @endif
    </div>

    {{-- REJECTION REASON MODAL --}}
    <div id="rejectModal" class="fixed inset-0 z-[60] hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeRejectModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="rejectForm" action="{{ route('vendor.orders.updateStatus') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" id="modalOrderId">
                    <input type="hidden" name="status" value="rejected">

                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-times text-red-600 text-xl"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Reject Order</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-3">Please specify the reason for rejecting this
                                        order. This will be visible to the system.</p>
                                    <textarea name="reason" rows="3" required
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm border p-2"
                                        placeholder="Type reason here (e.g., Out of Stock, Invalid Address)..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Confirm Rejection
                        </button>
                        <button type="button" onclick="closeRejectModal()"
                            class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- DELIVERY OTP MODAL --}}
    <div id="deliveryOtpModal" class="fixed inset-0 z-[60] hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeOtpModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="mdi mdi-shield-check text-green-600 text-xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Verify Delivery</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-3">Ask the customer for the 6-digit OTP shown in their
                                    'My Orders' section to confirm delivery.</p>
                                <input type="text" id="modalOtpInput" required
                                    class="w-full text-center tracking-widest font-mono text-2xl border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-lg border p-3"
                                    placeholder="• • • • • •" maxlength="6">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="submitOtpDelivery()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Verify & Deliver
                    </button>
                    <button type="button" onclick="closeOtpModal()"
                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT LOGIC --}}
    <script>
        // 1. COLLAPSE TOGGLE
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

        // 2. CLIENT-SIDE SEARCH
        function filterAdminOrders() {
            const input = document.getElementById('adminSearchInput');
            const filter = input.value.toLowerCase();
            const container = document.getElementById('ordersContainer');
            const cards = container.getElementsByClassName('order-card');
            const noResults = document.getElementById('noResultsMessage');
            const pagination = document.getElementById('paginationContainer');

            let visibleCount = 0;

            for (let i = 0; i < cards.length; i++) {
                const card = cards[i];
                const searchData = card.getAttribute('data-search');

                if (searchData.indexOf(filter) > -1) {
                    card.style.display = "";
                    visibleCount++;
                } else {
                    card.style.display = "none";
                }
            }

            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
                noResults.classList.add('flex');
                if (pagination) pagination.style.display = 'none';
            } else {
                noResults.classList.add('hidden');
                noResults.classList.remove('flex');
                if (pagination) pagination.style.display = '';
            }
        }

        // 3. STATUS LOGIC
        let currentSelectElement = null;
        let currentOrderIdForOtp = null;

        function handleStatusChange(selectElement, orderId) {
            const selectedValue = selectElement.value;
            const form = document.getElementById('statusForm-' + orderId);

            if (selectedValue === 'rejected') {
                openRejectModal(orderId, selectElement);
            } else if (selectedValue === 'delivered') {
                openOtpModal(orderId, selectElement);
            } else {
                form.submit();
            }
        }

        // REJECT MODAL LOGIC
        function openRejectModal(orderId, selectElement) {
            currentSelectElement = selectElement;
            document.getElementById('modalOrderId').value = orderId;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            if (currentSelectElement) {
                currentSelectElement.value = 'placed'; // Reset
            }
        }

        // OTP MODAL LOGIC
        function openOtpModal(orderId, selectElement) {
            currentSelectElement = selectElement;
            currentOrderIdForOtp = orderId;
            document.getElementById('modalOtpInput').value = ''; // Clear previous input
            document.getElementById('deliveryOtpModal').classList.remove('hidden');
        }

        function closeOtpModal() {
            document.getElementById('deliveryOtpModal').classList.add('hidden');
            if (currentSelectElement) {
                currentSelectElement.value = 'accepted'; // Reset back to accepted
            }
        }

        function submitOtpDelivery() {
            const otpValue = document.getElementById('modalOtpInput').value.trim();

            if (otpValue.length < 5) { // Assuming 6 digit OTP
                alert('Please enter a valid OTP.');
                return;
            }

            // Put the OTP into the hidden input of the correct form
            document.getElementById('otp-input-' + currentOrderIdForOtp).value = otpValue;

            // Submit the form
            document.getElementById('statusForm-' + currentOrderIdForOtp).submit();
        }

        // 4. INVOICE MODAL LOGIC
        function openInvoiceModal(orderId) {
            const contentSource = document.getElementById('invoice-data-' + orderId);
            const modalTarget = document.getElementById('modalInvoiceContent');

            if (contentSource && modalTarget) {
                modalTarget.innerHTML = contentSource.innerHTML;
                const modal = document.getElementById('invoiceModal');
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeInvoiceModal() {
            const modal = document.getElementById('invoiceModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function downloadPDF() {
            const element = document.getElementById('modalInvoiceContent');
            const opt = {
                margin: [10, 10, 10, 10],
                filename: 'Order_Invoice.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2,
                    useCORS: true
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };
            html2pdf().set(opt).from(element.firstElementChild).save();
        }
    </script>
@endsection
