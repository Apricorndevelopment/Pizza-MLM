@extends('layouts.layout')
@section('title', 'Vendor Withdrawal Requests')

@section('container')
<div class="min-h-screen bg-slate-50 font-sans pb-6">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-3">
                <span class="p-2 bg-indigo-100 rounded-lg text-indigo-600 shadow-sm">
                    <i class="bi bi-shop-window text-xl"></i>
                </span>
                Vendor Withdrawal Requests
            </h1>
            <p class="text-sm text-slate-500 mt-1">Manage and process payout requests from vendors.</p>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="mb-3 rounded-xl bg-emerald-50 border border-emerald-200 p-4 flex items-center shadow-sm">
                <i class="fas fa-check-circle text-emerald-500 text-xl mr-3"></i>
                <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                <button type="button" class="ml-auto text-emerald-500 hover:text-emerald-800" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-3 rounded-xl bg-red-50 border border-red-200 p-4 flex items-center shadow-sm">
                <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                <button type="button" class="ml-auto text-red-500 hover:text-red-800" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
            </div>
        @endif

        {{-- 1. PENDING REQUESTS --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
            <div class="px-3.5 py-3.5 border-b border-slate-100 bg-amber-50/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-clock text-amber-500"></i> Pending Requests
                </h3>
                <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">{{ $withdrawals->count() }} Pending</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 font-bold border-b border-slate-200">
                        <tr>
                            <th class="p-3">Vendor Details</th>
                            <th class="p-3">Payment Info</th>
                            <th class="p-3 text-right">Requested</th>
                            <th class="p-3 text-right">Charge</th>
                            <th class="p-3 text-right">Net Payable</th>
                            <th class="p-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($withdrawals as $req)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-3">
                                    <p class="font-bold text-slate-800 text-sm">{{ $req->vendor->company_name ?? 'N/A' }}</p>
                                    <p class="text-xs text-slate-500">{{ $req->user->name }} ({{ $req->user_ulid }})</p>
                                </td>
                                <td class="p-3">
                                    <span class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-[10px] font-bold text-slate-600 uppercase mb-1 inline-block">
                                        {{ $req->payment_method }}
                                    </span>
                                    @if($req->payment_method == 'bank')
                                        <p class="text-xs text-slate-600 font-mono">{{ $req->user->account_no }} <br> IFSC: {{ $req->user->ifsc_code }}</p>
                                    @else
                                        <p class="text-xs text-slate-600 font-mono">{{ $req->user->upi_id }}</p>
                                    @endif
                                </td>
                                <td class="p-3 text-right font-bold text-slate-700">₹{{ number_format($req->total_amount, 2) }}</td>
                                <td class="p-3 text-right font-semibold text-red-500">- ₹{{ number_format($req->vendor_charge, 2) }}</td>
                                <td class="p-3 text-right font-black text-emerald-600 text-lg">₹{{ number_format($req->credited_amount, 2) }}</td>
                                <td class="p-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('admin.vendor.withdraw.approve', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve this withdrawal?')">
                                            @csrf
                                            <button type="submit" class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 hover:bg-emerald-600 hover:text-white transition flex items-center justify-center" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.vendor.withdraw.reject', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject and refund this amount?')">
                                            @csrf
                                            <button type="submit" class="w-8 h-8 rounded-full bg-red-100 text-red-600 hover:bg-red-600 hover:text-white transition flex items-center justify-center" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-slate-400">
                                    <i class="fas fa-check-circle text-4xl mb-2 opacity-30"></i>
                                    <p>No pending vendor withdrawals.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 2. ALL PROCESSED REQUESTS --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-3 py-3.5 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-history text-indigo-500"></i> Processed History
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 font-bold border-b border-slate-200">
                        <tr>
                            <th class="p-3">Date</th>
                            <th class="p-3">Vendor</th>
                            <th class="p-3 text-right">Requested</th>
                            <th class="p-3 text-right">Net Paid</th>
                            <th class="p-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($allWithdrawls as $req)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-3 text-sm text-slate-500">{{ $req->created_at->format('d M Y, h:i A') }}</td>
                                <td class="p-3">
                                    <p class="font-bold text-slate-800 text-sm">{{ $req->user->vendor->company_name ?? 'N/A' }}</p>
                                    <p class="text-xs text-slate-500">{{ $req->user->name }}</p>
                                </td>
                                <td class="p-3 text-right font-bold text-slate-700">₹{{ number_format($req->total_amount, 2) }}</td>
                                <td class="p-3 text-right font-bold text-emerald-600">₹{{ number_format($req->credited_amount, 2) }}</td>
                                <td class="p-3 text-center">
                                    @if($req->status == 'approved') 
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200"><i class="fas fa-check-circle"></i> Approved</span>
                                    @else 
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-200"><i class="fas fa-times-circle"></i> Rejected</span> 
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-400">
                                    <p>No processed history found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($allWithdrawls->hasPages())
                <div class="px-3 py-3 border-t border-slate-100">
                    {{ $allWithdrawls->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection