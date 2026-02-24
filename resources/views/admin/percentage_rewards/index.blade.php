@extends('layouts.layout')
@section('title', 'Manage Rewards')

@section('container')
    <div class="container mx-auto px-2 sm:px-4">

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div class="flex items-center w-full md:w-auto">
                <div class="bg-indigo-50 p-3 rounded-full mr-4 text-indigo-600">
                    <i class="fas fa-trophy fa-lg"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-800">Reward Settings</h3>
                    <p class="text-slate-500 text-sm">Manage achievement ranks and rewards</p>
                </div>
            </div>

            {{-- Add New Button --}}
            <button onclick="openModal('create')"
                class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md transition-all duration-300 flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Add New Reward
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
            <div
                class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center">
                <span class="font-medium flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </span>
                <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900"><i
                        class="fas fa-times"></i></button>
            </div>
        @endif

        {{-- TABLE CARD --}}
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h4 class="font-bold text-slate-700">All Rewards</h4>
                <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">
                    {{ $rewards->count() }} Records
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                            <th class="px-6 py-4 font-semibold text-center w-20">Sr. No</th>
                            <th class="px-6 py-4 font-semibold">Rank Name</th>
                            <th class="px-6 py-4 font-semibold text-right">Achievement Target</th>
                            <th class="px-6 py-4 font-semibold text-right">Reward Amount</th>
                            <th class="px-6 py-4 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($rewards as $item)
                            <tr class="hover:bg-slate-50 transition-colors group">

                                {{-- Sr No --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-slate-100 text-slate-600 font-bold px-2.5 py-1 rounded-md text-xs">
                                        {{ $item->sr_no }}
                                    </span>
                                </td>

                                {{-- Rank --}}
                                <td class="px-6 py-4 font-bold text-indigo-700">
                                    {{ $item->rank }}
                                </td>

                                {{-- Achievement --}}
                                <td class="px-6 py-4 text-right text-slate-600 font-medium">
                                    <span class="achievement-value" data-value="{{ $item->achievement }}"></span> PV
                                </td>

                                {{-- Reward --}}
                                <td class="px-6 py-4 text-right text-emerald-600 font-bold">
                                    ₹<span class="reward-value" data-value="{{ $item->reward }}"></span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 text-right flex justify-end gap-2">
                                    <button onclick='openModal("edit", @json($item))'
                                        class="text-indigo-500 hover:text-indigo-700 p-2 rounded-full hover:bg-indigo-50 transition-colors"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('admin.rewards.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this reward?');"
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
                                        <i class="fas fa-box-open text-3xl mb-2 text-slate-300"></i>
                                        <p>No rewards configured yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 
    CENTERED MODAL 
    Uses 'flex items-center justify-center' on the parent container
--}}
    <div id="rewardModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">

        {{-- Container for Centering --}}
        <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">

            {{-- Overlay --}}
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal()">
            </div>

            {{-- Vertical Centering Helper --}}
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Modal Panel --}}
            <div
                class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg w-full relative z-10">

                {{-- Header --}}
                <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2" id="modalTitle">
                        {{-- JS Title --}}
                    </h3>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                {{-- Form --}}
                <form id="rewardForm" method="POST" class="p-6">
                    @csrf
                    <div id="methodField"></div>

                    <div class="space-y-4">

                        {{-- Row 1: Sr No & Rank --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Serial No</label>
                                <input type="number" name="sr_no" id="sr_no" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rank Name</label>
                                <input type="text" name="rank" id="rank" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                    placeholder="e.g. Silver">
                            </div>
                        </div>

                        {{-- Row 2: Achievement --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Achievement Target</label>
                            <input type="number" name="achievement" id="achievement" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                placeholder="e.g. 50000">
                            <p class="text-xs text-slate-400 mt-1">Target value required to unlock reward</p>
                        </div>

                        {{-- Row 3: Reward --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reward Amount</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-bold">₹</span>
                                </div>
                                <input type="number" name="reward" id="reward" required
                                    class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                    placeholder="0">
                            </div>
                        </div>

                    </div>

                    {{-- Footer --}}
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

    {{-- JAVASCRIPT --}}
    <script>
        function openModal(type, data = null) {
            const modal = document.getElementById('rewardModal');
            const form = document.getElementById('rewardForm');
            const title = document.getElementById('modalTitle');
            const btnText = document.getElementById('btnText');
            const methodField = document.getElementById('methodField');

            modal.classList.remove('hidden');

            if (type === 'create') {
                title.innerHTML = '<i class="fas fa-plus-circle"></i> Add New Reward';
                btnText.innerText = 'Save';
                form.action = "{{ route('admin.rewards.store') }}";
                methodField.innerHTML = '';
                form.reset();
            } else {
                title.innerHTML = '<i class="fas fa-edit"></i> Edit Reward';
                btnText.innerText = 'Update';
                form.action = `/admin/percentage-rewards/update/${data.id}`;
                methodField.innerHTML = '@method('PUT')';

                // Fill Data
                document.getElementById('sr_no').value = data.sr_no;
                document.getElementById('rank').value = data.rank;
                document.getElementById('achievement').value = data.achievement;
                document.getElementById('reward').value = data.reward;
            }
        }

        function closeModal() {
            document.getElementById('rewardModal').classList.add('hidden');
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") closeModal();
        });

        function formatNumberShort(num) {
        if (num >= 1_000_000_000) {
            return (num / 1_000_000_000).toFixed(2).replace(/\.0$/, '') + 'B';
        }
        if (num >= 1_000_000) {
            return (num / 1_000_000).toFixed(2).replace(/\.0$/, '') + 'M';
        }
        if (num >= 1_000) {
            return (num / 1_000).toFixed(2).replace(/\.0$/, '') + 'K';
        }
        return num;
    }

     document.addEventListener('DOMContentLoaded', () => {

        document.querySelectorAll('.achievement-value').forEach(el => {
            const value = Number(el.dataset.value);
            el.textContent = formatNumberShort(value);
        });

        document.querySelectorAll('.reward-value').forEach(el => {
            const value = Number(el.dataset.value);
            el.textContent = formatNumberShort(value);
        });

    });
    </script>
@endsection
