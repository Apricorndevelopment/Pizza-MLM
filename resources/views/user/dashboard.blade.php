@extends('userlayouts.layouts')
@section('title', 'Dashboard')
@section('container')
    <style>
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
    </style>

    <div class="container-fluid py-4">
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

        <div class="row">
            <!-- Power Leg Points Card -->
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm"
                    style="background: linear-gradient(135deg, #00c9ff, #92fe9d); color: #fff; border-radius: 16px;">
                    <div class="card-body">
                        <p class="mb-1 fs-6 text-uppercase fw-semibold">Power Leg Points</p>
                        <h2 class="fw-bold mb-1">{{ formatInLakhsCrores(Auth::user()->left_business) }}</h2>
                        <hr class="opacity-100 my-2" style="border-color: rgba(255,255,255,0.4      );">
                        <p class="mb-0 small opacity-75">Your stronger leg’s accumulated business volume</p>
                    </div>
                </div>
            </div>

            <!-- Weaker Leg Points Card -->
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm"
                    style="background: linear-gradient(135deg, #f093fb, #f5576c); color: #fff; border-radius: 16px;">
                    <div class="card-body">
                        <p class="mb-1 fs-6 text-uppercase fw-semibold">Weaker Leg Points</p>
                        <h2 class="fw-bold mb-1">{{ formatInLakhsCrores(Auth::user()->right_business) }}</h2>
                        <hr class="opacity-50 my-2" style="border-color: rgba(255,255,255,0.4);">
                        <p class="mb-0 small opacity-75">Your weaker leg's total business volume</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="row">
            <div class="col-sm-6 col-xl-4 mx-auto">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-body p-0">
                        <img src="images/geokranti.jpg" class="img-fluid w-100"
                            style="max-height: 450px; object-fit: cover;" alt="GeoKranti">
                        <div class="p-3 bg-light text-center">
                            <h5 class="fw-bold mb-0">GeoKranti - Empowering Your Journey</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
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
