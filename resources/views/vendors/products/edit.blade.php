@extends('vendorlayouts.layout')
@section('title', 'Edit My Product')
@section('container')

    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Edit Product: {{ $product->product_name }}</h2>
            </div>

            <!-- Form -->
            <div class="p-6">
                <form action="{{ route('vendor.products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Product Info Alert -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex items-center space-x-2">
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Status</span>
                                <span class="font-medium">{{ ucfirst($product->status) }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded">Max
                                    Coupons</span>
                                <span class="font-medium">{{ $product->max_coupon_usage ?? 0 }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">PV</span>
                                <span class="font-medium">{{ $product->pv ?? 'Not Set' }}</span>
                            </div>
                        </div>
                    </div>

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
                                Update Image
                            </label>
                            <div class="space-y-3">
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
                                    <div class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                                        <div class="flex items-center space-x-4">
                                            <img src="{{ asset($product->product_image) }}"
                                                alt="{{ $product->product_name }}"
                                                class="w-20 h-20 object-cover rounded-lg border border-gray-300">
                                            <div>
                                                <p class="text-sm text-gray-700">{{ basename($product->product_image) }}
                                                </p>
                                                <p class="text-xs text-gray-500">Click above to change</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Price Grid -->
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

                        <!-- Price -->
                        <div class="space-y-2">
                            <label for="profit" class="block text-sm font-medium text-gray-700">
                                Profit <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                                <input type="number" step="0.01" name="profit" id="profit"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                          @error('profit') border-red-500 ring-1 ring-red-500 @enderror transition duration-200"
                                    value="{{ old('profit', $product->profit) }}" required>
                            </div>
                            @error('profit')
                                <p class="text-sm text-red-600 mt-1 flex items-center">
                                    <i class="bi bi-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- GST -->
                        <div class="space-y-2">
                            <label for="gst" class="block text-sm font-medium text-gray-700">
                                GST (%) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">%</span>
                                <input type="number" step="0.01" name="gst" id="gst"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                          @error('gst') border-red-500 ring-1 ring-red-500 @enderror transition duration-200"
                                    value="{{ old('gst', $product->gst) }}" required>
                            </div>
                            @error('gst')
                                <p class="text-sm text-red-600 mt-1 flex items-center">
                                    <i class="bi bi-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- DP -->
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

                            <option value="non-veg" {{ old('isVeg', $product->isVeg) === 'non-veg' ? 'selected' : '' }}>
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


                    <!-- Buttons -->
                    <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200">
                        <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white font-medium rounded-lg
                                   hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2
                                   transition duration-200 flex items-center">
                            <i class="bi bi-check-circle mr-2"></i>
                            Save Changes
                        </button>
                        <a href="{{ route('vendor.products.index') }}"
                            class="px-6 py-2.5 bg-gray-200 text-gray-700 font-medium rounded-lg
                              hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2
                              transition duration-200 flex items-center">
                            <i class="bi bi-x-circle mr-2"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
