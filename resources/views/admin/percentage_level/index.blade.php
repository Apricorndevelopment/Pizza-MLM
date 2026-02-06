@extends('layouts.layout')
@section('title', 'Manage Level Percentages')

@section('container')
    <div class="container mx-auto px-4 py-8">

        {{-- Page Header --}}
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <div class="bg-indigo-50 p-3 rounded-full mr-4 text-indigo-600">
                    <i class="fas fa-layer-group fa-lg"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-800">Level Income Settings</h3>
                    <p class="text-slate-500 text-sm">Manage commission percentages</p>
                </div>
            </div>

            {{-- Add New Button (Triggers Generic Modal) --}}
            <button onclick="openModal('create')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-plus"></i> Add New Level
            </button>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div
                class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-xl"></i>
                    <div>
                        <p class="font-bold">Success</p>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900"><i
                        class="fas fa-times"></i></button>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                <p class="font-bold">Please fix the following errors:</p>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- TABLE --}}
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h4 class="font-bold text-slate-700">All Configured Levels</h4>
                <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">
                    {{ $levels->count() }} Records
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                            <th class="px-3 py-3 font-semibold w-20">Sr No.</th>
                            <th class="px-3 py-3 font-semibold text-center">Level No.</th>
                            <th class="px-3 py-3 font-semibold">Percentage</th>
                            <th class="px-3 py-3 font-semibold">Created Date</th>
                            <th class="px-3 py-3 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($levels as $item)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-3 py-3 text-slate-500 font-mono text-sm">{{ $loop->iteration }}</td>
                                <td class="px-3 py-3 font-bold text-center text-slate-700">{{ $item->level }}</td>
                                <td class="px-3 py-3">

                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-bold bg-green-100 text-green-800">

                                        {{ $item->percentage }}%

                                    </span>

                                </td>
                                <td class="px-3 py-3 text-slate-500 text-sm">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y') }}
                                </td>
                                <td class="px-3 py-3 text-right flex justify-end gap-2">

                                    {{-- Edit Button (Triggers JS) --}}
                                    <button onclick='openModal("edit", @json($item))'
                                        class="text-indigo-500 hover:text-indigo-700 p-2 rounded-full hover:bg-indigo-50 transition-colors"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    {{-- Delete Form --}}
                                    <form action="{{ route('admin.percentage.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this level?');"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-400 hover:text-red-600 p-2 rounded-full hover:bg-red-50 transition-colors"
                                            title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-slate-100 p-4 rounded-full mb-3">
                                            <i class="fas fa-layer-group text-3xl text-slate-300"></i>
                                        </div>
                                        <p class="text-lg font-medium text-slate-600">No Levels Found</p>
                                        <button onclick="openModal('create')"
                                            class="text-indigo-600 hover:underline text-sm mt-2">Create your first
                                            level</button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- UNIFIED MODAL (CREATE & EDIT) --}}
    <div id="levelModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">

        {{-- Flex Container for Centering --}}
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

            {{-- Background Overlay --}}
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal()">
            </div>

            {{-- Vertical Centering Trick --}}
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Modal Panel --}}
            <div
                class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full relative z-10">

                <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2" id="modalTitle">
                        {{-- JS will fill this --}}
                    </h3>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form id="levelForm" method="POST" class="p-6">
                    @csrf
                    <div id="methodField"></div> {{-- For PUT method --}}

                    <div class="space-y-5">
                        {{-- Level Input --}}
                        <div>
                            <label for="level" class="block text-sm font-medium text-gray-700 mb-1">Level No.</label>
                            <input type="number" name="level" id="level"
                                class="w-full pl-4 pr-1 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                placeholder="Enter Level No." required>
                        </div>

                        {{-- Percentage Input --}}
                        <div>
                            <label for="percentage" class="block text-sm font-medium text-gray-700 mb-1">Percentage
                                (%)</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="percentage" id="percentage"
                                    class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                    placeholder="0.00" required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-bold">%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium shadow-md transition-colors flex items-center gap-2">
                            <i class="fas fa-save"></i> <span id="btnText">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT LOGIC --}}
    <script>
        function openModal(type, data = null) {
            const modal = document.getElementById('levelModal');
            const form = document.getElementById('levelForm');
            const title = document.getElementById('modalTitle');
            const btnText = document.getElementById('btnText');
            const methodField = document.getElementById('methodField');

            modal.classList.remove('hidden');

            if (type === 'create') {
                // Setup for Create
                title.innerHTML = '<i class="fas fa-plus-circle"></i> Add New Level';
                btnText.innerText = 'Save Level';
                form.action = "{{ route('admin.percentage.store') }}";
                methodField.innerHTML = ''; // Clear PUT method
                form.reset();
            } else {
                // Setup for Edit
                title.innerHTML = '<i class="fas fa-edit"></i> Edit Level';
                btnText.innerText = 'Update Level';

                // Assuming your route is like /admin/percentage-level/update/{id}
                form.action = `/admin/percentage-level/update/${data.id}`;
                methodField.innerHTML = '@method('PUT')'; // Inject PUT method

                // Fill Data
                document.getElementById('level').value = data.level;
                document.getElementById('percentage').value = data.percentage;
            }
        }

        function closeModal() {
            document.getElementById('levelModal').classList.add('hidden');
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeModal();
            }
        });
    </script>
@endsection
