@extends('userlayouts.layouts')
@section('title', 'Dashboard')
@section('container')

    <style>
        .activation-card {
            border-left: 4px solid #6c5ce7;
            background-color: #f8f9fa;
        }

        .package-badge {
            font-size: 0.8rem;
            padding: 0.35rem 0.65rem;
        }

        .package-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }

        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
    </style>

    <div class="container py-4">
        {{-- <div class="card activation-card mb-4">
            <div class="card-body">
                <div class="row">
                    <!-- Left content: package details -->
                    <div class="col-12 col-md-9">
                        <h5 class="card-title mb-3">
                            <span class="badge bg-primary me-2 mb-2">Activation Package</span>
                            <span class="d-block d-md-inline">{{ $packageTransaction->package_name }}</span>
                        </h5>

                        <div class="row gx-0">
                            <div class="col-12 col-md-4 mb-3 mb-sm-0">
                                <p class="mb-2"><strong>Coupon Code:</strong>
                                    <span class="badge bg-success">{{ $packageTransaction->coupon_code }}</span>
                                </p>
                                <p class="mb-2"><strong>Purchase Date and Time:</strong></p>
                                <p class="mb-0">{{ $packageTransaction->transaction_date }}</p>
                            </div>
                            <div class="col-12 col-md-8">
                                <p class="mb-2"><strong>Gift Packet Status:</strong>
                                    <span class="status-badge status-active">
                                        {{ $packageTransaction->status ? $packageTransaction->status : 'pending' }}
                                    </span>
                                </p>
                                <p class="mb-0"><strong>Quantity:</strong>
                                    {{ $packageTransaction->quantity }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right content: price -->
                    <div class="col-12 col-md-3 text-md-end text-start mt-3 mt-md-0">
                        <p class="h4 mb-1">₹{{ $packageTransaction->final_price }}</p>
                        <small class="text-muted">Total Paid</small>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">My Packages</h4>
            </div>
            <div class="card-body">
                @if ($packages->isEmpty())
                    <div class="alert alert-info">You don't have any packages yet.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Index</th>
                                    <th>Package Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Rate</th>
                                    <th>Time</th>
                                    <th>Purchase Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $index = 1;
                                ?>
                                @foreach ($packages as $transaction)
                                    <tr>
                                        <td>{{ $index }} </td>
                                        <td>{{ $transaction->package_name }}</td>
                                        <td>{{ $transaction->quantity }}</td>
                                        <td>₹{{ number_format($transaction->final_price, 2) }}</td>
                                        <td>{{ $transaction->rate }}%</td>
                                        <td>{{ $transaction->time ?? '0' }} years</td>
                                        <td>{{ $transaction->created_at->format('d M Y, h:i A') }}</td>
                                    </tr>
                                    <?php
                                    $index++; ?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection


