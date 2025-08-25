@extends('userlayouts.layouts')
@section('title', 'Yearly Profit Distribution')
@section('container')

<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">My Yearly Profit Distributions</h4>
        </div>
        
        <div class="card-body">
            
            <!-- Yearly Profits -->
            @foreach($allProfits as $year => $profits)
                <div class="year-section mb-4">
                    <h4 class="d-flex justify-content-between align-items-center">
                        <span>{{ $year }} Distributions</span>
                        <span class="badge bg-primary">
                            Total: ₹{{ number_format($profits->sum('amount'), 2) }}
                        </span>
                    </h4>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Type</th>
                                    <th>Source</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($profits as $profit)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $profit['type'] == 'rank' ? 'success' : 'info' }}">
                                                {{ ucfirst($profit['type']) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($profit['type'] == 'rank')
                                                {{ $profit['rank'] }} ({{ $profit['percentage'] }}%)
                                            @else
                                                Package Share ({{ $profit['percentage'] }}%)
                                            @endif
                                        </td>
                                        <td class="fw-bold">₹{{ number_format($profit['amount'], 2) }}</td>
                                        <td>{{ $profit['date'] }}</td>
                                        <td>
                                            @if($profit['type'] == 'package')
                                                Weight: {{ $profit['weight'] }}/{{ $profit['total_weight'] }}
                                            @else
                                                Rank Reward
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
            
            @if($allProfits->count() == 0)
                <div class="text-center py-4">
                    <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No yearly profit distributions found</h5>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .year-section {
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 20px;
    }
    .year-section:last-child {
        border-bottom: none;
    }
    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
    }
    .table th {
        white-space: nowrap;
    }
</style>

@endsection