@extends('layouts.layout')
@section('title', 'Dashboard')
@section('container')

<div class="min-h-screen bg-gray-50">
    <div>
        <!-- Header -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <i class="bi bi-box-seam text-blue-600 mr-3"></i>
                        Vendor Products Management
                    </h1>
                    <p class="text-gray-600 mt-1">Manage all products from vendors</p>
                </div>
                {{-- <a href="{{ route('admin.products.create') }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg
                          hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                          transition duration-200 shadow-sm">
                    <i class="bi bi-plus-circle mr-2"></i>
                    Add Product
                </a> --}}
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div id="alert-success" class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm flex justify-between items-center transition-opacity duration-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-check-circle-fill text-green-500 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
                <button onclick="dismissAlert('alert-success')" class="ml-auto pl-3 text-green-500 hover:text-green-700 focus:outline-none">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div id="alert-error" class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm flex justify-between items-center transition-opacity duration-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-exclamation-circle-fill text-red-500 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
                <button onclick="dismissAlert('alert-error')" class="ml-auto pl-3 text-red-500 hover:text-red-700 focus:outline-none">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        <!-- Products Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Image
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Name & Details
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Vendor
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Price / PV
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Product Cost/ Profit
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($products as $product)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <!-- Image -->
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if ($product->product_image)
                                            <img src="{{ asset($product->product_image) }}" 
                                                 alt="{{ $product->product_name }}"
                                                 class="h-20 w-20 rounded-lg object-cover border border-gray-200">
                                        @else
                                            <div class="h-20 w-20 rounded-lg bg-gray-100 border border-gray-200 
                                                       flex items-center justify-center">
                                                <i class="bi bi-image text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Name & Details -->
                                <td class="px-2 py-3">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $product->product_name }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            MRP: ₹{{ number_format($product->mrp, 2) }}
                                            @if($product->gst)
                                                • GST: {{ $product->gst }}%
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Vendor -->
                                <td class="px-2 py-3">
                                    <div class="text-sm text-gray-900">
                                        @if ($product->vendor)
                                            <div class="flex items-center">
                                                <i class="bi bi-shop text-blue-500 mr-2"></i>
                                                <span>{{ $product->vendor->vendor_name }}</span>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                Company: {{ $product->vendor->company_name }}
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="bi bi-building mr-1"></i> Admin
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Price / PV -->
                                <td class="px-2 py-3">
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900">
                                            ₹{{ number_format($product->dp, 2) }}
                                        </div>
                                        <div class="text-xs text-purple-600 font-medium mt-1">
                                            <i class="bi bi-star-fill mr-1"></i>
                                            PV: {{ $product->pv ?? 'Not Set' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-3">
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900">
                                            Cost: ₹{{ number_format($product->product_cost, 2) }}
                                        </div>
                                        <div class="text-xs text-green-600 font-medium mt-1">
                                            <i class="bi bi-coin mr-1"></i>
                                            Profit: ₹{{ $product->profit ? number_format($product->profit, 2) : 'Not Set' }}
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Status -->
                                <td class="px-2 py-3 whitespace-nowrap">
                                    @if ($product->status == 'pending')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="bi bi-clock-history mr-1.5"></i> Pending
                                        </span>
                                    @elseif($product->status == 'approved')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="bi bi-check-circle mr-1.5"></i> Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="bi bi-x-circle mr-1.5"></i> Rejected
                                        </span>
                                    @endif
                                </td>
                                
                                <!-- Actions -->
                                <td class="px-2 py-3 whitespace-nowrap">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 border border-blue-300 text-sm font-medium rounded-lg 
                                              text-blue-700 bg-blue-50 hover:bg-blue-100 hover:border-blue-400 
                                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1
                                              transition duration-200">
                                        <i class="bi bi-pencil-square mr-2"></i>
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-gray-400">
                                        <i class="bi bi-box-seam text-4xl mb-3"></i>
                                        <p class="text-lg font-medium text-gray-500">No products found</p>
                                        <p class="text-sm text-gray-400 mt-1">Start by adding your first product</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if ($products->hasPages())
            <div class="mt-6">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<script>
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