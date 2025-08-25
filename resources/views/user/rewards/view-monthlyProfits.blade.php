@extends('userlayouts.layouts')
@section('title', 'Monthly Profits')

@section('container')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-coins me-2"></i> Monthly Profit Distributions
                </h5>
                <div class="badge bg-white text-primary fs-6">
                    Total: ₹{{ number_format($distributions->sum('distributed_amount'), 2) }}
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Date</th>
                            <th>Package</th>
                            <th>Investment</th>
                            <th>Rate</th>
                            <th>Profit</th>
                            <th class="pe-4">Remaining</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($distributions as $distribution)
                        <tr>
                            <td class="ps-4">
                                {{ $distribution->distribution_date }}
                            </td>
                            <td>
                                {{ $distribution->packagePurchase->package_name ?? 'N/A' }}
                            </td>
                            <td class="fw-bold">
                                ₹{{ number_format($distribution->purchase_amount, 2) }}
                            </td>
                            <td>
                                {{ $distribution->rate_percentage }}%
                            </td>
                            <td class="fw-bold text-success">
                                +₹{{ number_format($distribution->distributed_amount, 2) }}
                            </td>
                            <td class="pe-4">
                                {{ $distribution->months_remaining }} months
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-wallet fa-2x text-muted mb-3"></i>
                                <p class="text-muted">No profit distributions found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($distributions->hasPages())
            <div class="card-footer bg-white">
                {{ $distributions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection