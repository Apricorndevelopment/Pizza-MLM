@extends('userlayouts.layouts')
@section('title', 'Network Explorer')
@section('container')

<div class="container pt-2 pb-4">
    <!-- Welcome Card -->
    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-body p-4 bg-gradient-primary text-white rounded-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fs-3 mb-1">Welcome back, {{ Auth::user()->name }}</h5>
                    <p class="mb-0">Track your points and loyalty rewards</p>
                </div>
                <div class="icon-shape bg-white bg-opacity-25 rounded-circle p-3">
                    <i class="fas fa-wallet fs-2"></i>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs nav-fill mb-3" id="walletTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active d-flex align-items-center justify-content-center" id="points-tab" data-bs-toggle="tab" data-bs-target="#points" type="button" role="tab">
                <i class="fas fa-coins me-2"></i> Points Wallet
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center justify-content-center" id="loyalty-tab" data-bs-toggle="tab" data-bs-target="#loyalty" type="button" role="tab">
                <i class="fas fa-gem me-2"></i> Loyalty Wallet
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="walletTabContent">
        <!-- Points Tab -->
        <div class="tab-pane fade show active" id="points" role="tabpanel">
            <!-- Balance Card -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-success border-2 h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-muted mb-2">POINTS BALANCE</h6>
                                    <h2 class="mb-0 fw-bold text-success">{{ number_format($points) }}</h2>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                    <i class="fas fa-coins fs-3 text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Withdraw Button -->
                <div class="col-md-6 mt-3 mt-md-0 d-flex align-items-center justify-content-end">
                    <button class="btn btn-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                        <i class="fas fa-money-bill-wave me-2"></i> Withdraw Points
                    </button>
                </div>
            </div>

            <!-- Withdrawal Modal -->
            <div class="modal fade" id="withdrawModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title fw-bold">Withdraw Points</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="withdrawForm" action="{{ route('user.withdraw.points') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> Minimum withdrawal: 500 points
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Available Points</label>
                                    <input type="text" class="form-control-plaintext" value="{{ number_format($points) }}" readonly>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="withdrawAmount" class="form-label fw-bold">Amount to Withdraw</label>
                                    <input type="number" class="form-control" name="amount" id="withdrawAmount" 
                                           placeholder="Enter points amount" required>
                                    <div class="form-text">1 point = ₹1</div>
                                </div>

                                @php
                                    $user = Auth::user();
                                @endphp  
                                
                                <div class="mb-3">
                                    <label for="paymentMethod" class="form-label fw-bold">Payment Method</label>
                                    <select class="form-select" name="payment_method" id="paymentMethod" required>
                                        <option value="">Select Payment Method</option>
                                        @if ($user->account_no && $user->ifsc_code)
                                            <option value="bank">Bank Transfer</option>
                                        @endif
                                        @if ($user->upi_id)
                                            <option value="upi">UPI Transfer</option>
                                        @endif
                                    </select>
                                    @if (!$user->account_no && !$user->upi_id)
                                        <div class="alert alert-danger mt-2">
                                            <i class="fas fa-exclamation-circle me-2"></i> Please add payment details in your profile
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="alert alert-warning mt-4">
                                    <h6 class="fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Important Note</h6>
                                    <ul class="mb-0 ps-3">
                                        <li>Withdrawal requests are processed within 7 business days</li>
                                        <li>Tax deduction of 5% Admin Charge will be applied</li>
                                        <li>Tax deduction of 5% TDS Charge will be applied</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" onclick="return confirm('Are you sure you want to withdraw points')" class="btn btn-primary" id="submitWithdraw" 
                                    @if (!$user->account_no && !$user->upi_id) disabled @endif>
                                    <i class="fas fa-paper-plane me-2"></i> Submit Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Withdrawal History -->
            @if ($withdrawals->count() > 0)
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-history me-2 text-primary"></i> Withdrawal History
                        </h5>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-light text-dark me-2">{{ $withdrawals->total() }} records</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Date</th>
                                        <th>Amount</th>
                                        <th>After Tax</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($withdrawals as $withdrawal)
                                        <tr>
                                            <td class="ps-4">{{ $withdrawal->created_at->format('d M Y') }}</td>
                                            <td class="fw-bold">₹{{ number_format($withdrawal->total_amount, 2) }}</td>
                                            <td>₹{{ number_format($withdrawal->credited_amount, 2) }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ ucfirst($withdrawal->payment_method) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($withdrawal->status === 'pending')
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-clock me-1"></i> Pending
                                                    </span>
                                                @elseif($withdrawal->status === 'approved')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i> Approved
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times-circle me-1"></i> Rejected
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr class="collapse" id="details-{{ $withdrawal->id }}">
                                            <td colspan="6" class="bg-light">
                                                <div class="p-3">
                                                    <h6 class="fw-bold mb-3">Payment Details</h6>
                                                    @if ($withdrawal->payment_method === 'bank')
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <p class="mb-1"><strong>Account Number:</strong></p>
                                                                <p>{{ $withdrawal->user->account_no }}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <p class="mb-1"><strong>IFSC Code:</strong></p>
                                                                <p>{{ $withdrawal->user->ifsc_code }}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <p class="mb-1"><strong>Bank Name:</strong></p>
                                                                <p>{{ $withdrawal->user->bank_name }}</p>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <p class="mb-1"><strong>UPI ID:</strong></p>
                                                        <p>{{ $withdrawal->user->upi_id }}</p>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Withdrawals Pagination -->
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <div class="text-muted">
                                Showing {{ $withdrawals->firstItem() }} to {{ $withdrawals->lastItem() }} of {{ $withdrawals->total() }} entries
                            </div>
                            <nav aria-label="Withdrawals pagination">
                                <ul class="pagination mb-0">
                                    {{ $withdrawals->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Points Transactions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-2">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-exchange-alt me-2 text-primary"></i> Points Transactions
                        </h5>

                    </div>
                    
                    <!-- Filters Form -->
                    <div class="mt-3">
                        <form method="GET" action="{{ route('user.viewwallet') }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="points_type" class="form-label small fw-bold">Transaction Type</label>
                                    <select class="form-select form-select-sm" name="points_type" id="points_type">
                                        <option value="">All Transactions</option>
                                        <option value="credit" {{ request('points_type') == 'credit' ? 'selected' : '' }}>Credits</option>
                                        <option value="debit" {{ request('points_type') == 'debit' ? 'selected' : '' }}>Debits</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="points_start_date" class="form-label small fw-bold">Start Date</label>
                                    <input type="date" class="form-control form-control-sm" name="points_start_date" 
                                           id="points_start_date" value="{{ request('points_start_date') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="points_end_date" class="form-label small fw-bold">End Date</label>
                                    <input type="date" class="form-control form-control-sm" name="points_end_date" 
                                           id="points_end_date" value="{{ request('points_end_date') }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary btn-sm me-2">
                                        <i class="fas fa-search me-1"></i> Apply
                                    </button>
                                    <a href="{{ route('user.viewwallet') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-times me-1"></i> Clear
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if ($pointsTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Date</th>
                                        <th>Description</th>
                                        <th class="text-end pe-4">Points</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pointsTransactions as $transaction)
                                        <tr>
                                            <td class="ps-4">{{ $transaction->created_at->format('d M Y') }}</td>
                                            <td>{{ $transaction->notes ?? 'N/A' }}</td>
                                            <td class="text-end pe-4">
                                                <span class="fw-bold {{ $transaction->points >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $transaction->points >= 0 ? '+' : '' }}{{ $transaction->points }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Points Transactions Pagination -->
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <div class="text-muted">
                                Showing {{ $pointsTransactions->firstItem() }} to {{ $pointsTransactions->lastItem() }} of {{ $pointsTransactions->total() }} entries
                            </div>
                            <nav aria-label="Points transactions pagination">
                                <ul class="pagination mb-0">
                                    {{ $pointsTransactions->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
                                </ul>
                            </nav>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-exchange-alt fa-3x text-muted"></i>
                            </div>
                            <h5 class="fw-bold text-muted">No transactions found</h5>
                            <p class="text-muted">Try adjusting your filters</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Loyalty Tab -->
        <div class="tab-pane fade" id="loyalty" role="tabpanel">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-info border-2 h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-muted mb-2">LOYALTY BALANCE</h6>
                                    <h2 class="mb-0 fw-bold text-info">{{ number_format($loyalty) }}</h2>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                    <i class="fas fa-gem fs-3 text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mt-3 mt-md-0 d-flex align-items-center">
                    <div class="alert alert-info mb-0 w-100">
                        <i class="fas fa-info-circle me-2"></i> Loyalty points can be redeemed for exclusive rewards
                    </div>
                </div>
            </div>

            <!-- Loyalty Transactions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-exchange-alt me-2 text-primary"></i> Loyalty Transactions
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if ($loyaltyTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Date</th>
                                        <th>Description</th>
                                        <th class="text-end pe-4">Points</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($loyaltyTransactions as $transaction)
                                        <tr>
                                            <td class="ps-4">{{ $transaction->created_at->format('d M Y') }}</td>
                                            <td>{{ $transaction->notes ?? 'N/A' }}</td>
                                            <td class="text-end pe-4">
                                                <span class="fw-bold {{ $transaction->loyalty >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $transaction->loyalty >= 0 ? '+' : '' }}{{ $transaction->loyalty }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-gem fa-3x text-muted"></i>
                            </div>
                            <h5 class="fw-bold text-muted">No loyalty transactions yet</h5>
                            <p class="text-muted">Your loyalty transactions will appear here</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const withdrawForm = document.getElementById('withdrawForm');
        const withdrawAmount = document.getElementById('withdrawAmount');
        const submitBtn = document.getElementById('submitWithdraw');
        const pointsBalance = {{ $points }};

        withdrawAmount.addEventListener('input', function() {
            const amount = parseFloat(this.value);
            const errorElement = document.getElementById('amountError');
            
            if (isNaN(amount) || amount < 500 || amount > pointsBalance) {
                if (!errorElement) {
                    const div = document.createElement('div');
                    div.id = 'amountError';
                    div.className = 'invalid-feedback d-block';
                    div.textContent = amount < 500 ? 
                        'Minimum withdrawal is 500 points' : 
                        'Amount exceeds your available balance';
                    this.parentNode.appendChild(div);
                }
                this.classList.add('is-invalid');
                submitBtn.disabled = true;
            } else {
                if (errorElement) errorElement.remove();
                this.classList.remove('is-invalid');
                submitBtn.disabled = false;
            }
        });
    });
</script>

@endsection