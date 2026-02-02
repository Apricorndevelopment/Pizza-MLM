@extends('vendorlayouts.layout')
@section('title', 'My Products')
@section('container')

    <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">

        {{-- Alerts Section --}}
        @if (session('success'))
            <div id="alert-success"
                class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm flex justify-between items-center transition-opacity duration-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
                <button onclick="dismissAlert('alert-success')"
                    class="ml-auto pl-3 text-green-500 hover:text-green-700 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div id="alert-error"
                class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm flex justify-between items-center transition-opacity duration-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
                <button onclick="dismissAlert('alert-error')"
                    class="ml-auto pl-3 text-red-500 hover:text-red-700 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- Main Content --}}
        <div class="max-w-7xl mx-auto">
            <div class="sm:flex sm:items-center sm:justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">My Products</h1>
                    <p class="mt-2 text-sm text-gray-700">A list of all your products including their name, price, and
                        status.</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('vendor.products.create') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Add New Product
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Image</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product Name</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($products as $product)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            @if ($product->product_image)
                                                <img class="h-12 w-12 rounded-lg object-cover shadow-sm border border-gray-100"
                                                    src="{{ asset($product->product_image) }}" alt="">
                                            @else
                                                <div
                                                    class="h-12 w-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->product_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-semibold">₹{{ $product->price }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($product->status == 'approved')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full"></span> Approved
                                            </span>
                                        @elseif($product->status == 'rejected')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                <span class="w-1.5 h-1.5 mr-1.5 bg-red-500 rounded-full"></span> Rejected
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                <span class="w-1.5 h-1.5 mr-1.5 bg-yellow-500 rounded-full"></span> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('vendor.products.show', $product->id) }}"
                                            class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1.5 rounded-md hover:bg-blue-100 transition-colors">View</a>
                                        <a href="{{ route('vendor.products.edit', $product->id) }}"
                                            class="text-amber-600 hover:text-amber-900 bg-amber-50 px-3 py-1.5 rounded-md hover:bg-amber-100 transition-colors">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Container --}}
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function dismissAlert(alertId) {
            const element = document.getElementById(alertId);
            if (element) {
                element.style.opacity = '0';
                setTimeout(() => element.remove(), 500); // Remove after fade out
            }
        }
    </script>
@endsection
