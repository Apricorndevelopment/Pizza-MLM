@extends('vendorlayouts.layout')
@section('title', 'All Orders')

@section('container')
    <div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8">

        {{-- Alerts --}}
        @session('success')
            <div
                class="mb-4 alert rounded-md bg-green-50 p-4 border border-green-200 transition-opacity duration-500 ease-in-out">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button type="button" onclick="this.closest('.alert').remove()"
                                class="inline-flex rounded-md bg-green-50 p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50">
                                <span class="sr-only">Dismiss</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endsession

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
                    <div class="order-card bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow duration-300"
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
                                            <p class="text-sm font-bold text-slate-800">{{ $order->user->name ?? 'Guest' }}
                                            </p>
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
                                    <div class="flex flex-col text-[10px] mt-0.5">
                                        <span class="text-slate-500">Main: ₹{{ $order->wallet1_deducted }}</span>
                                        @if ($order->wallet2_deducted > 0)
                                            <span class="text-emerald-600">Cashback: ₹{{ $order->wallet2_deducted }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Col 4: Status & Actions --}}
                                <div
                                    class="w-full md:w-5/12 flex flex-col sm:flex-row items-center md:justify-end gap-3 md:pl-6">

                                    {{-- LOGIC: LOCK STATUS IF DELIVERED OR REJECTED --}}
                                    @if ($order->status === 'delivered' || $order->status === 'rejected')
                                        <div
                                            class="w-full sm:w-auto px-4 py-2 rounded-lg bg-slate-100 border border-slate-200 text-slate-500 text-xs font-bold uppercase cursor-not-allowed flex items-center justify-center gap-2">
                                            @if ($order->status === 'delivered')
                                                <i class="mdi mdi-check-all text-green-500"></i>
                                            @else
                                                <i class="mdi mdi-close-circle text-red-500"></i>
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

                                            <div class="relative group">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="mdi mdi-list-status text-slate-400"></i>
                                                </div>

                                                <select name="status"
                                                    onchange="handleStatusChange(this, '{{ $order->id }}')"
                                                    class="appearance-none block w-full pl-9 pr-8 py-2 text-xs font-bold bg-white border border-slate-200 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#059669] focus:border-transparent cursor-pointer shadow-sm hover:border-slate-300 transition-colors uppercase">

                                                    {{-- 1. IF PLACED: Show Accept & Reject --}}
                                                    @if ($order->status === 'placed')
                                                        <option value="placed" selected disabled>Placed (Action Required)
                                                        </option>
                                                        <option value="accepted" class="text-blue-600 font-bold">Accept
                                                            Order</option>
                                                        <option value="rejected" class="text-red-600 font-bold">Reject Order
                                                        </option>

                                                        {{-- 2. IF ACCEPTED: Show Delivered --}}
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
                            <div class="p-6 border-t border-slate-100">
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
                                                            class="inline-block px-2 py-0.5 rounded bg-white border border-slate-200 text-xs font-bold text-slate-600">
                                                            {{ $item->quantity }}
                                                        </span>
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
                    </div>
                @endforeach
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
            <div id="paginationContainer" class="mt-8 flex justify-center">
                {{ $orders->appends(request()->query())->links('pagination::tailwind') }}
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
    <div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            {{-- Background Overlay --}}
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Modal Content --}}
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
                                <i class="mdi mdi-alert-circle text-red-600 text-xl"></i>
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
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
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

        function handleStatusChange(selectElement, orderId) {
            const selectedValue = selectElement.value;
            const form = document.getElementById('statusForm-' + orderId);

            if (selectedValue === 'rejected') {
                // Open Modal for Reason
                openRejectModal(orderId, selectElement);
            } else {
                // Submit form for Accepted/Delivered
                form.submit();
            }
        }

        function openRejectModal(orderId, selectElement) {
            currentSelectElement = selectElement;
            document.getElementById('modalOrderId').value = orderId;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            if (currentSelectElement) {
                // Reset to default/previous value if cancelled
                currentSelectElement.value = 'placed';
            }
        }
    </script>
@endsection
