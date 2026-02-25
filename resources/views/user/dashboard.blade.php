@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')
@section('title', 'Dashboard')

@section('container')
    <style>
        :root {
            --font-primary: 'Inter', system-ui, -apple-system, sans-serif;
        }

        body {
            font-family: var(--font-primary);
            background-color: #f8f9fc;
        }

        /* --- Gradients & Cards (Kept your existing styles) --- */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #fad961 0%, #f76b1c 100%);
        }

        .bg-gradient-purple {
            background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
        }

        .bg-gradient-dark {
            background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
        }

        .bg-gradient-royal {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }

        .stat-card {
            border: none;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px) scale(1.01);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
        }

        .icon-box-glass {
            width: 56px;
            height: 56px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-label {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            opacity: 0.9;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .overlay-icon {
            position: absolute;
            right: -15px;
            bottom: -15px;
            font-size: 8rem;
            opacity: 0.1;
            transform: rotate(-15deg);
            pointer-events: none;
            z-index: 0;
        }

        .company-banner {
            background: linear-gradient(120deg, #2980b9, #8e44ad);
            border-radius: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px -10px rgba(142, 68, 173, 0.4);
        }

        .company-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .chart-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
        }

        /* --- Carousel Custom CSS --- */
        .dashboard-carousel-img {
            height: 180px;
            /* Mobile Height */
            object-fit: cover;
            width: 100%;
        }

        @media (min-width: 768px) {
            .dashboard-carousel-img {
                height: 380px;
                /* Desktop Height */
            }
        }

        .carousel-caption-bg {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            border-radius: 12px;
            padding: 10px 20px;
            display: inline-block;
        }
    </style>

    <div class="container-fluid py-4">

        {{-- 1. HERO BANNER (Rearranged: Welcome Title First, then Rank) --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="company-banner p-3.5 lg:p-6 text-white d-flex align-items-center justify-content-between">
                    <div class="position-relative z-10">
                        {{-- Welcome Title First --}}
                        <h1 class="display-5 fw-bolder mb-3">Welcome to Smart Save24</h1>

                        {{-- Rank Badge Second --}}
                        <div class="mb-3">
                            <span
                                class="badge bg-white/20 border border-white/30 rounded-pill px-3 py-2 text-white fs-6 fw-normal">
                                <i class="fas fa-crown text-warning me-1"></i> Current Rank:
                                <strong>{{ Auth::user()->current_rank ?? 'Member' }}</strong>
                            </span>
                        </div>

                        <p class="fs-5 opacity-90 mb-0">Hello, <strong>{{ $user->name }}</strong>! Let's grow your
                            earnings today.</p>
                    </div>
                    <div class="d-none d-lg-block position-relative z-10 pe-5">
                        <i class="fas fa-building fa-6x opacity-25 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. NEW: USER GALLERY CAROUSEL --}}
        @if ($galleries->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div id="galleryCarousel" class="carousel slide rounded-4 overflow-hidden shadow-sm"
                        data-bs-ride="carousel">
                        {{-- Indicators --}}
                        <div class="carousel-indicators">
                            @foreach ($galleries as $key => $gallery)
                                <button type="button" data-bs-target="#galleryCarousel"
                                    data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"
                                    aria-current="{{ $key == 0 ? 'true' : 'false' }}"
                                    aria-label="Slide {{ $key + 1 }}"></button>
                            @endforeach
                        </div>

                        {{-- Slides --}}
                        <div class="carousel-inner">
                            @foreach ($galleries as $key => $gallery)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <img src="{{ asset($gallery->photo) }}" class="d-block w-100 dashboard-carousel-img"
                                        alt="{{ $gallery->title ?? 'Banner' }}">
                                    @if ($gallery->title)
                                        <div class="carousel-caption d-none d-md-block">
                                            <div class="carousel-caption-bg">
                                                <h5 class="fw-bold mb-0">{{ $gallery->title }}</h5>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Controls --}}
                        <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- 3. MEDIA SECTION --}}
        <div class="row g-4 mb-4">
            {{-- Audio Playlist --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-gray-800 mb-0"><i class="fas fa-podcast text-primary me-2"></i> Audio
                            Updates</h5>
                        <span class="badge bg-primary-subtle text-primary rounded-pill">{{ $audios->count() }} New</span>
                    </div>
                    <div class="card-body p-4">
                        @if ($audios->count() > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach ($audios->take(2) as $audio)
                                    <div
                                        class="d-flex align-items-center bg-light p-3 rounded-3 border-start border-4 border-primary">
                                        <div class="me-3 bg-white p-2 rounded-circle shadow-sm">
                                            <i class="fas fa-play text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h6 class="mb-1 text-truncate fw-bold">{{ $audio->title }}</h6>
                                            <audio controls class="w-100" style="height: 30px;">
                                                <source src="{{ asset($audio->file_path) }}" type="audio/mpeg">
                                            </audio>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-music fa-3x mb-3 opacity-25"></i>
                                <p>No audio updates at the moment.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Video Gallery --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold text-gray-800 mb-0"><i class="fas fa-video text-danger me-2"></i> Latest Videos
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if ($videos->count() > 0)
                            <div class="row g-3">
                                @foreach ($videos->take(2) as $video)
                                    <div class="col-sm-6">
                                        <div class="position-relative rounded-3 overflow-hidden shadow-sm bg-dark">
                                            <video controls class="w-100 d-block"
                                                style="height: 160px; object-fit: cover; opacity: 0.9;">
                                                <source src="{{ asset($video->file_path) }}" type="video/mp4">
                                            </video>
                                            <div
                                                class="position-absolute bottom-0 start-0 w-100 p-2 bg-gradient-to-t from-black to-transparent">
                                                <small
                                                    class="text-white fw-bold text-truncate d-block">{{ $video->title }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-film fa-3x mb-3 opacity-25"></i>
                                <p>No video updates available.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Helper PHP --}}
        @php
            function formatCurrency($number)
            {
                if ($number >= 10000000) {
                    return '₹' . number_format($number / 10000000, 2) . 'Cr';
                }
                if ($number >= 100000) {
                    return '₹' . number_format($number / 100000, 2) . 'L';
                }
                return '₹' . number_format($number, 2);
            }
        @endphp

        {{-- 4. FINANCIAL OVERVIEW --}}
        <div class="d-flex align-items-center mb-4">
            <h4 class="fw-bold text-gray-800 mb-0">Financial Overview</h4>
            <div class="ms-3 flex-grow-1 border-bottom"></div>
        </div>

        <div class="row g-4 mb-4">
            {{-- Total Earnings --}}
            <div class="col-md-6 col-xl-3">
                <div class="stat-card bg-gradient-royal text-white p-4">
                    <div class="d-flex justify-content-between align-items-start z-10 position-relative">
                        <div>
                            <p class="stat-label text-white-50">Total Earnings</p>
                            <h2 class="stat-value">{{ formatCurrency($totalIncome) }}</h2>
                        </div>
                        <div class="icon-box-glass"><i class="fas fa-wallet"></i></div>
                    </div>
                    <i class="fas fa-coins overlay-icon text-white"></i>
                </div>
            </div>

            {{-- Wallet 1 --}}
            <div class="col-md-6 col-xl-3">
                <div class="stat-card bg-gradient-success text-white p-4">
                    <div class="d-flex justify-content-between align-items-start z-10 position-relative">
                        <div>
                            <p class="stat-label text-white-50">Personal Wallet</p>
                            <h2 class="stat-value">{{ formatCurrency($user->wallet1_balance) }}</h2>
                        </div>
                        <div class="icon-box-glass"><i class="fas fa-money-bill-wave"></i></div>
                    </div>
                    <i class="fas fa-chart-line overlay-icon text-white"></i>
                </div>
            </div>

            {{-- Wallet 2 --}}
            <div class="col-md-6 col-xl-3">
                <div class="stat-card bg-gradient-info text-white p-4">
                    <div class="d-flex justify-content-between align-items-start z-10 position-relative">
                        <div>
                            <p class="stat-label text-white-50">Wallet 2</p>
                            <h2 class="stat-value">{{ formatCurrency($user->wallet2_balance) }}</h2>
                        </div>
                        <div class="icon-box-glass"><i class="fas fa-university"></i></div>
                    </div>
                    <i class="fas fa-piggy-bank overlay-icon text-white"></i>
                </div>
            </div>

            {{-- Coupons --}}
            <div class="col-md-6 col-xl-3">
                <div class="stat-card bg-gradient-warning text-white p-4">
                    <div class="d-flex justify-content-between align-items-start z-10 position-relative">
                        <div>
                            <p class="stat-label text-white-50">Active Coupons</p>
                            <h2 class="stat-value">{{ number_format($totalCoupons) }}
                                (₹{{ number_format($totalCoupons * 10) }})</h2>
                        </div>
                        <div class="icon-box-glass"><i class="fas fa-ticket-alt"></i></div>
                    </div>
                    <i class="fas fa-tags overlay-icon text-white"></i>
                </div>
            </div>
        </div>

        {{-- 5. INCOME BREAKDOWN --}}
        <div class="d-flex align-items-center mb-4">
            <h4 class="fw-bold text-gray-800 mb-0">Income Breakdown</h4>
            <div class="ms-3 flex-grow-1 border-bottom"></div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4 col-xl-2">
                <div
                    class="card border-0 shadow-sm rounded-4 h-100 text-center py-4 px-2 position-relative overflow-hidden group hover:border-primary transition">
                    <div class="position-absolute top-0 start-0 w-100 h-1 bg-primary"></div>
                    <div class="mb-3 text-primary bg-primary-subtle d-inline-block p-3 rounded-circle">
                        <i class="fas fa-hand-holding-usd fa-lg"></i>
                    </div>
                    <h5 class="fw-bold text-gray-800 mb-1">{{ formatCurrency($directIncome) }}</h5>
                    <p class="text-muted small mb-0 fw-bold text-uppercase">Direct Income</p>
                </div>
            </div>
            <div class="col-md-4 col-xl-2">
                <div
                    class="card border-0 shadow-sm rounded-4 h-100 text-center py-4 px-2 position-relative overflow-hidden group hover:border-primary transition">
                    <div class="position-absolute top-0 start-0 w-100 h-1 bg-danger"></div>
                    <div class="mb-3 text-danger bg-danger-subtle d-inline-block p-3 rounded-circle">
                        <i class="fas fa-hand-holding-usd fa-lg"></i>
                    </div>
                    <h5 class="fw-bold text-gray-800 mb-1">{{ formatCurrency($bonusIncome) }}</h5>
                    <p class="text-muted small mb-0 fw-bold text-uppercase">Bonus Income</p>
                </div>
            </div>
            <div class="col-md-4 col-xl-2">
                <div
                    class="card border-0 shadow-sm rounded-4 h-100 text-center py-4 px-2 position-relative overflow-hidden">
                    <div class="position-absolute top-0 start-0 w-100 h-1 bg-success"></div>
                    <div class="mb-3 text-success bg-success-subtle d-inline-block p-3 rounded-circle">
                        <i class="fas fa-layer-group fa-lg"></i>
                    </div>
                    <h5 class="fw-bold text-gray-800 mb-1">{{ formatCurrency($levelIncome) }}</h5>
                    <p class="text-muted small mb-0 fw-bold text-uppercase">Level Income</p>
                </div>
            </div>
            <div class="col-md-4 col-xl-2">
                <div
                    class="card border-0 shadow-sm rounded-4 h-100 text-center py-4 px-2 position-relative overflow-hidden">
                    <div class="position-absolute top-0 start-0 w-100 h-1 bg-purple-500"></div>
                    <div class="mb-3 text-purple-500 d-inline-block p-3 rounded-circle">
                        <i class="fas fa-layer-group fa-lg"></i>
                    </div>
                    <h5 class="fw-bold text-gray-800 mb-1">{{ formatCurrency($cashbackIncome) }}</h5>
                    <p class="text-muted small mb-0 fw-bold text-uppercase">Cashback Income</p>
                </div>
            </div>
            <div class="col-md-4 col-xl-2">
                <div
                    class="card border-0 shadow-sm rounded-4 h-100 text-center py-4 px-2 position-relative overflow-hidden">
                    <div class="position-absolute top-0 start-0 w-100 h-1 bg-info"></div>
                    <div class="mb-3 text-info bg-info-subtle d-inline-block p-3 rounded-circle">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                    </div>
                    <h5 class="fw-bold text-gray-800 mb-1">{{ formatCurrency($repurchaseIncome) }}</h5>
                    <p class="text-muted small mb-0 fw-bold text-uppercase">Repurchase</p>
                </div>
            </div>
            <div class="col-md-4 col-xl-2">
                <div
                    class="card border-0 shadow-sm rounded-4 h-100 text-center py-4 px-2 position-relative overflow-hidden">
                    <div class="position-absolute top-0 start-0 w-100 h-1 bg-warning"></div>
                    <div class="mb-3 text-warning bg-warning-subtle d-inline-block p-3 rounded-circle">
                        <i class="fas fa-trophy fa-lg"></i>
                    </div>
                    <h5 class="fw-bold text-gray-800 mb-1">{{ formatCurrency($rewardIncome) }}</h5>
                    <p class="text-muted small mb-0 fw-bold text-uppercase">Rewards</p>
                </div>
            </div>
        </div>

        {{-- 6. CHART SECTION --}}
        <div class="row">
            <div class="col-12">
                <div class="chart-container p-3.5 sm:p-6">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                        <div class="mb-3 mb-md-0">
                            <h4 class="fw-bold text-gray-800 mb-1">Business Performance</h4>
                            <p class="text-muted mb-0">Track your team's growth trajectory over time.</p>
                        </div>
                        <div class="bg-light p-1 rounded-3 d-inline-flex">
                            <select id="filter-select"
                                class="form-select border-0 bg-transparent fw-bold text-secondary shadow-none cursor-pointer">
                                <option value="daily">Last 15 Days</option>
                                <option value="weekly">Last 8 Weeks</option>
                                <option value="monthly" selected>Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                    </div>
                    <div class="position-relative h-80 sm:h-[400px]" style="width: 100%;">
                        <canvas id="sales-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- CHART JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('sales-chart').getContext('2d');
            const filterSelect = document.getElementById('filter-select');

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
                        borderColor: '#4f46e5',
                        backgroundColor: (context) => {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                            gradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)');
                            gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');
                            return gradient;
                        },
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4f46e5',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 16,
                            titleFont: {
                                size: 14,
                                family: "'Inter', sans-serif"
                            },
                            bodyFont: {
                                size: 16,
                                weight: 'bold',
                                family: "'Inter', sans-serif"
                            },
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return 'Volume: ' + formatChartValue(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [4, 4],
                                color: '#e2e8f0',
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: 12,
                                    family: "'Inter', sans-serif"
                                },
                                color: '#64748b',
                                callback: function(value) {
                                    return formatChartValue(value);
                                },
                                padding: 10
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: 12,
                                    family: "'Inter', sans-serif"
                                },
                                color: '#64748b',
                                padding: 10
                            }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                }
            });

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

            loadChartData('monthly');

            filterSelect.addEventListener('change', function() {
                loadChartData(this.value);
            });
        });
    </script>
@endsection
