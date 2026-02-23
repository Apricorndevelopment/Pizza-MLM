@extends('layouts.layout')
@section('title', 'Package Management')
@section('container')

    <div class="min-h-screen bg-gray-50 px-2 lg:px-3">
        <div class="max-w-8xl mx-auto">

            @if(session('success'))
                <div class="mb-3 alert rounded-md bg-green-50 p-4 border border-green-200 transition-opacity duration-500 ease-in-out">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button type="button" onclick="this.closest('.alert').remove()"
                                    class="inline-flex rounded-md bg-green-50 p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50">
                                    <span class="sr-only">Dismiss</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mb-3">
                <h1 class="text-3xl font-bold text-gray-900">Product Package Management</h1>
                <p class="mt-2 text-sm text-gray-600">Manage your admin products.</p>
            </div>

            <div class="mb-6">

                <div
                    class="bg-white overflow-hidden shadow-sm rounded-xl border border-cyan-100 hover:shadow-md transition-shadow duration-200">
                    <div class="p-3">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-cyan-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-semibold text-gray-900">Admin Products</h2>
                                <p class="text-sm text-gray-500">Products to Purchase for the Users</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.package2.create') }}"
                                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 w-full sm:w-auto transition-colors">
                                <svg class="mr-2 -ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Admin Product
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">

                <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
                    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Product Package List</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Product</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        MRP</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        DP</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        PV</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Profit</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($product_package as $package)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-20 w-20 flex-shrink-0">
                                                    @if ($package->product_image)
                                                        <div class="h-20 w-20 overflow-hidden relative">
                                                            <img class="h-full w-full rounded-lg object-cover border border-gray-200"
                                                                src="{{ asset($package->product_image) }}"
                                                                alt="{{ $package->product_name }}">
                                                            <div class="h-3 w-3 absolute right-1 top-1 rounded-full">
                                                                @if ($package->isVeg === 'veg')
                                                                    <svg viewBox="0 0 100 100" width="100%" height="100%"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <rect x="5" y="5" width="90" height="90"
                                                                            rx="12" ry="12" fill="none"
                                                                            stroke="#16a34a" stroke-width="8" />
                                                                        <circle cx="50" cy="50" r="18"
                                                                            fill="#16a34a" />
                                                                    </svg>
                                                                @else
                                                                    <svg viewBox="0 0 100 100" width="100%" height="100%"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <rect x="5" y="5" width="90" height="90"
                                                                            rx="12" ry="12" fill="none"
                                                                            stroke="#dc2626" stroke-width="8" />
                                                                        <polygon points="50,30 70,70 30,70"
                                                                            fill="#dc2626" />
                                                                    </svg>
                                                                @endif
                                                            </div>

                                                        </div>
                                                    @else
                                                        <div
                                                            class="h-20 w-20 rounded-lg bg-cyan-100 flex items-center justify-center text-cyan-600 font-bold">
                                                            {{ substr($package->product_name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $package->product_name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-500">₹{{ $package->mrp }}
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-500">₹{{ $package->dp }}
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-500">{{ $package->pv }}
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $package->profit }}</td>
                                        <td class="px-3 py-3 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('admin.package2.edit', $package->id) }}"
                                                    class="text-cyan-600 hover:text-cyan-900 bg-cyan-50 hover:bg-cyan-100 px-3 py-1 rounded-md transition-colors">Edit</a>
                                                <form action="{{ route('admin.package2.destroy', $package->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md transition-colors"
                                                        onclick="return confirm('Are you sure you want to delete this package?')">Delete</button>
                                                </form>
                                                <button
                                                    onclick="openStockModal('{{ $package->id }}', '{{ $package->manage_stock }}', '{{ $package->stock_quantity }}')"
                                                    class="px-3 py-1 rounded-full text-xs font-bold border transition-colors
        {{ $package->is_in_stock ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-red-50 text-red-600 border-red-200' }}">
                                                    @if (!$package->manage_stock)
                                                        Manage Stock
                                                    @else
                                                        {{ $package->stock_quantity }} Left
                                                    @endif
                                                    <i class="fas fa-pen ml-1"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            class="px-6 py-10 text-center text-sm text-gray-500 bg-gray-50">
                                            <div class="flex flex-col items-center">
                                                <svg class="h-10 w-10 text-gray-400 mb-2" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                </svg>
                                                <p>No Product Packages found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- ADDED PAGINATION LINKS HERE --}}
                    @if ($product_package->hasPages())
                        <div class="px-4 py-3 border-t border-gray-200 bg-white sm:px-6">
                            {{ $product_package->links('pagination::bootstrap-5') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    
    <div id="stock-modal" class="fixed inset-0 z-50 hidden" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeStockModal()"></div>

        <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
            <div
                class="bg-white rounded-2xl shadow-2xl w-full max-w-sm border border-slate-100 overflow-hidden transform transition-all">

                <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-white font-bold text-lg"><i class="fas fa-boxes mr-2"></i> Update Inventory</h3>
                    <button onclick="closeStockModal()" class="text-white/80 hover:text-white"><i
                            class="fas fa-times"></i></button>
                </div>

                <form id="stock-form" method="POST" action="">
                    @csrf
                    @method('PUT')

                    <div class="p-6 space-y-4">

                        <div class="flex items-center justify-between bg-slate-50 p-3 rounded-xl border border-slate-200">
                            <span class="text-sm font-semibold text-slate-700">Track Stock?</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="manage_stock" id="modal_manage_stock" value="1"
                                    class="sr-only peer" onchange="toggleQuantityInput()">
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600">
                                </div>
                            </label>
                        </div>

                        <div id="quantity-container" class="opacity-50 pointer-events-none transition-all duration-200">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Total Available
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
                            <p class="text-xs text-gray-400 mt-2 text-center">Enter the final total quantity (e.g. Current
                                + New Stock)</p>
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

    <script>
        const stockModal = document.getElementById('stock-modal');
        const stockForm = document.getElementById('stock-form');
        const manageCheckbox = document.getElementById('modal_manage_stock');
        const qtyInput = document.getElementById('modal_stock_quantity');
        const qtyContainer = document.getElementById('quantity-container');

        function openStockModal(id, manageStock, quantity) {
            stockForm.action = `/admin/product-package/${id}/stock`;

            manageCheckbox.checked = (manageStock == 1);
            qtyInput.value = quantity;

            toggleQuantityInput();
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