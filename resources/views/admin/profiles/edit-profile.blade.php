@extends('layouts.layout')
@section('title', 'Edit Profile')
@section('container')

    <div class="min-h-screen bg-slate-50 pb-10 sm:px-3 lg:px-6 font-sans">
        <div class="max-w-5xl mx-auto">

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100">

                <div class="h-28 bg-[#0D6EFD] relative">
                    <div class="absolute inset-0 bg-gradient-to-b from-black/10 to-transparent"></div>
                </div>

                <div class="px-6 sm:px-8 pb-8">
                    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="relative flex flex-col sm:flex-row items-end -mt-12 sm:-mt-16 mb-6 gap-6">
                            
                            <div class="relative flex-shrink-0 group">
                                @if ($user->profile_picture)
                                    <div class="h-32 w-32 rounded-full ring-4 ring-white shadow-lg overflow-hidden bg-white">
                                        <img id="profile-pic-preview" src="{{ asset('storage/profile-pictures/' . basename($user->profile_picture)) }}"
                                            class="h-full w-full object-cover" alt="Profile Picture">
                                    </div>
                                @else
                                    <div id="profile-pic-preview-container" class="h-32 w-32 rounded-full ring-4 ring-white shadow-lg bg-slate-100 flex items-center justify-center text-slate-400 overflow-hidden relative">
                                        <i class="fa fa-user text-5xl" id="profile-pic-icon"></i>
                                        <img id="profile-pic-preview" src="" class="h-full w-full object-cover hidden absolute inset-0" alt="Profile Picture">
                                    </div>
                                @endif

                                <label class="absolute bottom-1 right-1 h-10 w-10 bg-[#0D6EFD] hover:bg-blue-700 text-white border-4 border-white rounded-full flex items-center justify-center cursor-pointer shadow-md transition-transform hover:scale-110" title="Change Photo">
                                    <i class="fa fa-camera text-sm"></i>
                                    <input type="file" class="hidden" id="profile_picture" name="profile_picture" accept="image/*">
                                </label>
                            </div>

                            <div class="flex-1 text-center sm:text-left pt-2 sm:pt-0 pb-2">
                                <h1 class="text-2xl font-bold text-slate-800">Edit Profile</h1>
                                <p class="text-sm font-medium text-slate-500">Update your personal details and password</p>
                            </div>

                        </div>
                        
                        @error('profile_picture')
                            <div class="text-red-500 text-sm mb-4 text-center sm:text-left font-medium">{{ $message }}</div>
                        @enderror

                        <div class="border-t border-slate-100 my-6"></div>

                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Personal Information</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">Full Name</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fa fa-user text-slate-400"></i>
                                        </div>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                            class="form-control pl-10 py-2.5 rounded-lg border-slate-200 focus:ring-2 focus:ring-[#0D6EFD]/20 focus:border-[#0D6EFD] bg-white @error('name') is-invalid @enderror">
                                    </div>
                                    @error('name')
                                        <div class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">Email Address</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fa fa-envelope text-slate-400"></i>
                                        </div>
                                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                            class="form-control pl-10 py-2.5 rounded-lg border-slate-200 focus:ring-2 focus:ring-[#0D6EFD]/20 focus:border-[#0D6EFD] bg-white @error('email') is-invalid @enderror">
                                    </div>
                                    @error('email')
                                        <div class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 my-6"></div>

                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Security (Change Password)</h3>
                            <p class="text-sm text-slate-500 mb-3">Leave blank if you don't want to change your password.</p>

                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    
                                    <div class="md:col-span-2">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">Current Password</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fa fa-lock text-slate-400"></i>
                                            </div>
                                            <input type="password" name="current_password" placeholder="Enter current password"
                                                class="form-control pl-10 py-2.5 rounded-lg border-slate-200 focus:ring-2 focus:ring-[#0D6EFD]/20 focus:border-[#0D6EFD] bg-white @error('current_password') is-invalid @enderror">
                                        </div>
                                        @error('current_password')
                                            <div class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">New Password</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fa fa-key text-slate-400"></i>
                                            </div>
                                            <input type="password" name="password" placeholder="New password"
                                                class="form-control pl-10 py-2.5 rounded-lg border-slate-200 focus:ring-2 focus:ring-[#0D6EFD]/20 focus:border-[#0D6EFD] bg-white @error('password') is-invalid @enderror">
                                        </div>
                                        @error('password')
                                            <div class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">Confirm New Password</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fa fa-check-circle text-slate-400"></i>
                                            </div>
                                            <input type="password" name="password_confirmation" placeholder="Confirm new password"
                                                class="form-control pl-10 py-2.5 rounded-lg border-slate-200 focus:ring-2 focus:ring-[#0D6EFD]/20 focus:border-[#0D6EFD] bg-white">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 my-8"></div>

                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <a href="{{ route('admin.profile') }}" class="w-full sm:w-auto px-6 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition-colors flex items-center justify-center">
                                <i class="fa fa-arrow-left mr-2"></i> Back
                            </a>
                            <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-[#0D6EFD] hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all active:scale-95 flex items-center justify-center">
                                <i class="fa fa-save mr-2"></i> Save Changes
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Smooth Image Preview Logic
        document.getElementById('profile_picture').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewImg = document.getElementById('profile-pic-preview');
                    const icon = document.getElementById('profile-pic-icon');
                    
                    if(icon) {
                        icon.classList.add('hidden'); // Hide the default icon
                    }
                    
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden'); // Reveal the image tag
                }
                reader.readAsDataURL(file);
            }
        });

        // Form Validation for Passwords
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const currentPass = form.querySelector('[name="current_password"]').value;
                const newPass = form.querySelector('[name="password"]').value;
                const confirmPass = form.querySelector('[name="password_confirmation"]').value;

                if (currentPass && (!newPass || !confirmPass)) {
                    e.preventDefault();
                    alert('Please fill both new password and confirmation fields if you want to change your password.');
                } else if (newPass && newPass !== confirmPass) {
                    e.preventDefault();
                    alert('New password and confirmation do not match.');
                }
            });
        });
    </script>

@endsection