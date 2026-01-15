@extends('userlayouts.layouts')
@section('title', 'Transfer Points')

@section('container')
    <div class="container py-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i> Transfer Points to Downline</h5>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-success mb-1">
                            <div class="card-body">
                                <h6 class="card-title text-muted">YOUR POINTS BALANCE</h6>
                                <h2 class="text-success fw-bold">{{ number_format(Auth::user()->wallet1_balance) }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="transferForm" method="POST" action="{{ route('user.transfer.points') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="ulid" class="form-label">Downline Member ULID</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="ulid" name="ulid"
                                    placeholder="Enter user's ULID" required>
                                <button class="btn btn-outline-primary" type="button" id="searchUserBtn">
                                    <i class="fas fa-search me-1"></i> Search
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="userDetails" class="card mb-2" style="display: none;">
                        <div class="card-body">
                            <h5 class="card-title" id="userName"></h5>
                            <p class="mb-1"><strong>Email:</strong> <span id="userEmail"></span></p>
                            <p class="mb-1"><strong>Current Points:</strong> <span id="userBalance"></span></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="points" class="form-label">Points to Transfer</label>
                            <input type="number" class="form-control" id="points" name="points" min="1"
                                required>
                            <div class="form-text">Minimum transfer: 1 point</div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="transferBtn" onclick="Are you sure you want to transfer points ?" disabled>
                        <i class="fas fa-paper-plane me-1"></i> Transfer Points
                    </button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i> Your Direct Downline (Level 1)</h5>
            </div>
            <div class="card-body">
                @if ($downlineUsers->isEmpty())
                    <div class="alert alert-info">You don't have any direct downline members yet</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover" id="downlineTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>ULID</th>
                                    <th>Email</th>
                                    <th>Points</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($downlineUsers as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->ulid }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ number_format($user->wallet1_balance) }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary select-user"
                                                data-ulid="{{ $user->ulid }}" data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
                                                data-balance="{{ $user->wallet1_balance }}">
                                                <i class="fas fa-hand-pointer me-1"></i> Select
                                            </button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search user functionality
            document.getElementById('searchUserBtn').addEventListener('click', function() {
                const ulid = document.getElementById('ulid').value.trim();
                if (!ulid) {
                    alert('Please enter a ULID');
                    return;
                }

                fetch('{{ route('user.search.downline') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            ulid: ulid
                        })
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            document.getElementById('userName').textContent = data.user.name + ' (' +
                                data.user.ulid + ')';
                            document.getElementById('userEmail').textContent = data.user.email;
                            document.getElementById('userBalance').textContent = data.user
                                .wallet1_balance;
                            document.getElementById('userDetails').style.display = 'block';
                            document.getElementById('transferBtn').disabled = false;
                        } else {
                            alert(data.message);
                            document.getElementById('userDetails').style.display = 'none';
                            document.getElementById('transferBtn').disabled = true;
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        alert('Error searching for user: ' + error.message);
                    });
            });

            // Select user from table
            document.querySelectorAll('.select-user').forEach(button => {
                button.addEventListener('click', function() {
                    const ulid = this.dataset.ulid;
                    const name = this.dataset.name;
                    const email = this.dataset.email;
                    const balance = this.dataset.balance;

                    document.getElementById('ulid').value = ulid;
                    document.getElementById('userName').textContent = name + ' (' + ulid + ')';
                    document.getElementById('userEmail').textContent = email;
                    document.getElementById('userBalance').textContent = balance;
                    document.getElementById('userDetails').style.display = 'block';
                    document.getElementById('transferBtn').disabled = false;
                });
            });

            // Form validation
            document.getElementById('transferForm').addEventListener('submit', function(e) {
                const points = parseInt(document.getElementById('points').value);
                const balance = parseInt('{{ Auth::user()->wallet1_balance }}');

                if (points > balance) {
                    e.preventDefault();
                    alert('You cannot transfer more points than your current balance');
                }
            });
        });
    </script>
@endsection