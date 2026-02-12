@extends('layouts.layout')
@section('title', 'Transfer Coupons')

@section('container')
    <div class="min-h-[85vh] w-full flex items-center justify-center bg-gray-50/50 p-4 sm:p-6">
        
        <div class="w-full max-w-4xl bg-white rounded-3xl shadow-xl overflow-hidden flex flex-col md:flex-row border border-gray-100">
            
            <div class="w-full md:w-2/5 bg-gradient-to-br from-blue-600 to-indigo-800 p-8 md:p-10 text-white flex flex-col justify-between relative overflow-hidden">
                
                <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:20px_20px]"></div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-500 rounded-full mix-blend-multiply filter blur-2xl opacity-50"></div>
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-500 rounded-full mix-blend-multiply filter blur-2xl opacity-50"></div>

                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 shadow-inner border border-white/30">
                        <i class="fas fa-exchange-alt text-2xl text-white"></i>
                    </div>
                    
                    <h2 class="text-3xl font-bold tracking-tight leading-tight mb-2">Transfer Coupons</h2>
                    <p class="text-blue-100 font-medium text-lg opacity-90">Global Distribution</p>
                    <p class="mt-4 text-blue-200 text-sm leading-relaxed opacity-80">
                        Use this tool to instantly credit promotional coupons to all registered user wallets in the system.
                    </p>
                </div>

                <div class="relative z-10 mt-8 md:mt-0">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-black/20 backdrop-blur-sm border border-white/10 text-xs font-medium text-blue-50">
                        <i class="fas fa-shield-alt"></i> Secure Admin Action
                    </div>
                </div>
            </div>

            <div class="w-full md:w-3/5 p-8 md:p-10 bg-white">
                
                @if (session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex items-start animate-fade-in-up">
                        <div class="flex-shrink-0 mt-0.5">
                            <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-bold text-emerald-800">Success</h3>
                            <p class="text-sm text-emerald-600 mt-0.5">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                <div class="mb-8 bg-amber-50 border-l-[3px] border-amber-400 p-4 rounded-r-lg">
                    <div class="flex gap-3">
                        <i class="fas fa-exclamation-triangle text-amber-500 mt-1"></i>
                        <div>
                            <h3 class="text-sm font-bold text-amber-900">Mass Distribution Warning</h3>
                            <p class="text-sm text-amber-700 mt-1 leading-snug">
                                You are about to send coupons to <span class="font-extrabold underline decoration-amber-500/50">every registered user</span>. This action is irreversible.
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.coupons.process_transfer') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-5">
                        <div class="space-y-1.5">
                            <label for="coupon_quantity" class="text-sm font-bold text-gray-700 ml-1">
                                Quantity per User
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-ticket-alt text-gray-400"></i>
                                </div>
                                <input type="number" 
                                       name="coupon_quantity" 
                                       id="coupon_quantity" 
                                       class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium" 
                                       placeholder="e.g. 10" 
                                       required 
                                       min="1">
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" 
                                    onclick="return confirm('⚠️ FINAL CONFIRMATION:\n\nAre you sure you want to distribute coupons to ALL users?');"
                                    class="group w-full relative flex items-center justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gray-900 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all duration-300 shadow-lg hover:shadow-blue-500/30">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <i class="fas fa-paper-plane text-gray-500 group-hover:text-blue-200 transition-colors"></i>
                                </span>
                                Transfer Coupons Now
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection