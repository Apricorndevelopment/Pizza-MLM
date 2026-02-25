@extends('layouts.layout')
@section('title', 'Edit Product')
@section('container')

    <div class="min-h-screen bg-gray-50 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">

            {{-- Breadcrumb / Header --}}
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Product</h1>
                    <p class="text-sm text-gray-500 mt-1">Update details for <span
                            class="font-semibold text-blue-600">{{ $product->product_name }}</span></p>
                </div>
                <a href="{{ route('admin.product-package') }}"
                    class="hidden sm:flex items-center text-gray-600 hover:text-blue-600 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Cancel Edit
                </a>
            </div>

            {{-- Alerts --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg bg-green-50 p-4 border-l-4 border-green-500 shadow-sm flex items-start">
                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                    <div>
                        <h3 class="text-sm font-medium text-green-800">Success</h3>
                        <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 p-4 border-l-4 border-red-500 shadow-sm">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle text-red-500 mt-1 mr-3"></i>
                        <div>
                            <h3 class="text-sm font-medium text-red-800">Validation Error</h3>
                            <ul class="mt-2 list-disc list-inside text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Main Card --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">

                {{-- Card Header --}}
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/10 p-2 rounded-lg backdrop-blur-sm">
                            <i class="fas fa-edit text-white text-lg"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-white tracking-wide">Update Information</h2>
                    </div>
                </div>

                <form action="{{ route('admin.package2.update', $product->id) }}" method="POST"
                    enctype="multipart/form-data" class="p-6 md:p-8">
                    @csrf
                    @method('PUT')

                    {{-- TRUE HIDDEN FIELD FOR CONTROLLER --}}
                    {{-- Retains existing state or falls back to '0' --}}
                    <input type="hidden" name="is_package_product" id="is_package_product_hidden" value="{{ old('is_package_product', $product->is_package_product ?? '0') }}">


                    {{-- Section 1: Basic Info & Image --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">

                        {{-- Left Column --}}
                        <div class="lg:col-span-7 space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Product Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="product_name"
                                    value="{{ old('product_name', $product->product_name) }}"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm placeholder-gray-400"
                                    required>
                            </div>


                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Max Coupons Allowed <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i
                                            class="bi bi-ticket-perforated"></i></span>
                                    <input type="number"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm"
                                        name="max_coupon_usage"
                                        value="{{ old('max_coupon_usage', $product->max_coupon_usage) }}" min="0">
                                </div>
                                <small class="text-gray-500 mt-1 block">Current Limit: {{ $product->max_coupon_usage }} coupons per unit</small>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                <textarea name="description" rows="4"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm placeholder-gray-400">{{ old('description', $product->description) }}</textarea>
                            </div>
                        </div>

                        {{-- Right Column: Image Logic --}}
                        <div class="lg:col-span-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Product Image</label>
                            <input type="file" id="product_image" name="product_image" class="hidden" accept="image/*">

                            @php
                                $hasImage =
                                    $product->product_image && file_exists(public_path($product->product_image));
                                $imageUrl = $hasImage ? asset($product->product_image) : '#';
                            @endphp

                            {{-- Drop Zone (Hidden if image exists) --}}
                            <div id="drop-zone"
                                class="{{ $hasImage ? 'hidden' : '' }} bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 p-6 flex flex-col items-center justify-center text-center hover:bg-blue-50 hover:border-blue-400 transition-all cursor-pointer group h-full min-h-[250px]">
                                <div
                                    class="bg-white p-4 rounded-full shadow-sm mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-blue-500"></i>
                                </div>
                                <h4 class="text-gray-900 font-medium mb-1">Click to upload new image</h4>
                                <p class="text-sm text-gray-500">or drag and drop here</p>
                            </div>

                            {{-- Preview / Existing Image Area --}}
                            <div id="preview-area" class="{{ $hasImage ? '' : 'hidden' }} h-full">
                                <div
                                    class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 h-full flex flex-col items-center justify-center relative group">
                                    <div
                                        class="w-40 h-40 rounded-lg overflow-hidden bg-gray-100 border border-gray-100 mb-4 shadow-inner">
                                        <img id="preview-img" src="{{ $imageUrl }}" alt="Product Image"
                                            class="w-full h-full object-cover">
                                    </div>

                                    <p id="file-status" class="text-sm font-medium text-gray-700 mb-2">
                                        {{ $hasImage ? 'Current Image' : 'New Image Selected' }}
                                    </p>

                                    <button type="button" id="remove-btn"
                                        class="inline-flex items-center px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                                        <i class="fas fa-camera mr-2"></i> Change Image
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="isVeg" class="block text-sm font-medium text-gray-700">
                            Is the Product Veg or Non Veg? <span class="text-red-500">*</span>
                        </label>

                        <select name="isVeg" id="isVeg" class="form-select w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm">
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
                    <hr class="border-gray-100 my-8">

                    {{-- Section 2: Pricing --}}
                    <div class="bg-blue-50/50 rounded-xl p-6 border border-blue-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                            <span
                                class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm mr-3">
                                <i class="fas fa-tag"></i>
                            </span>
                            Pricing Configuration
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="group">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">MRP <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₹</span>
                                    <input type="number" step="0.01" name="mrp"
                                        value="{{ old('mrp', $product->mrp) }}"
                                        class="w-full pl-8 pr-4 py-2.5 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all shadow-sm font-semibold text-gray-700"
                                        required>
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">GST %</label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="gst"
                                        value="{{ old('gst', $product->gst) }}"
                                        class="w-full pl-4 pr-8 py-2.5 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all shadow-sm font-semibold text-gray-700">
                                    <span
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">%</span>
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">DP <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₹</span>
                                    <input type="number" step="0.01" name="dp"
                                        value="{{ old('dp', $product->dp) }}"
                                        class="w-full pl-8 pr-4 py-2.5 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all shadow-sm font-semibold text-gray-700"
                                        required>
                                </div>
                            </div>

                            {{-- PROFIT FIELD WITH RECOMMENDATION LABEL --}}
                            <div class="group">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Profit
                                    <span class="text-red-500">*</span>
                                </label>

                                {{-- Placeholder for Recommended PV --}}
                                <label class="block text-xs mb-1 text-blue-600 font-bold" id="recomend"></label>

                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₹</span>
                                    <input type="number" step="0.1" name="profit" id="profit"
                                        value="{{ old('profit', $product->profit) }}"
                                        class="w-full pl-8 pr-4 py-2.5 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all shadow-sm font-semibold text-green-600"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 3: Capping & PV --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

                        {{-- PV FIELD --}}
                        <div>
                            <label for="pv" class="block text-sm font-semibold text-gray-700 mb-2">
                                PV (Point Value) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="pv" id="pv"
                                    value="{{ old('pv', $product->pv) }}"
                                    placeholder="Enter total Points Value (e.g. 50)"
                                    class="w-full pl-4 pr-4 py-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm"
                                    required>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Enter the direct point value for this product.</p>
                        </div>

                        {{-- CAPPING FIELD WITH TOGGLE CHECKBOX --}}
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            {{-- Checkbox Toggle (UI only) --}}
                            <div class="flex items-center mb-3">
                                <input type="checkbox" id="ui-toggle-capping"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer" 
                                    {{ old('is_package_product', $product->is_package_product ?? '0') == '1' ? 'checked' : '' }}>
                                <label for="ui-toggle-capping"
                                    class="ml-2 text-sm font-bold text-gray-700 cursor-pointer select-none">
                                    Set as Package Product (Enable Capping Limit)?
                                </label>
                            </div>

                            {{-- Hidden Input Container --}}
                            <div id="capping-section" class="transition-all duration-300 {{ old('is_package_product', $product->is_package_product ?? '0') == '1' ? 'block' : 'hidden' }}">
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                        <i class="fas fa-chart-line"></i>
                                    </span>
                                    <input type="number" name="capping" id="capping-input"
                                        value="{{ old('capping', $product->capping) }}" placeholder="Enter limit amount"
                                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Maximum earning limit from this package.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
                        <a href="{{ route('admin.product-package') }}"
                            class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-8 py-2.5 rounded-lg bg-green-600 text-white font-medium hover:bg-green-700 transition-all shadow-md shadow-green-500/30 transform active:scale-95 flex items-center">
                            <i class="fas fa-check-circle mr-2"></i> Update Product
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ==========================================
            // 1. FILE UPLOAD LOGIC
            // ==========================================
            const fileInput = document.getElementById('product_image');
            const dropZone = document.getElementById('drop-zone');
            const previewArea = document.getElementById('preview-area');
            const previewImg = document.getElementById('preview-img');
            const fileStatus = document.getElementById('file-status');
            const removeBtn = document.getElementById('remove-btn');

            dropZone.addEventListener('click', () => fileInput.click());

            fileInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        fileStatus.textContent = "New Image Selected";
                        fileStatus.classList.add('text-green-600');
                        dropZone.classList.add('hidden');
                        previewArea.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });

            removeBtn.addEventListener('click', function() {
                fileInput.click(); // Open file browser again
            });

            ['dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    if (eventName === 'dragover') dropZone.classList.add('border-blue-500',
                        'bg-blue-50');
                    else dropZone.classList.remove('border-blue-500', 'bg-blue-50');
                });
            });

            dropZone.addEventListener('drop', (e) => {
                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    fileInput.dispatchEvent(new Event('change'));
                }
            });

            // ==========================================
            // 2. PROFIT -> RECOMMENDED PV LOGIC
            // ==========================================
            const profitInput = document.getElementById('profit');
            const recomendLabel = document.getElementById('recomend');

            const calculatePv = () => {
                if (profitInput.value && profitInput.value > 0) {
                    const recommended = (parseFloat(profitInput.value) * 30) / 100;
                    recomendLabel.textContent = `Recommended PV: ${Math.round(recommended)}`;
                } else {
                    recomendLabel.textContent = '';
                }
            };

            profitInput.addEventListener('input', calculatePv);
            calculatePv(); // Run on load

            // ==========================================
            // 3. CAPPING TOGGLE LOGIC (Fixed for Backend Sync)
            // ==========================================
            const uiToggleCapping = document.getElementById('ui-toggle-capping');
            const hiddenStatusInput = document.getElementById('is_package_product_hidden');
            const cappingSection = document.getElementById('capping-section');
            const cappingInput = document.getElementById('capping-input');

            // Force initial state consistency
            if(uiToggleCapping.checked) {
                hiddenStatusInput.value = '1';
                cappingSection.classList.remove('hidden');
            } else {
                hiddenStatusInput.value = '0';
                cappingSection.classList.add('hidden');
            }

            uiToggleCapping.addEventListener('change', function() {
                if (this.checked) {
                    cappingSection.classList.remove('hidden');
                    hiddenStatusInput.value = '1';
                } else {
                    cappingSection.classList.add('hidden');
                    cappingInput.value = '0'; // Clear/reset value when disabled
                    hiddenStatusInput.value = '0';
                }
            });
        });
    </script>
@endsection