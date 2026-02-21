@extends('layouts.layout')
@section('title', 'All Orders')

@section('container')
    <div class="min-h-screen bg-slate-50 px-2 sm:px-3 lg:px-6">

        {{-- Alerts --}}
        <div id="alertContainer">
            @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-xl"></i>
                    <div>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900"><i class="fas fa-times"></i></button>
            </div>
            @endif

            @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-xl"></i>
                    <div>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900"><i class="fas fa-times"></i></button>
            </div>
            @endif
        </div>

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-[#064E3B]">All Orders</h1>
                <p class="text-slate-500 text-sm mt-1">Overview of all customer orders across the platform.</p>
            </div>

            {{-- Search Bar --}}
            <div class="w-full md:w-auto relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i id="searchIcon" class="mdi mdi-magnify text-slate-400 text-lg group-focus-within:text-emerald-600 transition-colors"></i>
                    <i id="searchLoader" class="mdi mdi-loading mdi-spin text-emerald-600 text-lg hidden"></i>
                </div>
                <div class="flex gap-4">
                    <input type="text" id="adminSearchInput" class="block w-full md:w-96 pl-10 pr-3 py-2.5 border-none rounded-xl bg-white shadow-sm ring-1 ring-slate-200 placeholder-slate-400 focus:ring-2 focus:ring-[#059669] focus:outline-none transition-all text-sm" placeholder="Search Order ID, Customer, or Status..." autocomplete="off">
                    <button id="adminSearchBtn1" class="p-2 bg-blue-300 rounded-xl">Search</button>
                </div>
            </div>
        </div>

        {{-- Orders Container --}}
        <div id="ordersContainer" class="space-y-4 min-h-[300px]">

            {{-- INITIAL PHP LOAD --}}
            @foreach ($orders as $order)
                <div class="order-card bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow duration-300 animate-fade-in">

                    {{-- Main Card Body --}}
                    <div class="p-3">
                        <div class="flex flex-col md:flex-row gap-6 md:items-center">

                            {{-- Col 1: Order Info --}}
                            <div class="w-full md:w-2/12">
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

                            {{-- Col 2: Customer --}}
                            <div class="w-full md:w-3/12 md:border-l md:border-slate-100 md:pl-6">
                                <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mb-1">Customer</p>
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs">
                                        {{ substr($order->user->name ?? 'G', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ $order->user->name ?? 'Guest' }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $order->user->email ?? 'No Email' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Col 3: Amount --}}
                            <div class="w-full md:w-2/12 md:border-l md:border-slate-100 md:pl-6">
                                <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mb-0.5">Total Amount</p>
                                <p class="text-xl font-bold text-[#064E3B]">₹{{ number_format($order->total_amount, 2) }}</p>
                                <div class="flex flex-col text-[10px] mt-0.5">
                                    <span class="text-slate-500">Main: ₹{{ $order->wallet1_deducted }}</span>
                                    @if ($order->wallet2_deducted > 0)
                                        <span class="text-emerald-600">Cashback: ₹{{ $order->wallet2_deducted }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Col 4: Actions --}}
                            <div class="w-full md:w-5/12 flex flex-col sm:flex-row items-center md:justify-end gap-3 md:pl-6">

                                {{-- Logic: Lock Status if Delivered/Rejected --}}
                                @if ($order->status === 'delivered' || $order->status === 'rejected')
                                    <div class="w-full sm:w-auto px-4 py-2 rounded-lg bg-slate-100 border border-slate-200 text-slate-500 text-xs font-bold uppercase cursor-not-allowed flex items-center justify-center gap-2">
                                        <i class="mdi mdi-{{ $order->status === 'delivered' ? 'check-all text-green-500' : 'close-circle text-red-500' }}"></i>
                                        {{ $order->status }}
                                    </div>
                                @else
                                    <form id="statusForm-{{ $order->id }}" action="{{ route('admin.orders.updateStatus') }}" method="POST" class="w-full sm:w-auto flex-grow">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        
                                        {{-- NAYA: Hidden OTP Input --}}
                                        <input type="hidden" name="delivery_otp" id="otp-input-{{ $order->id }}">

                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="mdi mdi-list-status text-slate-400"></i>
                                            </div>
                                            <select name="status" onchange="handleStatusChange(this, '{{ $order->id }}')" class="appearance-none block w-full pl-9 pr-8 py-2 text-xs font-bold bg-white border border-slate-200 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#059669] shadow-sm hover:border-slate-300 uppercase cursor-pointer">
                                                @if ($order->status === 'placed')
                                                    <option value="placed" selected disabled>Placed (Action Required)</option>
                                                    <option value="accepted" class="text-blue-600 font-bold">Accept Order</option>
                                                    <option value="rejected" class="text-red-600 font-bold">Reject Order</option>
                                                @elseif($order->status === 'accepted')
                                                    <option value="accepted" selected disabled>Accepted (Processing)</option>
                                                    <option value="delivered" class="text-green-600 font-bold">Mark Delivered</option>
                                                @endif
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <i class="mdi mdi-chevron-down text-slate-400 text-xs"></i>
                                            </div>
                                        </div>
                                    </form>
                                @endif

                                <button type="button" onclick="toggleDetails('details-{{ $order->id }}')" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-[#ECFDF5] hover:bg-emerald-100 text-[#059669] text-xs font-bold rounded-lg border border-[#10B981] transition-colors duration-200 gap-1 whitespace-nowrap">
                                    <span>Details</span><i id="icon-details-{{ $order->id }}" class="mdi mdi-chevron-down transition-transform duration-300"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Collapsible Item Details --}}
                    <div id="details-{{ $order->id }}" class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out opacity-0 bg-slate-50/50">
                        <div class="p-3 border-t border-slate-100">
                            
                            {{-- NAYA: Delivery Details Box --}}
                            <div class="mb-3 bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                                <h6 class="text-[11px] uppercase tracking-widest font-bold text-slate-400 mb-2 flex items-center gap-2">
                                    Delivery Details
                                </h6>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
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
                                Order Items
                            </h6>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="text-xs text-slate-400 border-b border-slate-200">
                                            <th class="pb-3 font-semibold pl-1">Product</th>
                                            <th class="pb-3 font-semibold">Vendor</th>
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
                                                        <div class="h-9 w-9 rounded-lg bg-white border border-slate-200 overflow-hidden flex-shrink-0">
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
                                                <td class="py-3 border-b border-slate-100">
                                                    <div class="flex items-center gap-1.5"><i class="mdi mdi-store text-emerald-500"></i><span class="text-slate-600 font-medium text-xs">{{ $item->vendor->name ?? 'Unknown' }}</span></div>
                                                </td>
                                                <td class="py-3 border-b border-slate-100 text-slate-600 font-medium">₹{{ number_format($item->price, 2) }}</td>
                                                <td class="py-3 border-b border-slate-100 text-center"><span class="inline-block px-2 py-0.5 rounded bg-white border border-slate-200 text-xs font-bold text-slate-600">{{ $item->quantity }}</span></td>
                                                <td class="py-3 pr-1 border-b border-slate-100 text-right font-bold text-[#064E3B]">₹{{ number_format($item->subtotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Infinite Scroll Sentinel --}}
        <div id="sentinel" class="py-8 text-center">
            <div id="scrollLoader" class="hidden flex justify-center items-center gap-2">
                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce"></div>
                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce delay-100"></div>
                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce delay-200"></div>
            </div>
            <p id="endOfData" class="hidden text-xs text-slate-400 font-medium">No more orders to load.</p>
        </div>

    </div>

    {{-- REJECTION REASON MODAL --}}
    <div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeRejectModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="mdi mdi-alert-circle text-red-600 text-xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Reject Order</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-3">Please specify the reason for rejection.</p>
                                <textarea id="modalRejectReason" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm border p-2" placeholder="Type reason here..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="submitReject()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Confirm Rejection</button>
                    <button type="button" onclick="closeRejectModal()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    {{-- NAYA: DELIVERY OTP MODAL --}}
    <div id="deliveryOtpModal" class="fixed inset-0 z-[60] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeOtpModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="mdi mdi-shield-check text-emerald-600 text-xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Verify Delivery OTP</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-3">Ask the customer for the 6-digit OTP shown in their dashboard to confirm delivery.</p>
                                <input type="text" id="modalOtpInput" required class="w-full text-center tracking-widest font-mono text-2xl border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-lg border p-3" placeholder="• • • • • •" maxlength="6">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="submitOtpDelivery()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Verify & Deliver
                    </button>
                    <button type="button" onclick="closeOtpModal()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT LOGIC --}}
    <script>
        // --- 1. EXISTING FUNCTIONS (Toggle & Modal) ---
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

        let currentSelectElement = null;
        let currentOrderIdAction = null; // Used for both OTP and Reject

        function handleStatusChange(selectElement, orderId) {
            const selectedValue = selectElement.value;
            currentSelectElement = selectElement;
            currentOrderIdAction = orderId;

            if (selectedValue === 'rejected') {
                document.getElementById('modalRejectReason').value = '';
                document.getElementById('rejectModal').classList.remove('hidden');
            } else if (selectedValue === 'delivered') {
                document.getElementById('modalOtpInput').value = '';
                document.getElementById('deliveryOtpModal').classList.remove('hidden');
            } else {
                document.getElementById('statusForm-' + orderId).submit();
            }
        }

        // Reject Modal Logic
        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            if (currentSelectElement) currentSelectElement.value = 'placed'; // Reset
        }

        function submitReject() {
            const reason = document.getElementById('modalRejectReason').value.trim();
            if(!reason) {
                alert('Please provide a reason for rejection.');
                return;
            }
            
            // Create hidden reason input if it doesn't exist
            let form = document.getElementById('statusForm-' + currentOrderIdAction);
            let reasonInput = form.querySelector('input[name="reason"]');
            if(!reasonInput) {
                reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'reason';
                form.appendChild(reasonInput);
            }
            reasonInput.value = reason;
            form.submit();
        }

        // OTP Modal Logic
        function closeOtpModal() {
            document.getElementById('deliveryOtpModal').classList.add('hidden');
            if (currentSelectElement) currentSelectElement.value = 'accepted'; // Reset
        }

        function submitOtpDelivery() {
            const otpValue = document.getElementById('modalOtpInput').value.trim();
            if(otpValue.length < 5) {
                alert('Please enter a valid OTP.');
                return;
            }
            document.getElementById('otp-input-' + currentOrderIdAction).value = otpValue;
            document.getElementById('statusForm-' + currentOrderIdAction).submit();
        }

        // --- 2. AJAX SEARCH & INFINITE SCROLL ---
        document.addEventListener('DOMContentLoaded', function() {
            let baseUrl = "{{ request()->url() }}";
            let nextPageUrl = "{{ $orders->nextPageUrl() }}";
            let isLoading = false;
            let searchTimeout = null;

            const searchInput = document.getElementById('adminSearchInput');
            const searchBtn1 = document.getElementById('adminSearchBtn1');
            const container = document.getElementById('ordersContainer');
            const scrollLoader = document.getElementById('scrollLoader');
            const endOfData = document.getElementById('endOfData');
            const searchIcon = document.getElementById('searchIcon');
            const searchLoader = document.getElementById('searchLoader');

            // Skeleton Generator
            function getSkeletonHtml(count = 3) {
                let html = '';
                for (let i = 0; i < count; i++) {
                    html += `
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 animate-pulse skeleton-card mb-4">
                        <div class="flex flex-col md:flex-row gap-6 items-center">
                            <div class="w-full md:w-2/12"><div class="h-6 bg-slate-200 rounded w-20 mb-2"></div><div class="h-4 bg-slate-200 rounded w-32"></div></div>
                            <div class="w-full md:w-3/12"><div class="flex items-center gap-3"><div class="h-8 w-8 rounded-full bg-slate-200"></div><div><div class="h-4 bg-slate-200 rounded w-24 mb-1"></div><div class="h-3 bg-slate-200 rounded w-32"></div></div></div></div>
                            <div class="w-full md:w-2/12"><div class="h-4 bg-slate-200 rounded w-16 mb-1"></div><div class="h-6 bg-slate-200 rounded w-24"></div></div>
                            <div class="w-full md:w-5/12 flex justify-end gap-2"><div class="h-9 bg-slate-200 rounded w-32"></div><div class="h-9 bg-slate-200 rounded w-20"></div></div>
                        </div>
                    </div>`;
                }
                return html;
            }

            function removeSkeletons() {
                document.querySelectorAll('.skeleton-card').forEach(el => el.remove());
            }

            // HTML Generator
            function getOrderHtml(order) {
                const date = new Date(order.created_at).toLocaleString('en-US', {
                    month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit'
                });
                const initial = order.user && order.user.name ? order.user.name.charAt(0) : 'G';
                const userName = order.user && order.user.name ? order.user.name : 'Guest';
                const userEmail = order.user && order.user.email ? order.user.email : 'No Email';
                
                // Delivery Info variables
                const userPhone = order.phone_number || (order.user && order.user.phone ? order.user.phone : 'N/A');
                const userAddress = order.address || (order.user && order.user.address ? order.user.address : 'N/A');
                const userLocation = order.location || 'N/A';

                // Status Logic
                let statusHtml = '';
                if (order.status === 'delivered' || order.status === 'rejected') {
                    const icon = order.status === 'delivered' ? 'check-all text-green-500' : 'close-circle text-red-500';
                    statusHtml = `<div class="w-full sm:w-auto px-4 py-2 rounded-lg bg-slate-100 border border-slate-200 text-slate-500 text-xs font-bold uppercase cursor-not-allowed flex items-center justify-center gap-2"><i class="mdi mdi-${icon}"></i> ${order.status}</div>`;
                } else {
                    let options = '';
                    if (order.status === 'placed') {
                        options = `<option value="placed" selected disabled>Placed (Action Required)</option><option value="accepted" class="text-blue-600 font-bold">Accept Order</option><option value="rejected" class="text-red-600 font-bold">Reject Order</option>`;
                    } else if (order.status === 'accepted') {
                        options = `<option value="accepted" selected disabled>Accepted (Processing)</option><option value="delivered" class="text-green-600 font-bold">Mark Delivered</option>`;
                    }

                    statusHtml = `
                    <form id="statusForm-${order.id}" action="{{ route('admin.orders.updateStatus') }}" method="POST" class="w-full sm:w-auto flex-grow">
                        @csrf
                        <input type="hidden" name="order_id" value="${order.id}">
                        <input type="hidden" name="delivery_otp" id="otp-input-${order.id}">
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="mdi mdi-list-status text-slate-400"></i></div>
                            <select name="status" onchange="handleStatusChange(this, '${order.id}')" class="appearance-none block w-full pl-9 pr-8 py-2 text-xs font-bold bg-white border border-slate-200 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#059669] shadow-sm hover:border-slate-300 uppercase cursor-pointer">${options}</select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"><i class="mdi mdi-chevron-down text-slate-400 text-xs"></i></div>
                        </div>
                    </form>`;
                }

                // Items Logic
                let itemsHtml = '';
                order.items.forEach(item => {
                    const img = item.product_image ?
                        `<img src="/${item.product_image}" class="h-full w-full object-cover">` :
                        `<div class="h-full w-full flex items-center justify-center text-slate-300"><i class="mdi mdi-image"></i></div>`;
                    const vendorName = item.vendor ? item.vendor.name : 'Unknown';
                    itemsHtml += `
                    <tr class="group/row hover:bg-white transition-colors">
                        <td class="py-3 pl-1 border-b border-slate-100"><div class="flex items-center gap-3"><div class="h-9 w-9 rounded-lg bg-white border border-slate-200 overflow-hidden flex-shrink-0">${img}</div><span class="font-bold text-slate-700">${item.product_name}</span></div></td>
                        <td class="py-3 border-b border-slate-100"><div class="flex items-center gap-1.5"><i class="mdi mdi-store text-emerald-500"></i><span class="text-slate-600 font-medium text-xs">${vendorName}</span></div></td>
                        <td class="py-3 border-b border-slate-100 text-slate-600 font-medium">₹${parseFloat(item.price).toLocaleString('en-IN', {minimumFractionDigits: 2})}</td>
                        <td class="py-3 border-b border-slate-100 text-center"><span class="inline-block px-2 py-0.5 rounded bg-white border border-slate-200 text-xs font-bold text-slate-600">${item.quantity}</span></td>
                        <td class="py-3 pr-1 border-b border-slate-100 text-right font-bold text-[#064E3B]">₹${parseFloat(item.subtotal).toLocaleString('en-IN', {minimumFractionDigits: 2})}</td>
                    </tr>`;
                });

                return `
                <div class="order-card bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow duration-300 animate-fade-in">
                    <div class="px-4 md:p-4">
                        <div class="flex flex-col md:flex-row gap-6 md:items-center">
                            <div class="w-full md:w-2/12"><div class="flex items-center gap-2 mb-2"><span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-semibold bg-[#ECFDF5] text-[#065F46] border border-emerald-100/50">#${order.order_id}</span></div><div class="text-xs text-slate-500 font-medium flex items-center"><i class="mdi mdi-calendar-clock mr-1.5"></i> ${date}</div></div>
                            <div class="w-full md:w-3/12 md:border-l md:border-slate-100 md:pl-6"><p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mb-1">Customer</p><div class="flex items-center gap-3"><div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs">${initial}</div><div><p class="text-sm font-bold text-slate-800">${userName}</p><p class="text-[10px] text-slate-400">${userEmail}</p></div></div></div>
                            <div class="w-full md:w-2/12 md:border-l md:border-slate-100 md:pl-6"><p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mb-0.5">Total Amount</p><p class="text-xl font-bold text-[#064E3B]">₹${parseFloat(order.total_amount).toLocaleString('en-IN', {minimumFractionDigits: 2})}</p><div class="flex flex-col text-[10px] mt-0.5"><span class="text-slate-500">Main: ₹${order.wallet1_deducted}</span>${order.wallet2_deducted > 0 ? `<span class="text-emerald-600">Cashback: ₹${order.wallet2_deducted}</span>` : ''}</div></div>
                            <div class="w-full md:w-5/12 flex flex-col sm:flex-row items-center md:justify-end gap-3 md:pl-6">${statusHtml}<button type="button" onclick="toggleDetails('details-${order.id}')" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-[#ECFDF5] hover:bg-emerald-100 text-[#059669] text-xs font-bold rounded-lg border border-[#10B981] transition-colors duration-200 gap-1 whitespace-nowrap"><span>Details</span><i id="icon-details-${order.id}" class="mdi mdi-chevron-down transition-transform duration-300"></i></button></div>
                        </div>
                    </div>
                    
                    <div id="details-${order.id}" class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out opacity-0 bg-slate-50/50">
                        <div class="p-6 border-t border-slate-100">
                            <div class="mb-6 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                                <h6 class="text-[11px] uppercase tracking-widest font-bold text-slate-400 mb-3 flex items-center gap-2"><i class="mdi mdi-map-marker-radius text-lg"></i> Delivery Details</h6>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div><span class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider">Phone Number</span><span class="font-semibold text-slate-800 text-sm">${userPhone}</span></div>
                                    <div class="lg:col-span-2"><span class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider">Delivery Address</span><span class="font-semibold text-slate-800 text-sm">${userAddress}</span></div>
                                    <div class="lg:col-span-3"><span class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider">Location/Landmark</span><span class="font-semibold text-slate-800 text-sm">${userLocation}</span></div>
                                </div>
                            </div>
                            <h6 class="text-[11px] uppercase tracking-widest font-bold text-slate-400 mb-4 flex items-center gap-2"><i class="mdi mdi-basket-outline text-lg"></i> Order Items</h6>
                            <div class="overflow-x-auto"><table class="w-full text-left border-collapse"><thead><tr class="text-xs text-slate-400 border-b border-slate-200"><th class="pb-3 font-semibold pl-1">Product</th><th class="pb-3 font-semibold">Vendor</th><th class="pb-3 font-semibold">Price</th><th class="pb-3 font-semibold text-center">Qty</th><th class="pb-3 font-semibold text-right pr-1">Subtotal</th></tr></thead><tbody class="text-sm">${itemsHtml}</tbody></table></div>
                        </div>
                    </div>
                </div>`;
            }

            // --- Fetch Function ---
            async function fetchData(url, replace = false) {
                if (!url || isLoading) return;
                isLoading = true;

                if (replace) {
                    container.innerHTML = getSkeletonHtml(3);
                    searchIcon.classList.add('hidden');
                    searchLoader.classList.remove('hidden');
                    endOfData.classList.add('hidden');
                } else {
                    container.insertAdjacentHTML('beforeend', getSkeletonHtml(2));
                }

                try {
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const data = await response.json();

                    let html = '';
                    if (data.data.length > 0) {
                        data.data.forEach(order => html += getOrderHtml(order));
                    } else if (replace) {
                        html = `<div class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200"><div class="h-20 w-20 rounded-full bg-[#ECFDF5] flex items-center justify-center mb-4"><i class="mdi mdi-magnify-remove-outline text-4xl text-[#059669]"></i></div><h3 class="text-lg font-bold text-slate-800">No Orders Found</h3></div>`;
                    }

                    if (replace) {
                        container.innerHTML = html;
                        window.scrollTo(0, 0);
                    } else {
                        removeSkeletons();
                        container.insertAdjacentHTML('beforeend', html);
                    }

                    nextPageUrl = data.next_page_url;

                    if (!nextPageUrl) {
                        if (!replace) endOfData.classList.remove('hidden');
                        if (observer) observer.unobserve(document.querySelector('#sentinel'));
                    } else {
                        endOfData.classList.add('hidden');
                        if (observer) observer.observe(document.querySelector('#sentinel'));
                    }

                } catch (error) {
                    console.error('Error:', error);
                    removeSkeletons();
                } finally {
                    isLoading = false;
                    searchLoader.classList.add('hidden');
                    searchIcon.classList.remove('hidden');
                }
            }

            // --- Search Listener ---
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const query = e.target.value;
                    let url = baseUrl;
                    if (query.length > 0) {
                        url += (url.includes('?') ? '&' : '?') + `search=${query}`;
                    }
                    fetchData(url, true);
                }
            });

            searchBtn1.addEventListener('click', function() {
                const query = searchInput.value;
                let url = baseUrl;
                if (query.length > 0) {
                    url += (url.includes('?') ? '&' : '?') + `search=${query}`;
                }
                fetchData(url, true);
            });

            // --- Infinite Scroll Observer ---
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && nextPageUrl) {
                        let url = nextPageUrl;
                        const query = searchInput.value;
                        if (query && !url.includes('search=')) url += `&search=${query}`;
                        fetchData(url, false);
                    }
                });
            }, {
                rootMargin: '200px',
                threshold: 0.1
            });

            if (document.querySelector('#sentinel')) observer.observe(document.querySelector('#sentinel'));
        });
    </script>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }
    </style>
@endsection