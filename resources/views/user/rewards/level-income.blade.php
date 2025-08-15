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

            <div class="card-body">
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
                                    <td class="p-2 p-md-3">{{ $loop->iteration }}</td>
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
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-info-circle me-2"></i>No level income records found
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
                    </div>
                    <div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0">
                                {{ $incomes->onEachSide(1)->links('pagination::bootstrap-5') }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Add this to your CSS file */
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
    </style>

@endsection
