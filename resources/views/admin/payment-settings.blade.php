@extends('layouts.layout')
@section('title', 'Payment Settings')

@section('container')
    <div class="min-h-screen bg-slate-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header Section --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Payment Settings</h1>
                <p class="mt-1 text-sm text-slate-500">Configure your payment receiving details.</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-emerald-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button type="button" onclick="this.closest('.bg-emerald-50').remove()"
                                        class="inline-flex rounded-md p-1.5 text-emerald-500 hover:bg-emerald-100 focus:outline-none">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Validation Error</h3>
                                <ul class="mt-1 list-disc list-inside text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Form Start --}}
                <form method="POST" action="{{ route('admin.payment.update') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="p-6 space-y-6">

                        {{-- UPI ID Section --}}
                        <div>
                            <label for="upi_id" class="block text-sm font-medium text-slate-700 mb-1">UPI ID (VPA)</label>
                            <div class="relative rounded-md shadow-sm max-w-lg">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-wallet text-slate-400 text-xs"></i>
                                </div>
                                <input type="text" name="upi_id" id="upi_id"
                                    value="{{ old('upi_id', $admin->upi_id) }}"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-9 sm:text-sm border-slate-300 rounded-md py-2"
                                    placeholder="username@bank" required>
                            </div>
                            <p class="mt-2 text-xs text-slate-500">The UPI ID where users will send money.</p>

                            @if ($admin->upi_id)
                                <div class="mt-2 flex items-center space-x-2">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-800">
                                        Active: {{ $admin->upi_id }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <hr class="border-slate-100">

                        {{-- QR Code Section --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-4">QR Code Image</label>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                                {{-- Upload Area --}}
                                <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-md hover:bg-slate-50 transition-colors cursor-pointer relative"
                                    onclick="document.getElementById('upi_qr').click()">
                                    <div class="space-y-1 text-center">
                                        <i class="fas fa-image text-slate-400 text-3xl mb-2"></i>
                                        <div class="flex text-sm text-slate-600 justify-center">
                                            <span
                                                class="relative bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Upload a file</span>
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500">PNG, JPG, WEBP up to 5MB</p>
                                        <input id="upi_qr" name="upi_qr" type="file" class="sr-only" accept="image/*"
                                            onchange="previewImage(this)">
                                    </div>
                                </div>

                                {{-- Preview Area --}}
                                <div class="flex space-x-4">
                                    {{-- Current Image --}}
                                    <div
                                        class="bg-slate-50 rounded-lg border border-slate-200 p-2  flex flex-col items-center justify-center">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 mb-1">Current</span>
                                        <div class="h-[16/9] w-40">
                                            @if ($admin->upi_qr)
                                                <img src="{{ asset('storage/' . $admin->upi_qr) }}" class="h-full w-full">
                                            @else
                                                <span class="text-xs text-slate-400">None</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- New Preview --}}
                                    <div id="preview-container"
                                        class="hidden bg-indigo-50 rounded-lg border border-indigo-100 p-2 w-32 h-32 flex flex-col items-center justify-center relative">
                                        <span class="text-[10px] uppercase font-bold text-indigo-400 mb-1">New</span>
                                        <img id="preview-img" src="#" class="h-20 w-20 object-contain">
                                        <div
                                            class="absolute -top-2 -right-2 bg-green-500 rounded-full p-1 border-2 border-white">
                                            <i class="fas fa-check text-white text-[10px]"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Footer Actions --}}
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end">
                        <a href="{{ route('admin.dashboard') }}"
                            class="text-sm font-medium text-slate-600 hover:text-slate-900 mr-4">Cancel</a>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Save Changes
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const previewContainer = document.getElementById('preview-container');
            const previewImg = document.getElementById('preview-img');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    // Add simple animation
                    previewContainer.classList.add('animate-pulse');
                    setTimeout(() => previewContainer.classList.remove('animate-pulse'), 500);
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                previewContainer.classList.add('hidden');
            }
        }
    </script>
@endsection
