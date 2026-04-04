@extends('vendorlayouts.layout')
@section('title', 'Manage Vendor Wallet')

@section('container')
<style>
    /* Chrome, Safari, Edge */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type="number"] {
  -moz-appearance: textfield;
}
</style>
<div class="min-h-screen bg-slate-50 pb-6 font-sans text-slate-600">
    <div class="max-w-7xl mx-auto px-3.5 py-3.5 sm:px-5 lg:px-6">

        {{-- Alerts --}}
        @if (session('success'))
            <div class="mb-3 rounded-xl bg-emerald-50 border border-emerald-200 p-4 flex items-center shadow-sm animate-fade-in" role="alert">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
                <button type="button" class="ml-auto pl-3 text-emerald-500 hover:text-emerald-800 transition" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-3 rounded-xl bg-red-50 border border-red-200 p-4 flex items-center shadow-sm animate-fade-in" role="alert">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
                <button type="button" class="ml-auto pl-3 text-red-500 hover:text-red-800 transition" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- 1. TOP SECTION: Balance & Action (Dark Gradient Kept Intact) --}}
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-3.5 lg:p-6 text-white shadow-xl relative overflow-hidden mb-6 flex flex-col md:flex-row justify-between items-center gap-3 animate-fade-in">
            <i class="fas fa-wallet absolute -right-4 -bottom-4 text-8xl text-white opacity-10 rotate-[-15deg]"></i>
            
            <div class="relative z-10 w-full md:w-auto text-center md:text-left">
                <p class="text-slate-300 text-sm font-bold uppercase tracking-wider mb-2">Vendor Wallet Balance</p>
                <h2 class="text-4xl lg:text-5xl font-black mb-1">₹{{ number_format($vendorWalletBalance, 2) }}</h2>
                <p class="text-xs text-slate-400">Available for withdrawal</p>
            </div>

            <div class="relative z-10 w-full md:w-auto text-center md:text-right">
                @if($withdrawalStatus)
                    <button onclick="toggleModal('withdrawModal')" class="w-full md:w-auto bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-8 rounded-xl transition-colors shadow-md flex items-center justify-center gap-2">
                        <i class="fas fa-money-bill-wave"></i> Request Withdrawal
                    </button>
                @else
                    <div class="bg-red-500/20 border border-red-500/50 text-red-100 p-3 rounded-xl text-center text-sm font-bold inline-block">
                        <i class="fas fa-lock mr-1"></i> Withdrawals are currently disabled
                    </div>
                @endif
            </div>
        </div>

        {{-- 2. MIDDLE SECTION: Withdrawal Requests --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8 animate-fade-in" style="animation-delay: 0.1s;">
            <div class="px-3.5 py-3.5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-university text-orange-500"></i> Withdrawal Requests
                </h3>
                <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-bold text-slate-500">
                    {{ $withdrawals->total() }} Records
                </span>
            </div>

            @if ($withdrawals->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-xs uppercase text-slate-500 font-bold border-b border-slate-200">
                                <th class="p-3">Date</th>
                                <th class="p-3">Amount</th>
                                <th class="p-3">Charge ({{ $percentageIncome->vendor_withdraw_charge ?? 0 }}%)</th>
                                <th class="p-3">Credited</th>
                                <th class="p-3">Method</th>
                                <th class="p-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($withdrawals as $with)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-3 text-sm font-medium text-slate-600">{{ $with->created_at->format('d M Y') }}</td>
                                    <td class="p-3 text-sm font-bold text-slate-800">₹{{ number_format($with->total_amount, 2) }}</td>
                                    <td class="p-3 text-sm font-bold text-red-500">- ₹{{ number_format($with->vendor_charge, 2) }}</td>
                                    <td class="p-3 text-sm font-bold text-emerald-600">₹{{ number_format($with->credited_amount, 2) }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-bold text-slate-600 uppercase">
                                            {{ ucfirst($with->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-center">
                                        @if($with->status == 'pending') 
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200"><i class="fas fa-clock"></i> Pending</span>
                                        @elseif($with->status == 'approved') 
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200"><i class="fas fa-check-circle"></i> Approved</span>
                                        @else 
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-200"><i class="fas fa-times-circle"></i> Rejected</span> 
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-3.5 py-3.5 border-t border-slate-100">
                    {{ $withdrawals->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="py-10 text-center text-slate-400">
                    <i class="fas fa-ghost text-4xl mb-3 opacity-30"></i>
                    <p>No withdrawal requests found.</p>
                </div>
            @endif
        </div>

        {{-- 3. BOTTOM SECTION: Wallet Transactions --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden animate-fade-in" style="animation-delay: 0.2s;">
            <div class="p-3.5 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-list text-indigo-500"></i> Wallet Transactions History
                </h3>
            </div>

            {{-- Filters --}}
            <div class="p-6 bg-slate-50 border-b border-slate-200">
                <form method="GET" action="{{ route('vendor.wallet.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Type</label>
                        <select name="wallet_type" class="w-full rounded-lg border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 p-2 outline-none">
                            <option value="">All Types</option>
                            <option value="credit" {{ request('wallet_type') == 'credit' ? 'selected' : '' }}>Credit (+)</option>
                            <option value="debit" {{ request('wallet_type') == 'debit' ? 'selected' : '' }}>Debit (-)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">From Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded-lg border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 p-2 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">To Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded-lg border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 p-2 outline-none">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white py-2 rounded-lg text-sm font-bold transition">Apply</button>
                        <a href="{{ route('vendor.wallet.index') }}" class="px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-500 hover:bg-slate-100 transition"><i class="fas fa-times"></i></a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                @if ($walletTransactions->count() > 0)
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500 font-bold border-b border-slate-200">
                            <tr>
                                <th class="p-3">Date</th>
                                <th class="p-3">Description</th>
                                <th class="p-3 text-right">Amount</th>
                                <th class="p-3 text-right">Balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($walletTransactions as $txn)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-3 text-sm text-slate-500">{{ $txn->created_at->format('d M Y, h:i A') }}</td>
                                    <td class="p-3 text-sm font-medium text-slate-700">{{ $txn->notes ?? 'Transaction' }}</td>
                                    <td class="p-3 text-right">
                                        <span class="text-sm font-bold {{ $txn->amount >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                            {{ $txn->amount >= 0 ? '+' : '' }}{{ $txn->amount }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-sm font-bold text-slate-800 text-right">₹{{ number_format($txn->balance, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-3.5 py-3.5 border-t border-slate-100">
                        {{ $walletTransactions->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="py-8 text-center text-slate-400">
                        <i class="fas fa-exchange-alt text-4xl mb-3 opacity-30"></i>
                        <p>No transactions found matching your filters.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- 4. WITHDRAWAL MODAL (WITH JS VALIDATION) --}}
<div id="withdrawModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="toggleModal('withdrawModal')"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            {{-- Card: Dark gradient matching the wallet balance card --}}
            <div class="relative transform overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-700/50">

                {{-- Decorative Icon --}}
                <i class="fas fa-money-bill-wave absolute -right-6 -bottom-6 text-8xl text-white opacity-10 rotate-[-15deg]"></i>

                <div class="relative z-10 px-6 pb-3 pt-4 sm:p-6">
                    {{-- Header --}}
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-white flex items-center gap-2" id="modal-title">
                            <i class="fas fa-hand-holding-usd text-emerald-400"></i> Withdraw Funds
                        </h3>
                        <button type="button" class="text-slate-400 hover:text-slate-200 transition-all hover:rotate-90 duration-200" onclick="toggleModal('withdrawModal')">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <form id="withdrawForm" action="{{ route('vendor.wallet.withdraw') }}" method="POST">
                        @csrf

                        {{-- Balance Card (Matches top wallet card style) --}}
                        <div class="bg-slate-700/30 backdrop-blur-sm border border-slate-600/50 rounded-xl p-3 text-center mb-4 shadow-inner">
                            <span class="text-xs font-bold text-slate-300 uppercase tracking-wider flex items-center justify-center gap-1">
                                <i class="fas fa-wallet text-emerald-400 text-xs"></i> Available Balance
                            </span>
                            <div class="text-3xl font-extrabold text-white mt-1 tracking-tight">₹{{ number_format($vendorWalletBalance, 2) }}</div>
                            <p class="text-[10px] text-slate-400 mt-1">Ready for withdrawal</p>
                        </div>

                        {{-- Amount Input --}}
                        <div class="mb-3">
                            <label for="withdrawAmount" class="block text-xs font-bold text-slate-300 uppercase mb-2 tracking-wider">Enter Amount</label>
                            <div class="relative rounded-xl shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                    <span class="text-slate-400 sm:text-sm font-bold">₹</span>
                                </div>
                                <input type="number" name="amount" id="withdrawAmount" class="block w-full rounded-xl bg-slate-700/50 border border-slate-600 pl-8 py-3 text-white placeholder-slate-400 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm font-medium outline-none transition" placeholder="Min 500" required>
                            </div>
                        </div>

                        @php $user = Auth::user(); @endphp

                        {{-- Payment Method --}}
                        <div class="mb-3">
                            <label for="paymentMethod" class="block text-xs font-bold text-slate-300 uppercase mb-2 tracking-wider">Receiving Account</label>
                            <select id="paymentMethod" name="payment_method" required class="block w-full rounded-xl bg-slate-700/50 border border-slate-600 py-3 px-3 text-white text-sm focus:border-emerald-500 focus:ring-emerald-500 outline-none transition cursor-pointer">
                                <option value="" class="bg-slate-800 text-slate-300">Select Method</option>
                                @if ($user->account_no && $user->ifsc_code)
                                    <option value="bank" class="bg-slate-800 text-white">🏦 Bank Transfer (••••{{ substr($user->account_no, -4) }})</option>
                                @endif
                                @if ($user->upi_id)
                                    <option value="upi" class="bg-slate-800 text-white">📱 UPI Transfer ({{ $user->upi_id }})</option>
                                @endif
                            </select>

                            @if (!$user->account_no && !$user->upi_id)
                                <div class="mt-3 flex items-center p-3 text-sm text-amber-200 bg-amber-500/10 rounded-lg border border-amber-500/30 backdrop-blur-sm">
                                    <i class="fas fa-exclamation-triangle mr-2 text-amber-300"></i>
                                    <span class="text-xs">Please update payment details in your profile first.</span>
                                </div>
                            @endif
                        </div>

                        {{-- Info Cards (Charge & Minimum) --}}
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-slate-700/30 rounded-xl p-2.5 text-center border border-slate-600/50">
                                <div class="flex items-center justify-center gap-1 text-emerald-400 text-xs font-bold mb-1">
                                    <i class="fas fa-charging-station text-[10px]"></i> <span>Withdrawal Charge</span>
                                </div>
                                <p class="text-white font-bold text-sm">{{ $percentageIncome->vendor_withdraw_charge ?? 0 }}%</p>
                            </div>
                            <div class="bg-slate-700/30 rounded-xl p-2.5 text-center border border-slate-600/50">
                                <div class="flex items-center justify-center gap-1 text-amber-400 text-xs font-bold mb-1">
                                    <i class="fas fa-chart-line text-[10px]"></i> <span>Minimum Amount</span>
                                </div>
                                <p class="text-white font-bold text-sm">₹500</p>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-6 flex gap-3">
                            <button type="button" class="flex-1 py-3 px-4 bg-slate-700/70 border border-slate-600 rounded-xl text-sm font-bold text-slate-200 shadow-sm hover:bg-slate-700 transition-all duration-200" onclick="toggleModal('withdrawModal')">
                                Cancel
                            </button>
                            <button type="submit" id="submitWithdraw" class="flex-1 py-3 px-4 bg-emerald-500 hover:bg-emerald-600 border border-transparent rounded-xl text-sm font-bold text-white shadow-lg shadow-emerald-500/20 transition-all duration-200 flex items-center justify-center gap-2" @if (!$user->account_no && !$user->upi_id) disabled @endif>
                                <i class="fas fa-check-circle"></i> Confirm Withdrawal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Modal Toggle
    function toggleModal(modalID) {
        const modal = document.getElementById(modalID);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; 
        } else {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    // Real-Time Form Validation Logic (As requested)
    document.addEventListener('DOMContentLoaded', function() {
        const withdrawAmount = document.getElementById('withdrawAmount');
        const submitBtn = document.getElementById('submitWithdraw');
        const walletBalance = {{ $vendorWalletBalance }}; // Dynamic Vendor Wallet Balance

        withdrawAmount.addEventListener('input', function() {
            const amount = parseFloat(this.value);
            const targetContainer = this.parentNode.parentNode;
            let errorElement = document.getElementById('amountError');

            if (isNaN(amount) || amount < 500 || amount > walletBalance) {
                if (!errorElement) {
                    const div = document.createElement('div');
                    div.id = 'amountError';
                    div.className = 'mt-2 text-sm text-red-600 font-bold flex items-center animate-fade-in';
                    div.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i> ` + (amount < 500 ?
                        'Minimum withdrawal amount is 500' :
                        'Amount exceeds your available balance');
                    targetContainer.appendChild(div);
                } else {
                    errorElement.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i> ` + (
                        amount < 500 ?
                        'Minimum withdrawal amount is 500' :
                        'Amount exceeds your available balance');
                }

                this.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                this.classList.remove('border-slate-300', 'focus:border-emerald-500', 'focus:ring-emerald-500');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                if (errorElement) errorElement.remove();

                this.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                this.classList.add('border-slate-300', 'focus:border-emerald-500', 'focus:ring-emerald-500');
                
                // Allow submit only if bank/upi is set (Check if button already has disabled due to no bank details)
                if(!this.hasAttribute('disabled-override')) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }
        });
    });
</script>

<style>
    .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

@endsection