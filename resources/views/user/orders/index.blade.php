@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')
@section('title', 'My Orders')

@section('container')
    {{-- Custom Scrollbar for a premium feel --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <div class="min-h-screen bg-gray-50/50 py-6">
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
                    <div
                        class="flex items-center bg-white rounded-2xl shadow-sm border border-gray-200 px-4 py-3 focus-within:ring-4 focus-within:ring-[#ECFDF5] focus-within:border-teal-500 transition-all duration-300 w-full md:w-96">
                        <i class="bi bi-search text-gray-400 group-focus-within:text-teal-500 transition-colors"></i>
                        <input type="text" id="orderSearchInput" onkeyup="filterOrders()"
                            class="w-full border-none bg-transparent focus:ring-0 text-sm ml-3 placeholder-gray-400 text-gray-700 font-medium h-full outline-none"
                            placeholder="Filter by Order ID, Status, or Amount...">
                    </div>
                </div>
            </div>

            @if ($orders->count() > 0)
                <div class="space-y-4" id="ordersListContainer">
                    @foreach ($orders as $order)
                        {{-- ORDER CARD --}}
                        <div class="order-card group bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:border-[#ECFDF5] transition-all duration-300 overflow-hidden relative mb-4"
                            data-search-term="#{{ $order->order_id }} {{ $order->status }} {{ $order->total_amount }}">

                            <div
                                class="absolute top-0 left-0 w-1 h-full bg-[#ECFDF5] group-hover:bg-teal-400 transition-all duration-300">
                            </div>

                            <div class="p-3 lg:p-4">
                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">

                                    {{-- Section 1: ID & Status --}}
                                    <div class="lg:col-span-4">
                                        <div class="mb-2">
                                            <span
                                                class="bg-[#ECFDF5] text-teal-800 font-semibold px-3 py-1.5 rounded-lg text-sm tracking-wide border border-teal-100/50">
                                                #{{ $order->order_id }}
                                            </span>
                                        </div>

                                        @php
                                            $statusConfig = match ($order->status) {
                                                'placed' => [
                                                    'bg' => 'bg-amber-50',
                                                    'text' => 'text-amber-700',
                                                    'border' => 'border-amber-100',
                                                    'icon' => 'bi-hourglass-split',
                                                ],
                                                'accepted' => [
                                                    'bg' => 'bg-blue-50',
                                                    'text' => 'text-blue-700',
                                                    'border' => 'border-blue-100',
                                                    'icon' => 'bi-check2-circle',
                                                ],
                                                'delivered' => [
                                                    'bg' => 'bg-[#ECFDF5]',
                                                    'text' => 'text-teal-700',
                                                    'border' => 'border-teal-100',
                                                    'icon' => 'bi-box-seam-fill',
                                                ],
                                                'rejected' => [
                                                    'bg' => 'bg-red-50',
                                                    'text' => 'text-red-700',
                                                    'border' => 'border-red-100',
                                                    'icon' => 'bi-x-circle-fill',
                                                ],
                                                default => [
                                                    'bg' => 'bg-gray-50',
                                                    'text' => 'text-gray-600',
                                                    'border' => 'border-gray-100',
                                                    'icon' => 'bi-circle',
                                                ],
                                            };
                                        @endphp
                                        <div class="mt-3 flex items-center gap-1">
                                            <span
                                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold border {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }} uppercase tracking-wider">
                                                <i class="bi {{ $statusConfig['icon'] }}"></i> {{ $order->status }}
                                            </span>
                                             <span class="text-gray-400 text-xs font-medium flex items-center gap-1">
                                                <i class="bi bi-clock"></i> {{ $order->created_at->format('M d • h:i A') }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Section 2: Payment Info --}}
                                    <div class="lg:col-span-4 lg:border-l lg:border-r border-gray-50 lg:px-8">
                                        <div class="flex justify-between items-end mb-1">
                                            <span
                                                class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Total
                                                Amount</span>
                                        </div>
                                        <div class="flex items-baseline gap-1">
                                            <span
                                                class="text-1xl font-black text-gray-800 tracking-tight">₹{{ number_format($order->total_amount, 2) }}</span>
                                        </div>
                                        <div class="flex flex-wrap gap-2 mt-3">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded bg-gray-50 border border-gray-100 text-[10px] text-gray-600 font-semibold">
                                                Main: ₹{{ $order->wallet1_deducted }}
                                            </span>
                                            @if ($order->wallet2_deducted > 0)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded bg-[#ECFDF5] border border-teal-100 text-[10px] text-teal-700 font-semibold">
                                                    Wallet 2: -₹{{ $order->wallet2_deducted }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Section 3: Actions --}}
                                    <div class="lg:col-span-4 flex justify-end gap-3">

                                        {{-- INVOICE BUTTON (Visible only if status is 'delivered') --}}
                                        @if ($order->status == 'delivered')
                                            <button onclick="openInvoiceModal('{{ $order->id }}')"
                                                class="w-full lg:w-auto flex items-center justify-center gap-2 px-3 py-1 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-bold rounded-xl border border-indigo-100 transition-all duration-200">
                                                <i class="bi bi-receipt text-xs"></i> <span class="text-sm">Invoice</span>
                                            </button>
                                        @endif

                                        {{-- VIEW ITEMS BUTTON (Always Visible) --}}
                                        <button onclick="toggleDetails('details-{{ $order->id }}')"
                                            class="w-full lg:w-auto group/btn relative flex items-center justify-center gap-2 px-4 py-2 bg-white text-gray-700 hover:text-teal-700 font-bold rounded-xl border border-gray-200 hover:border-teal-200 hover:bg-[#ECFDF5] transition-all duration-200">
                                            <span class="text-sm">View Items</span>
                                            <i id="icon-details-{{ $order->id }}"
                                                class="bi bi-chevron-down transition-transform duration-300 text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- COLLAPSIBLE DETAILS --}}
                            <div id="details-{{ $order->id }}"
                                class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out opacity-0 bg-[#FAFAFA]">
                                <div class="p-3 sm:p-4 lg:p-5 border-t border-gray-100">
                                    <h6
                                        class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <i class="bi bi-basket3-fill"></i> Order Summary
                                    </h6>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @foreach ($order->items as $item)
                                            <div
                                                class="flex items-start p-3 bg-white rounded-xl border border-gray-100 shadow-sm">
                                                <div
                                                    class="h-14 w-14 rounded-lg bg-gray-50 border border-gray-100 overflow-hidden flex-shrink-0 relative">
                                                    @if ($item->product_image)
                                                        <img src="{{ asset($item->product_image) }}"
                                                            class="h-full w-full object-cover">
                                                    @else
                                                        <div
                                                            class="h-full w-full flex items-center justify-center text-gray-300">
                                                            <i class="bi bi-image"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4 flex-grow">
                                                    <h4 class="text-sm font-bold text-gray-800 line-clamp-1">
                                                        {{ $item->product_name }}</h4>
                                                    <div class="flex justify-between items-end mt-1">
                                                        <span
                                                            class="text-xs text-gray-500 font-medium bg-gray-100 px-2 py-0.5 rounded">Qty:
                                                            {{ $item->quantity }}</span>
                                                        <span
                                                            class="text-sm font-bold text-teal-700">₹{{ $item->subtotal }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>


                            {{-- HIDDEN INVOICE TEMPLATE FOR THIS ORDER (Used by JS) --}}
                            <div id="invoice-data-{{ $order->id }}" class="hidden">
                                <div class="p-8 bg-white" style="font-family: sans-serif; color: #333;">

                                    {{-- Invoice Header --}}
                                    <div class="flex justify-between items-start mb-8 pb-8 border-b border-gray-200">
                                        <div>
                                            <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-wide">Bill
                                            </h2>
                                            <p class="text-sm text-gray-500 mt-1">#{{ $order->order_id }}</p>
                                        </div>

                                        {{-- SELLER INFORMATION LOGIC --}}
                                        <div class="text-right">
                                            @php
                                                // Get the first item to determine the seller
                                                $firstItem = $order->items->first();
                                            @endphp

                                            <h3 class="text-lg font-bold text-teal-700">
                                                @if ($firstItem && $firstItem->vendor_id)
                                                    {{-- CASE 1: Vendor Order --}}
                                                    {{ $firstItem->vendor->company_name }}

                                                    @if (!empty($firstItem->vendor->vendor_name))
                                                        <span class="block text-xs font-medium text-gray-500">
                                                            {{ $firstItem->vendor->user->email }}
                                                        </span>
                                                    @else
                                                        <span class="text-xs text-gray-500">(Vendor)</span>
                                                    @endif
                                                @else
                                                    {{-- CASE 2: Admin Order (vendor_id is null) --}}
                                                    Ziddi Group
                                                    <span class="text-xs text-gray-500">(Official Store)</span>
                                                @endif
                                            </h3>

                                            {{-- Contact Info --}}
                                            <p class="text-xs text-gray-500 mt-1">
                                                @if ($firstItem && $firstItem->vendor_id && $firstItem->vendor)
                                                    {{ $firstItem->vendor->email }}
                                                @else
                                                    support@example.com
                                                @endif
                                            </p>

                                            {{-- Address Info --}}
                                            <p class="text-xs text-gray-500">
                                                @if ($firstItem && $firstItem->vendor_id && $firstItem->vendor)
                                                    {{ $firstItem->vendor->company_address ?? 'Address not provided' }},
                                                    {{ $firstItem->vendor->company_city ?? 'City not provided' }},
                                                    {{ $firstItem->vendor->company_state ?? 'State not provided' }}
                                                @else
                                                    123 Main Street, New Delhi, India
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Bill To / Date Section (Remains mostly same) --}}
                                    <div class="flex justify-between mb-8">
                                        <div>
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Billed
                                                To</p>
                                            <p class="font-bold text-gray-800">{{ Auth::user()->name }}</p>
                                            <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                                            <p class="text-sm text-gray-600">{{ Auth::user()->phone ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-600 max-w-xs mt-1">
                                                {{ Auth::user()->address ?? 'Address not provided' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Date
                                            </p>
                                            <p class="font-bold text-gray-800">{{ $order->created_at->format('d M, Y') }}
                                            </p>
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1 mt-3">
                                                Status</p>
                                            <p
                                                class="text-sm font-bold uppercase {{ $order->status == 'delivered' ? 'text-teal-600' : 'text-gray-600' }}">
                                                {{ $order->status }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Table --}}
                                    <table class="w-full mb-8" style="width: 100%; border-collapse: collapse;">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">
                                                    Item</th>
                                                <th class="text-center py-3 px-4 text-xs font-bold text-gray-500 uppercase">
                                                    Qty</th>
                                                <th class="text-right py-3 px-4 text-xs font-bold text-gray-500 uppercase">
                                                    Price</th>
                                                <th class="text-right py-3 px-4 text-xs font-bold text-gray-500 uppercase">
                                                    Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach ($order->items as $item)
                                                <tr>
                                                    <td class="py-3 px-4 text-sm font-medium text-gray-800">
                                                        {{ $item->product_name }}</td>
                                                    <td class="py-3 px-4 text-sm text-gray-600 text-center">
                                                        {{ $item->quantity }}</td>
                                                    <td class="py-3 px-4 text-sm text-gray-600 text-right">
                                                        ₹{{ number_format($item->price, 2) }}</td>
                                                    <td class="py-3 px-4 text-sm font-bold text-gray-800 text-right">
                                                        ₹{{ number_format($item->subtotal, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    {{-- Totals --}}
                                    <div class="flex justify-end">
                                        <div class="w-1/2">
                                            <div class="flex justify-between py-2 border-b border-gray-100">
                                                <span class="text-sm text-gray-600">Subtotal</span>
                                                <span
                                                    class="text-sm font-bold text-gray-800">₹{{ number_format($order->total_amount, 2) }}</span>
                                            </div>
                                            <div class="flex justify-between py-2 border-b border-gray-100">
                                                <span class="text-sm text-gray-600">Personal Wallet</span>
                                                <span class="text-sm text-gray-800">
                                                    ₹{{ number_format($order->wallet1_deducted, 2) }}</span>
                                            </div>
                                            @if ($order->wallet2_deducted > 0)
                                                <div class="flex justify-between py-2 border-b border-gray-100">
                                                    <span class="text-sm text-gray-600">Paid via Second Wallet</span>
                                                    <span class="text-sm text-gray-800">
                                                        ₹{{ number_format($order->wallet2_deducted, 2) }}</span>
                                                </div>
                                            @endif
                                            <div class="flex justify-between py-2 border-b border-gray-100">
                                                <span class="text-sm text-gray-600">Coupon Off</span>
                                                <span class="text-sm text-gray-800">-
                                                    ₹{{ number_format($order->coupons_used * 10) }}</span>
                                            </div>
                                            <div class="flex justify-between py-3">
                                                <span class="text-base font-bold text-gray-800">Total Paid</span>
                                                <span
                                                    class="text-xl font-black text-teal-700">₹{{ number_format($order->wallet1_deducted + $order->wallet2_deducted, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Footer --}}
                                    <div class="mt-12 pt-8 border-t border-gray-200 text-center">
                                        <p class="text-sm font-bold text-gray-800">Thank you for your business!</p>
                                        <p class="text-xs text-gray-500 mt-1">For support, contact:
                                            @if ($order->vendor)
                                                {{ $order->vendor->email }}
                                            @elseif($order->admin)
                                                {{ $order->admin->email }}
                                            @else
                                                support@example.com
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                    {{-- INVOICE MODAL --}}
                    <div id="invoiceModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title"
                        role="dialog" aria-modal="true">

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
                    </div>

                    {{-- Logic for Modal & PDF Download --}}
                    <script>
                        // 1. OPEN MODAL & INJECT CONTENT
                        function openInvoiceModal(orderId) {
                            // Get the hidden invoice content for this specific order
                            const contentSource = document.getElementById('invoice-data-' + orderId);
                            const modalTarget = document.getElementById('modalInvoiceContent');

                            if (contentSource && modalTarget) {
                                // Copy HTML to modal
                                modalTarget.innerHTML = contentSource.innerHTML;

                                // Show Modal
                                const modal = document.getElementById('invoiceModal');
                                modal.classList.remove('hidden');

                                // Optional: Prevent background scroll
                                document.body.style.overflow = 'hidden';
                            }
                        }

                        // 2. CLOSE MODAL
                        function closeInvoiceModal() {
                            const modal = document.getElementById('invoiceModal');
                            modal.classList.add('hidden');
                            document.body.style.overflow = 'auto';
                        }

                        // 3. DOWNLOAD PDF FUNCTION
                        function downloadPDF() {
                            const element = document.getElementById('modalInvoiceContent');

                            // Configuration for html2pdf
                            const opt = {
                                margin: [10, 10, 10, 10], // top, left, bottom, right
                                filename: 'Invoice_Details.pdf',
                                image: {
                                    type: 'jpeg',
                                    quality: 0.98
                                },
                                html2canvas: {
                                    scale: 2,
                                    useCORS: true
                                }, // scale 2 for better quality
                                jsPDF: {
                                    unit: 'mm',
                                    format: 'a4',
                                    orientation: 'portrait'
                                }
                            };

                            // Trigger Download
                            // Note: 'element.firstElementChild' grabs the wrapper div inside the modal target to avoid extra padding issues
                            html2pdf().set(opt).from(element.firstElementChild).save();
                        }

                        // Existing Detail Toggle & Search Scripts...
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
                                if (pagination) pagination.style.display = 'none';
                            } else {
                                noResults.classList.add('hidden');
                                noResults.classList.remove('flex');
                                if (pagination) pagination.style.display = '';
                            }
                        }
                    </script>

                </div>

                {{-- No Results (Client Side) --}}
                <div id="noResultsMessage"
                    class="hidden flex-col items-center justify-center py-20 bg-white rounded-3xl border border-dashed border-gray-200">
                    <div class="bg-[#ECFDF5] p-5 rounded-full mb-4 animate-pulse">
                        <i class="bi bi-search text-3xl text-teal-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">No Orders Found</h3>
                    <p class="text-gray-500 text-sm mt-1">Try adjusting your filters.</p>
                    <button onclick="document.getElementById('orderSearchInput').value=''; filterOrders()"
                        class="mt-4 text-teal-600 font-bold text-sm hover:underline">Clear Search</button>
                </div>

                {{-- Pagination --}}
                <div class="mt-10" id="paginationContainer">
                    {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @else
                {{-- Empty State (Server Side) --}}
                <div
                    class="flex flex-col items-center justify-center py-24 bg-white rounded-3xl border border-dashed border-gray-200 shadow-sm">
                    <div class="bg-[#ECFDF5] p-8 rounded-full mb-6">
                        <i class="bi bi-bag-heart text-5xl text-teal-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">No Orders Yet</h3>
                    <p class="text-gray-500 mt-2 max-w-xs text-center text-sm">Looks like you haven't placed an order yet.
                        Discover our best products today!</p>
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
                if (pagination) pagination.style.display = 'none';
            } else {
                noResults.classList.add('hidden');
                noResults.classList.remove('flex');
                if (pagination) pagination.style.display = '';
            }
        }
    </script>
@endsection
