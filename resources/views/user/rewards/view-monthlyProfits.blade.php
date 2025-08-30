@extends('userlayouts.layouts')
@section('title', 'Monthly Profits')

@section('container')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-coins me-2"></i> Monthly Profit Distributions
                </h5>
                <div class="badge bg-white text-primary fs-6">
                    Total: ₹{{ number_format($totalAmount, 2) }}
                </div>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('user.monthly.profits') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">From Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ $startDate }}" max="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">To Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ $endDate }}" max="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Apply Filter
                        </button>
                        <a href="{{ route('user.monthly.profits') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="card-body p-0">
            @if($startDate || $endDate)
            <div class="alert alert-info m-3 mb-0">
                <i class="fas fa-info-circle me-2"></i>
                @if($startDate && $endDate)
                    Showing distributions from <strong>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</strong> 
                    to <strong>{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</strong>
                @elseif($startDate)
                    Showing distributions from <strong>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</strong>
                @elseif($endDate)
                    Showing distributions until <strong>{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</strong>
                @endif
            </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th class="ps-4">Date</th>
                            <th>Package</th>
                            <th>Investment</th>
                            <th>Rate</th>
                            <th>Profit</th>
                            <th class="pe-4">Remaining</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($distributions as $key => $distribution)
                        <tr>
                            <td class="ps-4">{{ ($distributions->currentPage() - 1) * $distributions->perPage() + $loop->iteration }}</td>
                            <td class="ps-4">
                                {{ $distribution->distribution_date }}
                            </td>
                            <td>
                                {{ $distribution->packagePurchase->package_name ?? 'N/A' }}
                            </td>
                            <td class="fw-bold">
                                ₹{{ number_format($distribution->purchase_amount, 2) }}
                            </td>
                            <td>
                                {{ $distribution->rate_percentage }}%
                            </td>
                            <td class="fw-bold text-success">
                                +₹{{ number_format($distribution->distributed_amount, 2) }}
                            </td>
                            <td class="pe-4">
                                {{ $distribution->months_remaining }} months
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-wallet fa-2x text-muted mb-3"></i>
                                <p class="text-muted">
                                    @if($startDate || $endDate)
                                        No profit distributions found for the selected date range
                                    @else
                                        No profit distributions found
                                    @endif
                                </p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($distributions->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing {{ $distributions->firstItem() }} to {{ $distributions->lastItem() }} of {{ $distributions->total() }} entries
                        @if($startDate || $endDate)
                            (Filtered)
                        @endif
                    </div>
                    {{ $distributions->appends(request()->except('page'))->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @else
            <div class="card-footer bg-white">
                <div class="text-muted small">
                    Showing {{ $distributions->count() }} entries
                    @if($startDate || $endDate)
                        (Filtered)
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }
    .card-footer {
        padding: 1rem;
    }
</style>

<script>
    // Set max date for end_date when start_date is selected
    document.getElementById('start_date').addEventListener('change', function() {
        document.getElementById('end_date').min = this.value;
    });
    
    // Set min date for start_date when end_date is selected
    document.getElementById('end_date').addEventListener('change', function() {
        document.getElementById('start_date').max = this.value;
    });
    
    // Initialize min/max values on page load
    document.addEventListener('DOMContentLoaded', function() {
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        
        if(startDate.value && endDate.value) {
            endDate.min = startDate.value;
            startDate.max = endDate.value;
        }
    });
</script>
@endsection