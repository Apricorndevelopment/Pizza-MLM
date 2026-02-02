@extends('layouts.layout')
@section('title', 'Dashboard')
@section('container')

    <div class="min-h-screen bg-gray-50/50 pb-8 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Alerts Section --}}
            <div class="mb-6 space-y-3">
                @if (session('success'))
                    <div class="flex items-center p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-100 shadow-sm"
                        role="alert">
                        <i class="fas fa-check-circle mr-3 text-lg"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                        <button type="button"
                            class="ml-auto -mx-1.5 -my-1.5 bg-emerald-50 text-emerald-500 rounded-lg focus:ring-2 focus:ring-emerald-400 p-1.5 hover:bg-emerald-200 inline-flex h-8 w-8"
                            data-bs-dismiss="alert" aria-label="Close" onclick="this.parentElement.remove()">
                            <span class="sr-only">Close</span>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-xl bg-red-50 border border-red-100 shadow-sm"
                        role="alert">
                        <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
                        <span class="font-medium">{{ session('error') }}</span>
                        <button type="button"
                            class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8"
                            data-bs-dismiss="alert" aria-label="Close" onclick="this.parentElement.remove()">
                            <span class="sr-only">Close</span>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
            </div>

            {{-- Modern Card --}}
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">

                {{-- Card Header with Theme Color #EBF2FE --}}
                <div class="bg-[#EBF2FE] px-6 py-5 border-b border-blue-100/50">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

                        {{-- Title --}}
                        <div>
                            <h4 class="text-xl font-bold text-slate-800 tracking-tight">Registered Members</h4>
                            <p class="text-xs text-slate-500 font-medium mt-1">Manage and view all system users</p>
                        </div>

                        {{-- Actions Toolbar --}}
                        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">

                            {{-- Search Form (Backend & Frontend Hybrid) --}}
                            <form action="{{ route('admin.viewmember') }}" method="GET"
                                class="relative group w-full sm:w-64">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-search text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                </div>
                                <input type="text" id="searchInput" name="ulid"
                                    class="block w-full pl-10 pr-3 py-2.5 bg-white border-0 ring-1 ring-slate-200 rounded-xl text-sm text-slate-600 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all shadow-sm"
                                    placeholder="Search Name, ULID..." value="{{ request('ulid') }}">
                                <input type="hidden" name="status" value="{{ $status }}">
                            </form>

                            {{-- Filter & Reset Group --}}
                            <div class="flex gap-2">
                                <form method="GET" action="{{ route('admin.viewmember') }}" class="w-full sm:w-auto">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-filter text-slate-400 text-xs"></i>
                                        </div>
                                        <select name="status" onchange="this.form.submit()"
                                            class="block w-full pl-9 pr-8 py-2.5 bg-white border-0 ring-1 ring-slate-200 rounded-xl text-sm font-medium text-slate-600 focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm cursor-pointer hover:bg-slate-50 transition-colors appearance-none">
                                            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Status
                                            </option>
                                            <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-slate-400 text-xs"></i>
                                        </div>
                                    </div>
                                </form>

                                <a href="{{ route('admin.viewmember') }}"
                                    class="flex items-center justify-center px-4 py-2.5 bg-white ring-1 ring-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50 hover:text-red-500 transition-all shadow-sm"
                                    title="Reset Filters">
                                    <i class="fas fa-redo-alt sm:mr-2"></i>
                                    <span class="hidden sm:inline">Reset</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Table Section --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th
                                    class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center w-16">
                                    #</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Member Info
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Sponsor</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Registered
                                    On</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">
                                    Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody id="memberTableBody" class="divide-y divide-slate-100 bg-white">
                            @foreach ($member as $index => $user)
                                <tr class="group hover:bg-[#EBF2FE]/30 transition-colors duration-200">
                                    {{-- Index --}}
                                    <td class="px-6 py-4 text-sm text-slate-500 text-center font-mono">
                                        {{ ($member->currentPage() - 1) * $member->perPage() + $index + 1 }}
                                    </td>

                                    {{-- Name & ULID (Index 1 for JS) --}}
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-bold text-slate-700 group-hover:text-blue-700 transition-colors">
                                                {{ $user->name }}
                                            </span>
                                            <span
                                                class="text-xs font-mono text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md w-fit mt-1 border border-slate-200">
                                                {{ $user->ulid }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Sponsor (Index 2 for JS) --}}
                                    <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                                        {{ $user->sponsor_id ?? 'N/A' }}
                                    </td>

                                    {{-- Date --}}
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm text-slate-600 font-medium">{{ $user->created_at->format('d M, Y') }}</span>
                                            <span
                                                class="text-xs text-slate-400">{{ $user->created_at->format('h:i A') }}</span>
                                        </div>
                                    </td>

                                    {{-- Status Badge --}}
                                    <td class="px-6 py-4 text-center">
                                        @if ($user->status == 'active')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                <span
                                                    class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span>
                                                Active
                                            </span>
                                        @elseif($user->status == 'inactive')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                                <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-1.5"></span>
                                                Inactive
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Action Buttons --}}
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center items-center gap-2">

                                            <a href="{{ route('admin.editmember', $user->id) }}"
                                                class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 hover:text-blue-700 transition-all"
                                                title="Edit Member">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="{{ route('admin.viewmemberdetails', $user->id) }}"
                                                class="p-2 rounded-lg text-teal-600 hover:bg-teal-50 hover:text-teal-700 transition-all"
                                                title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <form action="{{ route('admin.deletemember', $user->id) }}" method="post"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 rounded-lg text-red-500 hover:bg-red-50 hover:text-red-600 transition-all"
                                                    onclick="return confirm('Are you sure you want to delete {{ $user->name }} ({{ $user->ulid }})? This action cannot be undone.')"
                                                    title="Delete Member">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Section --}}
                @if ($member->hasPages())
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                        {{ $member->appends(['status' => $status, 'ulid' => request('ulid')])->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableBody = document.getElementById('memberTableBody');
            const rows = tableBody.getElementsByTagName('tr');

            // Event Listener for typing
            searchInput.addEventListener('keyup', function() {
                const filter = searchInput.value.toLowerCase();

                // Loop through all table rows
                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    // Get all text content from the row cells
                    const cells = row.getElementsByTagName('td');
                    let rowText = '';

                    // Combine text from Name/ULID (index 1) and Sponsor (index 2)
                    // Note: Index 0 is the row number (#)
                    if (cells.length > 1) {
                        rowText += cells[1].textContent.toLowerCase(); // Name & ULID column
                        rowText += " " + cells[2].textContent.toLowerCase(); // Sponsor column
                    }

                    // Check if the query exists in the row text
                    if (rowText.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });
    </script>

@endsection
