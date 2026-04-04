@extends('layouts.layout')
@section('title', 'Manage Auto Pools')

@section('container')
    <div class="container mx-auto">

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div class="flex items-center w-full md:w-auto">
                <div class="bg-indigo-50 p-3 rounded-full mr-4 text-indigo-600">
                    <i class="fas fa-network-wired fa-lg"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-800">Auto Pool Configuration</h3>
                    <p class="text-slate-500 text-sm">Manage pool levels, ranks, PV requirements, and incomes</p>
                </div>
            </div>

            {{-- Add New Button --}}
            <button onclick="openModal('create')"
                class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md transition-all duration-300 flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Add New Pool
            </button>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success Message --}}
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center">
                <span class="font-medium flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </span>
                <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- TABLE CARD --}}
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
            <div class="p-3 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h4 class="font-bold text-slate-700">All Auto Pools</h4>
                <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">
                    {{ $pools->count() }} Levels
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                            <th class="px-4 py-3.5 font-semibold">Category Name</th>
                            <th class="px-4 py-3.5 font-semibold text-center w-20">Level</th>
                            <th class="px-4 py-3.5 font-semibold">Rank Name</th>
                            <th class="px-4 py-3.5 font-semibold text-center">Required PV</th>
                            <th class="px-4 py-3.5 font-semibold text-right">Income</th>
                            <th class="px-4 py-3.5 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pools as $item)
                            <tr class="hover:bg-slate-50 transition-colors group">

                                {{-- Category Name --}}
                                <td class="px-4 py-3.5 font-bold text-indigo-700">
                                    {{ $item->category ? $item->category->category_name : 'N/A' }}
                                </td>
                                {{-- Level --}}
                                <td class="px-4 py-3.5 text-center">
                                    <span class="bg-slate-100 text-slate-600 font-bold px-2.5 py-1 rounded-md text-xs">
                                        {{ $item->pool_level }}
                                    </span>
                                </td>

                                {{-- Rank Name --}}
                                <td class="px-4 py-3.5 font-bold text-pink-500">
                                    {{ $item->rank_name }}
                                </td>

                                {{-- Required PV --}}
                                <td class="px-4 py-3.5 text-center text-slate-600 font-medium">
                                    {{ number_format($item->required_pv) }} PV
                                </td>

                                {{-- Income --}}
                                <td class="px-4 py-3.5 text-right text-emerald-600 font-bold">
                                    ₹{{ number_format($item->income, 2) }}
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 py-3.5 text-right flex justify-end gap-2">
                                    <button onclick='openModal("edit", @json($item))'
                                        class="text-indigo-500 hover:text-indigo-700 p-2 rounded-full hover:bg-indigo-50 transition-colors" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('admin.auto-pools.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this pool level?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 p-2 rounded-full hover:bg-red-50 transition-colors" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-8 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-network-wired text-3xl mb-2 text-slate-300"></i>
                                        <p>No auto pools configured yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="poolModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg w-full relative z-10">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-4 py-3.5 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2" id="modalTitle"></h3>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                {{-- Form --}}
                <form id="poolForm" method="POST" class="p-3">
                    @csrf
                    <div id="methodField"></div>

                    <div class="space-y-4">
                        {{-- Row 1: Level & Rank Name --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Pool Category</label>
                                <select name="category_id" id="category_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pool Level</label>
                                <input type="number" name="pool_level" id="pool_level" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                    placeholder="e.g. 1">
                            </div>
                            
                        </div>

                        {{-- Row 2: PV & Income --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rank Name</label>
                                <input type="text" name="rank_name" id="rank_name" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                    placeholder="e.g. Starter Pool">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Required PV</label>
                                <input type="number" name="required_pv" id="required_pv" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                    placeholder="e.g. 1000">
                            </div>
                           
                        </div>

                        {{-- Row 3: Condition --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                              <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Income Amount</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 font-bold">₹</span>
                                    </div>
                                    <input type="number" name="income" id="income" step="0.01" required
                                        class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                        placeholder="e.g. 500">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium shadow-md transition-colors flex items-center gap-2">
                            <i class="fas fa-save"></i> <span id="btnText">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        function openModal(type, data = null) {
            const modal = document.getElementById('poolModal');
            const form = document.getElementById('poolForm');
            const title = document.getElementById('modalTitle');
            const btnText = document.getElementById('btnText');
            const methodField = document.getElementById('methodField');

            modal.classList.remove('hidden');

            if (type === 'create') {
                title.innerHTML = '<i class="fas fa-plus-circle"></i> Add New Auto Pool';
                btnText.innerText = 'Save';
                form.action = "{{ route('admin.auto-pools.store') }}";
                methodField.innerHTML = '';
                form.reset();
            } else {
                title.innerHTML = '<i class="fas fa-edit"></i> Edit Auto Pool';
                btnText.innerText = 'Update';
                form.action = `/admin/auto-pools/update/${data.id}`;
                methodField.innerHTML = '@method("PUT")';

                // Fill Data
                document.getElementById('pool_level').value = data.pool_level;
                document.getElementById('rank_name').value = data.rank_name;
                document.getElementById('required_pv').value = data.required_pv;
                document.getElementById('income').value = data.income;
                document.getElementById('category_id').value = data.category_id;
            }
        }

        function closeModal() {
            document.getElementById('poolModal').classList.add('hidden');
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") closeModal();
        });
    </script>
@endsection