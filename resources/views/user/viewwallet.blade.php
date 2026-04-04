@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')
@section('title', 'Manage Wallet')
@section('container')

    <div class="min-h-screen bg-slate-50 pb-8 font-sans text-slate-600">
        <div class="max-w-7xl mx-auto px-4 py-3 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 flex items-center shadow-sm"
                    role="alert">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    </div>
                    <button type="button" class="ml-auto pl-3 text-emerald-500 hover:text-emerald-800 transition"
                        onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <div class="flex justify-center mb-6">
                <div class="bg-white p-1.5 rounded-xl shadow-sm border border-slate-200 inline-flex">
                    <button onclick="switchTab('wallet1')" id="tab-btn-wallet1"
                        class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 flex items-center gap-2 bg-emerald-50 text-emerald-700 shadow-sm ring-1 ring-emerald-200">
                        <i class="fas fa-coins"></i> Main Wallet
                    </button>
                    <button onclick="switchTab('wallet2')" id="tab-btn-wallet2"
                        class="px-6 py-2.5 rounded-lg text-sm font-bold text-slate-500 hover:text-slate-800 transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-shopping-bag"></i> Second Wallet
                    </button>
                </div>
            </div>

            <div id="wallet-content">

                <div id="wallet1-pane" class="block animate-fade-in">

                    <div class="flex justify-center w-full gap-6 mb-6 flex-col lg:flex-row">
                        <div
                            class="lg:col-span-2 w-full bg-white rounded-2xl shadow-sm border border-slate-200 px-4 py-3 flex flex-col justify-center">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Available
                                        Balance</p>
                                    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">
                                        {{ number_format($wallet1) }} <span
                                            class="text-2xl text-slate-400 font-medium">INR</span>
                                    </h2>
                                </div>
                                <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                                    <i class="fas fa-university text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br  w-full from-[#ECFDF5] to-emerald-100 rounded-2xl shadow-sm border border-emerald-200 px-4 py-3 flex flex-col justify-center text-center">
                            <h3 class="text-lg font-bold text-emerald-900 mb-1">Ready to payout?</h3>
                            <p class="text-sm text-emerald-700/80 mb-4">Transfer funds to your linked bank/UPI account
                                instantly.</p>
                            @if ($withdrawalStatus)
                                {{-- Is line ko replace karein --}}
                                <button type="button" onclick="toggleModal('withdrawModal')" class="btn btn-primary">
                                    <i class="fas fa-money-bill-wave me-2"></i> Withdraw Money
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary" disabled
                                    title="Withdrawals are currently disabled by Admin">
                                    <i class="fas fa-lock me-2"></i> Withdrawals Paused
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div
                            class="px-6 py-3 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50/50">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <i class="fas fa-history text-slate-400"></i> Withdrawal Requests
                            </h3>
                            <span
                                class="px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-bold text-slate-500">
                                {{ $withdrawals->total() }} Records
                            </span>
                        </div>

                        @if ($withdrawals->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr
                                            class="bg-slate-50 text-xs uppercase text-slate-500 font-bold border-b border-slate-200">
                                            <th class="px-3.5 py-3">Date</th>
                                            <th class="px-3.5 py-3">Amount</th>
                                            <th class="px-3.5 py-3">Net Payout</th>
                                            <th class="px-3.5 py-3">Charge</th>
                                            <th class="px-3.5 py-3">Method</th>
                                            <th class="px-3.5 py-3 text-center">Status</th>
                                            <th class="px-3.5 py-3"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach ($withdrawals as $withdrawal)
                                            <tr class="hover:bg-slate-50 transition-colors group">
                                                <td class="px-3.5 py-3 text-sm font-medium text-slate-600">
                                                    {{ $withdrawal->created_at->format('d M Y') }}
                                                </td>
                                                <td class="px-3.5 py-3 text-sm font-bold text-slate-800">
                                                    ₹{{ number_format($withdrawal->total_amount, 2) }}
                                                </td>
                                                <td class="px-3.5 py-3 text-sm font-bold text-emerald-600">
                                                    ₹{{ number_format($withdrawal->credited_amount, 2) }}
                                                </td>
                                                <td class="px-3.5 py-3 text-sm font-semibold flex flex-col">
                                                   <span> Admin Charge - {{ number_format($withdrawal->admin_charge/$withdrawal->total_amount*100, 2) }}% </span>
                                                   <span> TDS Charge - {{ number_format($withdrawal->tds_charge/$withdrawal->total_amount*100, 2) }}% </span>
                                                </td>
                                                <td class="px-3.5 py-3">
                                                    <span
                                                        class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-bold text-slate-600 uppercase">
                                                        {{ ucfirst($withdrawal->payment_method) }}
                                                    </span>
                                                </td>
                                                <td class="px-3.5 py-3 text-center">
                                                    @if ($withdrawal->status === 'pending')
                                                        <span
                                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200">
                                                            <i class="fas fa-clock"></i> Pending
                                                        </span>
                                                    @elseif($withdrawal->status === 'approved')
                                                        <span
                                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                                            <i class="fas fa-check-circle"></i> Approved
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-200">
                                                            <i class="fas fa-times-circle"></i> Rejected
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-3.5 py-3 text-right">
                                                    <button onclick="toggleDetails('details-{{ $withdrawal->id }}')"
                                                        class="text-slate-400 hover:text-emerald-600 transition">
                                                        <i class="fas fa-chevron-down"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="details-{{ $withdrawal->id }}"
                                                class="hidden bg-slate-50 border-b border-slate-200">
                                                <td colspan="7" class="px-3.5 py-3">
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                                        <div class="col-span-3">
                                                            <h6
                                                                class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">
                                                                Transaction Details</h6>
                                                        </div>
                                                        @if ($withdrawal->payment_method === 'bank')
                                                            <div><span class="text-slate-500 block text-xs">Bank
                                                                    Name</span><span
                                                                    class="font-bold text-slate-700">{{ $withdrawal->user->bank_name }}</span>
                                                            </div>
                                                            <div><span class="text-slate-500 block text-xs">Account
                                                                    No</span><span
                                                                    class="font-bold text-slate-700 font-mono">{{ $withdrawal->user->account_no }}</span>
                                                            </div>
                                                            <div><span class="text-slate-500 block text-xs">IFSC
                                                                    Code</span><span
                                                                    class="font-bold text-slate-700 font-mono">{{ $withdrawal->user->ifsc_code }}</span>
                                                            </div>
                                                        @else
                                                            <div class="col-span-3"><span
                                                                    class="text-slate-500 block text-xs">UPI ID</span><span
                                                                    class="font-bold text-slate-700 font-mono">{{ $withdrawal->user->upi_id }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-6 py-3 border-t border-slate-100">
                                {{ $withdrawals->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mt-8">
                        <div class="px-6 py-3 border-b border-slate-100">
                            <h3 class="text-lg font-bold text-slate-800">Main Wallet Transactions</h3>
                        </div>

                        <div class="p-6 bg-slate-50 border-b border-slate-200">
                            <form method="GET" action="{{ route('user.viewwallet') }}"
                                class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Type</label>
                                    <select name="wallet1_type"
                                        class="w-full rounded-lg border-slate-200 text-sm focus:ring-emerald-500 focus:border-emerald-500 p-2">
                                        <option value="">All Types</option>
                                        <option value="credit"
                                            {{ request('wallet1_type') == 'credit' ? 'selected' : '' }}>
                                            Credit (+)</option>
                                        <option value="debit" {{ request('wallet1_type') == 'debit' ? 'selected' : '' }}>
                                            Debit (-)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">From Date</label>
                                    <input type="date" name="wallet1_start_date"
                                        value="{{ request('wallet1_start_date') }}"
                                        class="w-full rounded-lg border-slate-200 text-sm focus:ring-emerald-500 focus:border-emerald-500 p-2">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">To Date</label>
                                    <input type="date" name="wallet1_end_date"
                                        value="{{ request('wallet1_end_date') }}"
                                        class="w-full rounded-lg border-slate-200 text-sm focus:ring-emerald-500 focus:border-emerald-500 p-2">
                                </div>
                                <div class="flex items-end gap-2">
                                    <button type="submit"
                                        class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded-lg text-sm font-bold transition">Apply</button>
                                    <a href="{{ route('user.viewwallet') }}"
                                        class="px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-500 hover:bg-slate-100 transition"><i
                                            class="fas fa-times"></i></a>
                                </div>
                            </form>
                        </div>

                        <div class="overflow-x-auto">
                            @if ($wallet1Transactions->count() > 0)
                                <table class="w-full text-left border-collapse">
                                    <thead
                                        class="bg-slate-50 text-xs uppercase text-slate-500 font-bold border-b border-slate-200">
                                        <tr>
                                            <th class="px-3.5 py-3">Date</th>
                                            <th class="px-3.5 py-3">Description</th>
                                            <th class="px-3.5 py-3 text-right">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach ($wallet1Transactions as $transaction)
                                            <tr class="hover:bg-slate-50 transition">
                                                <td class="px-3.5 py-3 text-sm text-slate-500">
                                                    {{ $transaction->created_at->format('d M Y') }}</td>
                                                <td class="px-3.5 py-3 text-sm font-medium text-slate-700">
                                                    {{ $transaction->notes ?? 'Transaction' }}</td>
                                                <td class="px-3.5 py-3 text-right">
                                                    <span
                                                        class="text-sm font-bold {{ $transaction->wallet1 >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                                        {{ $transaction->wallet1 >= 0 ? '+' : '' }}{{ $transaction->wallet1 }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="px-6 py-3 border-t border-slate-100">
                                    {{ $wallet1Transactions->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
                                </div>
                            @else
                                <div class="py-10 text-center text-slate-400">
                                    <i class="fas fa-exchange-alt text-4xl mb-3 opacity-30"></i>
                                    <p>No transactions found matching your filters.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div id="wallet2-pane" class="hidden animate-fade-in">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                        <div
                            class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-sky-200 p-6 flex flex-col justify-center relative overflow-hidden">
                            <div class="absolute right-0 top-0 w-32 h-32 bg-sky-50 rounded-full -mr-8 -mt-8 z-0"></div>
                            <div class="relative z-10 flex justify-between items-center">
                                <div>
                                    <p class="text-xs font-bold text-sky-500 uppercase tracking-wider mb-1">Shopping
                                        Credits</p>
                                    <h2 class="text-4xl font-extrabold text-slate-800">{{ number_format($wallet2) }}</h2>
                                </div>
                                <div class="p-3 bg-sky-50 rounded-xl text-sky-500">
                                    <i class="fas fa-gem text-2xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-sky-50 rounded-2xl border border-sky-100 p-6 flex items-center">
                            <div>
                                <div class="flex items-center gap-2 text-sky-700 font-bold mb-2">
                                    <i class="fas fa-info-circle"></i> Usage Info
                                </div>
                                <p class="text-sm text-sky-800 leading-relaxed">This wallet balance can be used exclusively
                                    for purchasing products on the platform.</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-3 border-b border-slate-100">
                            <h3 class="text-lg font-bold text-slate-800">Second Wallet History</h3>
                        </div>
                        @if ($wallet2Transactions->count() > 0)
                            <table class="w-full text-left border-collapse">
                                <thead
                                    class="bg-slate-50 text-xs uppercase text-slate-500 font-bold border-b border-slate-200">
                                    <tr>
                                        <th class="px-3.5 py-3">Date</th>
                                        <th class="px-3.5 py-3">Description</th>
                                        <th class="px-3.5 py-3 text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($wallet2Transactions as $transaction)
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="px-3.5 py-3 text-sm text-slate-500">
                                                {{ $transaction->created_at->format('d M Y') }}</td>
                                            <td class="px-3.5 py-3 text-sm font-medium text-slate-700">
                                                {{ $transaction->notes ?? 'N/A' }}</td>
                                            <td class="px-3.5 py-3 text-right">
                                                <span
                                                    class="text-sm font-bold {{ $transaction->wallet2 >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                                    {{ $transaction->wallet2 >= 0 ? '+' : '' }}{{ $transaction->wallet2 }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="py-10 text-center text-slate-400">
                                <i class="fas fa-ghost text-4xl mb-3 opacity-30"></i>
                                <p>No wallet transactions found.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <div id="withdrawModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                onclick="toggleModal('withdrawModal')"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">

                    <div
                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">

                        <div class="bg-white px-3 pb-4 pt-3 sm:p-5 sm:pb-3">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-xl font-bold text-slate-800" id="modal-title">Withdraw Funds</h3>
                                <button type="button" class="text-slate-400 hover:text-slate-600 transition"
                                    onclick="toggleModal('withdrawModal')">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>

                            <form id="withdrawForm" action="{{ route('user.withdraw.wallet1') }}" method="POST">
                                @csrf

                                <div class="bg-[#ECFDF5] border border-emerald-100 rounded-xl p-4 text-center mb-4">
                                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Available
                                        Balance</span>
                                    <div class="text-3xl font-extrabold text-emerald-800 mt-1">
                                        ₹{{ number_format($wallet1) }}</div>
                                </div>

                                <div class="mb-3">
                                    <label for="withdrawAmount"
                                        class="block text-xs font-bold text-slate-500 uppercase mb-2">Enter Amount</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-slate-500 sm:text-sm font-bold">₹</span>
                                        </div>
                                        <input type="number" name="amount" id="withdrawAmount"
                                            class="block w-full rounded-xl border-slate-300 pl-8 py-3 text-slate-900 placeholder-slate-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm font-bold"
                                            placeholder="Min 500" required>
                                    </div>
                                </div>

                                @php $user = Auth::user(); @endphp

                                <div class="mb-3">
                                    <label for="paymentMethod"
                                        class="block text-xs font-bold text-slate-500 uppercase mb-2">Receiving
                                        Account</label>
                                    <select id="paymentMethod" name="payment_method" required
                                        class="block w-full rounded-xl border-slate-300 py-3 text-slate-700 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        <option value="">Select Method</option>
                                        @if ($user->account_no && $user->ifsc_code)
                                            <option value="bank">Bank Transfer (Ending in
                                                {{ substr($user->account_no, -4) }})</option>
                                        @endif
                                        @if ($user->upi_id)
                                            <option value="upi">UPI Transfer ({{ $user->upi_id }})</option>
                                        @endif
                                    </select>

                                    @if (!$user->account_no && !$user->upi_id)
                                        <div
                                            class="mt-2 flex items-center p-3 text-sm text-red-700 bg-red-50 rounded-lg border border-red-100">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            <span>Please update payment details in your profile first.</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="bg-slate-50 rounded-xl p-3 mb-2 border border-slate-100">
                                    <ul class="space-y-2 text-xs text-slate-500 font-medium">
                                        <li class="flex items-center"><i
                                                class="fas fa-info-circle w-5 text-emerald-500"></i> Min. Withdrawal: ₹500
                                        </li>
                                        <li class="flex items-center"><i
                                                class="fas fa-percentage w-5 text-emerald-500"></i> {{ $percentageIncome->admin_charge }} % Admin Charge</li>
                                        <li class="flex items-center"><i
                                                class="fas fa-file-invoice-dollar w-5 text-emerald-500"></i> {{ $percentageIncome->tds_charge }}% TDS
                                            Deduction</li>
                                    </ul>
                                </div>

                                <div class="mt-6 flex gap-3">
                                    <button type="button"
                                        class="flex-1 py-3 px-4 bg-white border border-slate-300 rounded-xl text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition"
                                        onclick="toggleModal('withdrawModal')">Cancel</button>
                                    <button type="submit" id="submitWithdraw"
                                        class="flex-1 py-3 px-4 bg-emerald-600 border border-transparent rounded-xl text-sm font-bold text-white shadow-sm hover:bg-emerald-700 transition"
                                        onclick="return confirm('Are you sure you want to withdraw wallet1?')"
                                        @if (!$user->account_no && !$user->upi_id) disabled @endif>
                                        Confirm Withdrawal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Tab Switching Logic
        function switchTab(tabName) {
            // Hide all panes
            document.getElementById('wallet1-pane').classList.add('hidden');
            document.getElementById('wallet2-pane').classList.add('hidden');

            // Show selected pane
            document.getElementById(tabName + '-pane').classList.remove('hidden');

            // Update Button Styles
            const btn1 = document.getElementById('tab-btn-wallet1');
            const btn2 = document.getElementById('tab-btn-wallet2');

            const activeClasses = ['bg-emerald-50', 'text-emerald-700', 'shadow-sm', 'ring-1', 'ring-emerald-200'];
            const inactiveClasses = ['text-slate-500', 'hover:text-slate-800'];

            if (tabName === 'wallet1') {
                btn1.classList.add(...activeClasses);
                btn1.classList.remove(...inactiveClasses);
                btn2.classList.remove(...activeClasses);
                btn2.classList.add(...inactiveClasses);
            } else {
                btn2.classList.add(...activeClasses);
                btn2.classList.remove(...inactiveClasses);
                btn1.classList.remove(...activeClasses);
                btn1.classList.add(...inactiveClasses);
            }
        }

        // Modal Logic
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            } else {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // Toggle Table Details Row
        function toggleDetails(id) {
            const row = document.getElementById(id);
            if (row.classList.contains('hidden')) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        }

        // Original Validation Logic (Preserved)
        document.addEventListener('DOMContentLoaded', function() {
            const withdrawForm = document.getElementById('withdrawForm');
            const withdrawAmount = document.getElementById('withdrawAmount');
            const submitBtn = document.getElementById('submitWithdraw');
            const wallet1Balance = {{ $wallet1 }};

            withdrawAmount.addEventListener('input', function() {
                const amount = parseFloat(this.value);
                // We use 'parentNode.parentNode' because HTML structure is: 
                // <div> -> <div class="relative"> -> <input>
                // We want to append error to the outer <div>
                const targetContainer = this.parentNode.parentNode;

                // Check if error already exists
                let errorElement = document.getElementById('amountError');

                if (isNaN(amount) || amount < 500 || amount > wallet1Balance) {
                    if (!errorElement) {
                        const div = document.createElement('div');
                        div.id = 'amountError';
                        // Tailwind error classes
                        div.className = 'mt-2 text-sm text-red-600 font-bold flex items-center';
                        div.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i> ` + (amount < 500 ?
                            'Minimum withdrawal amount is 500' :
                            'Amount exceeds your available balance');

                        targetContainer.appendChild(div);
                    } else {
                        // Update text if element exists
                        errorElement.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i> ` + (
                            amount < 500 ?
                            'Minimum withdrawal amount is 500' :
                            'Amount exceeds your available balance');
                    }

                    this.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                    this.classList.remove('border-slate-300', 'focus:border-emerald-500',
                        'focus:ring-emerald-500');
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    if (errorElement) errorElement.remove();

                    this.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                    this.classList.add('border-slate-300', 'focus:border-emerald-500',
                        'focus:ring-emerald-500');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            });
        });
    </script>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

@endsection
