@extends('layouts.layout')
@section('title', 'Manage Home Statistics')

@section('container')
    <div class="container mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <div class="bg-indigo-50 p-3 rounded-full mr-4 text-indigo-600">
                    <i class="fas fa-chart-line fa-lg"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-800">Home Statistics</h3>
                    <p class="text-slate-500 text-sm">Manage counters displayed on home page</p>
                </div>
            </div>
            <button onclick="openModal('create')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-plus"></i> Add New Stat
            </button>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                <p><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</p>
            </div>
        @endif

        {{-- TABLE --}}
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h4 class="font-bold text-slate-700">All Statistics</h4>
                <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">{{ $stats->count() }} Records</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                            <th class="px-6 py-3 font-semibold">Value</th>
                            <th class="px-6 py-3 font-semibold">Title (Label)</th>
                            <th class="px-6 py-3 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($stats as $stat)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-bold text-indigo-600 text-lg">{{ $stat->value }}</td>
                                <td class="px-6 py-4 font-medium text-slate-700">{{ $stat->title }}</td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2">
                                    <button onclick='openModal("edit", @json($stat))' class="text-indigo-500 hover:text-indigo-700 p-2 rounded-full hover:bg-indigo-50" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.stats.destroy', $stat->id) }}" method="POST" onsubmit="return confirm('Delete this stat?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 p-2 rounded-full hover:bg-red-50" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400">No Statistics Added Yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="statModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal()"></div>
            <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full relative z-10">
                <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white" id="modalTitle"></h3>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200 focus:outline-none"><i class="fas fa-times text-xl"></i></button>
                </div>
                
                <form id="statForm" method="POST" class="p-6 space-y-5">
                    @csrf <div id="methodField"></div>

                    {{-- Value --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Value (e.g. 10K+)</label>
                        <input type="text" name="value" id="value" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                    </div>

                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title (e.g. Active Members)</label>
                        <input type="text" name="title" id="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-md"><span id="btnText">Save</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(type, data = null) {
            const modal = document.getElementById('statModal');
            const form = document.getElementById('statForm');
            const title = document.getElementById('modalTitle');
            const btnText = document.getElementById('btnText');
            const methodField = document.getElementById('methodField');

            modal.classList.remove('hidden');

            if (type === 'create') {
                title.innerText = 'Add New Statistic';
                btnText.innerText = 'Add Stat';
                form.action = "{{ route('admin.stats.store') }}";
                methodField.innerHTML = ''; 
                form.reset();
            } else {
                title.innerText = 'Edit Statistic';
                btnText.innerText = 'Update Stat';
                form.action = `/admin/home-statistics/update/${data.id}`;
                methodField.innerHTML = '@method("PUT")';
                
                document.getElementById('value').value = data.value;
                document.getElementById('title').value = data.title;
            }
        }

        function closeModal() { document.getElementById('statModal').classList.add('hidden'); }
    </script>
@endsection