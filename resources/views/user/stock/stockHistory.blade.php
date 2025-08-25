@extends('userlayouts.layouts')
@section('title', 'Stock Transfer History')

@section('container')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i> My Stock Transfers</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Type</th>
                            <th>User_ulid</th>
                            <th>Product</th>
                            <th class="text-center">Qty</th>
                            <th>Date</th>
                            <th class="text-center">Balance</th>
                            <th>From → To</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $transfer)
                        <tr>
                            <td class="ps-3">
                                @if($transfer->sender_id == Auth::user()->ulid)
                                    <span class="badge bg-danger">Sent</span>
                                @else
                                    <span class="badge bg-success">Received</span>
                                @endif
                            </td>
                            <td>
                                <span>
                                @if($transfer->sender_id == Auth::user()->ulid)
                                    To: {{ $transfer->receiver_ulid }}
                                @else
                                    From: {{ $transfer->sender_id==1 ? 'Admin' : $transfer->sender_id }}
                                @endif 
                                </span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $transfer->product->product_name }}</span>
                            </td>
                            <td class="text-center fw-bold">
                                @if($transfer->sender_id == Auth::user()->ulid)
                                    <span class="text-danger">-{{ $transfer->quantity }}</span>
                                @else
                                    <span class="text-success">+{{ $transfer->quantity }}</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $transfer->created_at->format('d M y') }}</small>
                                <br>
                                <small class="text-muted">{{ $transfer->created_at->format('h:i A') }}</small>
                            </td>
                            <td class="text-center">
                                <span>
                                    @if($transfer->sender_id == Auth::user()->ulid)
                                        {{ $transfer->sender_balance }}
                                    @else
                                        {{ $transfer->receiver_balance }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <small>
                                    {{ $transfer->from_location }}
                                    <i class="fas fa-arrow-right mx-1 text-muted small"></i>
                                    {{ $transfer->to_location }}
                                </small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-exchange-alt fa-2x text-muted mb-3"></i>
                                <p class="text-muted small mb-0">No stock transfers found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($transfers->hasPages())
            <div class="d-flex justify-content-between align-items-center border-top px-3 py-2">
                <div class="text-muted small">
                    Showing {{ $transfers->firstItem() }} to {{ $transfers->lastItem() }} of {{ $transfers->total() }} entries
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        {{-- Previous Page Link --}}
                        @if ($transfers->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $transfers->previousPageUrl() }}" rel="prev">&laquo;</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($transfers->getUrlRange(1, $transfers->lastPage()) as $page => $url)
                            @if ($page == $transfers->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($transfers->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $transfers->nextPageUrl() }}" rel="next">&raquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">&raquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .table-sm th, 
    .table-sm td {
        padding: 0.8rem;
        font-size: 0.9rem;
    }
    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge {
        font-size: 0.7rem;
        padding: 0.35rem 0.5rem;
    }
    .card-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
    
</style>
@endsection