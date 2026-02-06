@extends('layouts.layout')
@section('title', 'Member Details')
@section('container')

    @php
        if (!function_exists('formatLikeYouTube')) {
            function formatLikeYouTube($n)
            {
                if ($n >= 1000000000) {
                    return round($n / 1000000000, 2) . 'B';
                }
                if ($n >= 1000000) {
                    return round($n / 1000000, 2) . 'M';
                }
                if ($n >= 1000) {
                    return round($n / 1000, 2) . 'K';
                }
                return number_format($n);
            }
        }
    @endphp

    <div class="min-h-screen bg-slate-50 py-8 font-sans text-slate-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- 1. HEADER --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-3">
                        <span class="p-2 bg-blue-100 rounded-lg text-blue-600 shadow-sm">
                            <i class="fas fa-id-card-alt text-xl"></i>
                        </span>
                        Member Profile
                    </h1>
                    <p class="text-sm text-slate-500 mt-1 ml-1">
                        Viewing details for <span class="font-bold text-slate-700">{{ $member->name }}</span>
                        <span
                            class="font-mono text-xs bg-slate-100 px-2 py-0.5 rounded text-slate-500 ml-2">#{{ $member->ulid }}</span>
                    </p>
                </div>

                <a href="{{ route('admin.viewmember') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 shadow-sm hover:bg-slate-50 hover:text-slate-800 transition-all">
                    <i class="fas fa-arrow-left mr-2 text-xs"></i> Back to List
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- LEFT COLUMN: Primary Info --}}
                <div class="space-y-6 lg:col-span-1">

                    {{-- Profile Summary Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden relative group">
                        <div class="h-28 bg-gradient-to-r from-blue-600 to-indigo-600 relative overflow-hidden">
                            <div class="absolute inset-0 bg-white/10 opacity-30 pattern-dots"></div>
                        </div>

                        <div class="px-6 pb-6 relative">
                            <div class="-mt-14 mb-4 flex justify-between items-end">
                                <div class="relative">
                                    @if ($member->profile_picture)
                                        <img src="{{ asset('storage/' . $member->profile_picture) }}"
                                            class="w-28 h-28 rounded-2xl object-cover border-4 border-white shadow-lg bg-white">
                                    @else
                                        <div
                                            class="w-28 h-28 rounded-2xl border-4 border-white shadow-lg bg-slate-50 flex items-center justify-center text-slate-300 text-4xl">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif

                                </div>

                                <span
                                    class="mb-1 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $member->status == 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                    {{ $member->status }}
                                </span>
                            </div>

                            <h2 class="text-xl font-bold text-slate-800">{{ $member->name }}</h2>
                            <p class="text-sm text-slate-500 font-mono mb-6 flex items-center gap-2">
                                <i class="far fa-envelope text-slate-400"></i> {{ $member->email }}
                            </p>

                            <div class="space-y-4 pt-6 border-t border-slate-100">
                                <div class="flex items-start gap-4 group/item">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center flex-shrink-0 group-hover/item:bg-blue-100 transition-colors">
                                        <i class="fas fa-phone-alt text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-400 uppercase">Phone</p>
                                        <p class="text-sm font-semibold text-slate-700">{{ $member->phone }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4 group/item">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-purple-50 text-purple-500 flex items-center justify-center flex-shrink-0 group-hover/item:bg-purple-100 transition-colors">
                                        <i class="fas fa-map-marker-alt text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-400 uppercase">Location</p>
                                        <p class="text-sm font-semibold text-slate-700">
                                            {{ $member->address ?? 'Not Provided' }}</p>
                                        @if ($member->city || $member->state)
                                            <p class="text-xs text-slate-500 mt-0.5">
                                                {{ $member->city }}{{ $member->city && $member->state ? ', ' : '' }}{{ $member->state }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Network Info Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="fas fa-network-wired"></i> Network Details
                        </h3>

                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-semibold text-slate-500">Sponsor ID</span>
                                <span
                                    class="text-sm font-bold text-blue-600 font-mono bg-blue-50 px-2 py-0.5 rounded">{{ $member->sponsor_id }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-semibold text-slate-500">Current Rank</span>
                                <span
                                    class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded uppercase">{{ $member->current_rank ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500">Joined Date</span>
                            <span class="font-medium text-slate-700">{{ $member->created_at->format('d M, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm mt-2">
                            <span class="text-slate-500">Activation Date</span>
                            <span
                                class="font-medium text-slate-700">{{ $member->user_doa ? \Carbon\Carbon::parse($member->user_doa)->format('d M, Y') : 'Pending' }}</span>
                        </div>
                    </div>

                </div>

                {{-- RIGHT COLUMN: Details --}}
                <div class="space-y-6 lg:col-span-2">

                    {{-- 2. Financial Stats --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div
                            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden group hover:border-blue-300 transition-colors">
                            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <i class="fas fa-wallet text-6xl text-blue-600"></i>
                            </div>
                            <div class="relative z-10">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Personal Wallet
                                </p>
                                <div class="flex items-baseline gap-1">
                                    <span
                                        class="text-2xl font-black text-slate-800">₹{{ formatLikeYouTube($member->wallet1_balance) }}</span>
                                    <span
                                        class="text-xs font-medium text-green-500 bg-green-50 px-1.5 py-0.5 rounded">Active</span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden group hover:border-emerald-300 transition-colors">
                            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <i class="fas fa-coins text-6xl text-emerald-600"></i>
                            </div>
                            <div class="relative z-10">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Second Wallet</p>
                                <div class="flex items-baseline gap-1">
                                    <span
                                        class="text-2xl font-black text-emerald-600">₹{{ formatLikeYouTube($member->wallet2_balance) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Business & Banking Tabs --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
                            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                <i class="fas fa-briefcase text-slate-400"></i> Business & Banking Overview
                            </h3>
                        </div>

                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Business Volume --}}
                            <div class="relative">
                                <h4 class="text-xs font-bold text-slate-400 uppercase mb-4">Total Business</h4>
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                                        <i class="fas fa-chart-pie"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold text-slate-800">
                                            {{ number_format($member->total_business) }} <span
                                                class="text-sm text-slate-400 font-normal">PV</span></h2>
                                        <p class="text-xs text-green-600 font-medium flex items-center gap-1">
                                            <i class="fas fa-arrow-up"></i> Growing
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Bank Details --}}
                            <div class="bg-slate-50 rounded-xl p-5 border border-slate-100 relative">
                                <div class="absolute top-3 right-3 text-slate-300">
                                    <i class="fas fa-university text-4xl opacity-20"></i>
                                </div>
                                <h4 class="text-xs font-bold text-slate-400 uppercase mb-3">Bank Information</h4>

                                <div class="space-y-3">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-slate-500">Bank Name</span>
                                        <span class="font-semibold text-slate-800">{{ $member->bank_name ?? '--' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-slate-500">Account No</span>
                                        <span
                                            class="font-mono font-semibold text-slate-800 bg-white px-2 py-0.5 rounded border border-slate-100">{{ $member->account_no ?? '--' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-slate-500">IFSC Code</span>
                                        <span
                                            class="font-mono font-semibold text-slate-800">{{ $member->ifsc_code ?? '--' }}</span>
                                    </div>
                                    <div
                                        class="pt-2 mt-2 border-t border-slate-200 flex justify-between items-center text-sm">
                                        <span class="text-slate-500">UPI ID</span>
                                        <span class="font-semibold text-slate-800">{{ $member->upi_id ?? '--' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 4. KYC Documents --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-base font-bold text-slate-800">KYC Documents</h3>
                                <p class="text-xs text-slate-500 mt-1">Verify user identification proofs.</p>
                            </div>
                            <div class="flex gap-2">
                                <span class="px-2 py-1 bg-slate-100 rounded text-[10px] font-mono text-slate-500">PAN:
                                    {{ $member->pan_no ?? 'N/A' }}</span>
                                <span class="px-2 py-1 bg-slate-100 rounded text-[10px] font-mono text-slate-500">AADHAR:
                                    {{ $member->adhar_no ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            @php
                                $docs = [
                                    [
                                        'title' => 'Passbook',
                                        'file' => $member->passbook_photo,
                                        'icon' => 'fa-file-invoice',
                                    ],
                                    ['title' => 'Aadhar Card', 'file' => $member->adhar_photo, 'icon' => 'fa-id-card'],
                                    ['title' => 'PAN Card', 'file' => $member->pan_photo, 'icon' => 'fa-id-badge'],
                                    [
                                        'title' => 'Nominee',
                                        'subtitle' => $member->nom_name ?? 'N/A',
                                        'relation' => $member->nom_relation,
                                        'type' => 'info',
                                        'icon' => 'fa-users',
                                    ],
                                ];
                            @endphp

                            @foreach ($docs as $doc)
                                @if (isset($doc['type']) && $doc['type'] == 'info')
                                    {{-- Info Card (Nominee) --}}
                                    <div
                                        class="rounded-xl bg-purple-50 border border-purple-100 p-4 flex flex-col items-center justify-center text-center h-32">
                                        <div
                                            class="w-8 h-8 rounded-full bg-purple-100 text-purple-500 flex items-center justify-center mb-2">
                                            <i class="fas {{ $doc['icon'] }} text-xs"></i>
                                        </div>
                                        <p class="text-xs font-bold text-purple-400 uppercase mb-1">{{ $doc['title'] }}
                                        </p>
                                        <p class="text-sm font-bold text-purple-900 line-clamp-1">{{ $doc['subtitle'] }}
                                        </p>
                                        <p class="text-[10px] text-purple-600">{{ $doc['relation'] ?? '' }}</p>
                                    </div>
                                @else
                                    {{-- Image Card --}}
                                    <div
                                        class="group relative rounded-xl bg-slate-50 border border-slate-200 h-32 flex flex-col items-center justify-center text-center overflow-hidden hover:shadow-md transition-all">
                                        @if ($doc['file'])
                                            <img src="{{ asset('storage/' . $doc['file']) }}"
                                                class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                            <div
                                                class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                                <a href="{{ asset('storage/' . $doc['file']) }}" target="_blank"
                                                    class="px-3 py-1.5 bg-white/20 hover:bg-white/30 text-white text-xs font-bold rounded-lg border border-white/40 backdrop-blur-sm transition-colors">
                                                    View
                                                </a>
                                            </div>
                                        @else
                                            <i class="fas {{ $doc['icon'] }} text-slate-300 text-2xl mb-2"></i>
                                            <span class="text-xs text-slate-400 font-medium">Not Uploaded</span>
                                        @endif
                                        <div
                                            class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-slate-900/80 to-transparent p-2">
                                            <p class="text-[10px] font-bold text-white text-center shadow-sm">
                                                {{ $doc['title'] }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
