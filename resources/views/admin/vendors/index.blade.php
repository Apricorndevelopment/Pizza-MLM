@extends('layouts.layout')
@section('title', 'All Vendors List')

@section('container')
<div class="min-h-screen bg-slate-50 px-2 sm:px-4">
    
    {{-- Header & Search --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <span class="bg-indigo-100 text-indigo-600 p-2 rounded-lg">
                    <i class="fas fa-store"></i>
                </span>
                Registered Vendors
            </h1>
            <p class="text-slate-500 text-sm mt-1 ml-11">Total {{ $vendors->total() }} vendors joined</p>
        </div>

        {{-- Search Form --}}
        <form action="{{ route('admin.vendors.list') }}" method="GET" class="w-full flex gap-2 items-center md:w-auto relative">
            <div class="relative group w-full md:w-72">
                <span class="absolute left-3 top-2.5 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border-none ring-1 ring-slate-200 shadow-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm font-medium" 
                    placeholder="Search by Name, ID, Company...">
            </div>
            <div>
                <a href="{{ route('admin.vendors.list') }}" class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Clear</a>
            </div>
        </form>
    </div>

    {{-- Vendors Table Card --}}
    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                        <th class="p-3 font-bold">Vendor Info</th>
                        <th class="p-3 font-bold">Company Details</th>
                        <th class="p-3 font-bold">Location</th>
                        <th class="p-3 font-bold">Status</th>
                        <th class="p-3 font-bold">Joined Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($vendors as $vendor)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        
                        {{-- 1. Vendor Info (Name & ULID) --}}
                        <td class="p-3">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                                    {{ substr($vendor->vendor_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">{{ $vendor->user->name }}</p>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                        <i class="fas fa-id-card"></i> {{ $vendor->user->ulid ?? 'N/A' }}
                                    </span>
                                    <p class="text-slate-800">{{ $vendor->user->email }}</p>
                                    <p class="text-slate-800">Ph. No. - {{ $vendor->user->phone }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- 2. Company Info --}}
                        <td class="p-3">
                            <div class="max-w-[250px]">
                                <p class="font-bold text-slate-700 flex items-center gap-2">
                                    <i class="fas fa-building text-slate-400 text-xs"></i> 
                                    {{ $vendor->company_name }}
                                </p>
                                @if($vendor->gst)
                                    <p class="text-xs text-emerald-600 font-semibold mt-1 bg-emerald-50 inline-block px-1.5 rounded border border-emerald-100">
                                        GST: {{ $vendor->gst }}
                                    </p>
                                @else
                                    <p class="text-xs text-slate-400 mt-1 italic">No GST Info</p>
                                @endif
                            </div>
                        </td>

                        {{-- 3. Location (Address) --}}
                        <td class="p-3">
                            <div class="max-w-[200px]">
                                <p class="text-slate-600 font-medium truncate" title="{{ $vendor->company_city }}, {{ $vendor->company_state }}">
                                    {{ $vendor->company_city }}, {{ $vendor->company_state }}
                                </p>
                                <p class="text-xs text-slate-400 mt-0.5 truncate" title="{{ $vendor->comany_address }}">
                                    {{ $vendor->comany_address }}
                                </p>
                            </div>
                        </td>

                        {{-- 4. Shop Status --}}
                        <td class="p-3">
                            @if($vendor->isShopOpen)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Open
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Closed
                                </span>
                            @endif
                        </td>

                        {{-- 5. Date --}}
                        <td class="p-3 text-slate-500 font-medium">
                            {{ $vendor->created_at->format('d M Y') }}
                            <small class="block text-[10px] text-slate-400">{{ $vendor->created_at->format('h:i A') }}</small>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-slate-50 p-4 rounded-full mb-3">
                                    <i class="fas fa-store-slash text-3xl text-slate-300"></i>
                                </div>
                                <h3 class="text-slate-800 font-bold">No Vendors Found</h3>
                                <p class="text-slate-500 text-sm">Try adjusting your search criteria.</p>
                                <a href="{{ route('admin.vendors.list') }}" class="mt-4 text-indigo-600 hover:underline font-bold text-sm">View All Vendors</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($vendors->hasPages())
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200">
            {{ $vendors->links() }}
        </div>
        @endif
    </div>
</div>
@endsection