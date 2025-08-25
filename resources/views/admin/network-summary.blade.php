@extends('layouts.layout')

@section('title', 'Network Summary - Admin')

@section('container')
    <div class="container pb-4 pt-3">
        <h4 class="mb-3 text-primary">Network Summary - Admin</h4>
        
        <!-- Admin Info Card -->
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body bg-light py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $admin->name }}</h6>
                        <small class="text-muted">AUID: {{ $admin->auid }}</small>
                    </div>
                    <span class="badge bg-info">{{ $paginatedUsers->total() }} Users in Network</span>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-gradient-info text-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-filter me-1"></i>Filters</h6>
                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
            <div class="collapse show" id="filterCollapse">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('admin.network.summary') }}">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <label for="designation" class="form-label small fw-bold">Designation</label>
                                <select class="form-select form-select-sm" id="designation" name="designation">
                                    <option value="">All Designations</option>
                                    @foreach ($designations as $designation)
                                        <option value="{{ $designation }}"
                                            {{ request('designation') == $designation ? 'selected' : '' }}>
                                            {{ $designation }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="status" class="form-label small fw-bold">Status</label>
                                <select class="form-select form-select-sm" id="status" name="status">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="purchase_status" class="form-label small fw-bold">Purchase Status</label>
                                <select class="form-select form-select-sm" id="purchase_status" name="purchase_status">
                                    <option value="">All Status</option>
                                    <option value="paid" {{ request('purchase_status') == 'paid' ? 'selected' : '' }}>Paid
                                    </option>
                                    <option value="unpaid" {{ request('purchase_status') == 'unpaid' ? 'selected' : '' }}>
                                        Unpaid</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="start_date" class="form-label small fw-bold">Start Date</label>
                                <input type="date" class="form-control form-control-sm" id="start_date" name="start_date"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date" class="form-label small fw-bold">End Date</label>
                                <input type="date" class="form-control form-control-sm" id="end_date" name="end_date"
                                    value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-12 text-end mt-2">
                                <a href="{{ route('admin.network.summary') }}"
                                    class="btn btn-sm btn-outline-secondary me-2">Reset</a>
                                <button type="submit" class="btn btn-sm btn-primary">Apply Filters</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Results Section -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-gradient-info text-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-network-wired me-1"></i>Downline Team</h6>
                <span class="badge bg-light text-dark">{{ $paginatedUsers->total() }} Users</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Sr. No</th>
                                <th>Name/Ulid</th>
                                <th class="text-center">Sponsor</th>
                                <th class="text-center">Reg. Date/ Paid Date</th>
                                <th class="text-center">Purchase Status</th>
                                <th class="text-center">Total Purchased</th>
                                <th class="text-center">Level</th>
                                <th class="text-center">Designation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $index = ($paginatedUsers->currentPage() - 1) * $paginatedUsers->perPage() + 1;
                            @endphp
                            @forelse($paginatedUsers as $user)
                                <tr class="align-middle">
                                    <td>{{ $index++ }}</td>
                                    <td class="fw-medium">{{ $user->name }}({{ $user->ulid }})</td>
                                    <td class="text-center"><span class="badge bg-secondary">{{ $user->sponsor_id }}</span>
                                    </td>
                                    <td class="text-center">{{ $user->created_at->format('d M Y') }} /
                                        {{ $user->user_doa ?? 'Not Active' }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $user->purchase_status == 'paid' ? 'bg-success' : 'bg-warning' }} rounded-pill text-capitalize">
                                            {{ $user->purchase_status }}
                                        </span>
                                    </td>
                                    <td
                                        class="text-center fw-medium {{ $user->total_purchases > 0 ? 'text-success' : 'text-muted' }}">
                                        ₹{{ number_format($user->total_purchases, 2) }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill">{{ $user->level }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success rounded-pill text-capitalize">
                                            {{ $user->current_rank ?? 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-3 text-muted small">
                                        <i class="fas fa-users-slash fa-lg mb-1"></i><br>
                                        No downline users found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($paginatedUsers->hasPages())
                    <div class="card-footer bg-light py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="small text-muted">
                                Showing {{ $paginatedUsers->firstItem() }} to {{ $paginatedUsers->lastItem() }} of
                                {{ $paginatedUsers->total() }} entries
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    {{-- Previous Page Link --}}
                                    @if ($paginatedUsers->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">&laquo;</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $paginatedUsers->previousPageUrl() }}"
                                                rel="prev">&laquo;</a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($paginatedUsers->getUrlRange(1, $paginatedUsers->lastPage()) as $page => $url)
                                        @if ($page == $paginatedUsers->currentPage())
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
                                    @if ($paginatedUsers->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $paginatedUsers->nextPageUrl() }}"
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

    <style>
        .table-sm td,
        .table-sm th {
            padding: 0.7rem;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        .card-header {
            border-radius: 0.375rem 0.375rem 0 0 !important;
        }

        .table th {
            white-space: nowrap;
            font-weight: 600;
            font-size: 0.65rem;
            letter-spacing: 0.5px;
        }

        .badge.rounded-pill {
            min-width: 50px;
            font-size: 0.7rem;
            padding: 5px 8px;
        }

        .small {
            font-size: 0.85rem;
        }

        h4 {
            font-size: 1.25rem;
        }

        h6 {
            font-size: 0.9rem;
        }

        .container {
            max-width: 99%;
        }

        .pagination-sm .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .badge.rounded-pill {
                min-width: 50px;
            }

            .card-body .row>div {
                margin-bottom: 1rem;
            }
        }
    </style>
@endsection