@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')
@section('title', 'Our Registered Vendors')

@section('container')
<div class="min-h-screen bg-slate-50 font-sans pb-10">
    <div class="container mx-auto py-4 px-3">

        {{-- Header & Search Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-6">
            <div class="flex items-center">
                <div class="bg-indigo-100 p-3 rounded-xl mr-4 text-indigo-600 shadow-sm border border-indigo-200">
                    <i class="fas fa-store fa-lg"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Our Registered Vendors</h3>
                    <p class="text-slate-500 text-sm font-medium mt-1">Discover and connect with official company vendors.</p>
                </div>
            </div>

            {{-- Search Form --}}
            <form action="{{ route('user.vendors') }}" method="GET" class="w-full md:w-auto relative">
                <div class="relative flex items-center w-full md:w-80">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search vendor or city..." 
                        class="w-full pl-10 pr-20 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none shadow-sm transition-all text-slate-700 font-medium">
                    <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-3 rounded-lg transition-colors">
                        Search
                    </button>
                </div>
                @if(request('search'))
                    <div class="absolute -bottom-6 right-0 text-xs font-bold">
                        <a href="{{ route('user.vendors') }}" class="text-red-500 hover:text-red-700">Clear Search <i class="fas fa-times"></i></a>
                    </div>
                @endif
            </form>
        </div>

        {{-- Vendors Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($vendors as $vendor)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow duration-300 relative flex flex-col h-full">
                    
                    {{-- Card Header / Banner --}}
                    <div class="h-20 bg-gradient-to-r from-slate-800 to-slate-700 relative">
                        {{-- Shop Status Badge --}}
                        <div class="absolute top-3 right-3">
                            @if($vendor->isShopOpen)
                                <span class="bg-emerald-500/20 text-emerald-100 border border-emerald-500/30 text-[10px] font-black px-2.5 py-1 rounded-full backdrop-blur-sm flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span> Open Now
                                </span>
                            @else
                                <span class="bg-rose-500/20 text-rose-100 border border-rose-500/30 text-[10px] font-black px-2.5 py-1 rounded-full backdrop-blur-sm flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-rose-400 rounded-full"></span> Closed
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Avatar Logo --}}
                    <div class="flex justify-center -mt-10 relative z-10">
                        <div class="w-20 h-20 bg-gradient-to-r from-indigo-600 to-blue-700 rounded-2xl border-3 border-white shadow-sm flex items-center justify-center text-3xl text-white bg-indigo-50 font-black uppercase">
                            {{ substr($vendor->company_name, 0, 1) }}
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-3 pt-3 flex-grow flex flex-col text-center">
                        <h4 class="text-lg font-black text-slate-800 mb-1" title="{{ $vendor->company_name }}">
                            {{ $vendor->company_name }}
                        </h4>
                        <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-4">
                            <i class="fas fa-user-tie mr-1"></i> {{ $vendor->vendor_name }}
                        </p>

                        <div class="bg-slate-50 rounded-xl p-3 text-left border border-slate-100 flex-grow">
                            <ul class="space-y-2.5 text-sm">
                                <li class="flex items-start gap-2.5 text-slate-600">
                                    <i class="fas fa-map-marker-alt text-slate-400 mt-1 w-4 text-center"></i>
                                    <span class="font-medium leading-tight">
                                        {{ $vendor->company_address }}<br>
                                        {{ $vendor->company_city }}, {{ $vendor->company_state }} - {{ $vendor->zip_code }}
                                    </span>
                                </li>
                                @if($vendor->gst)
                                <li class="flex items-start gap-2.5 text-slate-600">
                                    <i class="fas fa-file-invoice-dollar text-slate-400 mt-0.5 w-4 text-center"></i>
                                    <span class="font-bold text-slate-700">GST No: <span class="font-semibold">{{ $vendor->gst }}</span></span>
                                </li>
                                @endif
                                <li class="flex items-start gap-2.5 text-slate-600">
                                    <i class="fas fa-envelope text-slate-400 mt-0.5 w-4 text-center"></i>
                                    <span class="font-medium truncate" title="{{ $vendor->user->email ?? 'N/A' }}">
                                        {{ $vendor->user->email ?? 'N/A' }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-1 sm:col-span-2 lg:col-span-3 xl:col-span-4 bg-white rounded-2xl p-10 text-center border border-slate-200 shadow-sm">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-50 text-slate-300 mb-4">
                        <i class="fas fa-store-slash text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-700 mb-1">No Vendors Found</h4>
                    <p class="text-sm text-slate-500">
                        @if(request('search'))
                            We couldn't find any vendors matching "{{ request('search') }}".
                        @else
                            There are currently no active vendors available in the system.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $vendors->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>
@endsection