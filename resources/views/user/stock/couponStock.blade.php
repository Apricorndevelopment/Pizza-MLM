@extends('userlayouts.layouts')
@section('title', 'Coupon Transfer')
@section('container')

    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-ticket-alt me-2"></i> Transfer Using Coupon</h5>
            </div>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card-body">
                <form action="{{ route('user.stock.transfer-by-coupon') }}" method="POST" id="couponTransferForm">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="couponCode" class="form-label">Geokranti Shopping Card Number</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="couponCode" name="coupon_code"
                                    placeholder="Example: GEO1PQ5KG" required>
                                <button class="btn btn-primary" type="button" id="validateCouponBtn">
                                    <i class="fas fa-check-circle me-1"></i> Validate
                                </button>
                            </div>
                            <small class="text-muted">Format: GEO{user_id}PQ{quantity}{unit}</small>
                        </div>
                    </div>

                    <div id="couponDetails" class="mb-4 p-3 border rounded" style="display: none;">
                        <h5>Coupon Information</h5>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <p><strong>Reciever:</strong> <span id="couponUserName"></span></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Product Quantity:</strong> <span id="couponQuantity"></span></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Coupon Code:</strong> <span id="couponCodeDisplay"></span></p>
                            </div>
                        </div>
                        <input type="hidden" name="receiver_ulid" id="couponUserUlid">
                        <input type="hidden" name="quantity" id="couponQtyValue">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="couponProductSelect" class="form-label">Product</label>
                                <select class="form-select" id="couponProductSelect" name="product_id" required>
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
                                <label for="couponToLocation" class="form-label">Destination Location</label>
                                <input type="text" class="form-control" id="couponToLocation" name="to_location"
                                    required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="couponNotes" class="form-label">Notes</label>
                                <textarea class="form-control" id="couponNotes" name="notes" rows="2"></textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane me-1"></i> Transfer Using Coupon
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validate coupon
            document.getElementById('validateCouponBtn').addEventListener('click', function() {
                const couponCode = document.getElementById('couponCode').value.trim();
                if (!couponCode) {
                    alert('Please enter a coupon code');
                    return;
                }

                // Basic format validation
                if (!couponCode.match(/^GEO\d+PQ\d+[A-Za-z]*$/i)) {
                    alert('Invalid coupon format. Example: GEO123PQ5KG or GEO123PQ5');
                    return;
                }

                fetch('{{ route('user.stock.validate-coupon') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            coupon_code: couponCode
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('couponUserName').textContent = data.user.name;
                            document.getElementById('couponQuantity').textContent = data.quantity;
                            document.getElementById('couponCodeDisplay').textContent = data.coupon_code;
                            document.getElementById('couponUserUlid').value = data.user.ulid;
                            document.getElementById('couponQtyValue').value = data.quantity;
                            document.getElementById('couponDetails').style.display = 'block';
                        } else {
                            alert(data.message);
                            document.getElementById('couponDetails').style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error validating coupon');
                    });
            });

            // Validate form submission
            document.getElementById('couponTransferForm').addEventListener('submit', function(e) {
                const productSelect = document.getElementById('couponProductSelect');
                const available = parseInt(productSelect.options[productSelect.selectedIndex].dataset
                    .available);
                const quantity = parseInt(document.getElementById('couponQtyValue').value);

                if (quantity > available) {
                    e.preventDefault();
                    alert('You cannot transfer more stock than you have available');
                }
            });
        });
    </script>
@endsection
