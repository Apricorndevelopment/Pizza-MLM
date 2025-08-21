@extends('layouts.layout')
@section('title', 'Sales Stock Management')
@section('container')

<div class="container py-4">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Sales Form -->
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-2">
                    <h5 class="mb-0"><i class="fas fa-cash-register me-2"></i>Record Sale</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.sales.stock.save') }}" method="POST" id="salesForm">
                        @csrf
                        <div class="mb-3">
                            <label for="user_ulid" class="form-label">User ULID</label>
                            <select class="form-select" id="user_ulid" name="user_ulid" required>
                                <option value="">Select User ULID</option>
                                @foreach($inventories->unique('user_ulid') as $inventory)
                                    <option value="{{ $inventory->user_ulid }}">{{ $inventory->user_ulid }} - {{ $inventory->user->name ?? 'N/A' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <select class="form-select" id="location" name="location" required>
                                <option value="">Select Location</option>
                                @foreach($inventories->unique('location') as $inventory)
                                    <option value="{{ $inventory->location }}">{{ $inventory->location }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="product_id" class="form-label">Product</label>
                            <select class="form-select" id="product_id" name="product_id" required disabled>
                                <option value="">Please select user and location first</option>
                            </select>
                            <div id="availableQuantity" class="form-text text-info mt-2"></div>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" 
                                   min="1" required placeholder="Enter quantity to sell" disabled>
                        </div>

                        <button type="submit" class="btn btn-success w-100" id="submitBtn" disabled>
                            <i class="fas fa-check me-2"></i>Record Sale
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sales History -->
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white py-2">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Sales History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Date</th>
                                    <th>User</th>
                                    <th>Product</th>
                                    <th class="text-center">Quantity</th>
                                    <th>Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $sale)
                                <tr>
                                    <td class="ps-3">
                                        <small>{{ $sale->created_at->format('d-M-y, h:i A') }}</small>
                                        <br>
                                    </td>
                                    <td>
                                        <small>{{ $sale->user_ulid }}</small>
                                        <br>
                                        <small class="text-muted">{{ $sale->user->name }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $sale->product_name }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $sale->quantity }}</span>
                                    </td>
                                    <td>
                                        <small>{{ $sale->location }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-receipt fa-2x text-muted mb-3"></i>
                                        <p class="text-muted small mb-0">No sales recorded yet</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($sales->hasPages())
                    <div class="d-flex justify-content-between align-items-center border-top px-3 py-2">
                        <div class="text-muted small">
                            Showing {{ $sales->firstItem() }} to {{ $sales->lastItem() }} of {{ $sales->total() }} entries
                        </div>
                        <nav>
                            {{ $sales->links() }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userUlidSelect = document.getElementById('user_ulid');
    const locationSelect = document.getElementById('location');
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const availableQuantityDiv = document.getElementById('availableQuantity');
    const submitBtn = document.getElementById('submitBtn');
    
    // Store inventory data from server
    const inventoryData = @json($inventories);
    
    // Function to filter products based on user and location
    function filterProducts() {
        const userUlid = userUlidSelect.value;
        const location = locationSelect.value;
        
        // Clear previous options
        productSelect.innerHTML = '';
        quantityInput.value = '';
        quantityInput.disabled = true;
        submitBtn.disabled = true;
        availableQuantityDiv.textContent = '';
        
        if (!userUlid || !location) {
            productSelect.disabled = true;
            productSelect.innerHTML = '<option value="">Please select user and location first</option>';
            return;
        }
        
        // Filter products based on selected user and location
        const filteredProducts = inventoryData.filter(item => 
            item.user_ulid === userUlid && item.location === location && item.quantity > 0
        );
        
        if (filteredProducts.length === 0) {
            productSelect.disabled = true;
            productSelect.innerHTML = '<option value="">No products available for this user and location</option>';
            return;
        }
        
        // Add filtered products to select
        productSelect.disabled = false;
        productSelect.innerHTML = '<option value="">Select Product</option>';
        
        filteredProducts.forEach(product => {
            const option = document.createElement('option');
            option.value = product.product_id;
            option.textContent = `${product.product.product_name} (Available: ${product.quantity})`;
            option.setAttribute('data-quantity', product.quantity);
            productSelect.appendChild(option);
        });
    }
    
    // Function to update available quantity
    function updateAvailableQuantity() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        
        if (productSelect.value && selectedOption) {
            const availableQty = selectedOption.getAttribute('data-quantity');
            availableQuantityDiv.textContent = `Available Quantity: ${availableQty}`;
            
            // Enable quantity input and set max value
            quantityInput.disabled = false;
            quantityInput.setAttribute('max', availableQty);
            quantityInput.setAttribute('placeholder', `Max: ${availableQty}`);
            
            // Enable submit button
            submitBtn.disabled = false;
        } else {
            availableQuantityDiv.textContent = '';
            quantityInput.disabled = true;
            quantityInput.value = '';
            submitBtn.disabled = true;
        }
    }
    
    // Add event listeners
    userUlidSelect.addEventListener('change', filterProducts);
    locationSelect.addEventListener('change', filterProducts);
    productSelect.addEventListener('change', updateAvailableQuantity);
    
    // Form validation
    document.getElementById('salesForm').addEventListener('submit', function(e) {
        const maxQuantity = parseInt(quantityInput.getAttribute('max'));
        const enteredQuantity = parseInt(quantityInput.value);
        
        if (enteredQuantity > maxQuantity) {
            e.preventDefault();
            alert(`Cannot sell more than available quantity (${maxQuantity})`);
        } else {
            if (!confirm('Are you sure you want to record this sale?')) {
                e.preventDefault();
            }
        }
    });
});
</script>

<style>
.table-sm th, .table-sm td {
    padding: 0.7rem;
    font-size: 0.85rem;
}
.table thead th {
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.card-header {
    border-radius: 0.5rem 0.5rem 0 0 !important;
}
.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.5rem;
}
</style>
@endsection