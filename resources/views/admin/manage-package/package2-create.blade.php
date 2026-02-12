@extends('layouts.layout')
@section('title', 'Create Product')
@section('container')

    <div class="min-h-screen pb-4 bg-gray-50 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">

            {{-- Header --}}
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Product Management</h1>
                    <p class="text-sm text-gray-500 mt-1">Add a new package to your inventory</p>
                </div>
                <a href="{{ route('admin.package') }}"
                    class="hidden sm:flex items-center text-gray-600 hover:text-blue-600 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Back to List
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

            {{-- Main Card --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">

                {{-- Card Header --}}
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/10 p-2 rounded-lg backdrop-blur-sm">
                            <i class="fas fa-box-open text-white text-lg"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-white tracking-wide">New Product Details</h2>
                    </div>
                </div>

                <form action="{{ route('admin.package2.store') }}" method="POST" enctype="multipart/form-data"
                    class="p-6 md:p-8">
                    @csrf

                    {{-- HIDDEN FIELD: Automatically set is_package_product to 1 --}}
                    <input type="hidden" name="is_package_product" value="1">

                    {{-- Section 1: Basic Info & Image --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">

                        {{-- Left Column: Inputs --}}
                        <div class="lg:col-span-7 space-y-6">
                            <div>
                                <label for="product_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Product Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="product_name" name="product_name"
                                    value="{{ old('product_name') }}"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm placeholder-gray-400"
                                    placeholder="e.g. Premium Starter Pack" required>
                            </div>

                            <div>
                                <label for="maxCoupon" class="block text-sm font-semibold text-gray-700 mb-2">Max Coupons
                                    Allowed <span class="text-danger">*</span></label>
                                <div>
                                    <input type="number"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm"
                                        required id="maxCoupon" name="max_coupon_usage" required
                                        value="{{ old('max_coupon_usage', 1) }}" min="1">
                                    @error('max_coupon_usage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">No. of coupons applicable per unit (1 Coupon = ₹10 Off)</small>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                <textarea name="description" rows="4"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm placeholder-gray-400"
                                    placeholder="Describe the product details here...">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        {{-- Right Column: Image Upload --}}
                        <div class="lg:col-span-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Product Image</label>
                            <div class="bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 p-6 flex flex-col items-center justify-center text-center hover:bg-blue-50 hover:border-blue-400 transition-all cursor-pointer group h-full min-h-[250px]"
                                id="drop-zone">
                                <input type="file" id="product_image" name="product_image" class="hidden"
                                    accept="image/*">

                                <div
                                    class="bg-white p-4 rounded-full shadow-sm mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-blue-500"></i>
                                </div>
                                <h4 class="text-gray-900 font-medium mb-1">Click to upload</h4>
                                <p class="text-sm text-gray-500 mb-4">or drag and drop here</p>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">PNG, JPG up to 5MB</p>
                            </div>

                            {{-- Preview Container --}}
                            <div id="preview-area"
                                class="hidden mt-4 bg-white rounded-xl border border-gray-200 shadow-sm p-3 relative group">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 shrink-0 border border-gray-100">
                                        <img id="preview-img" src="#" alt="Preview"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p id="file-name" class="text-sm font-medium text-gray-900 truncate"></p>
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">
                                            Ready to upload
                                        </span>
                                    </div>
                                    <button type="button" id="remove-btn"
                                        class="p-2 text-gray-400 hover:text-red-500 transition-colors rounded-full hover:bg-red-50">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>

                            @error('product_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2 mb-8">
                        <label for="isVeg" class="block text-sm font-medium text-gray-700">
                            Is the Product Veg or Non Veg? <span class="text-red-500">*</span>
                        </label>

                        <select name="isVeg" id="isVeg"
                            class="form-select w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm">
                            <option value="veg">Veg</option>
                            <option value="non-veg">Non Veg</option>
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
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">MRP
                                    <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₹</span>
                                    <input type="number" step="0.01" name="mrp" value="{{ old('mrp') }}"
                                        class="w-full pl-8 pr-4 py-2.5 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all shadow-sm font-semibold text-gray-700"
                                        required>
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">GST %
                                    <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="gst" value="{{ old('gst') }}"
                                        class="w-full pl-4 pr-8 py-2.5 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all shadow-sm font-semibold text-gray-700"
                                        required>
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
                                    <input type="number" step="0.01" name="dp" value="{{ old('dp') }}"
                                        class="w-full pl-8 pr-4 py-2.5 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all shadow-sm font-semibold text-gray-700"
                                        required>
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Profit
                                    <span class="text-red-500">*</span>
                                </label>
                                <label class="block text-xs mb-1 text-red-500" id="recomend">

                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₹</span>
                                    <input type="number" step="0.1" name="profit" id="profit"
                                        value="{{ old('profit') }}"
                                        class="w-full pl-8 pr-4 py-2.5 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all shadow-sm font-semibold text-green-600"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- New Section for Capping & PV --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

                        {{-- REFACTORED PV FIELD: Value only --}}
                        <div>
                            <label for="pv" class="block text-sm font-semibold text-gray-700 mb-2">
                                PV (Point Value) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="pv" id="pv" value="{{ old('pv') }}"
                                    placeholder="Enter total Points Value (e.g. 50)"
                                    class="w-full pl-4 pr-4 py-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm"
                                    required>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Enter the direct point value for this product.</p>
                        </div>

                        {{-- CAPPING FIELD WITH TOGGLE CHECKBOX --}}
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            {{-- 1. Checkbox to Enable --}}
                            <div class="flex items-center mb-3">
                                {{-- FIX: Removed name="is_package_product" to prevent conflict --}}
                                <input type="checkbox" id="toggle-capping"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                                <label for="toggle-capping"
                                    class="ml-2 text-sm font-bold text-gray-700 cursor-pointer select-none">
                                    Set Capping Limit?
                                </label>
                            </div>

                            {{-- 2. Hidden Input Container --}}
                            <div id="capping-section" class="hidden transition-all duration-300">
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                        <i class="fas fa-chart-line"></i>
                                    </span>
                                    <input type="number" name="capping" id="capping-input"
                                        value="{{ old('capping') }}" placeholder="Enter limit amount"
                                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Maximum earning limit from this package.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
                        <a href="{{ route('admin.package') }}"
                            class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-8 py-2.5 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md shadow-blue-500/30 transform active:scale-95 flex items-center">
                            <i class="fas fa-save mr-2"></i> Create Product
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // File Upload Logic
            const fileInput = document.getElementById('product_image');
            const dropZone = document.getElementById('drop-zone');
            const previewArea = document.getElementById('preview-area');
            const previewImg = document.getElementById('preview-img');
            const fileNameDisplay = document.getElementById('file-name');
            const removeBtn = document.getElementById('remove-btn');
            const profit = document.getElementById('profit');
            const recomend = document.getElementById('recomend');



            profit.addEventListener('input', function() {
                if (this.value) {
                    const recommended = (this.value * 30) / 100;
                    recomend.textContent = `Recommended PV: ${Math.round(recommended)}`;
                } else {
                    recomend.textContent = '';
                }

            });

            dropZone.addEventListener('click', () => fileInput.click());

            fileInput.addEventListener('change', function(e) {
                handleFiles(this.files);
            });

            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200');
            });

            dropZone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200');
                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    handleFiles(e.dataTransfer.files);
                }
            });

            function handleFiles(files) {
                if (files && files[0]) {
                    const file = files[0];
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        fileNameDisplay.textContent = file.name;
                        dropZone.classList.add('hidden');
                        previewArea.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            }

            removeBtn.addEventListener('click', function() {
                fileInput.value = '';
                dropZone.classList.remove('hidden');
                previewArea.classList.add('hidden');
            });

            // ==========================================
            // CAPPING TOGGLE LOGIC
            // ==========================================
            const toggleCapping = document.getElementById('toggle-capping');
            const cappingSection = document.getElementById('capping-section');
            const cappingInput = document.getElementById('capping-input');

            // Initial check in case of validation error redirect (old input)
            if (cappingInput.value && cappingInput.value !== '') {
                toggleCapping.checked = true;
                cappingSection.classList.remove('hidden');
            }

            toggleCapping.addEventListener('change', function() {
                if (this.checked) {
                    cappingSection.classList.remove('hidden');
                } else {
                    cappingSection.classList.add('hidden');
                    cappingInput.value = ''; // Clear value when disabled
                }
            });
        });
    </script>
@endsection
