@extends('userlayouts.layouts')
@section('title', 'Dashboard')
@section('container')

    <style>
        .package-table {
            font-size: 0.875rem;
        }

        .package-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            padding: 0.75rem;
            white-space: nowrap;
        }

        .package-table td {
            padding: 0.75rem;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
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

        .compact-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .card-header {
            padding: 0.75rem 1rem;
        }

        .card-title {
            margin-bottom: 0.5rem;
        }

        .section-title {
            border-left: 4px solid #0d6efd;
            padding-left: 10px;
            margin: 25px 0 15px 0;
        }

    </style>

    <div class="container py-3">

        <!-- Regular Packages Table -->
        <div class="card shadow-sm mb-4 mt-2">
            <div class="card-header bg-primary text-white py-2">
                <h5 class="mb-0"><i class="fas fa-cube me-2"></i>Regular Packages</h5>
            </div>
            <div class="card-body p-0">
                @if ($regularPackages->isEmpty())
                    <div class="alert alert-info m-3">You don't have any regular packages yet.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-striped package-table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Package Name</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-start">Price</th>
                                    <th class="text-start">Rate</th>
                                    <th class="text-start">Time</th>
                                    <th class="text-start">Purchase Date</th>
                                    <th class="text-start pe-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($regularPackages as $index => $transaction)
                                    <tr>
                                        <td class="ps-3 fw-medium">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                {{ $transaction->package_name }}
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $transaction->quantity }}</td>
                                        <td class="text-start fw-medium">₹{{ number_format($transaction->final_price, 2) }}
                                        </td>
                                        <td class="text-start">{{ $transaction->rate }}%</td>
                                        <td class="text-start">{{ $transaction->time ?? '0' }} yrs</td>
                                        <td class="text-start">{{ $transaction->created_at->format('d M Y') }}</td>
                                        <td class="text-start pe-3">
                                            <a href="{{ route('user.packages.invoice', ['id' => $transaction->id]) }}"
                                                class="btn btn-sm btn-outline-primary compact-btn">
                                                <i class="fas fa-receipt me-1"></i>Invoice
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection