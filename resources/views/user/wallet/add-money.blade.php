@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')
@section('title', 'Add Money to Wallet')

@section('container')
    <div class="min-h-screen bg-slate-50/50 py-6 sm:py-10 font-sans">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-3 bg-red-50 border border-red-200 rounded-xl p-4 flex gap-3 animate-fade-in-up">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-red-800">Please correct the following errors:</h3>
                        <ul class="list-disc list-inside text-sm text-red-700 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 mb-8">

                {{-- LEFT SIDE: Payment Details Card --}}
                <div class="lg:col-span-5 order-1">
                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-lg sm:shadow-xl overflow-hidden border border-slate-100 h-full flex flex-col relative group">

                        {{-- Decorative Gradient --}}
                        <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-blue-600 to-indigo-600"></div>

                        <div class="p-3.5 sm:p-6 flex-1 flex flex-col items-center justify-center text-center">
                            
                            {{-- QR Code Section --}}
                            <div class="mb-3.5 sm:mb-6">
                                <span class="inline-flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-indigo-50 text-indigo-600 mb-2">
                                    <i class="fas fa-qrcode text-lg sm:text-xl"></i>
                                </span>
                                <h2 class="text-lg sm:text-xl font-bold text-slate-800">Scan to Pay</h2>
                                <p class="text-xs sm:text-sm text-slate-500 mt-1">Use GPay, PhonePe, or Paytm</p>
                            </div>

                            {{-- QR Code Container --}}
                            <div class="relative bg-white p-2 rounded-xl sm:rounded-2xl border-2 border-slate-100 shadow-sm mb-6 group-hover:border-indigo-100 transition-colors duration-300 max-w-[260px] w-full mx-auto">
                                @if ($admin->upi_qr)
                                    <img src="{{ asset('storage/' . $admin->upi_qr) }}" alt="Admin QR" class="w-full h-auto object-contain rounded-lg">
                                @else
                                    <div class="aspect-square w-full bg-slate-50 flex flex-col items-center justify-center rounded-lg">
                                        <i class="fas fa-image text-slate-300 text-3xl sm:text-4xl mb-2"></i>
                                        <span class="text-xs sm:text-sm text-slate-400 font-medium">QR Unavailable</span>
                                    </div>
                                @endif

                                {{-- Scan markers --}}
                                <div class="absolute top-0 left-0 w-5 h-5 sm:w-6 sm:h-6 border-t-4 border-l-4 border-indigo-600 rounded-tl-lg -mt-1 -ml-1"></div>
                                <div class="absolute top-0 right-0 w-5 h-5 sm:w-6 sm:h-6 border-t-4 border-r-4 border-indigo-600 rounded-tr-lg -mt-1 -mr-1"></div>
                                <div class="absolute bottom-0 left-0 w-5 h-5 sm:w-6 sm:h-6 border-b-4 border-l-4 border-indigo-600 rounded-bl-lg -mb-1 -ml-1"></div>
                                <div class="absolute bottom-0 right-0 w-5 h-5 sm:w-6 sm:h-6 border-b-4 border-r-4 border-indigo-600 rounded-br-lg -mb-1 -mr-1"></div>
                            </div>

                            <div class="w-full max-w-xs space-y-4 text-left">
                                
                                {{-- UPI ID Copy Section --}}
                                @if($admin->upi_id)
                                <div>
                                    <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Official UPI ID</label>
                                    <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-2.5 sm:p-3 hover:border-indigo-300 transition-colors group/copy relative">
                                        <div class="flex-shrink-0 mr-2 sm:mr-3 text-indigo-500">
                                            <i class="fas fa-wallet text-sm sm:text-base"></i>
                                        </div>
                                        <div class="flex-1 font-mono font-semibold text-slate-700 text-sm sm:text-base truncate select-all" id="upi-text">
                                            {{ $admin->upi_id }}
                                        </div>
                                        <button onclick="copyToClipboard('upi-text', this)" type="button" class="text-slate-400 hover:text-indigo-600 focus:outline-none p-1 transition-colors relative" title="Copy to clipboard">
                                            <i class="far fa-copy"></i>
                                            <span class="copy-feedback absolute -top-8 -left-2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded opacity-0 transition-opacity pointer-events-none">Copied!</span>
                                        </button>
                                    </div>
                                </div>
                                @endif

                                {{-- Bank Details Section --}}
                                @if($admin->account_no)
                                <div class="pt-2 border-t border-slate-100">
                                    <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Or Bank Transfer</label>
                                    
                                    <div class="space-y-2 bg-slate-50 rounded-xl p-3 border border-slate-200">
                                        @if($admin->bank_name)
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-slate-500"><i class="fas fa-university w-4 text-center mr-1"></i> Bank:</span>
                                            <span class="font-bold text-slate-700">{{ $admin->bank_name }}</span>
                                        </div>
                                        @endif
                                        
                                        <div class="flex justify-between items-center text-sm group/copy">
                                            <span class="text-slate-500"><i class="fas fa-hashtag w-4 text-center mr-1"></i> A/C No:</span>
                                            <div class="flex items-center gap-2">
                                                <span class="font-mono font-bold text-slate-700 select-all" id="acc-text">{{ $admin->account_no }}</span>
                                                <button onclick="copyToClipboard('acc-text', this)" type="button" class="text-slate-400 hover:text-indigo-600 focus:outline-none relative">
                                                    <i class="far fa-copy text-xs"></i>
                                                    <span class="copy-feedback absolute -top-8 -right-2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded opacity-0 transition-opacity pointer-events-none">Copied!</span>
                                                </button>
                                            </div>
                                        </div>

                                        @if($admin->ifsc_code)
                                        <div class="flex justify-between items-center text-sm group/copy">
                                            <span class="text-slate-500"><i class="fas fa-code-branch w-4 text-center mr-1"></i> IFSC:</span>
                                            <div class="flex items-center gap-2">
                                                <span class="font-mono font-bold text-slate-700 uppercase select-all" id="ifsc-text">{{ $admin->ifsc_code }}</span>
                                                <button onclick="copyToClipboard('ifsc-text', this)" type="button" class="text-slate-400 hover:text-indigo-600 focus:outline-none relative">
                                                    <i class="far fa-copy text-xs"></i>
                                                    <span class="copy-feedback absolute -top-8 -right-2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded opacity-0 transition-opacity pointer-events-none">Copied!</span>
                                                </button>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @if(!$admin->upi_id && !$admin->account_no)
                                    <div class="text-center p-3 bg-amber-50 text-amber-600 rounded-lg text-sm font-medium border border-amber-100">
                                        Payment details are not configured by admin yet.
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT SIDE: Submission Form --}}
                <div class="lg:col-span-7 order-2">
                    <div
                        class="bg-white rounded-2xl sm:rounded-3xl shadow-md sm:shadow-lg border border-slate-100 overflow-hidden">
                        <div class="px-4 py-3 border-b border-slate-100 bg-white">
                            <h2 class="text-lg sm:text-xl font-bold text-slate-800 flex items-center gap-2">
                                <span
                                    class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-blue-100 text-blue-600 text-xs sm:text-sm">
                                    <i class="fas fa-pen"></i>
                                </span>
                                Submit Transaction Details
                            </h2>
                        </div>

                        <div class="p-3">
                            @if (session('success'))
                                <div
                                    class="mb-3 rounded-xl bg-emerald-50 border border-emerald-100 p-4 flex gap-3 animate-fade-in-up">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-emerald-500 mt-0.5 text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-emerald-800">Success</h3>
                                        <p class="text-sm text-emerald-700 mt-0.5">{{ session('success') }}</p>
                                    </div>
                                </div>
                            @endif

                            <form action="{{ route('user.funds.store') }}" method="POST" enctype="multipart/form-data"
                                class="space-y-3">
                                @csrf

                                {{-- Amount Field --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Amount Paid
                                        (₹)</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-slate-400 font-bold">₹</span>
                                        </div>
                                        <input type="number" name="amount" required step="0.01" min="1"
                                            value="{{ old('amount') }}"
                                            class="block w-full pl-10 pr-4 py-3 sm:py-3.5 bg-slate-50 border-transparent rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-semibold text-base sm:text-lg"
                                            placeholder="0.00">
                                    </div>
                                </div>

                                {{-- Transaction ID --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Transaction ID
                                        / UTR</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-receipt text-slate-400 text-sm"></i>
                                        </div>
                                        <input type="text" name="transaction_id" required
                                            value="{{ old('transaction_id') }}"
                                            class="block w-full pl-10 pr-4 py-3 sm:py-3.5 bg-slate-50 border-transparent rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm sm:text-base"
                                            placeholder="e.g. 3245xxxx9123">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                                    {{-- Sender UPI/Account --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Your UPI ID or A/C No.</label>
                                        <input type="text" name="sender_upi_id" required
                                            value="{{ old('sender_upi_id') }}"
                                            class="block w-full px-4 py-3 sm:py-3.5 bg-slate-50 border-transparent rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm sm:text-base"
                                            placeholder="you@bank or Ac number">
                                    </div>

                                    {{-- Payment Method --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Payment App / Method</label>
                                        <div class="relative">
                                            <select name="payment_method"
                                                class="block w-full px-4 py-3 sm:py-3.5 bg-slate-50 border-transparent rounded-xl text-slate-900 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all appearance-none text-sm sm:text-base cursor-pointer">
                                                <option value="PhonePe" {{ old('payment_method') == 'PhonePe' ? 'selected' : '' }}>PhonePe</option>
                                                <option value="Google Pay" {{ old('payment_method') == 'Google Pay' ? 'selected' : '' }}>Google Pay</option>
                                                <option value="Paytm" {{ old('payment_method') == 'Paytm' ? 'selected' : '' }}>Paytm</option>
                                                <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                                <option value="Other" {{ old('payment_method') == 'Other' ? 'selected' : '' }}>Other App</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- File Upload --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">
                                        Payment Screenshot <span class="text-slate-400 font-normal text-xs">(Optional)</span>
                                    </label>
                                    <label class="flex flex-col items-center justify-center w-full h-28 sm:h-32 px-4 transition bg-slate-50 border-2 border-slate-200 border-dashed rounded-xl appearance-none cursor-pointer hover:border-indigo-300 hover:bg-white focus:outline-none group active:scale-[0.99] transition-transform">
                                        <div class="flex flex-col items-center space-y-2 text-center">
                                            <div class="p-2 bg-white rounded-full shadow-sm group-hover:shadow-md transition-shadow">
                                                <i class="fas fa-cloud-upload-alt text-indigo-400 text-xl sm:text-2xl group-hover:text-indigo-600 transition-colors"></i>
                                            </div>
                                            <span class="font-medium text-slate-600 text-xs sm:text-sm group-hover:text-indigo-600">Tap to Upload Screenshot</span>
                                        </div>

                                        {{-- File Name Display --}}
                                        <div id="file-name-display" class="mt-2 text-xs font-semibold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-full hidden truncate max-w-[200px]"></div>
                                        <input type="file" name="receipt_image" id="receipt_image" accept="image/*" class="hidden" onchange="displayFileName(this)">
                                    </label>
                                </div>

                                <button type="submit"
                                    class="w-full py-3.5 sm:py-4 px-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 active:from-indigo-700 active:to-blue-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transform active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2 text-sm sm:text-base">
                                    <span>Submit Request</span>
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- History Table --}}
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 sm:px-8 sm:py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-base sm:text-lg">Transaction History</h3>
                    <span class="hidden sm:inline-block px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-semibold text-slate-500 shadow-sm">
                        Recent Activity
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[600px]">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 border-b border-slate-100">
                                <th class="px-6 sm:px-8 py-3 sm:py-4 text-xs font-bold uppercase tracking-wider">Date</th>
                                <th class="px-6 sm:px-8 py-3 sm:py-4 text-xs font-bold uppercase tracking-wider">Transaction ID</th>
                                <th class="px-6 sm:px-8 py-3 sm:py-4 text-xs font-bold uppercase tracking-wider">Amount</th>
                                <th class="px-6 sm:px-8 py-3 sm:py-4 text-xs font-bold uppercase tracking-wider text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($history as $item)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 sm:px-8 py-3 sm:py-4 text-sm text-slate-600 whitespace-nowrap">
                                        <div class="font-medium">{{ $item->created_at->format('d M, Y') }}</div>
                                        <div class="text-xs text-slate-400">{{ $item->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 sm:px-8 py-3 sm:py-4 text-sm font-mono text-slate-500 whitespace-nowrap group-hover:text-indigo-600 transition-colors">
                                        {{ $item->transaction_id }}
                                    </td>
                                    <td class="px-6 sm:px-8 py-3 sm:py-4 text-sm font-bold text-slate-800 whitespace-nowrap">
                                        ₹{{ number_format($item->amount, 2) }}
                                    </td>
                                    <td class="px-6 sm:px-8 py-3 sm:py-4 text-right whitespace-nowrap">
                                        @if ($item->status == 'approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                                Approved
                                            </span>
                                        @elseif($item->status == 'rejected')
                                            <div class="flex flex-col items-end">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                                    Rejected
                                                </span>
                                                @if ($item->admin_remark)
                                                    <span class="text-[10px] text-red-500 mt-1 max-w-[150px] truncate" title="{{ $item->admin_remark }}">
                                                        Reason: {{ $item->admin_remark }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse mr-1.5"></span>
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 sm:px-8 py-10 sm:py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                                <i class="fas fa-history text-lg sm:text-xl text-slate-300"></i>
                                            </div>
                                            <p class="text-sm">No transactions found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- Script for File Name Display & Copy functionality --}}
    <script>
        function displayFileName(input) {
            const display = document.getElementById('file-name-display');

            if (input.files && input.files[0]) {
                display.textContent = input.files[0].name;
                display.classList.remove('hidden');
            } else {
                display.textContent = '';
                display.classList.add('hidden');
            }
        }

        // Copy to clipboard function
        function copyToClipboard(elementId, button) {
            // Get the text from the element
            var text = document.getElementById(elementId).innerText.trim();
            
            // Create a temporary textarea to copy from
            var tempInput = document.createElement("textarea");
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            
            // Show feedback
            var feedback = button.querySelector('.copy-feedback');
            feedback.style.opacity = '1';
            feedback.style.transform = 'translateY(-5px)';
            
            // Change icon temporarily
            var icon = button.querySelector('i');
            icon.className = 'fas fa-check text-emerald-500';
            
            // Revert after 2 seconds
            setTimeout(function() {
                feedback.style.opacity = '0';
                feedback.style.transform = 'translateY(0)';
                icon.className = 'far fa-copy text-slate-400 hover:text-indigo-600';
            }, 2000);
        }
    </script>
@endsection