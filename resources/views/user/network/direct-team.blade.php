@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')

@section('title', 'Direct Team')

@section('container')
    <div class="min-h-screen bg-slate-50 py-8 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- 1. Hero / Header Section --}}
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-600 to-teal-600 p-8 text-white shadow-xl shadow-emerald-200 mb-8">
                {{-- Decorative Background Elements --}}
                <div class="absolute top-0 right-0 -mr-16 -mt-16 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -ml-10 -mb-10 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h2 class="text-3xl font-bold tracking-tight">Direct Team</h2>
                        <p class="text-emerald-100 mt-1">Manage and view your personally sponsored network members.</p>
                    </div>
                    
                    {{-- Quick Stat Badge --}}
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/20">
                        <div class="h-10 w-10 rounded-full bg-white text-emerald-600 flex items-center justify-center shadow-sm">
                            <i class="fas fa-users text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-emerald-100 font-medium uppercase tracking-wider">Total Directs</p>
                            <p class="text-2xl font-black">{{ count($directTeam) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Team List Card --}}
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                
                {{-- Toolbar / Filter Header --}}
                <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white">
                    <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Team Overview
                    </h3>
                    {{-- Search Placeholder (Optional visual element) --}}
                    <div class="relative w-full sm:w-64 group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-slate-400 group-focus-within:text-emerald-500"></i>
                        </div>
                        <input type="text" class="block w-full pl-10 pr-3 py-2 border-none rounded-lg bg-slate-50 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 transition-all" placeholder="Search members...">
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                                <th class="px-6 py-4 pl-8">Member Profile</th>
                                <th class="px-6 py-4 text-center">User ID (ULID)</th>
                                <th class="px-6 py-4 text-center">Total Business</th>
                                <th class="px-6 py-4 text-center">Rank</th>
                                <th class="px-6 py-4 text-right pr-8">Joined Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($directTeam as $user)
                                <tr class="hover:bg-emerald-50/30 transition-colors duration-200 group">
                                    
                                    {{-- Name & Avatar --}}
                                    <td class="px-6 py-4 pl-8 whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            {{-- Avatar Circle --}}
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-100 to-teal-100 border border-emerald-200 text-emerald-700 flex items-center justify-center text-sm font-bold shadow-sm group-hover:scale-110 transition-transform">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-slate-800 group-hover:text-emerald-700 transition-colors">
                                                    {{ $user->name }}
                                                </div>
                                                <div class="text-[10px] text-slate-400 font-medium">Direct Member</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- ULID --}}
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-slate-100 text-slate-600 border border-slate-200 group-hover:border-emerald-200 group-hover:bg-emerald-50 group-hover:text-emerald-700 transition-colors">
                                            {{ $user->ulid }}
                                        </span>
                                    </td>

                                    {{-- Left Business --}}
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="flex flex-col items-center">
                                            <span class="text-sm font-bold text-emerald-600">
                                                ₹{{ number_format($user->total_business ?? 0, 2) }}
                                            </span>
                                            <span class="text-[10px] uppercase font-bold text-slate-400">PV</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="flex flex-col items-center">
                                            <span class="text-sm font-bold text-amber-300 }}">
                                                {{ $user->current_rank ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Joined Date --}}
                                    <td class="px-6 py-4 text-right pr-8 whitespace-nowrap">
                                        <div class="flex flex-col items-end">
                                            <span class="text-sm font-semibold text-slate-700">
                                                {{ $user->created_at->format('d M, Y') }}
                                            </span>
                                            <span class="text-xs text-slate-400 flex items-center gap-1">
                                                <i class="far fa-clock text-[10px]"></i> {{ $user->created_at->format('h:i A') }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                {{-- Empty State --}}
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="h-20 w-20 bg-emerald-50 rounded-full flex items-center justify-center mb-4 shadow-inner">
                                                <i class="fas fa-user-plus text-3xl text-emerald-300"></i>
                                            </div>
                                            <h3 class="text-lg font-bold text-slate-700">No Direct Team Found</h3>
                                            <p class="text-slate-500 text-sm mt-1 max-w-xs mx-auto">
                                                You haven't sponsored anyone yet. Start referring users to build your direct team!
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Footer / Pagination area --}}
                @if(count($directTeam) > 0)
                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex justify-between items-center">
                        <p class="text-xs text-slate-500 font-medium">
                            Showing <span class="font-bold text-emerald-700">{{ count($directTeam) }}</span> records
                        </p>
                        {{-- Placeholder for pagination links if you add them later --}}
                        {{-- <div class="flex gap-1"> ... links ... </div> --}}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection