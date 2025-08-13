@extends('userlayouts.layouts')

@section('title', 'Direct Team')

@section('container')
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 text-primary">
            <i class="fas fa-users me-1"></i>Direct Team
        </h4>
        <span class="badge bg-primary rounded-pill small">
            {{ count($directTeam) }} Members
        </span>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white py-2 small">
            <i class="fas fa-list-alt me-1"></i>Team Overview
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="ps-3">Member</th>
                            <th class="text-center">ULID</th>
                            <th class="text-center">Left Business</th>
                            <th class="text-center">Right Business</th>
                            <th class="text-center">Joined On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($directTeam as $user)
                            <tr class="small">
                                <td class="ps-3 fw-medium">{{ $user->name }}</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary bg-opacity-10 text-dark small">{{ $user->ulid }}</span>
                                </td>
                                <td class="text-center fw-medium {{ $user->left_business >= 10000 ? 'text-success' : '' }}">
                                    ₹{{ number_format($user->left_business ?? 0, 2) }}
                                </td>
                                <td class="text-center fw-medium {{ $user->right_business >= 10000 ? 'text-success' : '' }}">
                                    ₹{{ number_format($user->right_business ?? 0, 2) }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark small">
                                        {{ $user->created_at->format('d M Y, h:i A') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 small">
                                    <i class="fas fa-user-friends fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No direct members found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(count($directTeam) > 0)
        <div class="card-footer bg-light py-2 small">
            <div class="text-muted">
                Showing {{ count($directTeam) }} members
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .card {
        border-radius: 8px;
    }
    .table th {
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
    }
    .table-sm td, .table-sm th {
        padding: 0.8rem;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.03);
    }
    .badge {
        font-weight: 500;
        font-size: 0.7rem;
        padding: 0.25em 0.5em;
    }
    h4 {
        font-size: 1.2rem;
    }
    .small {
        font-size: 0.85rem;
    }
</style>
@endsection