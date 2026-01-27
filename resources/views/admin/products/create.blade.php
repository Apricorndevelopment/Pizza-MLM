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
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="product_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Image <span class="text-danger">*</span></label>
                            <input type="file" name="product_image" class="form-control" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>MRP</label>
                            <input type="number" name="mrp" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Selling Price</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>GST (%)</label>
                            <input type="number" name="gst" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>DP</label>
                            <input type="number" name="dp" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="text-primary fw-bold">PV (Point Value)</label>
                            <input type="number" name="pv" class="form-control border-primary" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-primary fw-bold">Percentage (%)</label>
                            <input type="number" name="percentage" class="form-control border-primary" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-primary fw-bold">Max Coupons Allowed</label>
                            <input type="number" name="max_coupon_usage" class="form-control border-primary"
                                value="0">
                            <small class="text-muted">1 Coupon = ₹10 Discount</small>
                        </div>

                        <div class="col-12 mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Product</button>
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
            @if ($errors->any())
                form.classList.add('was-validated');
            @endif
        });
    </script>


@endsection
