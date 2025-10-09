@extends('userlayouts.layouts')
@section('title', 'Maturity Packages')
@section('container')

<style>
    .package-table {
        font-size: 0.875rem;
    }
    .package-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        padding: 0.75rem;
        white-space: nowrap;
    }
    .package-table td {
        padding: 0.75rem;
        vertical-align: middle;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }
    .badge-paid {
        background-color: #28a745;
    }
    .badge-pending {
        background-color: #ffc107;
        color: #000;
    }
    .badge-penalty {
        background-color: #dc3545;
    }
    .compact-btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .deduction-table {
        font-size: 0.8rem;
    }
    .accordion-button:not(.collapsed) {
        background-color: #f8f9fa;
        color: #0d6efd;
    }
</style>

<div class="container py-3">
    <!-- Maturity Packages Table -->
    <div class="card shadow-sm maturity-card mb-4">
        <div class="card-header bg-success text-white py-2">
            <h5 class="mb-0"><i class="fas fa-certificate me-2"></i>Maturity Packages</h5>
        </div>
        <div class="card-body p-0">
            @if ($maturityPackages->isEmpty())
                <div class="alert alert-info m-3">You don't have any maturity packages yet.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover package-table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Package Name</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-start">Price</th>
                                <th class="text-start">Rate</th>
                                <th class="text-start">Time</th>
                                <th class="text-start">Purchase Date</th>
                                <th class="text-start pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($maturityPackages as $index => $package)
                            <tr>
                                <td class="ps-3 fw-medium">{{ $index + 1 }}</td>
                                <td>
                                    <div>
                                        <p>{{ $package->package_name }}</p>
                                        <small class="badge bg-success mt-0">Maturity</small>
                                    </div>
                                </td>
                                <td class="text-center">{{ $package->quantity }}</td>
                                <td class="text-start fw-medium">₹{{ number_format($package->final_price, 2) }}</td>
                                <td class="text-start">{{ $package->rate }}%</td>
                                <td class="text-start">{{ $package->time ?? '0' }} yrs</td>
                                <td class="text-start">{{ $package->created_at->format('d M Y') }}</td>
                                <td class="text-start pe-3">
                                    <a href="{{ route('user.packages.maturity.invoice', ['id' => $package->id]) }}"
                                        class="btn btn-sm btn-outline-primary compact-btn mb-1">
                                        <i class="fas fa-receipt me-1"></i>Invoice
                                    </a>
                                    <a href="{{ route('user.packages.endorse', ['id' => $package->id]) }}"
                                        class="btn btn-sm btn-outline-success compact-btn">
                                        <i class="fas fa-stamp me-1"></i>Endorse
                                    </a>
                                </td>
                            </tr>
                            
                            <!-- Deduction Details Accordion -->
                            <tr>
                                <td colspan="8" class="p-0 border-0">
                                    <div class="accordion accordion-flush" id="accordionPackage{{ $package->id }}">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#flush-collapse{{ $package->id }}">
                                                    <i class="fas fa-calendar-alt me-2"></i>
                                                    View Monthly Deductions ({{ $package->maturityMonthlyDeductions->count() }})
                                                </button>
                                            </h2>
                                            <div id="flush-collapse{{ $package->id }}" class="accordion-collapse collapse" 
                                                data-bs-parent="#accordionPackage{{ $package->id }}">
                                                <div class="accordion-body">
                                                    @if($package->maturityMonthlyDeductions->count() > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-striped deduction-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Month</th>
                                                                    <th>Deduction Amount</th>
                                                                    <th>Penalty</th>
                                                                    <th>Total Deduction</th>
                                                                    <th>Status</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($package->maturityMonthlyDeductions as $deduction)
                                                                <tr>
                                                                    <td>{{ $deduction->deduction_month }}</td>
                                                                    <td>₹{{ number_format($deduction->deduction_amount, 2) }}</td>
                                                                    <td>₹{{ number_format($deduction->penalty_amount, 2) }}</td>
                                                                    <td class="fw-bold">₹{{ number_format($deduction->total_deduction, 2) }}</td>
                                                                    <td>
                                                                       @if($deduction->status === 'paid')
                                                                        <span class="badge badge-paid">Paid</span>
                                                                        @else
                                                                        <span class="badge badge-pending">Pending</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if($deduction->status === 'pending')
                                                                        <form action="{{ route('user.maturity.pay-deduction', $deduction->id) }}" 
                                                                            method="POST" class="d-inline">
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-sm btn-success" 
                                                                                onclick="return confirm('Pay ₹{{ number_format($deduction->total_deduction, 2) }} for {{ $deduction->deduction_month }}?')">
                                                                                Pay Now
                                                                            </button>
                                                                        </form>
                                                                        @else
                                                                        <span class="text-muted">Paid on {{ $deduction->deducted_at->format('d M Y') }}</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    @else
                                                    <div class="alert alert-info mb-0">
                                                        No deductions recorded yet. Deductions are processed monthly.
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
            @endif
        </div>
    </div>
</div>

@endsection