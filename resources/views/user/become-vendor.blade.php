@extends('userlayouts.layouts')
@section('title', 'Become a Vendor')
@section('container')

    <div class="min-h-screen bg-slate-50/50 py-3 px-3 sm:px-6 lg:px-8 font-sans text-slate-600">
        <div class="max-w-5xl mx-auto">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-1 space-y-4">

                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden relative">
                        <div class="h-20 bg-gradient-to-br from-blue-600 to-indigo-700 relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 rounded-full bg-white opacity-10">
                            </div>
                            <div class="absolute bottom-0 left-0 -ml-6 -mb-6 w-16 h-16 rounded-full bg-white opacity-10">
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <h2 class="text-white font-bold text-lg">Vendor Package</h2>
                            </div>
                        </div>

                        <div class="p-5">
                            <div class="text-center mb-6">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Cost</p>
                                <p class="text-3xl font-extrabold text-slate-800">₹{{ number_format($package->price, 0) }}
                                </p>
                                <p class="text-[10px] text-slate-400 mt-1">One-time activation fee</p>
                            </div>

                            <ul class="space-y-3 mb-6">
                                <li class="flex items-start text-xs">
                                    <i class="fas fa-check-circle text-emerald-500 mt-0.5 mr-2"></i>
                                    <span class="text-slate-600">List unlimited products</span>
                                </li>
                                <li class="flex items-start text-xs">
                                    <i class="fas fa-check-circle text-emerald-500 mt-0.5 mr-2"></i>
                                    <span class="text-slate-600">Dedicated Vendor Dashboard</span>
                                </li>
                                <li class="flex items-start text-xs">
                                    <i class="fas fa-check-circle text-emerald-500 mt-0.5 mr-2"></i>
                                    <span class="text-slate-600">Direct Wallet Payouts</span>
                                </li>
                                <li class="flex items-start text-xs">
                                    <i class="fas fa-check-circle text-emerald-500 mt-0.5 mr-2"></i>
                                    <span class="text-slate-600">Priority Support Access</span>
                                </li>
                            </ul>

                            <div class="bg-slate-50 rounded-lg p-3 border border-slate-100">
                                <div class="flex justify-between items-center text-xs mb-1">
                                    <span class="text-slate-500 font-medium">Your Wallet Balance</span>
                                    <span
                                        class="font-bold {{ Auth::user()->wallet1_balance >= $package->price ? 'text-emerald-600' : 'text-red-600' }}">
                                        ₹{{ number_format(Auth::user()->wallet1_balance, 2) }}
                                    </span>
                                </div>
                                @if (Auth::user()->wallet1_balance < $package->price)
                                    <div
                                        class="mt-2 text-[10px] text-red-500 bg-red-50 px-2 py-1 rounded border border-red-100 text-center">
                                        Insufficient funds. Need
                                        ₹{{ number_format($package->price - Auth::user()->wallet1_balance, 0) }} more.
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col items-center justify-center gap-2 mt-2">
                                <p class="text-xs text-blue-800 ">Need help with activation?</p>
                                <a href="#"
                                    class="text-xs font-bold text-blue-600 hover:text-blue-800 underline">Contact
                                    Support</a>
                            </div>
                        </div>
                    </div>



                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200">

                        <div class="border-b border-slate-100 px-6 py-4">
                            <h3 class="text-sm font-bold text-slate-800">Business Details</h3>
                        </div>

                        <div class="p-6">
                            <form action="{{ route('user.purchase_vendor') }}" method="POST">
                                @csrf

                                <div class="grid grid-cols-1 gap-6 mb-8">
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Company /
                                            Shop Name</label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                                <i class="fas fa-store text-xs"></i>
                                            </div>
                                            <input type="text" name="company_name" required
                                                placeholder="Enter your business name"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 pl-9 pr-4 text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all placeholder-slate-400">
                                        </div>
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">City /
                                            Location</label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                                <i class="fas fa-map-marker-alt text-xs"></i>
                                            </div>
                                            <input type="text" name="city" required placeholder="e.g. Mumbai, Delhi"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 pl-9 pr-4 text-sm focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all placeholder-slate-400">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-lg border border-slate-100 mb-6">
                                    <i class="fas fa-info-circle text-indigo-500 mt-0.5 text-sm"></i>
                                    <p class="text-xs text-slate-500 leading-relaxed">
                                        By clicking "Pay & Activate", you agree to our Vendor Terms & Conditions. The
                                        activation fee of <strong
                                            class="text-slate-700">₹{{ number_format($package->price, 0) }}</strong> will be
                                        deducted from your Wallet 1.
                                    </p>
                                </div>

                                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 pt-2">
                                    <a href="{{ route('user.profile') }}"
                                        class="w-full sm:w-auto px-6 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-50 rounded-lg transition text-center">
                                        Cancel
                                    </a>

                                    @if (Auth::user()->wallet1_balance >= $package->price)
                                        <button type="submit"
                                            onclick="return confirm('Are you sure you want to purchase vendor membership?')"
                                            class="w-full sm:w-auto px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-md shadow-indigo-200 transition-transform active:scale-[0.98] flex items-center justify-center gap-2">
                                            <span>Pay ₹{{ number_format($package->price, 0) }} & Activate</span>
                                            <i class="fas fa-arrow-right"></i>
                                        </button>
                                    @else
                                        <div class="w-full sm:w-auto flex flex-col sm:flex-row gap-3">
                                            <button type="button" disabled
                                                class="px-6 py-2.5 bg-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider rounded-lg border border-slate-200 cursor-not-allowed flex items-center justify-center gap-2">
                                                <i class="fas fa-lock"></i> Insufficient Balance
                                            </button>
                                            <a href="#"
                                                class="px-6 py-2.5 border border-indigo-600 text-indigo-600 hover:bg-indigo-50 text-xs font-bold uppercase tracking-wider rounded-lg text-center transition">
                                                Add Money
                                            </a>
                                        </div>
                                    @endif
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
