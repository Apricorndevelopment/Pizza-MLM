@extends('layouts.layout')
@section('title', 'Wallet Management')
@section('container')

    <div class="container mt-1">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#pointsTab">Points Wallet</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#rewardsTab">Loyalty Rewards</a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">
                    <!-- Points Tab -->
                    <div class="tab-pane fade show active" id="pointsTab">
                        <form action="{{ route('admin.addPoints') }}" method="POST" id="pointsForm">
                            @csrf
                            <input type="hidden" name="ulid" id="pointsULIDHidden">
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <label for="pointsULID" class="form-label">Enter User ULID</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control p-2" id="pointsULID"
                                            placeholder="Search by ULID">
                                        <button class="btn btn-primary" type="button" id="searchPointsUser">Search</button>
                                    </div>
                                </div>
                            </div>

                            <div id="pointsUserDetails" class="mb-4 p-3 border rounded" style="display: none;">
                                <h5>User Information</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong>Name:</strong> <span id="pointsUserName"></span></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Email:</strong> <span id="pointsUserEmail"></span></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Current Points:</strong> <span id="pointsUserBalance">0</span></p>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label for="pointsAmount" class="form-label">Points to Add/Deduct</label>
                                        <input type="number" class="form-control" id="pointsAmount" name="points"
                                            placeholder="Enter amount">
                                        <small class="text-muted">Use negative value to deduct points</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="pointsNotes" class="form-label">Notes</label>
                                        <input type="text" class="form-control" id="pointsNotes" name="notes"
                                            placeholder="Transaction notes">
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-success">Submit Points</button>
                                </div>
                            </div>
                        </form>

                        <!-- Filters Section -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Transactions</h6>
                            </div>
                            <div class="card-body py-3">
                                <form method="GET" action="{{ route('admin.wallet') }}">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-3">
                                            <label for="points_ulid" class="form-label small fw-semibold">Search by
                                                ULID</label>
                                            <input type="text" class="form-control form-control-sm" id="points_ulid"
                                                name="points_ulid" value="{{ request('points_ulid') }}"
                                                placeholder="Enter ULID">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="points_type" class="form-label small fw-semibold">Type</label>
                                            <select class="form-select form-select-sm" id="points_type" name="points_type">
                                                <option value="">All</option>
                                                <option value="credit"
                                                    {{ request('points_type') == 'credit' ? 'selected' : '' }}>Credit (+)
                                                </option>
                                                <option value="debit"
                                                    {{ request('points_type') == 'debit' ? 'selected' : '' }}>Debit (-)
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="points_start_date" class="form-label small fw-semibold">Start
                                                Date</label>
                                            <input type="date" class="form-control form-control-sm"
                                                id="points_start_date" name="points_start_date"
                                                value="{{ request('points_start_date') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="points_end_date" class="form-label small fw-semibold">End
                                                Date</label>
                                            <input type="date" class="form-control form-control-sm"
                                                id="points_end_date" name="points_end_date"
                                                value="{{ request('points_end_date') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                <i class="fas fa-check me-1"></i>Apply
                                            </button>
                                        </div>
                                        <div class="col-md-1">
                                            <a href="{{ route('admin.wallet') }}"
                                                class="btn btn-outline-secondary btn-sm w-100">
                                                <i class="fas fa-sync"></i>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h5 class="mb-3">Recent Points Transactions</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>ULID/Name</th>
                                            <th>Debit/Credit</th>
                                            <th>Balance</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pointsTransactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <a href="javascript:void(0)"
                                                        class="user-ulid-link text-decoration-none"
                                                        data-ulid="{{ optional($transaction->user)->ulid }}">
                                                        {{ optional($transaction->user)->name }}
                                                        ({{ optional($transaction->user)->ulid }})
                                                    </a>
                                                </td>
                                                <td
                                                    class="{{ $transaction->points >= 0 ? 'text-success' : 'text-danger' }} fw-medium">
                                                    {{ $transaction->points >= 0 ? '+' : '' }}{{ $transaction->points }}
                                                </td>
                                                <td>{{ $transaction->balance ?? 'N/A' }}</td>
                                                <td style="max-width: 500px; white-space: normal; word-wrap: break-word;">
                                                    {{ $transaction->notes ? $transaction->notes : 'N/A' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted small">
                                    Showing <span class="fw-semibold">{{ $pointsTransactions->firstItem() }}</span> to
                                    <span class="fw-semibold">{{ $pointsTransactions->lastItem() }}</span>
                                    of <span class="fw-semibold">{{ $pointsTransactions->total() }}</span> entries
                                </div>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0">
                                        {{-- Previous Page Link --}}
                                        @if ($pointsTransactions->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">&laquo;</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $pointsTransactions->appends(request()->except('points_page'))->previousPageUrl() }}"
                                                    rel="prev">&laquo;</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($pointsTransactions->getUrlRange(1, $pointsTransactions->lastPage()) as $page => $url)
                                            @if ($page == $pointsTransactions->currentPage())
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $page }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $pointsTransactions->appends(request()->except('points_page'))->url($page) }}">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($pointsTransactions->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $pointsTransactions->appends(request()->except('points_page'))->nextPageUrl() }}"
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
                    </div>

                    <!-- Loyalty Rewards Tab - MOVED OUTSIDE POINTS TAB -->
                    <div class="tab-pane fade" id="rewardsTab">
                        <form action="{{ route('admin.addLoyalty') }}" method="post" id="rewardsForm">
                            @csrf
                            <input type="hidden" name="ulid" id="rewardsULIDHidden">
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <label for="rewardsULID" class="form-label">Enter User ULID</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="rewardsULID"
                                            placeholder="Search by ULID">
                                        <button class="btn btn-primary" type="button"
                                            id="searchRewardsUser">Search</button>
                                    </div>
                                </div>
                            </div>

                            <!-- User Details Section (hidden by default) -->
                            <div id="rewardsUserDetails" class="mb-4 p-3 border rounded" style="display: none;">
                                <h5>User Information</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong>Name:</strong> <span id="rewardsUserName"></span></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Email:</strong> <span id="rewardsUserEmail"></span></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Current Loyalty:</strong> <span id="rewardsUserBalance">0</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label for="rewardsAmount" class="form-label">Loyalty to
                                            Add/Deduct</label>
                                        <input type="number" class="form-control" id="rewardsAmount" name="loyalty"
                                            placeholder="Enter amount">
                                        <small class="text-muted">Use negative value to deduct loyalty</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="rewardsNotes" class="form-label">Notes</label>
                                        <input type="text" class="form-control" id="rewardsNotes" name="notes"
                                            placeholder="Transaction notes">
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-success">Submit Loyalty</button>
                                </div>
                            </div>
                        </form>

                        <!-- Rewards Transaction History -->
                        <div class="mt-4">
                            <h5>Recent Loyalty Transactions</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>ULID</th>
                                            <th>Name</th>
                                            <th>Loyalty</th>
                                            <th>Notes</th>
                                            <th>Admin Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($loyaltyTransactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                                                <td>{{ optional($transaction->user)->ulid }}</td>
                                                <td>{{ optional($transaction->user)->name }}</td>
                                                <td
                                                    class="{{ $transaction->loyalty >= 0 ? 'text-success' : 'text-danger' }} fw-medium">
                                                    {{ $transaction->loyalty >= 0 ? '+' : '' }}{{ $transaction->loyalty }}
                                                </td>
                                                <td>{{ $transaction->notes ? $transaction->notes : 'N/A' }}</td>
                                                <td>{{ optional($transaction->admin)->name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript for AJAX functionality -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // ULID click functionality
                document.querySelectorAll('.user-ulid-link').forEach(link => {
                    link.addEventListener('click', function() {
                        const ulid = this.getAttribute('data-ulid');
                        if (ulid) {
                            document.getElementById('pointsULID').value = ulid;
                            document.getElementById('pointsULID').focus();
                            document.getElementById('searchPointsUser').click();
                        }
                    });
                });

                // Points search functionality
                document.getElementById('searchPointsUser').addEventListener('click', function() {
                    const ulid = document.getElementById('pointsULID').value;
                    if (ulid) {
                        fetch('/admin/get-user-by-ulid', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .content
                                },
                                body: JSON.stringify({
                                    ulid: ulid
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('pointsUserName').textContent = data.user.name;
                                    document.getElementById('pointsUserEmail').textContent = data.user
                                    .email;
                                    document.getElementById('pointsUserBalance').textContent = data.user
                                        .wallet1_balance;
                                    document.getElementById('pointsUserDetails').style.display = 'block';
                                } else {
                                    alert('User not found');
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });

                // Points form submission with confirmation
                document.getElementById('pointsForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const ulid = document.getElementById('pointsULID').value;
                    const pointsAmount = document.getElementById('pointsAmount').value;
                    document.getElementById('pointsULIDHidden').value = ulid;

                    if (!pointsAmount) {
                        alert('Please enter points amount');
                        return;
                    }

                    const action = parseInt(pointsAmount) >= 0 ? 'add' : 'deduct';
                    const pointsAbsolute = Math.abs(pointsAmount);
                    const confirmed = confirm(
                        `Are you sure you want to ${action} ${pointsAbsolute} points to user ${ulid}?`);

                    if (confirmed) {
                        this.submit();
                    }
                });

                // Rewards search functionality
                document.getElementById('searchRewardsUser').addEventListener('click', function() {
                    const ulid = document.getElementById('rewardsULID').value;
                    if (ulid) {
                        fetch('/admin/get-user-by-ulid', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .content
                                },
                                body: JSON.stringify({
                                    ulid: ulid
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('rewardsUserName').textContent = data.user.name;
                                    document.getElementById('rewardsUserEmail').textContent = data.user
                                        .email;
                                    document.getElementById('rewardsUserBalance').textContent = data.user
                                        .wallet2_balance;
                                    document.getElementById('rewardsUserDetails').style.display = 'block';
                                } else {
                                    alert('User not found');
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });

                // Rewards form submission with confirmation
                document.getElementById('rewardsForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const ulid = document.getElementById('rewardsULID').value;
                    const rewardsAmount = document.getElementById('rewardsAmount').value;
                    document.getElementById('rewardsULIDHidden').value = ulid;

                    if (!rewardsAmount) {
                        alert('Please enter loyalty amount');
                        return;
                    }

                    const action = parseInt(rewardsAmount) >= 0 ? 'add' : 'deduct';
                    const rewardsAbsolute = Math.abs(rewardsAmount);
                    const confirmed = confirm(
                        `Are you sure you want to ${action} ${rewardsAbsolute} loyalty points to user ${ulid}?`
                        );

                    if (confirmed) {
                        this.submit();
                    }
                });
            });
        </script>

        <style>
            .nav-tabs .nav-link {
                font-weight: 500;
                color: #495057;
            }

            .nav-tabs .nav-link.active {
                font-weight: 600;
                color: #0d6efd;
            }

            .table th {
                font-size: 13px;
                font-weight: 500;
            }

            .table td {
                font-size: 13px;
                vertical-align: middle;
            }

            .text-success {
                font-weight: 500;
            }

            .text-danger {
                font-weight: 500;
            }

            .user-ulid-link {
                color: #0d6efd;
            }

            .user-ulid-link:hover {
                text-decoration: underline;
            }

            .pagination {
                margin-bottom: 0;
            }

            .pagination .page-link {
                font-size: 0.875rem;
                padding: 0.25rem 0.5rem;
            }

            .card-header.bg-light {
                background-color: #f8f9fa !important;
            }

        </style>
    @endsection
