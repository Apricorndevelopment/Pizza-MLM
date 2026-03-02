@extends('layouts.layout')
@section('title', 'Dashboard')
@section('container')

    <div class="min-h-screen bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="">
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                            <i class="bi bi-pencil-square text-blue-600 mr-3"></i>
                            Edit Product
                        </h1>
                        <p class="text-gray-600 mt-1">Update product details and approval status</p>
                    </div>
                    <a href="{{ route('admin.products.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg 
                          text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="bi bi-arrow-left mr-2"></i>
                        Back to Products
                    </a>
                </div>
            </div>

            <!-- Validation Error Message -->
            @if ($errors->any())
                <div id="alert-error"
                    class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm flex justify-between items-center transition-opacity duration-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-exclamation-circle-fill text-red-500 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">Please fix the following errors:</p>
                            <ul class="mt-1 text-sm text-red-600 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button onclick="dismissAlert('alert-error')"
                        class="ml-auto pl-3 text-red-500 hover:text-red-700 focus:outline-none">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div id="alert-success"
                    class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm flex justify-between items-center transition-opacity duration-500">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <i class="bi bi-check-circle-fill text-green-500 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button onclick="dismissAlert('alert-success')"
                        class="ml-auto pl-3 text-green-500 hover:text-green-700 focus:outline-none">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Card Header -->
                <div class="px-6 py-3 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="bi bi-box-seam text-blue-500 mr-2"></i>
                        Product Details
                    </h2>
                </div>

                <!-- Form -->
                <div class="px-4 py-3">
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <!-- Vendor Info -->
                        @if ($product->vendor_id)
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="bi bi-shop text-blue-500 text-lg mr-3"></i>
                                    <div>
                                        <p class="font-medium text-blue-800">Vendor Product</p>
                                        <p class="text-sm text-blue-600">
                                            <strong>Vendor:</strong> {{ $product->vendor->vendor_name ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Product Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Product Name -->
                            <div class="space-y-2">
                                <label for="product_name" class="block text-sm font-medium text-gray-700">
                                    Product Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="product_name" id="product_name"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                          @error('product_name') border-red-500 ring-1 ring-red-500 @enderror transition duration-200"
                                    value="{{ old('product_name', $product->product_name) }}" required>
                                @error('product_name')
                                    <p class="text-sm text-red-600 mt-1 flex items-center">
                                        <i class="bi bi-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Product Image -->
                            <div class="space-y-2">
                                <label for="product_image" class="block text-sm font-medium text-gray-700">
                                    Product Image
                                </label>
                                <input type="file" name="product_image" id="product_image"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                          @error('product_image') border-red-500 ring-1 ring-red-500 @enderror transition duration-200
                                          file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                          file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700
                                          hover:file:bg-blue-100"
                                    accept="image/*">
                                @error('product_image')
                                    <p class="text-sm text-red-600 mt-1 flex items-center">
                                        <i class="bi bi-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror

                                @if ($product->product_image)
                                    <div class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                                        <div class="flex items-center space-x-4">
                                            <img src="{{ asset($product->product_image) }}"
                                                alt="{{ $product->product_name }}"
                                                class="w-16 h-16 object-cover rounded-lg border border-gray-300">
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">{{ $product->product_name }}
                                                </p>
                                                <p class="text-xs text-gray-500">Click above to change image</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Price Grid - UPDATED WITH GST AND DP -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- MRP -->
                            <div class="space-y-2">
                                <label for="mrp" class="block text-sm font-medium text-gray-700">
                                    MRP <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                                    <input type="number" step="0.01" name="mrp" id="mrp"
                                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                              @error('mrp') border-red-500 ring-1 ring-red-500 @enderror transition duration-200"
                                        value="{{ old('mrp', $product->mrp) }}" required>
                                </div>
                                @error('mrp')
                                    <p class="text-sm text-red-600 mt-1 flex items-center">
                                        <i class="bi bi-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <!-- DP - ADDED THIS FIELD -->
                            <div class="space-y-2">
                                <label for="dp" class="block text-sm font-medium text-gray-700">
                                    DP (Distributor Price) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                                    <input type="number" step="0.01" name="dp" id="dp"
                                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                              @error('dp') border-red-500 ring-1 ring-red-500 @enderror transition duration-200"
                                        value="{{ old('dp', $product->dp) }}" required>
                                </div>
                                @error('dp')
                                    <p class="text-sm text-red-600 mt-1 flex items-center">
                                        <i class="bi bi-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label for="profit" class="block text-sm font-medium text-gray-700">
                                    Profit <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>

                                    {{-- FIX: Changed name="Profit" to name="profit" to match @error('profit') --}}
                                    <input type="number" step="0.01" name="profit" id="profit"
                                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
            @error('profit') border-red-500 ring-1 ring-red-500 @enderror transition duration-200"
                                        value="{{ old('profit', $product->profit ?? '') }}" required>
                                </div>

                                @error('profit')
                                    <p class="text-sm text-red-600 mt-1 flex items-center">
                                        <i class="bi bi-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- GST - ADDED THIS FIELD -->
                            <div class="space-y-2">
                                <label for="gst" class="block text-sm font-medium text-gray-700">
                                    GST (%)
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">%</span>
                                    <input type="number" step="0.01" name="gst" id="gst"
                                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                              @error('gst') border-red-500 ring-1 ring-red-500 @enderror transition duration-200"
                                        value="{{ old('gst', $product->gst) }}">
                                </div>
                                @error('gst')
                                    <p class="text-sm text-red-600 mt-1 flex items-center">
                                        <i class="bi bi-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>


                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="4"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                         @error('description') border-red-500 ring-1 ring-red-500 @enderror transition duration-200"
                                placeholder="Enter product description">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-600 mt-1 flex items-center">
                                    <i class="bi bi-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Is Veg or Non Veg -->
                        <div class="space-y-2">
                            <label for="isVeg" class="block text-sm font-medium text-gray-700">
                                Is the Product Veg or Non Veg? <span class="text-red-500">*</span>
                            </label>

                            <select name="isVeg" id="isVeg" class="form-select">
                                <option value="veg" {{ old('isVeg', $product->isVeg) === 'veg' ? 'selected' : '' }}>
                                    Veg
                                </option>

                                <option value="non-veg"
                                    {{ old('isVeg', $product->isVeg) === 'non-veg' ? 'selected' : '' }}>
                                    Non Veg
                                </option>
                            </select>

                            @error('isVeg')
                                <p class="text-sm text-red-600 mt-1 flex items-center">
                                    <i class="bi bi-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Admin Controls Section -->
                        <div class="pt-6 border-t border-gray-200">
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                    <i class="bi bi-gear text-purple-600 mr-2"></i>
                                    Admin Controls (Approval Settings)
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">Configure commission, points, and approval status</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <!-- PV -->
                                <div class="space-y-2">
                                    <label for="pv" class="block text-sm font-medium text-gray-700">
                                        <span class="text-purple-600 font-semibold">PV (Point Value)</span>
                                    </label>
                                  
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-purple-500">
                                            <i class="bi bi-star"></i>
                                        </span>
                                        <input type="number" name="pv" id="pv"
                                            class="w-full pl-10 pr-4 py-2.5 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 
                                                  @error('pv') border-red-500 ring-1 ring-red-500 @enderror transition duration-200"
                                            value="{{ old('pv', $product->pv ?? 0) }}" required>
                                    </div>
                                      <div> {{-- Fixed height to prevent layout jump --}}
                                        <span class="text-xs font-bold text-red-600" id="recomendedPv"></span>
                                    </div>
                                    <p class="text-xs text-gray-500">Required for commission calculation</p>
                                    @error('pv')
                                        <p class="text-sm text-red-600 mt-1 flex items-center">
                                            <i class="bi bi-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="pv" class="block text-sm font-medium text-gray-700">
                                        <span class="text-purple-600 font-semibold">Percentage</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-purple-500">
                                            <i class="bi bi-percent"></i>
                                        </span>
                                        <input type="number" name="percentage" id="percentage"
                                            class="w-full pl-10 pr-4 py-2.5 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 
                                                  @error('percentage') border-red-500 ring-1 ring-red-500 @enderror transition duration-200"
                                            value="{{ old('percentage', $product->percentage ?? 0) }}" required>
                                    </div>
                                    <p class="text-xs text-gray-500">Required for percentage to the admin</p>
                                    @error('percentage')
                                        <p class="text-sm text-red-600 mt-1 flex items-center">
                                            <i class="bi bi-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Max Coupons -->
                                <div class="space-y-2">
                                    <label for="max_coupon_usage" class="block text-sm font-medium text-gray-700">
                                        <span class="text-purple-600 font-semibold">Max Coupons</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-purple-500">
                                            <i class="bi bi-ticket-perforated"></i>
                                        </span>
                                        <input type="number" name="max_coupon_usage" id="max_coupon_usage"
                                            class="w-full pl-10 pr-4 py-2.5 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 
                                                  @error('max_coupon_usage') border-red-500 ring-1 ring-red-500 @enderror transition duration-200"
                                            value="{{ old('max_coupon_usage', $product->max_coupon_usage ?? 0) }}" required>
                                    </div>
                                    @error('max_coupon_usage')
                                        <p class="text-sm text-red-600 mt-1 flex items-center">
                                            <i class="bi bi-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mt-6 space-y-2">
                                <label for="status" class="block text-sm font-medium text-gray-700">
                                    <span class="text-purple-600 font-semibold">Approval Status</span>
                                </label>
                                <select name="status" id="status"
                                    class="w-full px-4 py-2.5 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 
                                           @error('status') border-red-500 ring-1 ring-red-500 @enderror transition duration-200">
                                    <option value="pending"
                                        {{ old('status', $product->status) == 'pending' ? 'selected' : '' }}>
                                        <i class="bi bi-clock-history"></i> Pending
                                    </option>
                                    <option value="approved"
                                        {{ old('status', $product->status) == 'approved' ? 'selected' : '' }}>
                                        <i class="bi bi-check-circle"></i> Approved
                                    </option>
                                    <option value="rejected"
                                        {{ old('status', $product->status) == 'rejected' ? 'selected' : '' }}>
                                        <i class="bi bi-x-circle"></i> Rejected
                                    </option>
                                </select>
                                @error('status')
                                    <p class="text-sm text-red-600 mt-1 flex items-center">
                                        <i class="bi bi-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>

                        <!-- Form Actions -->
                        <div class="pt-6 border-t border-gray-200">
                            <div class="flex flex-wrap gap-3">
                                <button type="submit"
                                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg
                                           hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                                           transition duration-200 flex items-center">
                                    <i class="bi bi-check-circle mr-2"></i>
                                    Update Product
                                </button>
                                <a href="{{ route('admin.products.index') }}"
                                    class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg
                                      hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2
                                      transition duration-200 flex items-center">
                                    <i class="bi bi-x-circle mr-2"></i>
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const recomendedPv = document.getElementById('recomendedPv');
        const profit = document.getElementById('profit');

        if (profit) { // Safety check to ensure element exists
            const recommendedPvValue = (profit.value * 30) / 100;
            // FIX: Changed Math.rounded to Math.round
            recomendedPv.textContent = `Recommended PV: ${Math.round(recommendedPvValue)}`;
            profit.addEventListener('input', function() {
                console.log("running");
                if (this.value) {
                    const recommendedPvValue = (this.value * 30) / 100;
                    // FIX: Changed Math.rounded to Math.round
                    recomendedPv.textContent = `Recommended PV: ${Math.round(recommendedPvValue)}`;
                } else {
                    recomendedPv.textContent = '';
                }
            });
        }

        function dismissAlert(alertId) {
            const element = document.getElementById(alertId);
            if (element) {
                element.style.opacity = '0';
                setTimeout(() => element.remove(), 500);
            }
        }

        // Auto dismiss after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('[id^="alert-"]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    dismissAlert(alert.id);
                }, 5000);
            });
        });
    </script>

@endsection
