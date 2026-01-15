@extends('layouts.layout')
@section('title', 'Wallet Management')
@section('container')

    <div class="container">
        <!-- Pending Withdrawals Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="h5 mb-0">Pending Withdrawal Requests</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">User</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Details</th>
                                <th>Request Date</th>
                                <th class="pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($withdrawals as $withdrawal)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $withdrawal->user->name }}</div>
                                        <small class="text-muted">{{ $withdrawal->user_ulid }}</small>
                                    </td>
                                    <td class="fw-bold">₹{{ number_format($withdrawal->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            {{ ucfirst($withdrawal->payment_method) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($withdrawal->payment_method === 'bank')
                                            <small class="d-block">A/C: {{ $withdrawal->user->account_no }}</small>
                                            <small class="d-block">IFSC: {{ $withdrawal->user->ifsc_code }}</small>
                                            <small class="d-block">Bank: {{ $withdrawal->user->bank_name }}</small>
                                        @else
                                            <small class="d-block">UPI: {{ $withdrawal->user->upi_id }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $withdrawal->created_at->format('d M Y') }}
                                        <small
                                            class="d-block text-muted">{{ $withdrawal->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td class="pe-4">
                                        <div class="d-flex gap-2">
                                            <form action="{{ route('admin.withdrawls.approve', $withdrawal->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fa fa-check me-1"></i> Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.withdrawls.reject', $withdrawal->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fa fa-times me-1"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No pending withdrawal requests</h5>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- All Withdrawals Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="h5 mb-0">All Withdrawal History</h3>
                    <span class="badge bg-white text-secondary">
                        Total: {{ $allWithdrawls->total() }}
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">User</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Details</th>
                                <th class="pe-4">Request Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($allWithdrawls as $withdrawal)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $withdrawal->user->name }} <span
                                                class="text-muted">({{ $withdrawal->user_ulid }})</span></div>
                                    </td>
                                    <td class="fw-bold">₹{{ number_format($withdrawal->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            {{ ucfirst($withdrawal->payment_method) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($withdrawal->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($withdrawal->payment_method === 'bank')
                                            <small class="d-block">A/C: {{ $withdrawal->user->account_no }}</small>
                                            <small class="d-block">IFSC: {{ $withdrawal->user->ifsc_code }}</small>
                                        @else
                                            <small class="d-block">UPI: {{ $withdrawal->user->upi_id }}</small>
                                        @endif
                                    </td>
                                    <td class="pe-4">
                                        {{ $withdrawal->created_at->format('d M Y') }}
                                        <small
                                            class="d-block text-muted">{{ $withdrawal->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No withdrawal history found</h5>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($allWithdrawls->hasPages())
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $allWithdrawls->firstItem() }} to {{ $allWithdrawls->lastItem() }} of
                                {{ $allWithdrawls->total() }} entries
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    {{-- Previous Page Link --}}
                                    @if ($allWithdrawls->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">&laquo;</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $allWithdrawls->previousPageUrl() }}"
                                                rel="prev">&laquo;</a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($allWithdrawls->getUrlRange(1, $allWithdrawls->lastPage()) as $page => $url)
                                        @if ($page == $allWithdrawls->currentPage())
                                            <li class="page-item active" aria-current="page">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($allWithdrawls->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $allWithdrawls->nextPageUrl() }}"
                                                rel="next">&raquo;</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">&raquo;</span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
