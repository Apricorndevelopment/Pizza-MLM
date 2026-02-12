@extends('layouts.layout')
@section('title', 'Manage Coupons')

@section('container')
    <div class="container mx-auto px-4 py-8">

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div class="flex items-center w-full md:w-auto">
                <div class="bg-purple-50 p-3 rounded-full mr-4 text-purple-600">
                    <i class="fas fa-ticket-alt fa-lg"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-800">Coupon Management</h3>
                    <p class="text-slate-500 text-sm">Create and manage coupon batches</p>
                </div>
            </div>

            {{-- Add New Button --}}
            <button onclick="openModal('create')"
                class="w-full md:w-auto bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md transition-all duration-300 flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Add New Batch
            </button>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success Message --}}
        @if (session('success'))
            <div
                class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center">
                <span class="font-medium flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </span>
                <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900"><i
                        class="fas fa-times"></i></button>
            </div>
        @endif

        {{-- TABLE CARD --}}
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h4 class="font-bold text-slate-700">All Batches</h4>
                <span class="bg-purple-100 text-purple-700 text-xs font-bold px-3 py-1 rounded-full">
                    {{ $coupons->count() }} Records
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                            <th class="px-3 py-3 font-semibold text-center">Sr. No.</th>
                            <th class="px-3 py-3 font-semibold">Quantity</th>
                            <th class="px-3 py-3 font-semibold text-right">Total Coupion Price</th>
                            <th class="px-3 py-3 font-semibold text-right">Price Per Coupon</th>
                            <th class="px-3 py-3 font-semibold text-right">Created At</th>
                            <th class="px-3 py-3 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($coupons as $item)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                {{-- ID --}}
                                <td class="px-3 py-3 text-center text-slate-500">
                                    #{{ $loop->iteration }}
                                </td>

                                {{-- Quantity --}}
                                <td class="px-3 py-3 font-bold text-slate-800">
                                    <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-sm">
                                        {{ $item->coupon_qyt }} Units
                                    </span>
                                </td>

                                {{-- Total Batch Amount --}}
                                <td class="px-3 py-3 text-right text-emerald-600 font-bold">
                                    {{-- Calculation: Qty * Price --}}
                                    ₹{{ number_format($item->coupon_qyt * $item->coupon_price) }}
                                </td>

                                {{-- Per Coupon Price --}}
                                <td class="px-3 py-3 text-right text-slate-600 font-medium">
                                    ₹{{ number_format($item->coupon_price) }}
                                </td>

                                {{-- Date --}}
                                <td class="px-3 py-3 text-right text-slate-500 text-sm">
                                    {{ $item->created_at->format('d M Y') }}
                                </td>

                                {{-- Actions --}}
                                <td class="px-3 py-3 text-right flex justify-end gap-2">
                                    <button onclick='openModal("edit", @json($item))'
                                        class="text-indigo-500 hover:text-indigo-700 p-2 rounded-full hover:bg-indigo-50 transition-colors"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('admin.coupons.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this batch?');"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-400 hover:text-red-600 p-2 rounded-full hover:bg-red-50 transition-colors"
                                            title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-ticket-alt text-3xl mb-2 text-slate-300"></i>
                                        <p>No coupon batches found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="couponModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">

            {{-- Overlay --}}
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal()">
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Modal Panel --}}
            <div
                class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg w-full relative z-10">

                {{-- Header --}}
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2" id="modalTitle"></h3>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                {{-- Form --}}
                <form id="couponForm" method="POST" class="p-6">
                    @csrf
                    <div id="methodField"></div>

                    <div class="space-y-6">

                        {{-- 1. Quantity Input --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Coupon Quantity (Units)</label>
                            <div class="relative">
                                <input type="number" name="coupon_qyt" id="coupon_qyt" required min="1"
                                    step="1" oninput="calculatePerUnit()"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all shadow-sm"
                                    placeholder="How many coupons? (e.g. 100)">
                                <div
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>

                        {{-- 2. Total Amount Input (For Calculation) --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Total Coupon Price (₹)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-bold">₹</span>
                                </div>
                                {{-- Not sent to DB directly --}}
                                <input type="number" id="total_amount" required min="1" step="1"
                                    oninput="calculatePerUnit()"
                                    class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all shadow-sm"
                                    placeholder="Total value of all coupons? (e.g. 5000)">
                            </div>
                            <p class="text-xs text-slate-500 mt-1">Enter the total budget (Integers only).</p>
                        </div>

                        {{-- 3. Calculated Result (Price Per Coupon) --}}
                        <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                            <label class="block text-xs font-bold text-indigo-600 uppercase tracking-wide mb-1">
                                Result: Price Per Coupon (Integer)
                            </label>
                            <div class="flex items-center justify-between">
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-indigo-600 font-bold">₹</span>
                                    </div>
                                    {{-- FIELD SENT TO DB: coupon_price --}}
                                    <input type="number" name="coupon_price" id="coupon_price" readonly required
                                        class="w-full pl-8 pr-4 py-2 bg-transparent border-0 text-xl font-bold text-indigo-700 placeholder-indigo-300 focus:ring-0"
                                        placeholder="0">
                                </div>
                                <span class="text-xs font-medium text-indigo-400 whitespace-nowrap">/ per user</span>
                            </div>
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium shadow-md transition-colors flex items-center gap-2">
                            <i class="fas fa-save"></i> <span id="btnText">Save Batch</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        function calculatePerUnit() {
            const qty = parseInt(document.getElementById('coupon_qyt').value) || 0;
            const total = parseInt(document.getElementById('total_amount').value) || 0;
            const priceField = document.getElementById('coupon_price');

            if (qty > 0 && total > 0) {
                // Calculate per unit price (Integer floor)
                const perUnit = Math.floor(total / qty);
                priceField.value = perUnit;
            } else {
                priceField.value = '';
            }
        }

        function openModal(type, data = null) {
            const modal = document.getElementById('couponModal');
            const form = document.getElementById('couponForm');
            const title = document.getElementById('modalTitle');
            const btnText = document.getElementById('btnText');
            const methodField = document.getElementById('methodField');

            modal.classList.remove('hidden');

            // Reset Fields
            document.getElementById('coupon_qyt').value = '';
            document.getElementById('total_amount').value = '';
            document.getElementById('coupon_price').value = '';

            if (type === 'create') {
                title.innerHTML = '<i class="fas fa-plus-circle"></i> Add New Batch';
                btnText.innerText = 'Save Batch';
                form.action = "{{ route('admin.coupons.store') }}";
                methodField.innerHTML = '';
            } else {
                title.innerHTML = '<i class="fas fa-edit"></i> Edit Batch';
                btnText.innerText = 'Update Batch';
                form.action = `/admin/coupons/update/${data.id}`;
                methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';

                // Populate Data
                // 1. Qty
                let qty = data.coupon_qyt !== undefined ? parseInt(data.coupon_qyt) : parseInt(data.coupon_qty);
                document.getElementById('coupon_qyt').value = qty;

                // 2. Price (from DB column coupon_price)
                let price = parseInt(data.coupon_price);
                document.getElementById('coupon_price').value = price;

                // 3. Calculate Total for the Input Field (Qty * Price)
                if (qty && price) {
                    document.getElementById('total_amount').value = (qty * price);
                }
            }
        }

        function closeModal() {
            document.getElementById('couponModal').classList.add('hidden');
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") closeModal();
        });
    </script>
@endsection
