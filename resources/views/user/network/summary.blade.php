@extends('userlayouts.layouts')

@section('title', 'Network Summary')

@section('container')
<div class="container mt-3">
    <h4 class="mb-3 text-primary">Network Summary</h4>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient-info text-white py-2">
            <h6 class="mb-0"><i class="fas fa-network-wired me-1"></i>Downline Team</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th class="text-center">ULID</th>
                            <th class="text-center">Left Business</th>
                            <th class="text-center">Right Business</th>
                            <th class="text-center">Level</th>
                            <th class="text-center">Rank</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($downlineUsers as $user)
                            <tr class="align-middle">
                                <td class="ps-3 fw-medium">{{ $user->name }}</td>
                                <td class="text-center"><span class="badge bg-secondary">{{ $user->ulid }}</span></td>
                                <td class="text-center {{ $user->left_business >= 10000 ? 'text-success fw-bold' : '' }}">
                                    ₹{{ number_format($user->left_business ?? 0, 2) }}
                                </td>
                                <td class="text-center {{ $user->right_business >= 10000 ? 'text-success fw-bold' : '' }}">
                                    ₹{{ number_format($user->right_business ?? 0, 2) }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill">{{ $user->level }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success rounded-pill text-capitalize">
                                        {{ $user->current_rank ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-3 text-muted small">
                                    <i class="fas fa-users-slash fa-lg mb-1"></i><br>
                                    No downline users found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .table-sm td, .table-sm th {
        padding: 0.7rem;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    .card-header {
        border-radius: 0.375rem 0.375rem 0 0 !important;
    }
    .table th {
        white-space: nowrap;
        font-weight: 600;
        font-size: 0.65rem;
        letter-spacing: 0.5px;
    }
    .badge.rounded-pill {
        min-width: 50px;
        font-size: 0.7rem;
        padding: 5px 8px;
    }
    .small {
        font-size: 0.85rem;
    }
    h4 {
        font-size: 1.25rem;
    }
    h6 {
        font-size: 0.9rem;
    }
    .container {
        max-width: 99%;
    }
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .badge.rounded-pill {
            min-width: 50px;
        }
    }
</style>
@endsection