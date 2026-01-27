@extends('vendorlayouts.layout')
@section('title', 'Product Details')
@section('content')

    <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">

        {{-- Alerts Section (Consistent across pages) --}}
        @if (session('success'))
            <div id="alert-success"
                class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm flex justify-between items-center">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
                <button onclick="document.getElementById('alert-success').remove()"
                    class="text-green-500 hover:text-green-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <div class="max-w-6xl mx-auto">
            {{-- Breadcrumb / Back --}}
            <div class="mb-6 flex items-center justify-between">
                <a href="{{ route('vendor.products.index') }}"
                    class="group flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">
                    <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                    Back to Products
                </a>
                <div class="flex space-x-3">
                    <a href="{{ route('vendor.products.edit', $product->id) }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-pencil-alt mr-2 text-gray-400"></i> Edit
                    </a>

                    <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST"
                        class="d-inline-block"
                        onsubmit="return confirm('Are you sure you want to permanently delete this product?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-trash-alt mr-2"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="md:flex">
                    {{-- Left Column: Image --}}
                    <div
                        class="md:w-1/3 bg-gray-50 p-8 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-gray-200">
                        <div
                            class="relative w-full aspect-square rounded-xl overflow-hidden shadow-lg border border-gray-100 bg-white">
                            <img src="{{ asset($product->product_image) }}"
                                class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-500"
                                alt="{{ $product->product_name }}">

                            {{-- Status Overlay --}}
                            <div class="absolute top-4 left-4">
                                @if ($product->status == 'approved')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-500 text-white shadow-md">
                                        <i class="fas fa-check mr-1.5"></i> APPROVED
                                    </span>
                                @elseif($product->status == 'rejected')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-500 text-white shadow-md">
                                        <i class="fas fa-times mr-1.5"></i> REJECTED
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-400 text-yellow-900 shadow-md">
                                        <i class="fas fa-clock mr-1.5"></i> PENDING
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Details --}}
                    <div class="md:w-2/3 p-8">
                        <div class="mb-6">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->product_name }}</h1>
                            <div class="flex items-center space-x-4">
                                <span
                                    class="text-3xl font-bold text-green-600">₹{{ number_format($product->price, 2) }}</span>
                                <span
                                    class="text-lg text-gray-400">₹{{ number_format($product->mrp, 2) }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider">GST</span>
                                <span class="block text-lg font-semibold text-gray-900">{{ $product->gst }}%</span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider">DP</span>
                                <span class="block text-lg font-semibold text-gray-900">₹{{ $product->dp }}</span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider">PV</span>
                                <span class="block text-lg font-semibold text-gray-900">{{ $product->pv ?? '-' }}</span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                <span
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Coupons</span>
                                <span
                                    class="block text-lg font-semibold text-gray-900">{{ $product->max_coupon_usage ?? 0 }}</span>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3 border-b pb-2">Product
                                Description</h3>
                            <div
                                class="prose prose-sm max-w-none text-gray-600 bg-gray-50/50 p-4 rounded-lg border border-gray-100 leading-relaxed">
                                {{ $product->description ?? 'No detailed description available for this product.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
