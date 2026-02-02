@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')
@section('title', 'Edit Profile')
@section('container')

    <div class="min-h-screen bg-slate-50/50 py-4 px-4 sm:px-6 lg:px-8 font-sans">
        <div class="max-w-5xl mx-auto">

            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">

                <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="sticky top-0 z-10 bg-slate-50/95 backdrop-blur-sm border-b border-slate-200 px-4 sm:px-8">
                        <nav class="-mb-px flex space-x-8 overflow-x-auto scrollbar-hide" aria-label="Tabs">

                            <button type="button" onclick="switchEditTab('personal')" id="nav-personal"
                                class="tab-btn whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors duration-200 border-indigo-600 text-indigo-600">
                                <i class="fas fa-user-circle mr-2"></i> Personal
                            </button>

                            <button type="button" onclick="switchEditTab('kyc')" id="nav-kyc"
                                class="tab-btn whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors duration-200 border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700">
                                <i class="fas fa-id-card mr-2"></i> KYC Docs
                            </button>

                            <button type="button" onclick="switchEditTab('nominee')" id="nav-nominee"
                                class="tab-btn whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors duration-200 border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700">
                                <i class="fas fa-user-shield mr-2"></i> Nominee
                            </button>

                            <button type="button" onclick="switchEditTab('bank')" id="nav-bank"
                                class="tab-btn whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors duration-200 border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700">
                                <i class="fas fa-university mr-2"></i> Bank
                            </button>

                            <button type="button" onclick="switchEditTab('password')" id="nav-password"
                                class="tab-btn whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors duration-200 border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700">
                                <i class="fas fa-lock mr-2"></i> Security
                            </button>
                        </nav>
                    </div>

                    <div class="p-6 sm:p-10 min-h-[400px]">

                        <div id="content-personal" class="tab-section block animate-fade-in">

                            <div class="flex flex-col lg:flex-row gap-8 items-start">

                                <div class="w-full lg:flex-1 order-2 lg:order-1">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Full
                                                Name</label>
                                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                                            @error('name')
                                                <p class="text-red-500 text-xs">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Email
                                                Address</label>
                                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                                            @error('email')
                                                <p class="text-red-500 text-xs">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Phone
                                                Number</label>
                                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                                            @error('phone')
                                                <p class="text-red-500 text-xs">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">City /
                                                State</label>
                                            <input type="text" name="state" value="{{ old('state', $user->state) }}"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                                            @error('state')
                                                <p class="text-red-500 text-xs">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="md:col-span-2 space-y-1.5">
                                            <label
                                                class="text-xs font-bold text-slate-500 uppercase tracking-wide">Address</label>
                                            <textarea name="address" rows="3"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none resize-none">{{ old('address', $user->address) }}</textarea>
                                            @error('address')
                                                <p class="text-red-500 text-xs">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full lg:w-72 flex-none order-1 lg:order-2">
                                    <div
                                        class="bg-slate-50 rounded-2xl p-6 border border-slate-200 flex flex-col items-center text-center sticky top-28">

                                        <div
                                            class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-md mb-4 bg-white">
                                            <img id="profile-pic-preview"
                                                src="{{ $user->profile_picture ? asset('storage/profile-pictures/' . basename($user->profile_picture)) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff' }}"
                                                class="w-full h-full object-cover">
                                        </div>

                                        <label for="profile_picture"
                                            class="px-6 py-2 text-sm font-bold text-indigo-600 bg-white border-2 border-indigo-100 rounded-xl cursor-pointer hover:bg-indigo-50 hover:border-indigo-200 transition-all shadow-sm active:scale-95">
                                            <i class="fas fa-camera mr-2"></i> Upload Photo
                                        </label>
                                        <input type="file" id="profile_picture" name="profile_picture" accept="image/*"
                                            class="hidden">

                                        <p class="text-xs text-slate-400 mt-3">Allowed *.jpeg, *.jpg, *.png, *.gif</p>
                                        @error('profile_picture')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div id="content-kyc" class="tab-section hidden animate-fade-in">
                            <div class="space-y-8">
                                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                                    <h3 class="text-sm font-bold text-indigo-600 uppercase mb-4 flex items-center gap-2">
                                        <i class="fas fa-fingerprint"></i> Aadhaar Details
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Aadhaar
                                                Number</label>
                                            <input type="text" name="adhar_no"
                                                value="{{ old('adhar_no', $user->adhar_no) }}"
                                                class="w-full bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none">
                                            @error('adhar_no')
                                                <p class="text-red-500 text-xs">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="space-y-1.5">
                                            <label
                                                class="text-xs font-bold text-slate-500 uppercase tracking-wide">Document
                                                Photo</label>
                                            <input type="file" name="adhar_photo"
                                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200 cursor-pointer">
                                            @if ($user->adhar_photo)
                                                <a href="{{ asset('storage/aadhaar-documents/' . basename($user->adhar_photo)) }}"
                                                    target="_blank"
                                                    class="text-xs text-indigo-600 hover:underline flex items-center gap-1 mt-2">
                                                    <i class="fas fa-external-link-alt"></i> Current Document
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                                    <h3 class="text-sm font-bold text-indigo-600 uppercase mb-4 flex items-center gap-2">
                                        <i class="fas fa-id-badge"></i> PAN Details
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">PAN
                                                Number</label>
                                            <input type="text" name="pan_no"
                                                value="{{ old('pan_no', $user->pan_no) }}"
                                                class="w-full bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none">
                                            @error('pan_no')
                                                <p class="text-red-500 text-xs">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="space-y-1.5">
                                            <label
                                                class="text-xs font-bold text-slate-500 uppercase tracking-wide">Document
                                                Photo</label>
                                            <input type="file" name="pan_photo"
                                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200 cursor-pointer">
                                            @if ($user->pan_photo)
                                                <a href="{{ asset('storage/pan-documents/' . basename($user->pan_photo)) }}"
                                                    target="_blank"
                                                    class="text-xs text-indigo-600 hover:underline flex items-center gap-1 mt-2">
                                                    <i class="fas fa-external-link-alt"></i> Current Document
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="content-nominee" class="tab-section hidden animate-fade-in">
                            <div class="max-w-2xl mx-auto space-y-6">
                                <div class="bg-indigo-50/50 border border-indigo-100 p-6 rounded-2xl text-center">
                                    <div
                                        class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm text-indigo-600">
                                        <i class="fas fa-user-friends text-xl"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800">Nominee Information</h3>
                                    <p class="text-xs text-slate-500">Ensure your account legacy is secure.</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Nominee
                                            Name</label>
                                        <input type="text" name="nom_name"
                                            value="{{ old('nom_name', $user->nom_name) }}"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                                        @error('nom_name')
                                            <p class="text-red-500 text-xs">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="space-y-1.5">
                                        <label
                                            class="text-xs font-bold text-slate-500 uppercase tracking-wide">Relationship</label>
                                        <input type="text" name="nom_relation"
                                            value="{{ old('nom_relation', $user->nom_relation) }}"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                                        @error('nom_relation')
                                            <p class="text-red-500 text-xs">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="content-bank" class="tab-section hidden animate-fade-in">

                            <div class="flex flex-col lg:flex-row gap-8 items-start">

                                <div class="w-full lg:flex-1 order-2 lg:order-1">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Bank
                                                Name</label>
                                            <input type="text" name="bank_name"
                                                value="{{ old('bank_name', $user->bank_name) }}"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                                            @error('bank_name')
                                                <p class="text-red-500 text-xs">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Account
                                                Number</label>
                                            <input type="text" name="account_no"
                                                value="{{ old('account_no', $user->account_no) }}"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                                            @error('account_no')
                                                <p class="text-red-500 text-xs">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">IFSC
                                                Code</label>
                                            <input type="text" name="ifsc_code"
                                                value="{{ old('ifsc_code', $user->ifsc_code) }}"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                                            @error('ifsc_code')
                                                <p class="text-red-500 text-xs">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">UPI
                                                ID</label>
                                            <input type="text" name="upi_id"
                                                value="{{ old('upi_id', $user->upi_id) }}"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                                            @error('upi_id')
                                                <p class="text-red-500 text-xs">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    </div>
                                </div>

                                <div class="w-full lg:w-72 flex-none order-1 lg:order-2">
                                    <div
                                        class="bg-yellow-50/50 rounded-2xl p-6 border border-yellow-100 flex flex-col items-center text-center sticky top-28">

                                        <div
                                            class="w-24 h-24 rounded-xl bg-white border-2 border-yellow-100 flex items-center justify-center mb-4 text-yellow-300 shadow-sm">
                                            @if ($user->passbook_photo)
                                                <img src="{{ asset('storage/passbook-photos/' . basename($user->passbook_photo)) }}"
                                                    class="w-full h-full object-cover rounded-xl">
                                            @else
                                                <i class="fas fa-file-invoice-dollar text-4xl"></i>
                                            @endif
                                        </div>

                                        <label class="block w-full cursor-pointer">
                                            <span class="sr-only">Upload Passbook</span>
                                            <div
                                                class="px-4 py-2 bg-yellow-100 text-yellow-700 text-sm font-bold rounded-lg hover:bg-yellow-200 transition-colors">
                                                <i class="fas fa-upload mr-2"></i> Upload Proof
                                            </div>
                                            <input type="file" name="passbook_photo" class="hidden" accept="image/*">
                                        </label>

                                        @if ($user->passbook_photo)
                                            <a href="{{ asset('storage/passbook-photos/' . basename($user->passbook_photo)) }}"
                                                target="_blank"
                                                class="text-xs text-yellow-600 hover:underline mt-3 flex items-center gap-1">
                                                <i class="fas fa-external-link-alt"></i> View Full Image
                                            </a>
                                        @else
                                            <p class="text-xs text-yellow-600/60 mt-3">Upload clear photo of
                                                passbook/cheque</p>
                                        @endif

                                        @error('passbook_photo')
                                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div id="content-password" class="tab-section hidden animate-fade-in">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                                <div class="space-y-6">
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Current
                                            Password</label>
                                        <input type="password" name="current_password"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                                        @error('current_password')
                                            <p class="text-red-500 text-xs">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">New
                                            Password</label>
                                        <input type="password" name="password"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                                        @error('password')
                                            <p class="text-red-500 text-xs">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Confirm
                                            Password</label>
                                        <input type="password" name="password_confirmation"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                                    </div>
                                </div>

                                <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 text-sm text-blue-800">
                                    <h4 class="font-bold mb-3 flex items-center gap-2"><i class="fas fa-shield-alt"></i>
                                        Password Requirements</h4>
                                    <ul class="list-disc pl-5 space-y-1 opacity-80 text-xs leading-relaxed">
                                        <li>Minimum 8 characters long</li>
                                        <li>At least one uppercase letter (A-Z)</li>
                                        <li>At least one lowercase letter (a-z)</li>
                                        <li>At least one number (0-9)</li>
                                        <li>At least one special character (@$!%*?&)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div
                        class="bg-slate-50 border-t border-slate-100 p-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <a href="{{ route('user.profile') }}"
                            class="text-slate-500 hover:text-slate-700 text-sm font-medium transition-colors">
                            <i class="fas fa-arrow-left mr-1"></i> Cancel & Go Back
                        </a>
                        <button type="submit"
                            class="w-full sm:w-auto px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 transition-all transform active:scale-95 text-sm">
                            Save Changes
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        // 1. Tab Switching Logic
        function switchEditTab(tabName) {
            // 1. Hide all form sections
            document.querySelectorAll('.tab-section').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('block');
            });

            // 2. Show selected section
            const selectedContent = document.getElementById('content-' + tabName);
            if (selectedContent) selectedContent.classList.remove('hidden');

            // 3. Reset all navigation buttons (Inactive State)
            const navButtons = document.querySelectorAll('.tab-btn');
            navButtons.forEach(btn => {
                // Remove Active Classes
                btn.classList.remove('border-indigo-600', 'text-indigo-600');

                // Add Inactive Classes
                btn.classList.add('border-transparent', 'text-slate-500', 'hover:border-slate-300',
                    'hover:text-slate-700');
            });

            // 4. Set Active State for clicked button
            const activeBtn = document.getElementById('nav-' + tabName);
            if (activeBtn) {
                // Remove Inactive Classes
                activeBtn.classList.remove('border-transparent', 'text-slate-500', 'hover:border-slate-300',
                    'hover:text-slate-700');

                // Add Active Classes
                activeBtn.classList.add('border-indigo-600', 'text-indigo-600');
            }
        }

        // 2. Image Preview Logic
        document.getElementById('profile_picture').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-pic-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }
    </style>

@endsection
