@extends('vendorlayouts.layout')
@section('title', 'Company Profile')

@section('container')
    <div class="container mx-auto px-4 sm:px-6 py-6">

        {{-- Page Header --}}
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center gap-3">
                <div class="bg-indigo-50 py-2.5 px-3 rounded-full text-indigo-600 shadow-sm border border-indigo-100">
                    <i class="fas fa-building fa-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Company Profile</h1>
                    <p class="text-slate-500 text-sm font-medium">Manage your business identity and store visibility</p>
                </div>
            </div>
            
            {{-- Edit Trigger Button --}}
            <button onclick="openEditModal()" 
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-semibold shadow-lg shadow-indigo-200 transition-all flex items-center gap-2 active:scale-95">
                <i class="fas fa-edit"></i> Edit Company Profile
            </button>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex justify-between items-center animate-fade-in">
                <div class="flex items-center gap-3">
                    <div class="bg-emerald-100 p-2 rounded-full text-emerald-600">
                        <i class="fas fa-check"></i>
                    </div>
                    <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- MAIN PROFILE CARD (DISPLAY ONLY) --}}
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
            
            {{-- Status Banner --}}
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-700">Business Details</h2>
                
                <div class="flex items-center gap-2 px-4 py-1.5 rounded-full border {{ $vendor->isShopOpen ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-red-50 border-red-200 text-red-700' }}">
                    <span class="relative flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $vendor->isShopOpen ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 {{ $vendor->isShopOpen ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                    </span>
                    <span class="text-xs font-bold uppercase tracking-wide">{{ $vendor->isShopOpen ? 'Shop Open' : 'Shop Closed' }}</span>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    {{-- Left Column --}}
                    <div class="space-y-6">
                        <div class="group">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block">Company Name</label>
                            <div class="flex items-center gap-3 text-slate-700">
                                <i class="fas fa-briefcase text-indigo-400 w-5"></i>
                                <span class="font-bold text-lg">{{ $vendor->company_name }}</span>
                            </div>
                        </div>

                        <div class="group">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block">GST Number</label>
                            <div class="flex items-center gap-3 text-slate-700">
                                <i class="fas fa-file-invoice text-indigo-400 w-5"></i>
                                <span class="font-semibold">{{ $vendor->gst ?? 'Not Available' }}</span>
                            </div>
                        </div>

                        <div class="group">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block">Address</label>
                            <div class="flex items-start gap-3 text-slate-700">
                                <i class="fas fa-map-marker-alt text-indigo-400 w-5 mt-1"></i>
                                <span class="font-medium leading-relaxed">{{ $vendor->company_address }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block">City</label>
                                <div class="flex items-center gap-3 text-slate-700">
                                    <i class="fas fa-city text-indigo-400 w-5"></i>
                                    <span class="font-semibold">{{ $vendor->company_city }}</span>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block">State</label>
                                <div class="flex items-center gap-3 text-slate-700">
                                    <i class="fas fa-map text-indigo-400 w-5"></i>
                                    <span class="font-semibold">{{ $vendor->company_state }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block">Zip Code</label>
                            <div class="flex items-center gap-3 text-slate-700">
                                <i class="fas fa-mail-bulk text-indigo-400 w-5"></i>
                                <span class="font-semibold">{{ $vendor->zip_code }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div id="editModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

        {{-- Modal Panel --}}
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
                    
                    {{-- Modal Header --}}
                    <div class="bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-4 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <i class="fas fa-pen-to-square"></i> Edit Business Profile
                        </h3>
                        <button onclick="closeModal()" class="text-white/80 hover:text-white transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    {{-- Edit Form --}}
                    <form action="{{ route('vendor.company.update') }}" method="POST" class="p-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Company Name --}}
                            <div class="md:col-span-1">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Company Name</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-slate-400"><i class="fas fa-briefcase"></i></span>
                                    <input type="text" name="company_name" value="{{ old('company_name', $vendor->company_name) }}" 
                                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all font-semibold" required>
                                </div>
                            </div>

                            {{-- GST --}}
                            <div class="md:col-span-1">
                                <label class="block text-sm font-bold text-slate-700 mb-2">GST Number</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-slate-400"><i class="fas fa-file-invoice"></i></span>
                                    <input type="text" name="gst" value="{{ old('gst', $vendor->gst) }}" 
                                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all font-semibold">
                                </div>
                            </div>

                            {{-- Address --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Registered Address</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-slate-400"><i class="fas fa-map-marker-alt"></i></span>
                                    <textarea name="company_address" rows="2" required
                                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all font-medium resize-none">{{ old('company_address', $vendor->company_address) }}</textarea>
                                </div>
                            </div>

                            {{-- City --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">City</label>
                                <input type="text" name="company_city" value="{{ old('company_city', $vendor->company_city) }}" 
                                    class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none" required>
                            </div>

                            {{-- State --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">State</label>
                                <input type="text" name="company_state" value="{{ old('company_state', $vendor->company_state) }}" 
                                    class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none" required>
                            </div>

                            {{-- Zip --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Zip Code</label>
                                <input type="text" name="zip_code" value="{{ old('zip_code', $vendor->zip_code) }}" 
                                    class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none" required>
                            </div>

                            {{-- Shop Status (Toggle) --}}
                            <div class="md:col-span-2 bg-slate-50 p-4 rounded-xl border border-slate-200 mt-2">
                                <label class="block text-sm font-bold text-slate-700 mb-3">Shop Visibility Status</label>
                                <div class="flex gap-4">
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="isShopOpen" value="1" class="peer sr-only" {{ $vendor->isShopOpen == 1 ? 'checked' : '' }}>
                                        <div class="rounded-lg border-2 border-slate-200 p-3 text-center peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:bg-white transition-all">
                                            <div class="text-emerald-600 mb-1"><i class="fas fa-store"></i></div>
                                            <div class="font-bold text-sm text-slate-700">Open Shop</div>
                                        </div>
                                    </label>
                                    
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="isShopOpen" value="0" class="peer sr-only" {{ $vendor->isShopOpen == 0 ? 'checked' : '' }}>
                                        <div class="rounded-lg border-2 border-slate-200 p-3 text-center peer-checked:border-red-500 peer-checked:bg-red-50 hover:bg-white transition-all">
                                            <div class="text-red-600 mb-1"><i class="fas fa-store-slash"></i></div>
                                            <div class="font-bold text-sm text-slate-700">Close Shop</div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                        </div>

                        {{-- Footer Actions --}}
                        <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="button" onclick="closeModal()" 
                                class="px-5 py-2.5 bg-white border border-slate-300 rounded-lg text-slate-700 font-bold hover:bg-slate-50 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" 
                                class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 shadow-md transition-all flex items-center gap-2">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        const modal = document.getElementById('editModal');

        function openEditModal() {
            modal.classList.remove('hidden');
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.add('hidden');
            // Enable body scroll
            document.body.style.overflow = 'auto';
        }

        // Close on Escape Key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") closeModal();
        });
    </script>
@endsection