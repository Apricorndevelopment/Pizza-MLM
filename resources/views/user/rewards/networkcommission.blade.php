@extends('userlayouts.layouts')
@section('title', 'Dashboard')
@section('container')

<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-coins me-2"></i>Network Commissions
                </h4>
                <span class="badge bg-white text-primary">Level 2 & 3</span>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('user.commissions.level2') }}" class="row g-3 align-items-end">
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
                        <a href="{{ route('user.commissions.level2') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="card-body">
            @if($startDate || $endDate)
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle me-2"></i>
                @if($startDate && $endDate)
                    Showing commissions from <strong>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</strong> 
                    to <strong>{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</strong>
                @elseif($startDate)
                    Showing commissions from <strong>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</strong>
                @elseif($endDate)
                    Showing commissions until <strong>{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</strong>
                @endif
            </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>From User Name</th>
                            <th>From User ID</th>
                            <th>Purchase Amount</th>
                            <th>Commission</th>
                            <th>Level</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commissions as $key => $commission)
                        <tr>
                            <td>{{ ($commissions->currentPage() - 1) * $commissions->perPage() + $key + 1 }}</td>
                            <td>{{ $commission->from_name }}</td>
                            <td>{{ $commission->from_ulid }}</td>
                            <td class="text-success">₹{{ number_format($commission->purchase_amount, 2) }}</td>
                            <td class="text-primary fw-bold">₹{{ number_format($commission->commission, 2) }}</td>
                            <td class="text-secondary">{{ $commission->level }}</td>
                            <td>{{ $commission->created_at->format('d M Y h:i a') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-info-circle me-2"></i>
                                @if($startDate || $endDate)
                                    No commission records found for the selected date range
                                @else
                                    No commission records found
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Bootstrap 5 Pagination -->
            @if($commissions->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $commissions->firstItem() }} to {{ $commissions->lastItem() }} of {{ $commissions->total() }} entries
                    @if($startDate || $endDate)
                        (Filtered)
                    @endif
                </div>
                
                <nav aria-label="Commission pagination">
                    <ul class="pagination mb-0">
                        {{ $commissions->appends(request()->except('page'))->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </ul>
                </nav>
            </div>
            @else
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $commissions->count() }} records
                    @if($startDate || $endDate)
                        (Filtered)
                    @endif
                </div>
            </div>
            @endif
            
            <div class="mt-3">
                <a href="{{ route('user.commissions.level1') }}" class="btn btn-sm btn-outline-primary">
                    View Referral Commissions <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .table th {
        font-weight: 600;
    }
    .table-responsive {
        border-radius: 0.375rem;
        overflow: hidden;
    }
    .pagination {
        margin-bottom: 0;
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