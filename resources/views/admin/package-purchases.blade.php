@extends('layouts.layout')
@section('title', 'User Package Purchases')
@section('container')

    <div class="container-fluid">
        <div class="row ">
            <div class="col-12 p-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-shopping-bag me-2"></i>Package Purchases
                            </h4>
                            <span class="badge bg-light text-primary fs-6">Total: {{ $purchases->total() }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <!-- Purchases Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="20">#</th>
                                        <th class="p-3">User</th>
                                        <th class="p-3">ULID</th>
                                        <th class="p-3">Package</th>
                                        <th class="p-3">Qty</th>
                                        <th class="p-3">Rate</th>
                                        <th class="p-3">Time</th>
                                        <th class="p-3">Price</th>
                                        <th class="p-3">Maturity</th>
                                        <th class="p-3">Purchase Date</th>
                                        <th width="100">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($purchases as $purchase)
                                        <tr>
                                            <td class="text-muted p-3">
                                                {{ $loop->iteration + ($purchases->currentPage() - 1) * $purchases->perPage() }}
                                            </td>
                                            <td class="p-2">
                                               
                                                    <p class="fw-semibold">{{ $purchase->user->name ?? 'N/A' }}
                                                    </p>
                                                 
                                            </td>
                                            <td class="p-3">
                                                <p class="text-primary">{{ $purchase->ulid }}</p>
                                            </td>
                                            <td class="p-3">
                                                <p class="fw-semibold small">{{ $purchase->package_name }}</p>
                                            </td>
                                            <td class="p-3">
                                                <p>{{ $purchase->quantity }}</p>
                                            </td>
                                            <td class="p-3">
                                                <p>{{ $purchase->rate }}%</p>
                                            </td>
                                            <td class="p-3">
                                                <p class="badge bg-info">{{ $purchase->time }} Years</p>
                                            </td>
                                            <td>
                                                <p class="fw-bold text-primary">₹{{ number_format($purchase->final_price) }}</p>
                                            </td>
                                            <td class="p-3">
                                                @if ($purchase->maturity == 1)
                                                    <span class="badge bg-success">Yes</span>
                                                @else
                                                    <span class="badge bg-secondary">No</span>
                                                @endif
                                            </td>
                                            <td class="p-3">
                                                <div class="small">
                                                    <div>{{ $purchase->created_at->format('d M Y') }}</div>
                                                    <div class="text-muted">{{ $purchase->created_at->format('h:i A') }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary btn-sm" title="View Details"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#purchaseModal{{ $purchase->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>

                                                <!-- Details Modal -->
                                                <div class="modal fade" id="purchaseModal{{ $purchase->id }}"
                                                    tabindex="-1">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title">Purchase Details</h5>
                                                                <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <h6 class="border-bottom pb-2">User Information</h6>
                                                                        <p><strong>Name:</strong>
                                                                            {{ $purchase->user->name ?? 'N/A' }}</p>
                                                                        <p><strong>Email:</strong>
                                                                            {{ $purchase->user->email ?? 'N/A' }}</p>
                                                                        <p><strong>ULID:</strong>
                                                                            <span class="text-info">{{ $purchase->ulid }}</span></p>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <h6 class="border-bottom pb-2">Package Details</h6>
                                                                        <p><strong>Package:</strong>
                                                                            {{ $purchase->package_name }}</p>
                                                                        <p><strong>Quantity:</strong>
                                                                            {{ $purchase->quantity }}</p>
                                                                        <p><strong>Time:</strong> {{ $purchase->time }}
                                                                            Years</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-3">
                                                                    <div class="col-md-6">
                                                                        <h6 class="border-bottom pb-2">Financial Details
                                                                        </h6>
                                                                        <p><strong>Capital:</strong>
                                                                            {{ number_format($purchase->capital, 2) }}%</p>
                                                                        <p><strong>Rate:</strong> {{ $purchase->rate}}%
                                                                        </p>
                                                                        <p><strong>Profit Share:</strong>
                                                                            {{ $purchase->profit_share==1 ? "Yes" : "No" }}
                                                                        </p>
                                                                        <p><strong>Final Price:</strong>
                                                                            ₹{{ number_format($purchase->final_price, 2) }}
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <h6 class="border-bottom pb-2">Status & Additional
                                                                            Info</h6>
                                                                        <p><strong>Maturity:</strong>
                                                                            @if ($purchase->maturity == 1)
                                                                                <span class="badge bg-success">Yes</span>
                                                                            @else
                                                                                <span class="badge bg-secondary">No</span>
                                                                            @endif
                                                                        </p>
                                                                      
                                                                            <p><strong>Invoice No:</strong> <span
                                                                                    class="badge bg-info">{{ $purchase->invoice_no }}</span>
                                                                            </p>
                                                                            <p><strong>BED No:</strong> <span
                                                                                    class="badge bg-primary">{{ $purchase->bed_no }}</span>
                                                                            </p>
                                                                    
                                                                        <p><strong>Purchased At:</strong>
                                                                            {{ $purchase->created_at->format('d M Y, h:i A') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="13" class="text-center py-4">
                                                <div class="empty-state">
                                                    <i class="fas fa-shopping-bag fa-2x text-muted mb-3"></i>
                                                    <h5>No Purchases Found</h5>
                                                    <p class="text-muted mb-0">No package purchases have been made yet.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($purchases->hasPages())
                            <div class="card-footer bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        Showing {{ $purchases->firstItem() }} to {{ $purchases->lastItem() }} of
                                        {{ $purchases->total() }} entries
                                    </div>
                                    <nav>
                                        {{ $purchases->links('pagination::bootstrap-4') }}
                                    </nav>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .empty-state {
            padding: 40px 0;
            text-align: center;
        }
    </style>

@endsection
