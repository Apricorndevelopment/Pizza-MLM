@extends('userlayouts.layouts')
@section('title', 'Dashboard')
@section('container')

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="package-card">
                    <div class="package-card-inner">
                        <!-- Logo watermarked in background (via CSS) -->
                        <div class="geo-logo-overlay">
                            <img src="{{ asset('geokrantilogo-removebg.png') }}" alt="logo" />
                            <div class="geo-brand">Geokranti</div>
                        </div>
                        <div class="package-card-header">
                            <div class="package-card-logo">
                                <i class="fas fa-gift"></i>
                                <span>Activation Package</span>
                            </div>
                            <div class="package-card-status">
                                <span class="badge {{ $package->status == 'delivered' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $package->status == 'delivered' ? 'Delivered' : 'Not Delivered' }}
                                </span>
                                <div class="activated-on">
                                    <small>Activated On</small>
                                    <span>{{ \Carbon\Carbon::parse($package->transaction_date)->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="package-card-body">
                            <h3 class="package-card-title">{{ $package->package_name }}</h3>
                            <div class="package-card-details">
                                <div class="detail-row">
                                    <span class="detail-label">Package Value</span>
                                    <span class="detail-value">₹{{ $package->final_price }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Coupon Code</span>
                                    <span class="detail-value coupon-code">{{ $package->coupon_code }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Purchase Date</span>
                                    <span
                                        class="detail-value">{{ \Carbon\Carbon::parse($package->transaction_date)->format('d M Y') }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Quantity</span>
                                    <span class="detail-value">{{ $package->quantity }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="package-card-footer">
                            <div class="package-card-chip">
                                <i id="fas1" class="fas fa-microchip"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .package-card {
            margin-bottom: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 40, 30, 0.20);
            overflow: hidden;
            background: linear-gradient(145deg, #a0e17c 0%, #d3e0ff 70%, #354e3a 100%);
            /* Nature inspired: green/blue tones */
            position: relative;
            border: 1px solid green
        }

        #fas1 {
            font-weight: 900;
            color: #FFD700;
            /* Gold */
        }

        .package-card-inner {
            color: #226a00;
            position: relative;
            padding: 24px;
            min-height: 280px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            backdrop-filter: blur(1.5px);
        }

        .geo-logo-overlay {
            position: absolute;
            left: 50%;
            top: 42%;
            transform: translate(-50%, -50%);
            text-align: center;
            opacity: 0.18;
            z-index: 0;
            width: 140px;
            pointer-events: none;
        }

        .geo-logo-overlay img {
            width: 118px;
            border-radius: 12px;
            filter: grayscale(5%) drop-shadow(0 2px 10px #212 0.13);
        }

        .geo-logo-overlay .geo-brand {
            font-size: 1.2rem;
            font-family: 'Cinzel', 'Georgia', serif;
            margin-top: -10px;
            letter-spacing: 2px;
            color: #155c34;
            font-weight: bold;
            text-shadow: 1px 1px 8px #eafaf0;
            opacity: 0.7;
        }

        .package-card-header,
        .package-card-body,
        .package-card-footer {
            position: relative;
            z-index: 2;
        }

        .package-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .package-card-logo {
            display: flex;
            align-items: center;
            font-size: 1.18rem;
            font-weight: 600;
            color: #226a00;
        }

        .package-card-logo i {
            margin-right: 11px;
            font-size: 1.9rem;
        }

        .package-card-status {
            text-align: right;
        }

        .package-card-status .badge {
            margin-bottom: 6px;
            padding: 5px 8px;
            font-size: 0.7rem;
            font-weight: 600;
            border-radius: 5px;
        }

        .activated-on {
            font-size: 0.74rem;
            line-height: 1.15;
            margin-top: 2.5px;
        }

        .activated-on small {
            opacity: 0.8;
            display: block;
        }

        .activated-on span {
            font-weight: 600;
        }

        .package-card-title {
            font-size: 1.5rem;
            font-weight: 700;
            text-shadow: 0 2px 7px rgba(0, 45, 0, 0.18);
            margin-bottom: 20px;
            letter-spacing: 0.08em;
        }

        .package-card-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .detail-row {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 0.7rem;
            opacity: 0.85;
            margin-bottom: 2px;
        }

        .detail-value {
            font-size: 0.98rem;
            font-weight: 600;
        }

        .coupon-code {
            /* color: #143602d9; */

            font-family: monospace;
        }

        .package-card-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 15px;
        }

        .package-card-chip {
            font-size: 2.2rem;
            opacity: 0.79;
        }

        .package-card:hover {
            transform: scale(1.015);
            transition: 0.3s;
        }

        @media (max-width: 768px) {

            /* .package-card-details { grid-template-columns: 1fr; } */
            .geo-logo-overlay img {
                width: 100px;
            }

            .geo-logo-overlay .geo-brand {
                margin-top: 0px;
            }
        }
    </style>
@endsection