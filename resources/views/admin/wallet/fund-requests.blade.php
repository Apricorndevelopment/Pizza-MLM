@extends('layouts.layout')
@section('title', 'Manage Fund Requests')

@section('container')
    <div class="min-h-screen bg-slate-50/50 py-8 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header & Filters --}}
            <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Fund Requests</h1>
                    <p class="text-sm text-slate-500 mt-1">Manage wallet top-up requests from users.</p>
                </div>

                {{-- Filter Tabs --}}
                <div class="flex p-1 space-x-1 bg-slate-200 rounded-xl" role="tablist">
                    <button onclick="filterStatus('pending')"
                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all {{ $status === 'pending' ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-600 hover:text-indigo-600' }}">
                        Pending
                    </button>
                    <button onclick="filterStatus('approved')"
                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all {{ $status === 'approved' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-emerald-600' }}">
                        Approved
                    </button>
                    <button onclick="filterStatus('rejected')"
                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all {{ $status === 'rejected' ? 'bg-white text-red-700 shadow-sm' : 'text-slate-600 hover:text-red-600' }}">
                        Rejected
                    </button>
                </div>
            </div>

            {{-- Alerts --}}
            @if (session('success'))
                <div id="alert-success"
                    class="mb-6 rounded-xl bg-emerald-50 border border-emerald-100 p-4 flex gap-3 items-start animate-fade-in-up">
                    <i class="fas fa-check-circle text-emerald-500 mt-0.5 text-lg"></i>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-emerald-800">Success</h3>
                        <p class="text-sm text-emerald-700 mt-0.5">{{ session('success') }}</p>
                    </div>
                    <button onclick="document.getElementById('alert-success').remove()"
                        class="text-emerald-400 hover:text-emerald-600"><i class="fas fa-times"></i></button>
                </div>
            @endif
            @if (session('error'))
                <div id="alert-error"
                    class="mb-6 rounded-xl bg-red-50 border border-red-100 p-4 flex gap-3 items-start animate-fade-in-up">
                    <i class="fas fa-exclamation-circle text-red-500 mt-0.5 text-lg"></i>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-red-800">Error</h3>
                        <p class="text-sm text-red-700 mt-0.5">{{ session('error') }}</p>
                    </div>
                    <button onclick="document.getElementById('alert-error').remove()"
                        class="text-red-400 hover:text-red-600"><i class="fas fa-times"></i></button>
                </div>
            @endif

            {{-- Table Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 border-b border-slate-200/60">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">User Details</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Transaction Info</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Proof</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Date</th>
                                @if ($status === 'pending')
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-right">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($requests as $req)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    {{-- User --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm mr-3">
                                                {{ substr($req->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-slate-900">{{ $req->user->name }}</div>
                                                <div class="text-xs text-slate-500">{{ $req->user->ulid }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Amount --}}
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            ₹{{ number_format($req->amount, 2) }}
                                        </span>
                                    </td>

                                    {{-- Transaction --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-mono text-slate-600">{{ $req->transaction_id }}</div>
                                        <div class="flex items-center mt-1 space-x-2 text-xs text-slate-500">
                                            <span
                                                class="bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">{{ $req->payment_method }}</span>
                                            <span>From: {{ $req->sender_upi_id }}</span>
                                        </div>
                                    </td>

                                    {{-- Proof --}}
                                    <td class="px-6 py-4">
                                        @if ($req->receipt_image)
                                            <a href="{{ asset('storage/' . $req->receipt_image) }}" target="_blank"
                                                class="group/link inline-flex items-center text-xs font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                                <i
                                                    class="fas fa-image mr-1.5 text-lg group-hover/link:scale-110 transition-transform"></i>
                                                View Receipt
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-400 italic">No proof attached</span>
                                        @endif
                                    </td>

                                    {{-- Date --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-600">{{ $req->created_at->format('d M Y') }}</div>
                                        <div class="text-xs text-slate-400">{{ $req->created_at->format('h:i A') }}</div>
                                    </td>

                                    {{-- Actions --}}
                                    @if ($status === 'pending')
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2 relative">
                                                {{-- Approve Form --}}
                                                <form action="{{ route('admin.funds.update', $req->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit"
                                                        onclick="return confirm('Confirm adding ₹{{ $req->amount }} to wallet?')"
                                                        class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-600 hover:bg-emerald-200 hover:text-emerald-700 flex items-center justify-center transition-all shadow-sm"
                                                        title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>

                                                {{-- Reject Button (Triggers Global Modal) --}}
                                                <button type="button"
                                                    onclick="openRejectModal('{{ route('admin.funds.update', $req->id) }}')"
                                                    class="w-9 h-9 rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-700 flex items-center justify-center transition-all shadow-sm"
                                                    title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div
                                                class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-inbox text-3xl text-slate-300"></i>
                                            </div>
                                            <h3 class="text-slate-900 font-medium">No {{ ucfirst($status) }} Requests</h3>
                                            <p class="text-slate-500 text-sm mt-1">There are no records to display for this
                                                status.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                    {{ $requests->appends(['status' => $status])->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- GLOBAL CENTERED REJECT MODAL --}}
    <div id="reject-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeRejectModal()"></div>

        {{-- Modal Panel --}}
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">

                    {{-- Header --}}
                    <div class="bg-red-50 px-4 py-4 sm:px-6 flex items-center justify-between border-b border-red-100">
                        <h3 class="text-lg font-bold leading-6 text-red-800 flex items-center gap-2" id="modal-title">
                            <span class="bg-red-100 p-1.5 rounded-lg"><i
                                    class="fas fa-exclamation-triangle text-red-600"></i></span>
                            Reject Request
                        </h3>
                        <button onclick="closeRejectModal()"
                            class="text-red-400 hover:text-red-600 transition-colors p-1 rounded-full hover:bg-red-100">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>

                    {{-- Form --}}
                    <form id="reject-form" method="POST" action="">
                        @csrf
                        <div class="px-4 py-6 sm:p-6">
                            <input type="hidden" name="action" value="reject">

                            <label for="remark" class="block text-sm font-semibold leading-6 text-slate-800 mb-2">
                                Please provide a reason for rejection <span class="text-red-500">*</span>
                            </label>

                            <div class="mt-1 relative">
                                <textarea id="remark" name="remark" rows="4" required
                                    class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6 bg-slate-50 transition-all resize-none"
                                    placeholder="e.g. Transaction ID invalid, amount mismatch, duplicate request..."></textarea>
                            </div>
                            <p class="mt-2 text-xs text-slate-500 italic"><i class="fas fa-info-circle mr-1"></i> This
                                reason will be visible to the user.</p>
                        </div>

                        {{-- Footer --}}
                        <div
                            class="bg-slate-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-100 gap-3">
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-xl bg-red-600 px-5 py-2.5 text-sm font-bold text-white shadow-md hover:bg-red-700 sm:w-auto transition-all transform hover:scale-[1.02]">
                                Confirm Rejection
                            </button>
                            <button type="button" onclick="closeRejectModal()"
                                class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-all">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript Logic --}}
    <script>
        // 1. Filter Logic
        function filterStatus(status) {
            const url = new URL(window.location.href);
            url.searchParams.set('status', status);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        // 2. Reject Modal Logic
        const modal = document.getElementById('reject-modal');
        const rejectForm = document.getElementById('reject-form');

        function openRejectModal(actionUrl) {
            // Set the form action dynamically
            rejectForm.action = actionUrl;
            // Show modal
            modal.classList.remove('hidden');
        }

        function closeRejectModal() {
            modal.classList.add('hidden');
            // Clear textarea
            document.getElementById('remark').value = '';
        }

        // Close on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape" && !modal.classList.contains('hidden')) {
                closeRejectModal();
            }
        });
    </script>
@endsection
