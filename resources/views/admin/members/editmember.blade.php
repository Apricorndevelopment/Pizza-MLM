@extends('layouts.layout')
@section('title', 'Edit Member')
@section('container')

    <div class="min-h-screen bg-gray-50/50 pb-8 pt-3 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Modern Card --}}
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">

                {{-- Card Header --}}
                <div class="bg-[#EBF2FE] px-8 py-6 border-b border-blue-100/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-slate-800 tracking-tight">Edit Member Details</h2>
                            <p class="text-xs text-slate-500 font-medium mt-1">Update profile information for
                                {{ $member->name }}</p>
                        </div>
                        <div class="hidden sm:block">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $member->status == 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                Current Status: {{ ucfirst($member->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Form Section --}}
                <div class="p-8">
                    <form action="{{ route('admin.updatemember', $member->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                            {{-- LEFT COLUMN: Personal Info --}}
                            <div class="space-y-6">
                                <h3
                                    class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2 mb-4">
                                    Personal Information</h3>

                                {{-- Name --}}
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Full
                                        Name</label>
                                    <input type="text" id="name" name="name"
                                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                                        value="{{ old('name', $member->name) }}" required>
                                    @error('name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email
                                        Address</label>
                                    <input type="email" id="email" name="email"
                                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                                        value="{{ old('email', $member->email) }}" required>
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                <div>
                                    <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Phone
                                        Number</label>
                                    <input type="text" id="phone" name="phone"
                                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                                        value="{{ old('phone', $member->phone) }}">
                                    @error('phone')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- RIGHT COLUMN: Account & Address --}}
                            <div class="space-y-6">
                                <h3
                                    class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2 mb-4">
                                    Account & Location</h3>

                                {{-- Status --}}
                                <div>
                                    <label for="status" class="block text-sm font-semibold text-slate-700 mb-1.5">Account
                                        Status</label>
                                    <div class="relative">
                                        <select id="status" name="status"
                                            class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none appearance-none cursor-pointer">
                                            <option value="active" {{ $member->status == 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="inactive" {{ $member->status == 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-500">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                {{-- Address --}}
                                <div>
                                    <label for="address"
                                        class="block text-sm font-semibold text-slate-700 mb-1.5">Address</label>
                                    <input type="text" id="address" name="address"
                                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                                        value="{{ old('address', $member->address) }}">
                                    @error('address')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- City/State --}}
                                <div>
                                    <label for="state" class="block text-sm font-semibold text-slate-700 mb-1.5">City /
                                        State</label>
                                    <input type="text" id="state" name="state"
                                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                                        value="{{ old('state', $member->state) }}">
                                    @error('state')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- SECURITY SECTION (Full Width) --}}
                        <div class="mt-8 pt-6 border-t border-slate-100">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Security Settings
                                <span class="text-[10px] normal-case font-normal text-slate-400 ml-1">(Leave blank to keep
                                    current password)</span>
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">New
                                        Password</label>
                                    <input type="password" id="password" name="password"
                                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                                        placeholder="••••••••">
                                    @error('password')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation"
                                        class="block text-sm font-semibold text-slate-700 mb-1.5">Confirm New
                                        Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                                        placeholder="••••••••">
                                </div>
                            </div>
                        </div>

                        {{-- Actions Footer --}}
                        <div class="mt-10 flex items-center justify-between pt-6 border-t border-slate-100">
                            <a href="{{ route('admin.viewmember') }}"
                                class="flex items-center text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i> Cancel & Go Back
                            </a>

                            <button type="submit"
                                class="inline-flex items-center justify-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 transition-all transform active:scale-[0.98]">
                                <i class="fas fa-save mr-2"></i> Save Changes
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
