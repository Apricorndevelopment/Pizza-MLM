@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')
@section('title', 'Dashboard')

@section('container')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --card-1: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%);
            --card-2: linear-gradient(120deg, #89f7fe 0%, #66a6ff 100%);
            --card-3: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);
            --card-4: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
            --card-5: linear-gradient(to top, #cfd9df 0%, #e2ebf0 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
        }

        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
        }

        /* --- Header Section --- */
        .welcome-banner {
            background: var(--primary-gradient);
            border-radius: 20px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px -10px rgba(102, 126, 234, 0.5);
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            pointer-events: none;
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            pointer-events: none;
        }

        /* --- Dashboard Cards --- */
        .stat-card {
            border: none;
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
            position: relative;
            color: white;
            height: 100%;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .stat-card .card-body {
            position: relative;
            z-index: 2;
            padding: 1.5rem;
        }

        .stat-card .icon-circle {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(4px);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: inset 0 0 10px rgba(255,255,255,0.1);
        }

        .stat-card h3 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.2rem;
            letter-spacing: -0.5px;
        }

        .stat-card p {
            font-size: 0.9rem;
            opacity: 0.9;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0;
        }

        .stat-card .overlay-shape {
            position: absolute;
            top: -20px;
            right: -20px;
            font-size: 8rem;
            opacity: 0.1;
            transform: rotate(15deg);
            z-index: 1;
        }

        /* Specific Card Gradients */
        .bg-purple-gradient { background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); }
        .bg-blue-gradient { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .bg-green-gradient { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .bg-orange-gradient { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .bg-dark-gradient { background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); }
        .bg-royal-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }

        /* --- Chart Section --- */
        .chart-card {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .form-select-custom {
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            font-size: 0.85rem;
            padding: 0.4rem 2rem 0.4rem 0.8rem;
            background-color: #f9fafb;
        }
        .form-select-custom:focus {
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
            border-color: #6366f1;
        }

        /* --- Animations --- */
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }
        .delay-6 { animation-delay: 0.6s; }

    </style>

    <div class="container-fluid">

        @if (session('welcome_popup'))
            <div class="modal fade" id="welcomeModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                        <div class="modal-body text-center p-5">
                            <div class="mb-4">
                                <img src="/images/telegram.jpg" alt="Welcome" class="img-fluid rounded-circle shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                            </div>
                            <h3 class="fw-bold text-gray-800">Welcome Back, {{ session('welcome_name') }}! 🎉</h3>
                            <p class="text-muted mt-2">We're glad to see you again. Check your dashboard for the latest updates.</p>
                            <button type="button" class="btn btn-primary rounded-pill px-4 py-2 mt-3 fw-bold shadow-sm" data-bs-dismiss="modal">
                                Go to Dashboard <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row mb-4 fade-in-up">
            <div class="col-12">
                <div class="welcome-banner p-4 p-md-5 d-flex align-items-center justify-content-between">
                    <div class="text-white position-relative z-10">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <h1 class="fw-bold mb-0">Dashboard</h1>
                            <span class="badge bg-white/20 backdrop-blur-sm border border-white/30 rounded-pill px-3 py-1">
                                <i class="fas fa-circle text-green-400 me-1" style="font-size: 8px;"></i> {{ ucfirst(Auth::user()->status) }}
                            </span>
                        </div>
                        <p class="mb-0 opacity-90 fs-5">Hello, {{ Auth::user()->name }} ({{ Auth::user()->ulid }})</p>
                        <p class="mb-0 opacity-75 small mt-1"><i class="fas fa-crown me-1 text-warning"></i> Rank: {{ Auth::user()->current_rank ?? 'N/A' }}</p>
                    </div>
                    <div class="d-none d-md-block position-relative z-10">
                        <div class="bg-white/20 p-1 rounded-circle backdrop-blur-sm">
                            <img src="{{ Auth::user()->profile_picture ? asset('storage/profile-pictures/' . basename(Auth::user()->profile_picture)) : asset('foodvendor-logo.png') }}" 
                                 class="rounded-circle shadow-lg" width="80" height="80" alt="Profile" style="object-fit: cover;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                @session('success')
                    <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center" role="alert">
                        <i class="fas fa-check-circle me-2 fs-5"></i>
                        <div>{{ session('success') }} {{ session('coupon_code') ? ' | Coupon: ' . session('coupon_code') : '' }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endsession
                @session('error')
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endsession
            </div>
        </div>
        @php
            function formatInLakhsCrores($number) {
                if ($number >= 1000000000000) return '₹' . number_format($number / 1000000000000, 2) . 'T';
                if ($number >= 1000000000) return '₹' . number_format($number / 1000000000, 2) . 'B';
                if ($number >= 10000000) return '₹' . number_format($number / 10000000, 2) . 'Cr';
                if ($number >= 100000) return '₹' . number_format($number / 100000, 2) . 'L';
                return '₹' . number_format($number, 2);
            }
        @endphp

        <div class="row g-4">
            
            <div class="col-md-12 col-lg-4 fade-in-up delay-2">
                <div class="stat-card bg-royal-gradient">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="icon-circle text-white">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <p>Total Business</p>
                                <h3>{{ formatInLakhsCrores(Auth::user()->total_business) }}</h3>
                            </div>
                        </div>
                        <i class="fas fa-chart-area overlay-shape"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 fade-in-up delay-3">
                <div class="stat-card bg-blue-gradient">
                    <div class="card-body">
                        <div class="icon-circle text-white">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <p>Direct Income</p>
                        <h3>{{ formatInLakhsCrores($directIncome) }}</h3>
                        <i class="fas fa-hand-holding-usd overlay-shape"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 fade-in-up delay-4">
                <div class="stat-card bg-purple-gradient">
                    <div class="card-body">
                        <div class="icon-circle text-white">
                            <i class="fas fa-gift"></i>
                        </div>
                        <p>Bonus Income</p>
                        <h3>{{ formatInLakhsCrores($bonusIncome) }}</h3>
                        <i class="fas fa-gift overlay-shape"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 fade-in-up delay-5">
                <div class="stat-card bg-orange-gradient">
                    <div class="card-body">
                        <div class="icon-circle text-white">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <p>Rewards Income</p>
                        <h3>{{ formatInLakhsCrores($rewardIncome) }}</h3>
                        <i class="fas fa-trophy overlay-shape"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 fade-in-up delay-6">
                <div class="stat-card bg-green-gradient">
                    <div class="card-body">
                        <div class="icon-circle text-white">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <p>Level Income</p>
                        <h3>{{ formatInLakhsCrores($levelIncome) }}</h3>
                        <i class="fas fa-layer-group overlay-shape"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 fade-in-up delay-6">
                <div class="stat-card bg-dark-gradient">
                    <div class="card-body">
                        <div class="icon-circle text-white">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <p>Repurchase Income</p>
                        <h3>{{ formatInLakhsCrores($repurchaseIncome) }}</h3>
                        <i class="fas fa-shopping-cart overlay-shape"></i>
                    </div>
                </div>
            </div>

        </div>

        <div class="row my-4 fade-in-up delay-1">
            <div class="col-12">
                <div class="chart-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-bold text-gray-800 mb-1">Sales Performance</h5>
                            <p class="text-muted small mb-0">Business growth analytics</p>
                        </div>
                        <select id="filter-select" class="form-select-custom">
                            <option value="daily">Last 15 Days</option>
                            <option value="weekly">Last 8 Weeks</option>
                            <option value="monthly" selected>Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <div style="height: 300px; width: 100%;">
                        <canvas id="sales-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>

         <div class="col-12 fade-in-up delay-6">
                <div class="stat-card" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-4">
                            <div class="icon-circle text-white mb-0" style="width: 60px; height: 60px;">
                                <i class="fas fa-rupee-sign fs-4"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-white-50">Total Earnings</p>
                                <h2 class="mb-0 fw-bold">{{ formatInLakhsCrores($totalIncome) }}</h2>
                            </div>
                        </div>
                        <div class="text-end d-none d-md-block">
                            <span class="badge bg-white/20 border border-white/20 rounded-pill px-3 py-2">
                                Lifetime
                            </span>
                        </div>
                    </div>
                </div>
            </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Welcome Modal Logic
            @if (session('welcome_popup'))
                var welcomeModal = new bootstrap.Modal(document.getElementById('welcomeModal'));
                welcomeModal.show();
            @endif

            // Chart Logic
            const ctx = document.getElementById('sales-chart').getContext('2d');
            const filterSelect = document.getElementById('filter-select');

            // Format numbers logic for Chart Tooltips
            function formatChartValue(value) {
                if (value >= 10000000) return '₹' + (value / 10000000).toFixed(2) + 'Cr';
                if (value >= 100000) return '₹' + (value / 100000).toFixed(2) + 'L';
                return '₹' + value.toLocaleString();
            }

            let salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Business Volume',
                        data: [],
                        borderColor: '#6366f1',
                        backgroundColor: (context) => {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.4)');
                            gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');
                            return gradient;
                        },
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#6366f1',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            padding: 12,
                            titleFont: { size: 13 },
                            bodyFont: { size: 14, weight: 'bold' },
                            callbacks: {
                                label: function(context) {
                                    return ' ' + formatChartValue(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [2, 4], color: '#f3f4f6' },
                            ticks: {
                                font: { size: 11 },
                                callback: function(value) { return formatChartValue(value); }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        }
                    }
                }
            });

            // Fetch Data
            function loadChartData(filter = 'monthly') {
                fetch(`{{ route('dashboard.sales.data') }}?filter=${filter}`)
                    .then(response => response.json())
                    .then(data => {
                        salesChart.data.labels = data.labels;
                        salesChart.data.datasets[0].data = data.data;
                        salesChart.update();
                    })
                    .catch(error => console.error('Error:', error));
            }

            // Init Load
            loadChartData('monthly');

            // Event Listener
            filterSelect.addEventListener('change', function() {
                loadChartData(this.value);
            });
        });
    </script>
@endsection