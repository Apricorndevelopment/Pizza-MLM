@extends('userlayouts.layouts')
@section('title', 'Stock Transfer')
@section('container')

    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i> Transfer Stock</h5>
            </div>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card-body">
                <form action="{{ route('user.stock.transfer') }}" method="POST" id="transferForm">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="receiverUlid" class="form-label">Enter Receiver ULID</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="receiverUlid" placeholder="Search by ULID"
                                    required>
                                <button class="btn btn-primary" type="button" id="searchUserBtn">
                                    <i class="fas fa-search me-1"></i> Search
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="userDetails" class="mb-4 p-3 border rounded" style="display: none;">
                        <h5>Receiver Information</h5>
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
                                <label for="productSelect" class="form-label">Product</label>
                                <select class="form-select" id="productSelect" name="product_id" required>
                                    <option value="">Select product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            data-available="{{ Auth::user()->packageInventories->where('product_id', $product->id)->first()->quantity ?? 0 }}">
                                            {{ $product->product_name }}
                                            (Available:
                                            {{ Auth::user()->packageInventories->where('product_id', $product->id)->first()->quantity ?? 0 }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" min="1"
                                    required>
                                <small class="text-muted" id="availableStock"></small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="to_location" class="form-label">Destination Location</label>
                                <input type="text" class="form-control" id="to_location" name="to_location" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success" onclick="return confirm('Are You sure you want to transfer stock ?')">
                            <i class="fas fa-paper-plane me-1"></i> Transfer Stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search user
            document.getElementById('searchUserBtn').addEventListener('click', function() {
                const ulid = document.getElementById('receiverUlid').value.trim();
                if (!ulid) {
                    alert('Please enter a ULID');
                    return;
                }

                fetch('{{ route('user.stock.search-user') }}', {
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

            // Show available quantity when product selected
            document.getElementById('productSelect').addEventListener('change', function() {
                const available = this.options[this.selectedIndex].dataset.available;
                document.getElementById('availableStock').textContent = `Available: ${available}`;
                document.getElementById('quantity').max = available;
            });

            // Validate form
            document.getElementById('transferForm').addEventListener('submit', function(e) {
                const quantity = parseInt(document.getElementById('quantity').value);
                const available = parseInt(document.getElementById('productSelect').options[document
                    .getElementById('productSelect').selectedIndex].dataset.available);

                if (quantity > available) {
                    e.preventDefault();
                    alert('You cannot transfer more stock than you have available');
                }
            });
        });
    </script>
@endsection