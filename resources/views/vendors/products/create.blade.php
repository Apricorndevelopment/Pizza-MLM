@extends('vendorlayouts.layout')
@section('title', 'Add Product')
@section('container')

    <div class="max-w-6xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Add New Product</h2>
            </div>

            <div class="p-6">
                <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="product_name" class="block text-sm font-medium text-gray-700">
                                Product Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="product_name" id="product_name"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                      @error('product_name') border-red-500 ring-1 ring-red-500 @enderror transition duration-200"
                                value="{{ old('product_name') }}" placeholder="Enter product name" required>
                            @error('product_name')
                                <p class="text-sm text-red-600 mt-1 flex items-center">
                                    <i class="bi bi-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="product_image" class="block text-sm font-medium text-gray-700">
                                Product Image <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="file" name="product_image" id="product_image"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                              @error('product_image') border-red-500 ring-1 ring-red-500 @enderror transition duration-200
                                              file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                              file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700
                                              hover:file:bg-blue-100"
                                    accept="image/*" required onchange="previewCreateImage(event)">
                            </div>
                            
                            <div id="preview_container" class="hidden mt-2 p-2 bg-gray-50 border border-gray-200 rounded-lg">
                                <p class="text-xs text-gray-500 mb-1">Preview:</p>
                                <img id="create_preview_img" src="#" alt="Image Preview" class="h-24 w-auto object-cover rounded-md border border-gray-300">
                            </div>
                            @error('product_image')
                                <p class="text-sm text-red-600 mt-1 flex items-center">
                                    <i class="bi bi-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Supported formats: JPG, PNG, GIF, WEBP. Max size: 5MB</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="space-y-2">
                            <label for="mrp" class="block text-sm font-medium text-gray-700">
                                MRP <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                                <input type="number" step="0.01" name="mrp" id="mrp"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                              @error('mrp') border-red-500 ring-1 ring-red-500 @enderror transition duration-200"
                                    placeholder="0.00" value="{{ old('mrp') }}" required>
                            </div>
                            @error('mrp')
                                <p class="text-sm text-red-600 mt-1 flex items-center">
                                    <i class="bi bi-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="dp" class="block text-sm font-medium text-gray-700">
                                DP (Distributor Price) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                                <input type="number" step="0.01" name="dp" id="dp"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                              @error('dp') border-red-500 ring-1 ring-red-500 @enderror transition duration-200"
                                    placeholder="0.00" value="{{ old('dp') }}" required>
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
                                <input type="number" step="0.01" name="profit" id="profit"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                              @error('profit') border-red-500 ring-1 ring-red-500 @enderror transition duration-200"
                                    placeholder="0.00" value="{{ old('profit') }}" required>
                            </div>
                            @error('profit')
                                <p class="text-sm text-red-600 mt-1 flex items-center">
                                    <i class="bi bi-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="gst" class="block text-sm font-medium text-gray-700">
                                GST (%) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">%</span>
                                <input type="number" step="0.01" name="gst" id="gst"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                              @error('gst') border-red-500 ring-1 ring-red-500 @enderror transition duration-200"
                                    placeholder="Enter GST percentage" value="{{ old('gst', 5) }}" required>
                            </div>
                            @error('gst')
                                <p class="text-sm text-red-600 mt-1 flex items-center">
                                    <i class="bi bi-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                      @error('description') border-red-500 ring-1 ring-red-500 @enderror transition duration-200"
                            placeholder="Enter product description">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-sm text-red-600 mt-1 flex items-center">
                                <i class="bi bi-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="isVeg" class="block text-sm font-medium text-gray-700">
                            Is the Product Veg or Non Veg? <span class="text-red-500">*</span>
                        </label>
                        <select name="isVeg" id="isVeg" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="veg" {{ old('isVeg') === 'veg' ? 'selected' : '' }}>Veg</option>
                            <option value="non-veg" {{ old('isVeg') === 'non-veg' ? 'selected' : '' }}>Non Veg</option>
                        </select>
                        @error('isVeg')
                            <p class="text-sm text-red-600 mt-1 flex items-center">
                                <i class="bi bi-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200">
                        <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white font-medium rounded-lg
                                   hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2
                                   transition duration-200 flex items-center">
                            <i class="bi bi-cloud-upload mr-2"></i>
                            Upload Product
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

    <script>
        function previewCreateImage(event) {
            const reader = new FileReader();
            const imageField = document.getElementById('create_preview_img');
            const container = document.getElementById('preview_container');

            reader.onload = function() {
                if (reader.readyState == 2) {
                    imageField.src = reader.result;
                    container.classList.remove('hidden');
                }
            }

            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
@endsection