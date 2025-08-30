@extends('userlayouts.layouts')
@section('title', 'Dashboard')
@section('container')

    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-gradient-info text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-layer-group me-2"></i>Level Income Report
                    </h4>
                    <div>
                        <span class="badge bg-white text-info">Total: ₹{{ number_format($totalIncome, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="card-body border-bottom">
                <form method="GET" action="{{ route('user.reports.level-income') }}" class="row g-3 align-items-end">
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
                            <button type="submit" class="btn btn-info text-white">
                                <i class="fas fa-filter me-1"></i> Apply Filter
                            </button>
                            <a href="{{ route('user.reports.level-income') }}" class="btn btn-outline-secondary">
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
                        Showing income from <strong>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</strong> 
                        to <strong>{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</strong>
                    @elseif($startDate)
                        Showing income from <strong>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</strong>
                    @elseif($endDate)
                        Showing income until <strong>{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</strong>
                    @endif
                </div>
                @endif
                
                <div class="table-responsive">
                    <table class="table table-hover table-striped ">
                        <thead class="table-dark">
                            <tr>
                                <th class="p-2 p-md-3">#</th>
                                <th class="p-2 p-md-3">Level</th>
                                <th class="p-2 p-md-3">From User</th>
                                <th class="p-2 p-md-3">Package</th>
                                <th class="p-2 p-md-3">Purchase Amt</th>
                                <th class="p-2 p-md-3">Rate</th>
                                <th class="p-2 p-md-3">Percentage</th>
                                <th class="p-2 p-md-3">Amount</th>
                                <th class="p-2 p-md-3">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($incomes as $key => $income)
                                <tr>
                                    <td class="p-2 p-md-3">{{ ($incomes->currentPage() - 1) * $incomes->perPage() + $loop->iteration }}</td>
                                    <td class="p-2 p-md-3">
                                        <span
                                            class="badge bg-{{ $income->level <= 4 ? 'primary' : ($income->level <= 25 ? 'success' : 'warning') }}">
                                            @if ($income->level <= 4)
                                                Level {{ $income->level }}
                                            @else
                                                Level {{ $income->level }} ({{ $income->level <= 25 ? '5-25' : '26-50' }})
                                            @endif
                                        </span>
                                    </td>
                                    <td class="p-2 p-md-3">{{ $income->from_user_name }}({{ $income->from_user_ulid }})</td>
                                    <td class="p-2 p-md-3">{{ $income->package_name ?? 'N/A' }}</td>
                                    <td class="p-2 p-md-3">₹{{ number_format($income->purchase_amount, 2) }}</td>
                                    <td class="p-2 p-md-3">{{ $income->rate ? $income->rate . '%' : 'N/A' }} </td>
                                    <td class="p-2 p-md-3">{{ $income->percentage }}%</td>
                                    <td class="fw-bold text-success p-2 p-md-3">₹{{ number_format($income->amount, 2) }}</td>
                                    <td class="p-2 p-md-3">{{ $income->created_at->format('d M Y h:i a') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-info-circle me-2"></i>
                                        @if($startDate || $endDate)
                                            No level income records found for the selected date range
                                        @else
                                            No level income records found
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
                    <div class="text-muted small d-none d-md-block">
                        Showing <span class="fw-semibold">{{ $incomes->firstItem() }}</span> to
                        <span class="fw-semibold">{{ $incomes->lastItem() }}</span> of
                        <span class="fw-semibold">{{ $incomes->total() }}</span> entries
                        @if($startDate || $endDate)
                            (Filtered)
                        @endif
                    </div>
                    <div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0">
                                {{ $incomes->appends(request()->except('page'))->onEachSide(1)->links('pagination::bootstrap-5') }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .pagination .page-link {
            color: #495057;
        }

        .pagination .page-link:hover {
            color: #0d6efd;
        }
        
        .table th {
            font-weight: 600;
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