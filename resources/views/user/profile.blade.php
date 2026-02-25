@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')
@section('title', 'My Profile')
@section('container')

    <div class="min-h-screen bg-slate-50/50 py-4 px-3 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            {{-- Alerts --}}
            <div id="alerts-container">
                @if (session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex justify-between items-center mb-4 text-sm"
                        id="alert-success">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle"></i> <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="document.getElementById('alert-success').remove()" class="hover:text-emerald-900"><i
                                class="fas fa-times"></i></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex justify-between items-center mb-4 text-sm"
                        id="alert-error">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i> <span>{{ session('error') }}</span>
                        </div>
                        <button onclick="document.getElementById('alert-error').remove()" class="hover:text-red-900"><i
                                class="fas fa-times"></i></button>
                    </div>
                @endif
            </div>

            <div class="flex flex-col lg:flex-row gap-6">

                {{-- LEFT COLUMN: Sidebar --}}
                <div class="w-full lg:w-1/3">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

                        <div class="h-24 bg-gradient-to-r from-indigo-500 to-purple-600"></div>

                        <div class="px-4 p-4">

                            <div class="relative flex justify-between items-end -mt-10 mb-4">
                                <div class="relative">
                                    @if ($user->profile_picture)
                                        <img src="{{ asset('storage/profile-pictures/' . basename($user->profile_picture)) }}"
                                            class="w-20 h-20 rounded-full border-4 border-white shadow-sm object-cover bg-white"
                                            alt="Profile">
                                    @else
                                        <div
                                            class="w-20 h-20 rounded-full border-4 border-white shadow-sm bg-slate-100 flex items-center justify-center text-slate-300">
                                            <i class="fas fa-user text-3xl"></i>
                                        </div>
                                    @endif
                                    {{-- Sidebar Status Dot --}}
                                    <span
                                        class="absolute bottom-1 right-1 w-4 h-4 border-2 border-white rounded-full {{ $user->status == 'active' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                </div>
                                <div class="mb-1">
                                    <a href="{{ route('user.profile.edit') }}"
                                        class="text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:underline">
                                        Edit Profile
                                    </a>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h2 class="text-lg font-bold text-slate-800 leading-tight">{{ $user->name }}</h2>
                                <p class="text-xs text-slate-500 font-mono mt-1 truncate">{{ $user->email }}</p>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span
                                        class="bg-slate-100 text-slate-600 text-[10px] uppercase font-bold px-2 py-1 rounded border border-slate-200">
                                        ID: {{ $user->ulid }}
                                    </span>
                                    <span
                                        class="bg-slate-100 text-slate-600 text-[10px] uppercase font-bold px-2 py-1 rounded border border-slate-200">
                                        Sponsor: {{ $user->sponsor_id ?? '--' }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-0 border-t border-slate-100 py-3 ">
                                <div class="text-center border-r border-slate-100 pr-2">
                                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Wallet 1</p>
                                    <p class="text-sm font-bold text-slate-700">₹{{ $user->wallet1_balance ?? 0 }}</p>
                                </div>
                                <div class="text-center pl-2">
                                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Wallet 2</p>
                                    <p class="text-sm font-bold text-emerald-600">₹{{ $user->wallet2_balance ?? 0 }}</p>
                                </div>
                            </div>

                            <div class="border-t border-slate-100 space-y-4 pt-3">
                                <div class="flex items-center justify-between">
                                    <h3
                                        class="text-xs font-bold text-slate-800 uppercase tracking-wide flex items-center gap-1">
                                        <i class="fas fa-gift text-indigo-500"></i> Refer & Earn
                                    </h3>

                                    <div class="flex gap-1.5">
                                        <button onclick="shareOnWhatsApp()"
                                            class="w-6 h-6 rounded bg-green-50 text-green-600 hover:bg-green-100 border border-green-200 flex items-center justify-center transition">
                                            <i class="fab fa-whatsapp text-xs"></i>
                                        </button>
                                        <button onclick="shareOnFacebook()"
                                            class="w-6 h-6 rounded bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-200 flex items-center justify-center transition">
                                            <i class="fab fa-facebook-f text-xs"></i>
                                        </button>
                                        <button onclick="shareOnTelegram()"
                                            class="w-6 h-6 rounded bg-sky-50 text-sky-600 hover:bg-sky-100 border border-sky-200 flex items-center justify-center transition">
                                            <i class="fab fa-telegram-plane text-xs"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex items-center gap-0 shadow-sm rounded-md overflow-hidden">
                                    <div class="flex-1 min-w-0 bg-slate-50 border border-r-0 border-slate-300 py-2 px-3">
                                        <p id="referralLink" class="text-xs text-slate-600 font-mono truncate select-all">
                                            {{ url('/register') }}?sponsor_id={{ $user->ulid }}
                                        </p>
                                    </div>
                                    <button onclick="copyReferralLink(this)"
                                        class="bg-white border border-slate-300 px-3 py-2 hover:bg-slate-50 transition text-indigo-600 font-bold text-xs uppercase tracking-wider">
                                        Copy
                                    </button>
                                </div>
                            </div>

                            @if ($user->is_vendor === 0)
                                <div class="pt-4 border-t border-slate-100">
                                    <a href="{{ route('user.become_vendor') }}"
                                        class="block w-full py-2.5 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold uppercase tracking-wider text-center rounded-lg shadow-sm transition-transform active:scale-[0.98]">
                                        Become a Vendor
                                    </a>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Main Content --}}
                <div class="w-full h-full lg:w-2/3">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 min-h-[500px]">

                        <div class="border-b border-slate-100 px-2 pt-2">
                            <nav class="flex space-x-4 overflow-x-auto scrollbar-hide" aria-label="Tabs">
                                <button onclick="switchTab('profile')" id="tab-btn-profile"
                                    class="tab-btn border-b-2 border-indigo-500 text-indigo-600 px-4 py-3 text-sm font-medium whitespace-nowrap flex items-center gap-2 transition-colors">
                                    Profile Info
                                </button>
                                <button onclick="switchTab('kyc')" id="tab-btn-kyc"
                                    class="tab-btn border-b-2 border-transparent text-slate-500 hover:text-slate-700 px-4 py-3 text-sm font-medium whitespace-nowrap flex items-center gap-2 transition-colors">
                                    KYC Docs
                                </button>
                                <button onclick="switchTab('nominee')" id="tab-btn-nominee"
                                    class="tab-btn border-b-2 border-transparent text-slate-500 hover:text-slate-700 px-4 py-3 text-sm font-medium whitespace-nowrap flex items-center gap-2 transition-colors">
                                    Nominee
                                </button>
                                <button onclick="switchTab('bank')" id="tab-btn-bank"
                                    class="tab-btn border-b-2 border-transparent text-slate-500 hover:text-slate-700 px-4 py-3 text-sm font-medium whitespace-nowrap flex items-center gap-2 transition-colors">
                                    Banking
                                </button>
                            </nav>
                        </div>

                        <div class="p-3.5 sm:p-6">
                            <div id="tab-content-profile" class="tab-content block animate-fade-in">
                                <div class="grid grid-cols-1 gap-3 mb-3">

                                    {{-- 1. ACCOUNT STATUS CARD --}}
                                    @if ($user->status === 'active')
                                        <div
                                            class="bg-emerald-50 border border-emerald-200 rounded-xl p-2 flex items-start gap-2 sm:gap-3">
                                            <div
                                                class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                                                <i class="fas fa-check-circle text-sm sm:text-xl"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-emerald-800 font-bold text-sm">Account Status: ACTIVE</h4>
                                                <p class="text-emerald-600 text-xs mt-1">You are eligible for all incomes
                                                    and rewards.</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-red-50 border border-red-200 rounded-xl p-2 flex items-start gap-3">
                                            <div
                                                class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 text-red-600">
                                                <i class="fas fa-times-circle text-sm sm:text-xl"></i>
                                            </div>
                                            <div class="flex-grow">
                                                <h4 class="text-red-800 font-bold text-sm">Account Status: INACTIVE</h4>
                                                <p class="text-red-600 text-xs mt-1 leading-relaxed">
                                                    Your status is inactive. Purchase any admin product to be active.
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- 2. CAPPING LIMIT CARD --}}
                                    <div
                                        class="bg-blue-50 border border-blue-200 rounded-xl p-2 flex flex-col sm:flex-row items-center justify-between gap-2 sm:gap-3">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 text-blue-600">
                                                <i class="fas fa-chart-line text-sm sm:text-xl"></i>
                                            </div>
                                            <div>
                                                <div class="flex items-center justify-between">
                                                    <h4 class="text-blue-800 font-bold text-xs sm:text-sm uppercase tracking-wider">
                                                        Daily Capping Limit</h4>
                                                    <div class="flex items-baseline gap-1">
                                                        <span class="text-xl sm:text-2xl font-bold text-blue-900">
                                                            ₹{{ number_format($user->capping_limit ?? 0, 2) }}
                                                        </span>
                                                        <span class="text-xs text-blue-500 font-medium">/ day</span>
                                                    </div>
                                                </div>
                                                <p class="text-blue-600 text-[9px] sm:text-[11px] mt-2 leading-relaxed max-w-md">
                                                    Your daily earning limit from Level or Repurchase incomes is
                                                    <strong>₹{{ number_format($user->capping_limit ?? 0, 0) }}</strong>.
                                                    Purchase the more bigger admin package to increase the capping limit.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Personal
                                    Details
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                                    <div class="border-b border-slate-50 pb-2">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Full
                                            Name</label>
                                        <span class="block text-sm font-medium text-slate-800">{{ $user->name }}</span>
                                    </div>
                                    <div class="border-b border-slate-50 pb-2">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Email
                                            Address</label>
                                        <span
                                            class="block text-sm font-medium text-slate-800 break-all">{{ $user->email }}</span>
                                    </div>
                                    <div class="border-b border-slate-50 pb-2">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Phone
                                            Number</label>
                                        <span
                                            class="block text-sm font-medium text-slate-800">{{ $user->phone ?? 'Not provided' }}</span>
                                    </div>
                                    <div class="border-b border-slate-50 pb-2">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Parent
                                            ID</label>
                                        <span
                                            class="block text-sm font-medium text-slate-800">{{ $user->parent_id ?? 'N/A' }}</span>
                                    </div>
                                    <div class="md:col-span-2 border-b border-slate-50 pb-2">
                                        <label
                                            class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Address</label>
                                        <span class="block text-sm font-medium text-slate-800">
                                            {{ $user->address ?? 'Not provided' }}{{ $user->state ? ', ' . $user->state : '' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div id="tab-content-kyc" class="tab-content hidden animate-fade-in">
                                <div class="space-y-6">
                                    <div
                                        class="flex flex-col sm:flex-row gap-4 p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                                        <div class="sm:w-1/3">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Document</p>
                                            <p class="text-sm font-bold text-slate-700 mb-1">Aadhaar Card</p>
                                            <p
                                                class="text-xs font-mono text-slate-500 bg-white border px-2 py-1 rounded inline-block">
                                                {{ $user->adhar_no ?? 'N/A' }}</p>
                                        </div>
                                        <div class="sm:w-2/3">
                                            <div
                                                class="h-40 w-full bg-white rounded-lg overflow-hidden border border-slate-200 flex items-center justify-center">
                                                @if ($user->adhar_photo)
                                                    <img src="{{ asset('storage/aadhaar-documents/' . basename($user->adhar_photo)) }}"
                                                        class="w-full h-full object-cover" alt="Aadhaar">
                                                @else
                                                    <span
                                                        class="text-xs text-slate-400 flex flex-col items-center gap-1"><i
                                                            class="fas fa-image text-xl"></i> No Upload</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="flex flex-col sm:flex-row gap-4 p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                                        <div class="sm:w-1/3">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Document</p>
                                            <p class="text-sm font-bold text-slate-700 mb-1">PAN Card</p>
                                            <p
                                                class="text-xs font-mono text-slate-500 bg-white border px-2 py-1 rounded inline-block">
                                                {{ $user->pan_no ?? 'N/A' }}</p>
                                        </div>
                                        <div class="sm:w-2/3">
                                            <div
                                                class="h-40 w-full bg-white rounded-lg overflow-hidden border border-slate-200 flex items-center justify-center">
                                                @if ($user->pan_photo)
                                                    <img src="{{ asset('storage/pan-documents/' . basename($user->pan_photo)) }}"
                                                        class="w-full h-full object-cover" alt="PAN">
                                                @else
                                                    <span
                                                        class="text-xs text-slate-400 flex flex-col items-center gap-1"><i
                                                            class="fas fa-image text-xl"></i> No Upload</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="tab-content-nominee" class="tab-content hidden animate-fade-in">
                                <div
                                    class="bg-indigo-50 rounded-xl p-8 text-center border border-indigo-100 max-w-md mx-auto mt-4">
                                    <div
                                        class="w-14 h-14 bg-white text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                                        <i class="fas fa-user-shield text-xl"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-800 mb-6">Nominee Details</h4>
                                    <div class="text-left space-y-3">
                                        <div
                                            class="bg-white p-3 rounded-lg border border-indigo-100 flex justify-between items-center">
                                            <span class="text-[10px] uppercase text-slate-400 font-bold">Name</span>
                                            <span
                                                class="text-sm font-medium text-slate-700">{{ $user->nom_name ?? 'Not Assigned' }}</span>
                                        </div>
                                        <div
                                            class="bg-white p-3 rounded-lg border border-indigo-100 flex justify-between items-center">
                                            <span
                                                class="text-[10px] uppercase text-slate-400 font-bold">Relationship</span>
                                            <span
                                                class="text-sm font-medium text-slate-700">{{ $user->nom_relation ?? 'Not Assigned' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="tab-content-bank" class="tab-content hidden animate-fade-in">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Account
                                            Information</h4>
                                        <div class="space-y-4">
                                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                                <span
                                                    class="text-[10px] text-slate-400 uppercase font-bold block mb-1">Bank
                                                    Name</span>
                                                <p class="text-sm font-medium text-slate-800">
                                                    {{ $user->bank_name ?? 'N/A' }}</p>
                                            </div>
                                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                                <span
                                                    class="text-[10px] text-slate-400 uppercase font-bold block mb-1">Account
                                                    No</span>
                                                <p class="text-sm font-mono text-slate-800">
                                                    {{ $user->account_no ?? 'N/A' }}</p>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                                    <span
                                                        class="text-[10px] text-slate-400 uppercase font-bold block mb-1">IFSC</span>
                                                    <p class="text-sm font-mono text-slate-800">
                                                        {{ $user->ifsc_code ?? 'N/A' }}</p>
                                                </div>
                                                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                                    <span
                                                        class="text-[10px] text-slate-400 uppercase font-bold block mb-1">UPI
                                                        ID</span>
                                                    <p class="text-sm font-medium text-slate-800 truncate">
                                                        {{ $user->upi_id ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Proof
                                            Document</h4>
                                        <div
                                            class="rounded-xl border border-slate-200 overflow-hidden h-56 bg-slate-50 flex items-center justify-center">
                                            @if ($user->passbook_photo)
                                                <img src="{{ asset('storage/passbook-photos/' . basename($user->passbook_photo)) }}"
                                                    class="w-full h-full object-cover" alt="Passbook">
                                            @else
                                                <div class="text-center text-slate-400">
                                                    <i class="fas fa-file-invoice text-3xl mb-2 opacity-50"></i>
                                                    <p class="text-xs">No passbook photo</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // COPY FUNCTION
        function copyReferralLink(btn) {
            const text = document.getElementById('referralLink').innerText.trim();
            const originalText = btn.innerText;

            navigator.clipboard.writeText(text).then(() => {
                btn.innerHTML = '<span class="text-emerald-600 font-bold">Copied!</span>';
                setTimeout(() => {
                    btn.innerHTML = '<span>Copy</span>';
                }, 2000);
            }).catch(err => {
                console.error('Copy failed', err);
            });
        }

        // TAB FUNCTION
        function switchTab(tabName) {
            // Hide Contents
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById('tab-content-' + tabName).classList.remove('hidden');

            // Update Buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-indigo-500', 'text-indigo-600');
                btn.classList.add('border-transparent', 'text-slate-500', 'hover:text-slate-700');
            });
            const activeBtn = document.getElementById('tab-btn-' + tabName);
            activeBtn.classList.remove('border-transparent', 'text-slate-500', 'hover:text-slate-700');
            activeBtn.classList.add('border-indigo-500', 'text-indigo-600');
        }

        // SHARE FUNCTIONS
        const getLink = () => document.getElementById('referralLink').innerText.trim();
        const getMsg = () => `Join me here: ${getLink()}`;

        function shareOnWhatsApp() {
            window.open(`https://wa.me/?text=${encodeURIComponent(getMsg())}`, '_blank');
        }

        function shareOnFacebook() {
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(getLink())}`, '_blank');
        }

        function shareOnTelegram() {
            window.open(`https://t.me/share/url?url=${encodeURIComponent(getLink())}&text=${encodeURIComponent(getMsg())}`,
                '_blank');
        }
    </script>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(2px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.2s ease-out forwards;
        }
    </style>

@endsection
