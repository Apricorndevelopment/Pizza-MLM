@extends('layouts.layout')
@section('title', 'Wallet Management')
@section('container')

    <div class="min-h-screen bg-slate-50 font-sans text-slate-600">
        <div class="max-w-7xl mx-auto">

            {{-- Page Header & Toggle --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                        <span class="px-3 py-2 bg-indigo-100 rounded-lg text-indigo-600 shadow-sm">
                            <i class="fas fa-wallet text-xl"></i>
                        </span>
                        Wallet Management
                    </h1>
                    <p class="text-sm text-slate-500 mt-1 ml-1">Manage user withdrawal requests and history.</p>
                </div>

                {{-- GLOBAL WITHDRAWAL TOGGLE --}}
                <div class="bg-white px-3 py-2.5 rounded-full shadow-sm border border-slate-200 flex items-center gap-4">
                    <span id="withdrawalLabel"
                        class="text-sm font-bold {{ Auth::guard('admin')->user()->is_withdrawal_open ? 'text-emerald-600' : 'text-slate-500' }} flex items-center gap-2">
                        <span
                            class="w-2 h-2 rounded-full {{ Auth::guard('admin')->user()->is_withdrawal_open ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                        {{ Auth::guard('admin')->user()->is_withdrawal_open ? 'Withdrawals Enabled' : 'Withdrawals Disabled' }}
                    </span>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="withdrawalToggle" class="sr-only peer"
                            {{ Auth::guard('admin')->user()->is_withdrawal_open ? 'checked' : '' }}>
                        <div
                            class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500">
                        </div>
                    </label>
                </div>
            </div>
            {{-- Pending Withdrawals Section --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-8 overflow-hidden">
               {{-- UPDATED HEADER WITH TOTAL AMOUNT --}}
                <div class="px-4 py-3.5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-amber-50/30">
                    <h2 class="font-bold flex items-center gap-2 text-slate-800">
                        <i class="fas fa-clock text-amber-500"></i> Pending Requests
                    </h2>
                    
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                        {{-- NEW: Total Pending Amount Badge --}}
                        <span class="px-3 py-1 rounded-lg bg-white border border-slate-200 text-slate-600 text-xs font-bold shadow-sm flex items-center gap-1.5">
                            <i class="fas fa-coins text-amber-400"></i> Total Amount: 
                            <span class="text-amber-600 text-sm">₹{{ number_format($withdrawals->sum('total_amount'), 2) }}</span>
                        </span>

                        <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200 shadow-sm">
                            Action Required
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500">
                            <tr>
                                <th class="px-3.5 py-3">User</th>
                                <th class="px-3.5 py-3">Amount</th>
                                <th class="px-3.5 py-3">Charge</th>
                                <th class="px-3.5 py-3">Amount After Charge</th>
                                <th class="px-3.5 py-3">Details</th>
                                <th class="px-3.5 py-3">Request Date</th>
                                <th class="px-3.5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($withdrawals as $withdrawal)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-3.5 py-3">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-bold text-slate-800 text-sm">{{ $withdrawal->user->name }}</span>
                                            <span
                                                class="text-xs font-mono text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded w-fit mt-1">{{ $withdrawal->user_ulid }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3.5 py-3">
                                        <span
                                            class="text-slate-800 text-sm font-semibold">₹{{ number_format($withdrawal->total_amount, 2) }}</span>
                                    </td>
                                    <td class="px-3.5 py-3">
                                        <span
                                            class="inline-flex items-center text-xs font-semibold tracking-wide">
                                            Admin Charge: {{ $withdrawal->admin_charge }}
                                        </span>
                                        <span
                                            class="inline-flex items-center text-xs font-semibold tracking-wide">
                                            TDS Charge: {{ $withdrawal->tds_charge }}
                                        </span>
                                    </td>
                                    <td class="px-3.5 py-3">
                                        <span
                                            class="font-black text-slate-800 text-base">₹{{ number_format($withdrawal->credited_amount, 2) }}</span>
                                    </td>
                                    <td class="px-3.5 py-3 text-xs text-slate-500">
                                        @if ($withdrawal->payment_method === 'bank')
                                            <div class="space-y-0.5">
                                                <p><span class="font-semibold text-slate-700">Bank:</span>
                                                    {{ $withdrawal->user->bank_name }}</p>
                                                <p><span class="font-semibold text-slate-700">A/C:</span> <span
                                                        class="font-mono">{{ $withdrawal->user->account_no }}</span>
                                                </p>
                                                <p><span class="font-semibold text-slate-700">IFSC:</span> <span
                                                        class="font-mono">{{ $withdrawal->user->ifsc_code }}</span></p>
                                            </div>
                                        @else
                                            <p><span class="font-semibold text-slate-700">UPI:</span>
                                                {{ $withdrawal->user->upi_id }}</p>
                                        @endif
                                    </td>
                                    <td class="px-3.5 py-3">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-semibold text-slate-700">{{ $withdrawal->created_at->format('d M, Y') }}</span>
                                            <span
                                                class="text-xs text-slate-400">{{ $withdrawal->created_at->diffForHumans() }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3.5 py-3 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <form action="{{ route('admin.withdrawls.approve', $withdrawal->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm shadow-emerald-200 transition-all active:scale-95 flex items-center gap-1">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.withdrawls.reject', $withdrawal->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3 py-1.5 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-bold rounded-lg transition-all active:scale-95 flex items-center gap-1">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div
                                                class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                                <i class="fas fa-check-circle text-emerald-400 text-xl"></i>
                                            </div>
                                            <p class="text-slate-500 font-medium">No pending requests</p>
                                            <p class="text-slate-400 text-xs">All caught up!</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- All History Section --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-3.5 py-3 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h2 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-history text-slate-400"></i> Withdrawal History
                    </h2>
                    <span
                        class="px-2.5 py-0.5 rounded-md bg-white border border-slate-200 text-slate-500 text-xs font-bold shadow-sm">
                        Total: {{ $allWithdrawls->total() }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500">
                            <tr>
                                <th class="px-3.5 py-3">User</th>
                                <th class="px-3.5 py-3">Amount</th>
                                <th class="px-3.5 py-3">Charges</th>
                                <th class="px-3.5 py-3">Credited Amount</th>
                                <th class="px-3.5 py-3">Status</th>
                                <th class="px-3.5 py-3">Details</th>
                                <th class="px-3.5 py-3">Request Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($allWithdrawls as $withdrawal)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-3.5 py-3">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-bold text-slate-800 text-sm">{{ $withdrawal->user->name }}</span>
                                            <span class="text-xs text-slate-400">#{{ $withdrawal->user_ulid }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3.5 py-3">
                                        <span
                                            class="font-semibold text-slate-700">₹{{ number_format($withdrawal->total_amount, 2) }}</span>
                                    </td>
                                    <td class="px-3.5 py-3">
                                        <span
                                            class="inline-flex items-center text-xs font-semibold tracking-wide">
                                            Admin Charge: {{ $withdrawal->admin_charge }}
                                        </span>
                                        <span
                                            class="inline-flex items-center text-xs font-semibold tracking-wide">
                                            TDS Charge: {{ $withdrawal->tds_charge }}
                                        </span>
                                    </td>
                                    <td class="px-3.5 py-3">
                                        <span class="font-black text-base text-slate-700"> {{ $withdrawal->credited_amount }}  </span>
                                    </td>
                                    <td class="px-3.5 py-3">
                                        @if ($withdrawal->status === 'approved')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                                Approved
                                            </span>
                                        @elseif($withdrawal->status === 'rejected')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span> Rejected
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3.5 py-3 text-xs text-slate-500">
                                        @if ($withdrawal->payment_method === 'bank')
                                            <span
                                                class="font-mono text-slate-600">{{ $withdrawal->user->account_no }}</span>
                                            <span class="text-slate-400 mx-1">|</span> {{ $withdrawal->user->bank_name }}
                                        @else
                                            {{ $withdrawal->user->upi_id }}
                                        @endif
                                    </td>
                                    <td class="px-3.5 py-3 text-xs text-slate-500">
                                        {{ $withdrawal->created_at->format('d M, Y') }}
                                        <span
                                            class="text-slate-400 block text-[10px]">{{ $withdrawal->created_at->format('h:i A') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-sm">
                                        No withdrawal history found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($allWithdrawls->hasPages())
                    <div class="px-3.5 py-3 border-t border-slate-100 bg-white">
                        {{ $allWithdrawls->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script>
        document.getElementById('withdrawalToggle').addEventListener('change', function(e) {
            const checkbox = this;
            const isChecked = checkbox.checked;
            const label = document.getElementById('withdrawalLabel');
            const status = isChecked ? 1 : 0;

            // 1. Ask for Confirmation
            const userConfirmed = confirm("Are you sure you want to " + (isChecked ? "ENABLE" : "DISABLE") +
                " withdrawals?");

            if (!userConfirmed) {
                // Revert state if cancelled
                checkbox.checked = !isChecked;
                return;
            }

            // 2. Optimistic UI Update (Only if confirmed)
            if (isChecked) {
                label.innerHTML =
                    '<span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Withdrawals Enabled';
                label.classList.replace('text-slate-500', 'text-emerald-600');
            } else {
                label.innerHTML = '<span class="w-2 h-2 rounded-full bg-slate-400"></span> Withdrawals Disabled';
                label.classList.replace('text-emerald-600', 'text-slate-500');
            }

            // 3. Send Request
            fetch("{{ route('admin.withdrawal.toggle') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(data.message);
                        // Optional: Show a success toast here
                    } else {
                        // Revert on server error
                        checkbox.checked = !isChecked;
                        alert('Server Error: Failed to update status.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Revert on network error
                    checkbox.checked = !isChecked;
                    alert('Network Error: Failed to update status.');
                });
        });
    </script>

@endsection
