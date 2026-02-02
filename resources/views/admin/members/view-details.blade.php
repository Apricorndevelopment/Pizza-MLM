@extends('layouts.layout')
@section('title', 'Member Details')
@section('container')

    <div class="min-h-screen bg-gray-50/50 py-8 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Page Header & Actions --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-3">
                        <span class="p-2 bg-blue-100 rounded-lg text-blue-600">
                            <i class="fas fa-user-circle text-xl"></i>
                        </span>
                        Member Profile
                    </h1>
                    <p class="text-sm text-slate-500 mt-1 ml-1"> viewing details for <span
                            class="font-semibold text-slate-700">{{ $member->name }}</span> ({{ $member->ulid }})</p>
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
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="h-24 bg-[#EBF2FE]"></div>
                        <div class="px-6 pb-6 relative">
                            <div class="-mt-12 mb-4">
                                @if ($member->profile_picture)
                                    <img src="{{ asset('storage/' . $member->profile_picture) }}"
                                        class="w-24 h-24 rounded-2xl object-cover border-4 border-white shadow-md bg-white">
                                @else
                                    <div
                                        class="w-24 h-24 rounded-2xl border-4 border-white shadow-md bg-slate-100 flex items-center justify-center text-slate-300 text-3xl">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                            </div>

                            <h2 class="text-lg font-bold text-slate-800">{{ $member->name }}</h2>
                            <p class="text-sm text-slate-500 font-mono mb-4">{{ $member->email }}</p>

                            <div class="flex flex-wrap gap-2 mb-6">
                                <span
                                    class="px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                    ID: {{ $member->ulid }}
                                </span>
                                <span
                                    class="px-2.5 py-1 rounded-md text-xs font-bold {{ $member->status == 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-50 text-slate-600 border-slate-100' }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </div>

                            <div class="space-y-3 pt-4 border-t border-slate-50">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-phone mt-1 text-slate-400 text-xs"></i>
                                    <span class="text-sm text-slate-600 font-medium">{{ $member->phone }}</span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-map-marker-alt mt-1 text-slate-400 text-xs"></i>
                                    <span class="text-sm text-slate-600">
                                        {{ $member->address ?? 'No Address' }}
                                        @if ($member->state)
                                            <br><span class="text-slate-400 text-xs">{{ $member->state }}</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Account Structure Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Network Structure</h3>

                        <div class="space-y-4">
                            <div
                                class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-xs font-semibold text-slate-500">Sponsor</span>
                                <span class="text-sm font-bold text-blue-600 font-mono">{{ $member->sponsor_id }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-xs font-semibold text-slate-500">Parent Node</span>
                                <span
                                    class="text-sm font-bold text-slate-700 font-mono">{{ $member->parent_id ?? 'Root' }}</span>
                            </div>
                            <div class="pt-2">
                                <div class="text-xs text-slate-400 mb-1">Activation Date</div>
                                <div class="text-sm font-medium text-slate-700">{{ $member->user_doa ?? 'Not Activated' }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- RIGHT COLUMN: Details --}}
                <div class="space-y-6 lg:col-span-2">

                    {{-- Financial Stats --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div
                            class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Wallet 1</p>
                                <p class="text-2xl font-bold text-slate-800 mt-1">₹{{ $member->wallet1_balance }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                        <div
                            class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Wallet 2</p>
                                <p class="text-2xl font-bold text-emerald-600 mt-1">₹{{ $member->wallet2_balance }}</p>
                            </div>
                            <div
                                class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <i class="fas fa-coins"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Business & Bank Info --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="border-b border-slate-100">
                            <nav class="flex space-x-6 px-6" aria-label="Tabs">
                                <button class="border-b-2 border-blue-500 py-4 text-sm font-bold text-blue-600">
                                    Business & Banking
                                </button>
                            </nav>
                        </div>

                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Business --}}
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 uppercase mb-4 flex items-center gap-2">
                                    <i class="fas fa-chart-pie text-blue-500"></i> Business Volume
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-slate-500">Left Business</span>
                                        <span class="font-bold text-slate-700">{{ $member->left_business }}</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                                        <div class="bg-blue-500 h-1.5 rounded-full" style="width: 40%"></div>
                                    </div>

                                    <div class="flex justify-between items-center text-sm mt-4">
                                        <span class="text-slate-500">Right Business</span>
                                        <span class="font-bold text-slate-700">{{ $member->right_business }}</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                                        <div class="bg-purple-500 h-1.5 rounded-full" style="width: 60%"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Bank --}}
                            <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                                <h4 class="text-xs font-bold text-slate-900 uppercase mb-4 flex items-center gap-2">
                                    <i class="fas fa-university text-slate-500"></i> Bank Details
                                </h4>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">Bank Name</span>
                                        <span class="font-semibold text-slate-700">{{ $member->bank_name ?? '--' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">Account No</span>
                                        <span
                                            class="font-mono font-semibold text-slate-700">{{ $member->account_no ?? '--' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">IFSC Code</span>
                                        <span
                                            class="font-mono font-semibold text-slate-700">{{ $member->ifsc_code ?? '--' }}</span>
                                    </div>
                                    <div class="flex justify-between pt-2 border-t border-slate-200 mt-2">
                                        <span class="text-slate-500">UPI ID</span>
                                        <span class="font-semibold text-slate-700">{{ $member->upi_id ?? '--' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Documents Grid --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-base font-bold text-slate-800">KYC Documents</h3>
                            <div class="text-xs text-slate-500">
                                <span class="font-semibold">ID Proofs:</span>
                                Aadhar: {{ $member->adhar_no ?? 'N/A' }} | PAN: {{ $member->pan_no ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            {{-- Passbook --}}
                            <div
                                class="group relative rounded-xl bg-slate-50 border border-slate-100 p-2 aspect-square flex flex-col items-center justify-center text-center hover:shadow-md transition-all cursor-pointer overflow-hidden">
                                @if ($member->passbook_photo)
                                    <img src="{{ asset('storage/' . $member->passbook_photo) }}"
                                        class="absolute inset-0 w-full h-full object-cover rounded-lg group-hover:scale-105 transition-transform duration-500">
                                    <div
                                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <span
                                            class="text-white text-xs font-bold border border-white/50 px-2 py-1 rounded">View</span>
                                    </div>
                                @else
                                    <i class="fas fa-file-invoice text-slate-300 text-2xl mb-2"></i>
                                    <span class="text-xs text-slate-400 font-medium">No Passbook</span>
                                @endif
                                <span
                                    class="absolute bottom-2 left-2 bg-white/90 px-2 py-0.5 rounded text-[10px] font-bold text-slate-600 shadow-sm pointer-events-none">Passbook</span>
                            </div>

                            {{-- Aadhar --}}
                            <div
                                class="group relative rounded-xl bg-slate-50 border border-slate-100 p-2 aspect-square flex flex-col items-center justify-center text-center hover:shadow-md transition-all cursor-pointer overflow-hidden">
                                @if ($member->adhar_photo)
                                    <img src="{{ asset('storage/' . $member->adhar_photo) }}"
                                        class="absolute inset-0 w-full h-full object-cover rounded-lg group-hover:scale-105 transition-transform duration-500">
                                    <div
                                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <span
                                            class="text-white text-xs font-bold border border-white/50 px-2 py-1 rounded">View</span>
                                    </div>
                                @else
                                    <i class="fas fa-id-card text-slate-300 text-2xl mb-2"></i>
                                    <span class="text-xs text-slate-400 font-medium">No Aadhar</span>
                                @endif
                                <span
                                    class="absolute bottom-2 left-2 bg-white/90 px-2 py-0.5 rounded text-[10px] font-bold text-slate-600 shadow-sm pointer-events-none">Aadhar</span>
                            </div>

                            {{-- PAN --}}
                            <div
                                class="group relative rounded-xl bg-slate-50 border border-slate-100 p-2 aspect-square flex flex-col items-center justify-center text-center hover:shadow-md transition-all cursor-pointer overflow-hidden">
                                @if ($member->pan_photo)
                                    <img src="{{ asset('storage/' . $member->pan_photo) }}"
                                        class="absolute inset-0 w-full h-full object-cover rounded-lg group-hover:scale-105 transition-transform duration-500">
                                    <div
                                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <span
                                            class="text-white text-xs font-bold border border-white/50 px-2 py-1 rounded">View</span>
                                    </div>
                                @else
                                    <i class="fas fa-id-badge text-slate-300 text-2xl mb-2"></i>
                                    <span class="text-xs text-slate-400 font-medium">No PAN</span>
                                @endif
                                <span
                                    class="absolute bottom-2 left-2 bg-white/90 px-2 py-0.5 rounded text-[10px] font-bold text-slate-600 shadow-sm pointer-events-none">PAN
                                    Card</span>
                            </div>

                            {{-- Nominee Info Box --}}
                            <div class="rounded-xl bg-purple-50 border border-purple-100 p-4 flex flex-col justify-center">
                                <h5 class="text-xs font-bold text-purple-400 uppercase tracking-wider mb-2">Nominee</h5>
                                <p class="text-sm font-bold text-purple-900">{{ $member->nom_name ?? 'N/A' }}</p>
                                <p class="text-xs text-purple-600 mt-1">{{ $member->nom_relation ?? 'Relation: N/A' }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
