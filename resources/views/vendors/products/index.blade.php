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
                                        <div class="text-sm text-gray-900 font-semibold">₹{{ $product->mrp }}</div>
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
                                        <button
                                            onclick="openStockModal('{{ $product->id }}', '{{ $product->manage_stock }}', '{{ $product->stock_quantity }}')"
                                            class="px-3 py-1 rounded-full text-xs font-bold border transition-colors
    {{ $product->is_in_stock ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-red-50 text-red-600 border-red-200' }}">
                                            @if (!$product->manage_stock)
                                                Edit Stock QYT
                                            @else
                                                {{ $product->stock_quantity }} Left
                                            @endif
                                            <i class="fas fa-pen ml-1"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="stock-modal" class="fixed inset-0 z-50 hidden" aria-modal="true">
                    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                        onclick="closeStockModal()"></div>

                    <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
                        <div
                            class="bg-white rounded-2xl shadow-2xl w-full max-w-sm border border-slate-100 overflow-hidden transform transition-all">

                            <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center">
                                <h3 class="text-white font-bold text-lg"><i class="fas fa-boxes mr-2"></i> Update Inventory
                                </h3>
                                <button onclick="closeStockModal()" class="text-white/80 hover:text-white"><i
                                        class="fas fa-times"></i></button>
                            </div>

                            <form id="stock-form" method="POST" action="">
                                @csrf
                                @method('PUT')

                                <div class="p-6 space-y-4">

                                    <div
                                        class="flex items-center justify-between bg-slate-50 p-3 rounded-xl border border-slate-200">
                                        <span class="text-sm font-semibold text-slate-700">Track Stock?</span>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="manage_stock" id="modal_manage_stock"
                                                value="1" class="sr-only peer" onchange="toggleQuantityInput()">
                                            <div
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600">
                                            </div>
                                        </label>
                                    </div>

                                    <div id="quantity-container"
                                        class="opacity-50 pointer-events-none transition-all duration-200">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Available
                                            Quantity</label>
                                        <div class="relative">
                                            <button type="button" onclick="adjustQty(-1)"
                                                class="absolute left-0 inset-y-0 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-l-lg border-r border-slate-300 font-bold">-</button>
                                            <input type="number" name="stock_quantity" id="modal_stock_quantity"
                                                class="w-full text-center py-2 border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 font-mono font-bold text-lg"
                                                value="0">
                                            <button type="button" onclick="adjustQty(1)"
                                                class="absolute right-0 inset-y-0 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-r-lg border-l border-slate-300 font-bold">+</button>
                                        </div>
                                    </div>

                                </div>

                                <div class="bg-slate-50 px-6 py-4 flex justify-end">
                                    <button type="submit"
                                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-indigo-700 transition-colors shadow-md shadow-indigo-200">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
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


        const stockModal = document.getElementById('stock-modal');
        const stockForm = document.getElementById('stock-form');
        const manageCheckbox = document.getElementById('modal_manage_stock');
        const qtyInput = document.getElementById('modal_stock_quantity');
        const qtyContainer = document.getElementById('quantity-container');

        function openStockModal(id, manageStock, quantity) {
            // 1. Set Form Action Dynamically
            // Ensure you have a named route like 'vendor.product.stock.update'
            stockForm.action = `/vendor/products/${id}/stock`;

            // 2. Set Values
            manageCheckbox.checked = (manageStock == 1);
            qtyInput.value = quantity;

            // 3. Update UI State
            toggleQuantityInput();

            // 4. Show Modal
            stockModal.classList.remove('hidden');
        }

        function closeStockModal() {
            stockModal.classList.add('hidden');
        }

        function toggleQuantityInput() {
            if (manageCheckbox.checked) {
                qtyContainer.classList.remove('opacity-50', 'pointer-events-none');
            } else {
                qtyContainer.classList.add('opacity-50', 'pointer-events-none');
            }
        }

        function adjustQty(change) {
            let current = parseInt(qtyInput.value) || 0;
            let newVal = current + change;
            if (newVal < 0) newVal = 0;
            qtyInput.value = newVal;
        }
    </script>
@endsection
