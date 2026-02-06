@extends('layouts.layout')

@section('title', 'Network Summary - Admin')

@section('container')
    <div class="min-h-screen bg-slate-50 py-8 font-sans text-slate-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Admin Network Overview</h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Viewing network for <strong>{{ $admin->name }}</strong> (AUID: {{ $admin->auid }})
                    </p>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-1 pr-4 flex items-center gap-4 shadow-sm">
                    <div class="bg-blue-50 text-blue-700 h-12 w-12 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Network</p>
                        <p class="text-xl font-bold text-slate-800 leading-none">{{ $paginatedUsers->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 overflow-hidden transition-all duration-300"
                id="filterContainer">

                <div class="px-6 py-4 bg-white border-b border-slate-100 flex justify-between items-center cursor-pointer hover:bg-slate-50 transition-colors"
                    onclick="toggleFilters()">
                    <div class="flex items-center gap-2 text-slate-700">
                        <div class="bg-slate-100 p-1.5 rounded text-slate-500">
                            <i class="fas fa-filter text-xs"></i>
                        </div>
                        <span class="text-sm font-bold uppercase tracking-wide">Search & Filters</span>
                    </div>
                    <div>
                        <a href="{{ route('admin.network.summary') }}"
                            class="text-sm font-bold text-slate-500 hover:text-slate-800 transition px-4 py-2">Reset</a>

                        <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-300"
                            id="filterIcon"></i>
                    </div>
                </div>

                <div id="filterBody" class="hidden">
                    <div class="p-6 bg-slate-50/50">
                        <form method="GET" action="{{ route('admin.network.summary') }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5">

                                <div class="space-y-1.5">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Designation</label>
                                    <div class="relative">
                                        <select name="designation"
                                            class="w-full appearance-none rounded-lg border-slate-200 bg-white py-2.5 px-3 text-sm font-medium focus:border-blue-500 focus:ring-blue-500 shadow-sm text-slate-600">
                                            <option value="">All Ranks</option>
                                            @foreach ($designations as $designation)
                                                <option value="{{ $designation }}"
                                                    {{ request('designation') == $designation ? 'selected' : '' }}>
                                                    {{ $designation }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div
                                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-1.5">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Status</label>
                                    <div class="relative">
                                        <select name="status"
                                            class="w-full appearance-none rounded-lg border-slate-200 bg-white py-2.5 px-3 text-sm font-medium focus:border-blue-500 focus:ring-blue-500 shadow-sm text-slate-600">
                                            <option value="">All Status</option>
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="inactive"
                                                {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        <div
                                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">From</label>
                                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                                        class="w-full rounded-lg border-slate-200 bg-white py-2.5 px-3 text-sm font-medium focus:border-blue-500 focus:ring-blue-500 shadow-sm text-slate-600">
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">To</label>
                                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                                        class="w-full rounded-lg border-slate-200 bg-white py-2.5 px-3 text-sm font-medium focus:border-blue-500 focus:ring-blue-500 shadow-sm text-slate-600">
                                </div>

                                <div class="flex items-end">
                                    <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2.5 px-6 rounded-lg shadow-md shadow-blue-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                                        <i class="fas fa-search"></i> Apply Filters
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-slate-50 border-b border-slate-100 text-xs uppercase font-bold text-slate-500 tracking-wider">
                                <th class="px-6 py-4 text-center w-16">#</th>
                                <th class="px-6 py-4">User Profile</th>
                                <th class="px-6 py-4 text-center">Sponsor</th>
                                <th class="px-6 py-4 text-center">Dates</th>
                                <th class="px-6 py-4 text-center">Level</th>
                                <th class="px-6 py-4 text-center">Designation</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @php $index = ($paginatedUsers->currentPage() - 1) * $paginatedUsers->perPage() + 1; @endphp
                            @forelse($paginatedUsers as $user)
                                <tr class="group hover:bg-blue-50/50 transition-colors duration-200">
                                    <td class="px-6 py-4 text-center text-slate-400 font-mono text-sm font-bold">
                                        {{ $index++ }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-sm font-bold text-slate-800 group-hover:text-blue-700 transition-colors">
                                                    {{ $user->name }}
                                                </span>
                                                <span class="text-xs font-mono text-slate-400 flex items-center gap-1">
                                                    <i class="fas fa-id-card text-[10px]"></i> {{ $user->ulid }}
                                                </span>
                                                <div class="flex items-center gap-1.5 mt-1">
                                                    <div
                                                        class="w-2 h-2 rounded-full {{ $user->status == 'active' ? 'bg-emerald-500' : 'bg-slate-300' }}">
                                                    </div>
                                                    <span
                                                        class="text-[10px] font-bold uppercase text-slate-400">{{ $user->status }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-mono font-bold">
                                            {{ $user->sponsor_id }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-slate-600">Reg: {{ $user->created_at->format('d M Y') }}</span>
                                            <span class="text-[10px] text-slate-400">Act: {{ $user->user_doa ?? 'N/A' }}</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 font-bold text-xs border border-blue-100">
                                            {{ $user->level }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if ($user->current_rank)
                                            <span class="inline-flex px-3 py-1 rounded-full bg-slate-800 text-white text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                                {{ $user->current_rank }}
                                            </span>
                                        @else
                                            <span class="text-slate-300 text-xs italic">--</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center bg-slate-50/30">
                                        <div class="flex flex-col items-center">
                                            <div class="bg-white p-4 rounded-full shadow-sm mb-3">
                                                <i class="fas fa-users-slash text-slate-300 text-2xl"></i>
                                            </div>
                                            <h3 class="text-slate-800 font-bold">No Records Found</h3>
                                            <p class="text-slate-500 text-sm mt-1">Try adjusting your filters.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($paginatedUsers->hasPages())
                    <div class="px-6 py-3 bg-white border-t border-slate-100">
                        {{ $paginatedUsers->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function toggleFilters() {
            const body = document.getElementById('filterBody');
            const icon = document.getElementById('filterIcon');

            if (body.classList.contains('hidden')) {
                // Open
                body.classList.remove('hidden');
                // Animate Icon
                icon.style.transform = 'rotate(180deg)';
            } else {
                // Close
                body.classList.add('hidden');
                // Reset Icon
                icon.style.transform = 'rotate(0deg)';
            }
        }
    </script>
@endsection