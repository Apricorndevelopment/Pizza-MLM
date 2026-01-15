@extends('layouts.layout')
@section('title', 'Dashboard')
@section('container')

<div class="container">
    <!-- Header with back button -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <h4 class="mb-0 text-primary">
            <i class="bi bi-plus-circle-fill me-2"></i>Add New Product
        </h4>
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> Back to Products
        </a>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-light py-3 border-bottom">
            <h5 class="mb-0 text-dark">Product Information</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.products.store') }}" method="POST" id="productForm" class="needs-validation" novalidate>
                @csrf
                
                <div class="row g-3">
                    <!-- Product Name -->
                    <div class="col-md-6 mb-3">
                        <label for="productName" class="form-label fw-medium text-dark">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control p-2 border-secondary @error('product_name') is-invalid @enderror" 
                               id="productName" name="product_name" required value="{{ old('product_name') }}">
                        @error('product_name')
                        <div class="invalid-feedback">
                           {{ $message }}
                        </div>
                        @enderror
                    </div>
                    
                    <!-- Price -->
                    <div class="col-md-6 mb-3">
                        <label for="productPrice" class="form-label fw-medium text-dark">Price <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" class="form-control p-2 border-secondary @error('price') is-invalid @enderror" 
                                   id="productPrice" name="price" required value="{{ old('price') }}">
                            @error('price')
                            <div class="invalid-feedback">
                               {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="col-12 mb-4">
                        <label for="productDescription" class="form-label fw-medium text-dark">Description</label>
                        <textarea class="form-control p-2 border-secondary @error('description') is-invalid @enderror" 
                                  id="productDescription" name="description" rows="4" placeholder="Enter product details...">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">
                           {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="d-flex justify-content-between align-items-center pt-3 mt-2 border-top">
                    <button type="reset" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-eraser me-2"></i>Reset
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-2"></i>Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('productForm');
    
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        form.classList.add('was-validated');
    }, false);
    
    // Add old input values after validation fails
    @if($errors->any())
        form.classList.add('was-validated');
    @endif
});
</script>


@endsection