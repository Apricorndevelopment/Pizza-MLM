@extends('layouts.layout')
@section('title', 'Stock Transfer')

@section('container')

    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa fa-cubes me-2"></i> Admin Stock Transfer</h5>
            </div>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card-body">
                <form action="{{ route('admin.stock.transfer') }}" method="POST">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="receiverUlid" class="form-label">Enter User ULID</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="receiverUlid" placeholder="Search by ULID"
                                    required>
                                <button class="btn btn-primary" type="button" id="searchUserBtn">
                                    <i class="fa fa-search me-1"></i> Search
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="userDetails" class="mb-4 p-3 border rounded" style="display: none;">
                        <h5>User Information</h5>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <p><strong>Name:</strong> <span id="userName"></span></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Email:</strong> <span id="userEmail"></span></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>ULID:</strong> <span id="userUlid"></span></p>
                            </div>
                        </div>
                        <input type="hidden" name="receiver_ulid" id="receiverUlidHidden">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="productSelect" class="form-label">Products</label>
                                <select class="form-select" id="productSelect" name="product_id" required>
                                    <option value="">Select product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" min="1"
                                    required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to transfer stock ?')">
                            <i class="fa fa-paper-plane me-1"></i> Transfer Stock
                        </button>
                    </div>
                </form>
            </div>

        </div>
        <div class="card shadow-sm mt-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Stock Transfer History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Sender</th>
                                <th>Receiver</th>
                                <th>product</th>
                                <th>Quantity</th>
                                <th>Location</th>
                                <th>Balance</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockTransfers as $transfer)
                                <tr>
                                    <td>
                                        {{ Auth::guard('admin')->user()->name ?? 'N/A' }}
                                    </td>
                                    <td>{{ $transfer->receiver->name ?? 'N/A' }}</td>
                                    <td>{{ $transfer->product->product_name ?? 'N/A' }}</td>
                                    <td>{{ $transfer->quantity }}</td>
                                    <td>{{ $transfer->to_location }} </td>
                                    <td>{{ $transfer->receiver_balance }} </td>
                                    <td>{{ $transfer->created_at->format('Y-m-d H:i a') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">No stock transfer history found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('searchUserBtn').addEventListener('click', function() {
                const ulid = document.getElementById('receiverUlid').value.trim();
                if (!ulid) {
                    alert('Please enter a ULID');
                    return;
                }

                fetch('{{ route('admin.stock.search-user') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            ulid: ulid
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('userName').textContent = data.user.name;
                            document.getElementById('userEmail').textContent = data.user.email;
                            document.getElementById('userUlid').textContent = data.user.ulid;
                            document.getElementById('receiverUlidHidden').value = data.user.ulid;
                            document.getElementById('userDetails').style.display = 'block';
                        } else {
                            alert(data.message);
                            document.getElementById('userDetails').style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error searching for user');
                    });
            });
        });
    </script>
@endsection
