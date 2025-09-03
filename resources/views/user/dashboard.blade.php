@extends('userlayouts.layouts')
@section('title', 'Dashboard')
@section('container')
    <style>
        :root {
            --power-leg-gradient: linear-gradient(135deg, #00c9ff, #92fe9d);
            --weaker-leg-gradient: linear-gradient(135deg, #f093fb, #f5576c);
            --referral-gradient: linear-gradient(135deg, #4facfe, #00f2fe);
            --network-gradient: linear-gradient(135deg, #fa709a, #fee140);
            --monthly-gradient: linear-gradient(135deg, #a8edea, #fed6e3);
        }

        .card-tale {
            background: linear-gradient(135deg, #a29bfe, #6c5ce7);
            color: white;
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(108, 92, 231, 0.3);
        }

        .card-dark-blue {
            background: linear-gradient(135deg, #0984e3, #74b9ff);
            color: white;
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(9, 132, 227, 0.3);
        }

        .fs-30 {
            font-size: 2rem;
            font-weight: 700;
        }

        .transparent {
            background-color: transparent !important;
        }

        .shadow-inset {
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .avatar-lg {
            height: 160px;
            width: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            overflow: hidden;
        }

        .avatar-lg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }


        .bg-gradient-primary {
            background: linear-gradient(135deg, #6c5ce7, #341f97) !important;
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #fdcb6e, #e17055) !important;
        }

        .rounded-pill {
            border-radius: 50rem !important;
        }

        .badge {
            font-size: 0.85rem;
        }

        select.form-select {
            border-radius: 0.5rem;
        }

        .btn-lg {
            font-size: 1.1rem;
        }

        .fw-medium {
            font-weight: 500;
        }

        .custom-card {
            border-radius: 16px;
            color: white;
            border: none;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .custom-card hr {
            border-color: rgba(255, 255, 255, 0.3);
        }

        .custom-card .small {
            opacity: 0.8;
        }

        .dashboard-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            color: #fff;
            position: relative;
            height: 100%;
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            transition: height 0.5s ease;
            z-index: 1;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }

        .dashboard-card:hover::before {
            height: 100%;
        }

        .card-content {
            position: relative;
            z-index: 2;
            padding: 1.5rem;
        }

        .card-icon {
            font-size: 1.8rem;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .card-title {
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .card-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .card-divider {
            height: 2px;
            background: rgba(255, 255, 255, 0.3);
            border: none;
            margin: 12px 0;
            opacity: 0.7;
            transition: all 0.3s ease;
        }

        .dashboard-card:hover .card-divider {
            opacity: 1;
            transform: scaleX(1.05);
        }

        .card-description {
            font-size: 0.85rem;
            opacity: 0.85;
            margin-bottom: 0;
        }

        .weaker-leg-card {
            background: var(--weaker-leg-gradient);
        }

        .referral-card {
            background: var(--referral-gradient);
        }

        .network-card {
            background: var(--network-gradient);
        }

        .monthly-card {
            background: var(--monthly-gradient);
            color: #333;
        }

        .monthly-card .card-divider {
            background: rgba(0, 0, 0, 0.2);
        }

        .monthly-card .card-description {
            color: #444;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .dashboard-title {
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .dashboard-subtitle {
            color: #6c757d;
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('sales-chart').getContext('2d');
            const filterSelect = document.getElementById('filter-select');

             function formatInLakhsCrores(number) {
            if (number >= 1000000000000) {
                return '₹' + (number / 1000000000000).toFixed(3) + 'T';
            } else if (number >= 1000000000) {
                return '₹' + (number / 1000000000).toFixed(2) + 'B';
            } else if (number >= 10000000) {
                return '₹' + (number / 10000000).toFixed(2) + 'Cr';
            } else if (number >= 100000) {
                return '₹' + (number / 100000).toFixed(2) + 'L';
            } else {
                return '₹' + number.toLocaleString();
            }
        }

            let salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Business',
                        data: [],
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4e73df',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                   return formatInLakhsCrores(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            });

            // Fetch chart data
            function loadChartData(filter = 'monthly') {
                fetch(`{{ route('dashboard.sales.data') }}?filter=${filter}`)
                    .then(response => response.json())
                    .then(data => {
                        salesChart.data.labels = data.labels;
                        salesChart.data.datasets[0].data = data.data;
                        salesChart.update();
                    })
                    .catch(error => console.error('Error fetching chart data:', error));
            }

            // Initial load
            loadChartData('monthly');

            // On filter change
            filterSelect.addEventListener('change', function() {
                loadChartData(this.value);
            });
        });
    </script>


    <div class="container-fluid py-3">
        <!-- Header Section -->
        <div class="row mb-2">
            <div class="col-12">
                <div class="card bg-gradient-primary shadow-inset border-0">
                    <div class="card-body py-3 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="d-flex align-items-center gap-3">
                                    <h2 class="fw-bold text-white mb-1 fs-3 fs-md-2">Welcome back, {{ Auth::user()->name }}!
                                    </h2>
                                    <span class="badge bg-white text-primary px-2 px-md-3 py-1 py-md-2 rounded-pill">
                                        <i class="fas fa-circle me-1 small"></i>
                                        <span class="fs-6 fs-md-5">{{ ucfirst(Auth::user()->status) }}</span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column mt-2 gap-2">
                                    <span class="text-white fs-6 fs-md-5">
                                        <i class="fas fa-user me-1"></i> User Name:
                                        {{ Auth::user()->ulid }}
                                    </span>
                                    <span class="text-white fs-6 fs-md-5">
                                        <i class="fas fa-trophy me-1"></i> Rank:
                                        {{ Auth::user()->current_rank ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                            <div class="avatar avatar-lg rounded-circle shadow d-none d-md-block">
                                <img src="abcd.webp" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts Section -->
        <div class="row mb-2">
            <div class="col-12">
                @session('success')
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        {{ session('coupon_code') ? ' and You got a Coupon: ' . session('coupon_code') : '' }}
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endsession

                @session('error')
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endsession
            </div>
        </div>

        @if (Auth::user()->status == 'inactive')
            <!-- Activation Modal -->
            <div class="modal fade" id="activationModal" tabindex="-1" aria-labelledby="activationModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-gradient-primary text-white">
                            <h5 class="modal-title" id="activationModalLabel">Activate Your Account</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if ($packages->isNotEmpty())
                                @php $firstPackage = $packages->first(); @endphp
                                <div class="card mb-3 border-primary">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $firstPackage->package_name }}</h5>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="fw-medium">Price:</span>
                                            <span class="fw-bold">₹{{ $firstPackage->price }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="fw-medium">Package Quantity:</span>
                                            <span class="fw-bold">{{ $firstPackage->package_quantity }}</span>
                                        </div>

                                        <hr>
                                        <p class="small text-muted">To activate your account, you need to purchase this
                                            starter package.</p>
                                    </div>
                                </div>

                                <form id="activationForm" action="{{ route('user.purchase-package') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="package_id" value="{{ $firstPackage->id }}">

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="fw-medium">Your Balance:</span>
                                        <span class="badge bg-success rounded-pill px-3">
                                            ₹{{ Auth::user()->points_balance }}
                                        </span>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit"
                                            onclick="return confirm('Are you sure you want to purchase package ?')"
                                            class="btn btn-success btn-lg">
                                            <i class="fas fa-shopping-cart me-2"></i> Purchase Package
                                            (₹{{ $firstPackage->price }})
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-warning">No packages available for activation</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Original activation button (now triggers modal) -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-gradient-warning text-white border-0 py-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <h5 class="mb-0 fw-semibold">Account Activation Required</h5>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center py-3">
                                <button id="activateBtn" class="btn btn-primary btn-lg px-4 rounded-pill"
                                    data-bs-toggle="modal" data-bs-target="#activationModal">
                                    <i class="fas fa-bolt me-2"></i>Activate Account
                                </button>
                                <p class="text-muted mt-2">Activate your account to access all features</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card shadow-lg border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="card-title mb-0 text-primary">Sales Performance Report</h5>
                                <p class="text-muted mb-0">Track your business growth over time</p>
                            </div>
                            <div>
                                <select id="filter-select" class="form-select form-select-sm border-primary">
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly" selected>Monthly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary rounded p-2 me-2">
                                <i class="bi bi-graph-up text-white"></i>
                            </div>
                            <p class="text-muted mb-0 flex-grow-1">
                                Analyze your total business performance with interactive charts
                            </p>
                        </div>

                        <div style="overflow-x: auto; position: relative; height: 300px;">
                            <canvas id="sales-chart"></canvas>
                        </div>

                        <div class="mt-3 d-flex justify-content-end">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-2">
                                <i class="bi bi-circle-fill text-primary me-1"></i> Business Revenue
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @php
            function formatInLakhsCrores($number)
            {
                if ($number >= 1000000000000) {
                    return '₹' . number_format($number / 1000000000000, 3) . ' Trillion';
                } elseif ($number >= 1000000000) {
                    return '₹' . number_format($number / 1000000000, 2) . ' Billion';
                } elseif ($number >= 10000000) {
                    return '₹' . number_format($number / 10000000, 2) . ' Cr';
                } elseif ($number >= 100000) {
                    return '₹' . number_format($number / 100000, 2) . ' Lakh';
                } else {
                    return '₹' . number_format($number, 2);
                }
            }
        @endphp

        <div class="container">
            <div class="dashboard-header">
                <p class="dashboard-subtitle">Track your earnings, network growth, and business performance metrics</p>
            </div>

            <div class="row">
                <!-- Power Leg Points Card -->
                <div class="col-md-6 mb-4">
                    <div class="dashboard-card power-leg-card shadow-sm"
                        style="background: linear-gradient(135deg, #6a11cb, #2575fc);">
                        <div class="card-content">
                            <div class="card-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <p class="card-title">Power Leg Points</p>
                            <h2 class="card-value">{{ formatInLakhsCrores(Auth::user()->left_business) }}</h2>
                            <hr class="card-divider">
                            <p class="card-description">Your stronger leg's accumulated business volume</p>
                        </div>
                    </div>
                </div>

                <!-- Weaker Leg Points Card -->
                <div class="col-md-6 mb-4">
                    <div class="dashboard-card weaker-leg-card shadow-sm">
                        <div class="card-content">
                            <div class="card-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <p class="card-title">Weaker Leg Points</p>
                            <h2 class="card-value">{{ formatInLakhsCrores(Auth::user()->right_business) }}</h2>
                            <hr class="card-divider">
                            <p class="card-description">Your weaker leg's total business volume</p>
                        </div>
                    </div>
                </div>

                <!-- Referral Bonus Card -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="dashboard-card referral-card shadow-sm">
                        <div class="card-content">
                            <div class="card-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <p class="card-title">Referral Bonus</p>
                            <h2 class="card-value">{{ formatInLakhsCrores($referralCommission) }}</h2>
                            <hr class="card-divider">
                            <p class="card-description">Commissions from your referral users</p>
                        </div>
                    </div>
                </div>

                <!-- Network Bonus Card -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="dashboard-card network-card shadow-sm">
                        <div class="card-content">
                            <div class="card-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <p class="card-title">Network Bonus</p>
                            <h2 class="card-value">{{ formatInLakhsCrores($networkCommission) }}</h2>
                            <hr class="card-divider">
                            <p class="card-description">Commissions from your network users</p>
                        </div>
                    </div>
                </div>

                <!-- Monthly Income Card -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="dashboard-card monthly-card shadow-sm">
                        <div class="card-content">
                            <div class="card-icon">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <p class="card-title">Monthly Income</p>
                            <h2 class="card-value">{{ formatInLakhsCrores($monthlyIncome) }}</h2>
                            <hr class="card-divider">
                            <p class="card-description">Monthly based income from purchased packages</p>
                        </div>
                    </div>
                </div>

                <!-- Level Income Card -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="dashboard-card network-card shadow-sm"
                        style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                        <div class="card-content">
                            <div class="card-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <p class="card-title">Level Income</p>
                            <h2 class="card-value">{{ formatInLakhsCrores($levelIncome) }}</h2>
                            <hr class="card-divider">
                            <p class="card-description">Monthly Incomes from the downline user's packages</p>
                        </div>
                    </div>
                </div>

                <!-- Rewards Income Card -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="dashboard-card monthly-card shadow-sm"
                        style="background: linear-gradient(135deg, #a1c4fd, #c2e9fb);">
                        <div class="card-content">
                            <div class="card-icon">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <p class="card-title">Rewards Income</p>
                            <h2 class="card-value">{{ formatInLakhsCrores($rewardIncome) }}</h2>
                            <hr class="card-divider">
                            <p class="card-description">Total Income for attaining the ranks</p>
                        </div>
                    </div>
                </div>

                <!-- Royalty Rewards Card -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="dashboard-card monthly-card shadow-sm"
                        style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                        <div class="card-content">
                            <div class="card-icon">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <p class="card-title">Royalty Income</p>
                            <h2 class="card-value">{{ formatInLakhsCrores($royaltyRewards) }}</h2>
                            <hr class="card-divider">
                            <p class="card-description">Income from yearly distribution of rewards</p>
                        </div>
                    </div>
                </div>

                <!-- Total Income -->
                <div class="col-md-6 col-lg-4 mb-4 mx-auto">
                    <div class="dashboard-card shadow-sm" style="background: linear-gradient(135deg, #6b6b83, #3b8d99);">
                        <div class="card-content">
                            <div class="card-icon">
                                <i class="fas fa-rupee-sign"></i>
                            </div>
                            <p class="card-title">Total Income</p>
                            <h2 class="card-value">{{ formatInLakhsCrores($totalIncome) }}</h2>
                            <hr class="card-divider">
                            <p class="card-description">Total incomes from all incomes</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <script>
        document.getElementById('activationForm')?.addEventListener('submit', function(e) {
            const balance = {{ Auth::user()->points_balance }};
            const packagePrice = {{ $packages->isNotEmpty() ? $packages->first()->price : 0 }};

            if (balance < packagePrice) {
                e.preventDefault();
                alert('Insufficient balance to purchase this package');
            }
        });
    </script>

@endsection
