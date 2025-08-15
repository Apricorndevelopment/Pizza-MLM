@extends('userlayouts.layouts')
@section('title', 'Dashboard')
@section('container')

    <div class="container py-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Rank Rewards for {{ $user->name }}</h4>
            </div>

            <div class="card-body">
                <!-- Current Rank -->
                <div class="alert alert-info d-flex align-items-center">
                    <i class="fas fa-medal fa-2x me-3"></i>
                    <div>
                        <strong class="h5">Current Rank:</strong>
                        <span class="badge bg-success fs-6">{{ $currentRank ?? 'No Rank Yet' }}</span>
                    </div>
                </div>

                <!-- Improved Rank Progress -->
                <div class="mb-4">
                    <h5 class="mb-3">Rank Journey</h5>
                    <div class="overflow-auto">
                        <div class="rank-progress-container flex-nowrap" style="min-width: max-content;">
                            @foreach ($allRanks as $index => $rank)
                                <div
                                    class="rank-step {{ $currentRank == $rank ? 'current-rank' : (array_search($rank, $allRanks) < array_search($currentRank, $allRanks) ? 'completed-rank' : 'future-rank') }}">
                                    <div class="rank-circle">
                                        @if ($currentRank == $rank)
                                            <i class="fas fa-crown"></i>
                                        @elseif(array_search($rank, $allRanks) < array_search($currentRank, $allRanks))
                                            <i class="fas fa-check"></i>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </div>
                                    <div class="rank-label">{{ $rank }}</div>
                                </div>
                                @if (!$loop->last)
                                    <div class="rank-connector"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                </div>

                <!-- Rewards Table -->
                <!-- Rewards Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th width="25%">Rank</th>
                                <th width="20%">Reward Amount</th>
                                <th width="20%">Date Awarded</th>
                                <th width="15%">Status</th>
                                <th width="20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rewards as $reward)
                                <tr class="{{ $reward['rank'] == $currentRank ? 'table-success' : 'table-light' }}">
                                    <td>{{ $reward['rank'] }}</td>
                                    <td class="fw-bold">₹{{ number_format($reward['amount'], 2) }}</td>
                                    <td>{{ $reward['date'] }}</td>
                                    <td>
                                        @if ($reward['status'] == 1)
                                            <span class="badge bg-success">Claimed</span>
                                        @elseif($reward['status'] == 2)
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($reward['status'] == 0)
                                            <form action="{{ route('user.rank.claimReward', $reward['id']) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i> Claim
                                                </button>
                                            </form>
                                            <form action="{{ route('user.rank.rejectReward', $reward['id']) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            </form>
                                        @elseif($reward['status'] == 1)
                                            <span class="text-success fw-bold">Claimed</span>
                                        @elseif($reward['status'] == 2)
                                            <span class="text-danger fw-bold">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-gift fa-2x text-muted mb-2"></i>
                                        <p class="h5 text-muted">No rewards yet</p>
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
        /* Rank Progress Styles */
        .rank-progress-container {
            display: flex;
            gap: 5px;
            /* Reduced gap */
            align-items: flex-start;
            padding: 0 10px;
            position: relative;
            flex-wrap: nowrap;
        }

        .rank-step {
            flex: 0 0 auto;
            /* prevent shrinking */
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 66px;
            /* More compact width */
            text-align: center;
            white-space: normal;
            word-break: break-word;
            z-index: 2;
        }

        .rank-circle {
            width: 40px;
            height: 40px;
            font-size: 0.85rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .rank-label {
            margin-top: 6px;
            font-size: 0.8rem;
            line-height: 1.1;
            word-wrap: break-word;
        }

        .rank-connector {
            height: 3px;
            background: #e9ecef;
            flex-grow: 1;
            position: relative;
            top: -14px;
        }

        /* Optional: Make scroll smoother and visible */
        .overflow-auto {
            overflow-x: auto;
            scrollbar-width: thin;
        }

        /* Rank Status Colors */
        .completed-rank .rank-circle {
            background-color: #28a745;
        }

        .current-rank .rank-circle {
            background-color: #ffc107;
        }

        .future-rank .rank-circle {
            background-color: #6c757d;
        }

        /* Table Improvements */
        .table-hover tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .align-middle td {
            vertical-align: middle !important;
        }
    </style>

@endsection
