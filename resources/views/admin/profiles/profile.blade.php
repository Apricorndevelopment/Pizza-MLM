@extends('layouts.layout')
@section('title', 'Dashboard')
@section('container')

    <div class="min-h-screen bg-slate-50 pb-10 pt-3 sm:px-6 lg:px-8 font-sans">
        <div class="max-w-5xl mx-auto">

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100">

                <div class="h-32 bg-[#0D6EFD] relative">
                    <div class="absolute inset-0 bg-gradient-to-b from-black/10 to-transparent"></div>
                </div>

                <div class="px-6 sm:px-8 pb-8">
                    <div class="relative flex flex-col sm:flex-row items-end -mt-12 sm:-mt-16 mb-6 gap-6">

                        <div class="relative flex-shrink-0">
                            @if ($user->profile_picture)
                                <div class="h-32 w-32 rounded-full ring-4 ring-white shadow-lg overflow-hidden bg-white">
                                    <img src="{{ asset('storage/profile-pictures/' . basename($user->profile_picture)) }}"
                                        class="h-full w-full object-cover" alt="Profile Picture">
                                </div>
                            @else
                                <div
                                    class="h-32 w-32 rounded-full ring-4 ring-white shadow-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                    <i class="fa fa-user text-5xl"></i>
                                </div>
                            @endif

                            <span
                                class="absolute bottom-2 right-2 h-5 w-5 bg-green-500 border-2 border-white rounded-full"></span>
                        </div>

                        <div class="flex-1 text-center sm:text-left pt-2 sm:pt-0">
                            <h1 class="text-2xl font-bold text-slate-800">Hello, {{ $user->name }}</h1>
                            <p class="text-sm font-medium text-[#0D6EFD]">Administrator</p>
                        </div>

                        <div class="hidden sm:block mb-1">
                            <a href="{{ route('admin.profile.edit') }}"
                                class="inline-flex items-center px-4 py-2 bg-[#0D6EFD] hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-md transition-all duration-200 transform hover:-translate-y-0.5">
                                <i class="fa fa-edit mr-2"></i> Edit Profile
                            </a>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 my-6"></div>

                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Personal Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-blue-50 text-[#0D6EFD] rounded-lg">
                                        <i class="fa fa-id-badge"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-500 font-medium uppercase">Admin ID (AUID)</p>
                                        <p class="text-base font-bold text-slate-800">{{ $user->auid }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-blue-50 text-[#0D6EFD] rounded-lg">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-500 font-medium uppercase">Full Name</p>
                                        <p class="text-base font-bold text-slate-800">{{ $user->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 md:col-span-2">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-blue-50 text-[#0D6EFD] rounded-lg">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-500 font-medium uppercase">Email Address</p>
                                        <p class="text-base font-bold text-slate-800">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 sm:hidden">
                        <a href="{{ route('admin.profile.edit') }}"
                            class="flex w-full items-center justify-center px-4 py-3 bg-[#0D6EFD] hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg transition-colors">
                            <i class="fa fa-edit mr-2"></i> Edit Profile
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
