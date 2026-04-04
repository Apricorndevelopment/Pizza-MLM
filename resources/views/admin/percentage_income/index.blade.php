@extends('layouts.layout')
@section('title', 'Percentage Income Settings')

@section('container')
    <div class="mx-auto py-2">

        {{-- Page Header --}}
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <div class="bg-indigo-50 p-3 rounded-full mr-4 text-indigo-600">
                    <i class="fas fa-wallet fa-lg"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-800">Income Distribution</h3>
                    <p class="text-slate-500 text-sm">Manage percentage splits for wallets</p>
                </div>
            </div>
            {{-- No Add Button --}}
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div
                class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-xl"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900"><i
                        class="fas fa-times"></i></button>
            </div>
        @endif

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

        {{-- TABLE CARD --}}
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h4 class="font-bold text-slate-700">Configuration List</h4>
                <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">
                    {{ $incomes->count() }} Records
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                            <th class="px-1.5 py-2.5 font-semibold">Direct Income</th>
                            <th class="px-1.5 py-2.5 font-semibold">Bonus Income</th>
                            <th class="px-1.5 py-2.5 font-semibold">Vendor Income</th>
                            <th class="px-1.5 py-2.5 font-semibold">Cashback Income</th>
                            <th class="px-1.5 py-2.5 font-semibold">Personal Wallet</th>
                            <th class="px-1.5 py-2.5 font-semibold">Second Wallet</th>
                            <th class="px-1.5 py-2.5 font-semibold">TDS Charge</th>
                            <th class="px-1.5 py-2.5 font-semibold">Admin Charge</th>
                            <th class="px-1.5 py-2.5 font-semibold">Vendor Withdrawal Charge</th>
                            <th class="px-1.5 py-2.5 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($incomes as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                {{-- Data Columns --}}
                                <td class="px-1.5 py-2.5 text-slate-700 font-bold">{{ $item->direct_income }}%</td>
                                <td class="px-1.5 py-2.5 text-slate-700 font-bold">{{ $item->bonus_income }}%</td>
                                <td class="px-1.5 py-2.5 text-slate-700 font-bold">{{ $item->vendor_income }}%</td>
                                <td class="px-1.5 py-2.5 text-slate-700 font-bold">{{ $item->cashback_income }}%</td>
                                <td class="px-1.5 py-2.5 text-slate-700 font-bold">{{ $item->personal_wallet }}%</td>
                                <td class="px-1.5 py-2.5 text-slate-700 font-bold">{{ $item->second_wallet }}%</td>
                                <td class="px-1.5 py-2.5 text-slate-700 font-bold">{{ $item->tds_charge }}%</td>
                                <td class="px-1.5 py-2.5 text-slate-700 font-bold">{{ $item->admin_charge }}%</td>
                                <td class="px-1.5 py-2.5 text-slate-700 font-bold">{{ $item->vendor_withdraw_charge }}%</td>

                                {{-- Actions (Only Edit) --}}
                                <td class="px-1.5 py-2.5 text-right">
                                    <button onclick='openEditModal(@json($item))'
                                        class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-md text-sm font-medium transition-colors">
                                        <i class="fas fa-edit mr-1.5"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <p>No configuration found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL (Perfectly Centered) --}}
    <div id="incomeModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">

        {{-- Flex Container for Centering --}}
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

            {{-- Background Overlay --}}
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal()">
            </div>

            {{-- Vertical Centering Trick --}}
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Modal Panel --}}
            <div
                class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full relative z-10">

                <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fas fa-edit"></i> Edit Configuration
                    </h3>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form id="incomeForm" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Direct Income --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Direct Income</label>
                            <div class="relative">
                                <input type="number" step="0.1" name="direct_income" id="direct_income" required
                                    class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                                <span class="absolute right-3 top-2 text-gray-400 font-bold">%</span>
                            </div>
                        </div>
                        {{-- Bonus Income --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bonus Income</label>
                            <div class="relative">
                                <input type="number" step="0.1" name="bonus_income" id="bonus_income" required
                                    class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                                <span class="absolute right-3 top-2 text-gray-400 font-bold">%</span>
                            </div>
                        </div>
                        {{-- Vendor Income --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Vendor Income</label>
                            <div class="relative">
                                <input type="number" step="0.1" name="vendor_income" id="vendor_income" required
                                    class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                                <span class="absolute right-3 top-2 text-gray-400 font-bold">%</span>
                            </div>
                        </div>

                        {{-- Cashback Income --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cashback Income</label>
                            <div class="relative">
                                <input type="number" step="0.1" name="cashback_income" id="cashback_income" required
                                    class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                                <span class="absolute right-3 top-2 text-gray-400 font-bold">%</span>
                            </div>
                        </div>

                        {{-- Personal Wallet --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Personal Wallet</label>
                            <div class="relative">
                                <input type="number" step="0.1" name="personal_wallet" id="personal_wallet"
                                    required
                                    class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                                <span class="absolute right-3 top-2 text-gray-400 font-bold">%</span>
                            </div>
                        </div>

                        {{-- Second Wallet --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Second Wallet</label>
                            <div class="relative">
                                <input type="number" step="0.1" name="second_wallet" id="second_wallet" required
                                    class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                                <span class="absolute right-3 top-2 text-gray-400 font-bold">%</span>
                            </div>
                        </div>
                        <div class="col-span-2 text-lg font-bold text-gray-700 mt-1">
                            Withdraw Charge
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Admin Charge</label>
                            <div class="relative">
                                <input type="number" step="0.1" name="admin_charge" id="admin_charge" required
                                    class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                                <span class="absolute right-3 top-2 text-gray-400 font-bold">%</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">TDS Charge</label>
                            <div class="relative">
                                <input type="number" step="0.1" name="tds_charge" id="tds_charge" required
                                    class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                                <span class="absolute right-3 top-2 text-gray-400 font-bold">%</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Vendor Withdraw Charge</label>
                            <div class="relative">
                                <input type="number" step="0.1" name="vendor_withdraw_charge"
                                    id="vendor_withdraw_charge" required
                                    class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                                <span class="absolute right-3 top-2 text-gray-400 font-bold">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium shadow-md transition-colors flex items-center gap-2">
                            <i class="fas fa-save"></i> Update Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        function openEditModal(data) {
            const modal = document.getElementById('incomeModal');
            const form = document.getElementById('incomeForm');

            // Set Form Action Dynamically
            form.action = `/admin/percentage-income/update/${data.id}`;

            // Fill inputs
            document.getElementById('direct_income').value = data.direct_income;
            document.getElementById('bonus_income').value = data.bonus_income;
            document.getElementById('vendor_income').value = data.vendor_income;
            document.getElementById('cashback_income').value = data.cashback_income;
            document.getElementById('personal_wallet').value = data.personal_wallet;
            document.getElementById('second_wallet').value = data.second_wallet;
            document.getElementById('admin_charge').value = data.admin_charge;
            document.getElementById('tds_charge').value = data.tds_charge;
            document.getElementById('vendor_withdraw_charge').value = data.vendor_withdraw_charge;

            // Show Modal
            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('incomeModal').classList.add('hidden');
        }

        // Close on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") closeModal();
        });
    </script>
@endsection
