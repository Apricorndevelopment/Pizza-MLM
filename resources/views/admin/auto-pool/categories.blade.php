@extends('layouts.layout')
@section('title', 'Manage Auto Pool Categories')

@section('container')
    <div class="container mx-auto">

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div class="flex items-center w-full md:w-auto">
                <div class="bg-indigo-50 p-3 rounded-full mr-4 text-indigo-600 shadow-sm border border-indigo-100">
                    <i class="fas fa-tags fa-lg"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Auto Pool Categories</h3>
                    <p class="text-slate-500 text-sm font-medium">Manage categories and their entry conditions</p>
                </div>
            </div>

            <button onclick="openModal('create')"
                class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition-all duration-300 flex items-center justify-center gap-2">
                <i class="fas fa-plus-circle"></i> Add Category
            </button>
        </div>

        {{-- Validation & Alerts --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 mb-6 rounded-xl shadow-sm animate-fade-in">
                <div class="flex items-center mb-2 font-bold"><i class="fas fa-exclamation-triangle mr-2"></i> Error:</div>
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 mb-6 rounded-xl shadow-sm flex justify-between items-center animate-fade-in">
                <span class="font-bold flex items-center gap-2"><i class="fas fa-check-circle text-lg"></i> {{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900"><i class="fas fa-times"></i></button>
            </div>
        @endif

        {{-- TABLE CARD --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-3 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h4 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                    <i class="fas fa-list text-indigo-500"></i> Category & Conditions
                </h4>
                <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full border border-indigo-200">
                    {{ $categories->count() }} Categories
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                            <th class="p-3 font-bold text-center w-16">S.no</th>
                            <th class="p-3 font-bold">Category Name</th>
                            <th class="p-3 font-bold">Required Package</th>
                            <th class="p-3 font-bold text-center">Req. PV</th>
                            <th class="p-3 font-bold text-center">Req. Directs</th>
                            <th class="p-3 font-bold text-center">Status</th>
                            <th class="p-3 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($categories as $index => $item)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="p-3 text-center">
                                    <span class="bg-slate-100 border border-slate-200 text-slate-600 font-bold px-2 py-1 rounded-lg text-xs">{{ $index + 1 }}</span>
                                </td>
                                <td class="p-3 font-bold text-slate-800 text-sm">
                                    {{ $item->category_name }}
                                </td>
                                <td class="p-3 text-sm font-medium text-slate-600">
                                    @if($item->package)
                                        <span class="text-indigo-600 font-bold"><i class="fas fa-box-open mr-1"></i> {{ $item->package->product_name }}</span>
                                    @else
                                        <span class="text-slate-400">None</span>
                                    @endif
                                </td>
                                <td class="p-3 text-center font-bold text-slate-700 text-sm">
                                    {{ $item->pv_required ?? '0' }} PV
                                </td>
                                <td class="p-3 text-center font-bold text-slate-700 text-sm">
                                    {{ $item->direct_count ?? '0' }}
                                </td>
                                <td class="p-3 text-center">
                                    @if($item->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200"><i class="fas fa-check-circle"></i> Active</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200"><i class="fas fa-times-circle"></i> Inactive</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right flex justify-end gap-2">
                                    <button onclick='openModal("edit", @json($item))' class="w-8 h-8 flex items-center justify-center text-indigo-500 bg-indigo-50 hover:bg-indigo-600 hover:text-white rounded-lg transition-colors shadow-sm"><i class="fas fa-edit"></i></button>
                                    <form action="{{ route('admin.autopool_categories.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this category?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center text-red-500 bg-red-50 hover:bg-red-600 hover:text-white rounded-lg transition-colors shadow-sm"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-tags text-4xl mb-3 opacity-30"></i>
                                        <p class="font-medium text-lg">No categories found.</p>
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
    <div id="categoryModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-2xl w-full relative z-10 border border-slate-100">
                
                <div class="bg-white px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2" id="modalTitle"></h3>
                    <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors bg-slate-50 hover:bg-slate-100 w-8 h-8 rounded-full flex items-center justify-center outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="categoryForm" method="POST" class="p-6">
                    @csrf
                    <div id="methodField"></div>

                    <div class="space-y-4">
                        
                        {{-- Row 1: Name & Status --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Category Name <span class="text-red-500">*</span></label>
                                <input type="text" name="category_name" id="category_name" required
                                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all font-medium text-slate-700"
                                    placeholder="e.g. Star Pool">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Status <span class="text-red-500">*</span></label>
                                <select name="is_active" id="is_active" required
                                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all font-medium text-slate-700">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>

                        {{-- Required Entry Conditions --}}
                        <div class="bg-indigo-50/50 p-3 rounded-xl border border-indigo-100">
                            <h4 class="text-sm font-bold text-indigo-800 mb-4 flex items-center gap-2"><i class="fas fa-lock"></i> Entry Conditions</h4>
                            
                            <div class="space-y-4">
                                {{-- Package Dropdown (Empty by default, filled by JS) --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Required Package (Capping Product)</label>
                                    <select name="product_package_id" id="product_package_id"
                                        class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all font-medium text-slate-700">
                                        {{-- Options will be injected here by Javascript --}}
                                    </select>
                                    <p class="text-[10px] text-slate-500 mt-1">Only unused packages are available in this list.</p>
                                </div>

                                {{-- PV & Directs --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Required Self PV</label>
                                        <input type="number" name="pv_required" id="pv_required" min="0" value="0"
                                            class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all font-medium text-slate-700">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Direct Referrals</label>
                                        <input type="number" name="direct_count" id="direct_count" min="0" value="0"
                                            class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all font-medium text-slate-700">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Each Direct Referral PV</label>
                                        <input type="number" name="each_direct_pv" id="each_direct_pv" min="0" value="0"
                                            class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all font-medium text-slate-700">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-700 hover:bg-slate-50 font-bold transition-colors">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-bold shadow-md transition-colors flex items-center gap-2">
                            <i class="fas fa-save"></i> <span id="btnText">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        const allPackages = @json($allPackages ?? []);
        const usedPackages = @json($usedPackageIds ?? []);

        function openModal(type, data = null) {
            const modal = document.getElementById('categoryModal');
            const form = document.getElementById('categoryForm');
            const title = document.getElementById('modalTitle');
            const btnText = document.getElementById('btnText');
            const methodField = document.getElementById('methodField');
            const packageSelect = document.getElementById('product_package_id');

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Reset Select Dropdown
            packageSelect.innerHTML = '<option value="">No Specific Package Required</option>';

            if (type === 'create') {
                title.innerHTML = '<div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mr-1"><i class="fas fa-plus"></i></div> Add New Category';
                btnText.innerText = 'Save Category';
                form.action = "{{ route('admin.autopool_categories.store') }}";
                methodField.innerHTML = '';
                form.reset();
                document.getElementById('is_active').value = '1';
                document.getElementById('pv_required').value = '0';
                document.getElementById('direct_count').value = '0';
                document.getElementById('each_direct_pv').value = '0';

                // ONLY show unused packages
                allPackages.forEach(pkg => {
                    if (!usedPackages.includes(pkg.id)) {
                        packageSelect.innerHTML += `<option value="${pkg.id}">${pkg.product_name} (₹${pkg.pv} PV)</option>`;
                    }
                });

            } else {
                title.innerHTML = '<div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mr-1"><i class="fas fa-edit"></i></div> Edit Category';
                btnText.innerText = 'Update Category';
                form.action = `/admin/auto-pool-categories/update/${data.id}`;
                methodField.innerHTML = '@method("PUT")';

                document.getElementById('category_name').value = data.category_name;
                document.getElementById('is_active').value = data.is_active;
                
                // Show unused packages + the package currently assigned to this category
                allPackages.forEach(pkg => {
                    if (!usedPackages.includes(pkg.id) || (data.product_package_id && pkg.id === data.product_package_id)) {
                        packageSelect.innerHTML += `<option value="${pkg.id}">${pkg.product_name} (₹${pkg.pv} PV)</option>`;
                    }
                });

                document.getElementById('product_package_id').value = data.product_package_id || '';
                document.getElementById('pv_required').value = data.pv_required;
                document.getElementById('direct_count').value = data.direct_count;
                document.getElementById('each_direct_pv').value = data.each_direct_pv;
            }
        }

        function closeModal() {
            document.getElementById('categoryModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") closeModal();
        });
    </script>

    <style>
        .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection