@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')
@section('title', 'Auto Pool Progress')

@section('container')
<div class="min-h-screen bg-slate-50 font-sans">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">

         {{-- Page Header --}}
        <div class="flex items-center mb-8">
            <div class="bg-gradient-to-r from-indigo-500 to-blue-600 p-3 rounded-xl mr-4 text-white shadow-md">
                <i class="fas fa-layer-group fa-lg"></i>
            </div>
            <div>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight">Auto Pool Progress</h3>
                <p class="text-slate-500 text-sm font-medium mt-1">Track your global single leg achievements and roadmap</p>
            </div>
        </div>

        {{-- TOP SUMMARY ROW --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
            {{-- Earnings Card --}}
            <div class="bg-gradient-to-r from-indigo-600 to-blue-700 rounded-2xl p-3 text-white shadow-lg relative overflow-hidden flex items-center justify-between">
                <i class="fas fa-trophy absolute -right-4 -bottom-4 text-7xl opacity-10"></i>
                <div class="z-10">
                    <p class="text-indigo-100 text-xs font-bold uppercase tracking-wider mb-1">Total Pool Earnings</p>
                    <h2 class="text-4xl font-black">₹{{ number_format($totalEarnings, 2) }}</h2>
                </div>
                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-2xl z-10">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>

            {{-- Status Card --}}
            <div class="bg-white rounded-2xl p-3.5 shadow-sm border border-slate-200 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Current Status</p>
                    @if($isAllCompleted)
                        <h3 class="text-2xl font-bold text-emerald-600 mb-1"><i class="fas fa-check-double mr-1"></i> All Pools Completed!</h3>
                        <p class="text-xs text-slate-500">You have successfully conquered the entire system.</p>
                    @else
                        <h3 class="text-xl font-bold text-slate-800 mb-1">
                            {{ $tracker->currentCategory->category_name ?? 'No Active Pool' }}
                            @if($tracker->currentPool)
                                <span class="text-indigo-600">- Level {{ $tracker->currentPool->pool_level }}</span>
                            @endif
                        </h3>
                        <p class="text-xs font-bold mt-1">
                            {{-- DYNAMIC STATUS HANDLING --}}
                            @if(!$tracker->current_category_id)
                                <span class="text-slate-500 bg-slate-100 px-2 py-1 rounded-md"><i class="fas fa-shopping-cart mr-1"></i> Purchase a package to enter</span>
                            @else
                                <span class="text-emerald-500 bg-emerald-50 px-2 py-1 rounded-md"><i class="fas fa-bolt mr-1"></i> Active - Collecting PV</span>
                            @endif
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- CURRENT OBJECTIVE SECTION --}}
        @if(!$isAllCompleted)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
                <div class="bg-slate-50 border-b border-slate-100 px-3 py-3">
                    <h4 class="font-bold text-slate-800"><i class="fas fa-crosshairs text-indigo-500 mr-2"></i> Current Objective</h4>
                </div>
                <div class="p-3.5 md:p-6">
                    
                    @if(!$tracker->current_category_id)
                        {{-- STATE 1: WAITING FOR PRODUCT PURCHASE --}}
                        <div class="max-w-2xl mx-auto text-center py-6">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4">
                                <i class="fas fa-shopping-cart text-2xl"></i>
                            </div>
                            <h5 class="text-xl font-bold text-slate-700 mb-2">Ready to enter an Auto Pool?</h5>
                            <p class="text-sm text-slate-500">You are not currently active in any pool. Purchase an eligible Auto Pool Package to instantly unlock its respective category and start earning!</p>
                        </div>
                    @else
                        {{-- STATE 2: ACTIVE IN A CATEGORY --}}
                        
                        {{-- A. Active Pool Progress Bar --}}
                        @if($tracker->currentPool)
                            @php
                                $reqSlPv = $tracker->currentPool->required_pv;
                                $currSlPv = $tracker->single_leg_pv;
                                $slPercent = $reqSlPv > 0 ? min(100, ($currSlPv / $reqSlPv) * 100) : 100;
                                $accumulatedAmount = ($slPercent / 100) * $tracker->currentPool->income;
                            @endphp
                            <div class="max-w-3xl mx-auto text-center mb-8">
                                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-emerald-100 text-emerald-600 mb-3 shadow-inner border-3 border-white ring-2 ring-emerald-50">
                                    <i class="fas fa-bolt text-xl"></i>
                                </div>
                                <h4 class="text-2xl font-black text-slate-800 mb-2">You are in the Auto Pool!</h4>
                                <p class="text-slate-500 mb-6 font-medium">Collecting Single Leg PV from the global network downline.</p>

                                <div class="bg-emerald-50 rounded-2xl p-6 border border-emerald-200 relative overflow-hidden text-left">
                                    <i class="fas fa-chart-line absolute -right-4 -bottom-4 text-emerald-500/10 text-6xl"></i>
                                    <div class="flex justify-between items-end mb-3 relative z-10">
                                        <div>
                                            <p class="text-sm font-bold text-emerald-700 uppercase tracking-wider mb-1">Target Rank: {{ $tracker->currentPool->rank_name }}</p>
                                            <h5 class="text-3xl font-black text-slate-800">Reward: ₹{{ number_format($tracker->currentPool->income, 0) }}</h5>
                                        </div>
                                        <div class="text-right bg-white px-3 py-2 rounded-xl shadow-sm border border-emerald-100">
                                            <span class="text-lg font-black text-emerald-600">{{ $currSlPv }} <span class="text-sm text-slate-500 font-bold">/ {{ $reqSlPv }} PV</span></span>
                                        </div>
                                    </div>
                                    <div class="w-full bg-white rounded-full h-5 mb-2 overflow-hidden shadow-inner relative z-10 border border-emerald-100">
                                        <div class="bg-gradient-to-r from-emerald-400 to-teal-500 h-5 rounded-full transition-all duration-1000 relative" style="width: {{ $slPercent }}%">
                                            <div class="absolute inset-0 bg-white/20" style="background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent); background-size: 1rem 1rem;"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center relative z-10 mt-2">
                                        <span class="text-xs sm:text-sm font-bold text-emerald-800 bg-white/60 px-3 py-1.5 rounded-lg border border-emerald-200 shadow-sm">
                                            <i class="fas fa-coins text-amber-500 mr-1"></i> ₹{{ number_format($accumulatedAmount, 2) }} Accumulated
                                        </span>
                                        <p class="text-sm text-emerald-700 font-bold">{{ number_format($slPercent, 1) }}% Completed</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- B. Category Exit Conditions (Shown while Active) --}}
                        @if($tracker->currentCategory)
                            @php
                                $reqPv = $tracker->currentCategory->pv_required;
                                $reqDir = $tracker->currentCategory->direct_count;
                                $currPv = $tracker->category_repurchase_pv;
                                $currDir = $tracker->category_directs_count;
                                $pvPercent = $reqPv > 0 ? min(100, ($currPv / $reqPv) * 100) : 100;
                                $dirPercent = $reqDir > 0 ? min(100, ($currDir / $reqDir) * 100) : 100;
                            @endphp

                            @if($reqPv > 0 || $reqDir > 0)
                                <div class="border-t border-slate-100 pt-6 mt-4">
                                    <div class="mb-4">
                                        <h5 class="text-sm font-bold text-slate-800"><i class="fas fa-clipboard-check text-amber-500 mr-2"></i> Category Exit Requirements</h5>
                                        <p class="text-xs text-slate-500 mt-1">Complete these conditions to unlock the final reward of this category and become eligible to enter new pools.</p>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        {{-- Repurchase PV Progress --}}
                                        <div class="bg-white border border-indigo-100 rounded-xl p-5 shadow-sm">
                                            <div class="flex justify-between text-sm font-bold mb-3">
                                                <span class="text-slate-700">Repurchase PV</span>
                                                <span class="text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">{{ $currPv }} / {{ $reqPv }} PV</span>
                                            </div>
                                            <div class="w-full bg-slate-100 rounded-full h-3 mb-2 overflow-hidden">
                                                <div class="bg-indigo-500 h-3 rounded-full transition-all duration-1000" style="width: {{ $pvPercent }}%"></div>
                                            </div>
                                            <p class="text-xs text-slate-500 font-bold text-right">{{ number_format($pvPercent, 1) }}% Done</p>
                                        </div>

                                        {{-- Directs Progress --}}
                                        <div class="bg-white border border-blue-100 rounded-xl p-5 shadow-sm">
                                            <div class="flex justify-between text-sm font-bold mb-3">
                                                <span class="text-slate-700">Direct Referrals</span>
                                                <span class="text-blue-600 bg-blue-50 px-2 py-0.5 rounded">{{ $currDir }} / {{ $reqDir }}</span>
                                            </div>
                                            <div class="w-full bg-slate-100 rounded-full h-3 mb-2 overflow-hidden">
                                                <div class="bg-blue-500 h-3 rounded-full transition-all duration-1000" style="width: {{ $dirPercent }}%"></div>
                                            </div>
                                            <p class="text-xs text-slate-500 font-bold text-right">{{ number_format($dirPercent, 1) }}% Done</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                    @endif
                </div>
            </div>
        @endif

        {{-- ROADMAP GRID SECTION --}}
        <div>
            <div class="mb-3 flex items-center justify-between">
                <h3 class="text-2xl font-bold text-slate-800">Auto Pool Roadmap</h3>
            </div>

            @foreach($categories as $category)
                @php
                    $catStatus = 'upcoming'; 
                    if ($tracker->current_category_id == $category->id) {
                        $catStatus = 'current';
                    } elseif (in_array($category->id, $completedCategoryIds)) {
                        $catStatus = 'completed'; 
                    }
                    
                    $catTotalReward = $category->pools->sum('income');
                @endphp

                <div class="mb-3 bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
                    
                    {{-- Category Header --}}
                    <div class="flex flex-wrap items-center justify-between mb-3 gap-3">
                        <div class="flex items-center">
                            <h4 class="text-xl font-bold uppercase tracking-wider {{ $catStatus == 'completed' ? 'text-emerald-600' : ($catStatus == 'current' ? 'text-indigo-600' : 'text-slate-400') }}">
                                {{ $category->category_name }}
                            </h4>
                        </div>
                        
                        <div class="text-sm font-black text-emerald-700 bg-emerald-50 border border-emerald-200 px-4 py-1.5 rounded-xl shadow-sm flex items-center gap-2">
                            <i class="fas fa-gift text-emerald-500"></i> Total Category Reward: ₹{{ number_format($catTotalReward, 0) }}
                        </div>
                    </div>

                    {{-- Package Details --}}
                    @if($category->package)
                        <div class="bg-indigo-50/50 border border-indigo-100 rounded-xl p-3 mb-5 flex flex-wrap gap-x-6 gap-y-2 text-sm font-medium text-indigo-900 shadow-sm">
                            <div class="flex items-center gap-2">
                                <div class="bg-white p-1.5 rounded text-indigo-500 shadow-sm"><i class="fas fa-box-open"></i></div> 
                                Req. Package: <span class="font-bold">{{ $category->package->product_name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="bg-white p-1.5 rounded text-emerald-500 shadow-sm"><i class="fas fa-tag"></i></div> 
                                DP Price: <span class="font-bold">₹{{ number_format($category->package->dp, 0) }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="bg-white p-1.5 rounded text-amber-500 shadow-sm"><i class="fas fa-star"></i></div> 
                                PV: <span class="font-bold">{{ $category->package->pv }}</span>
                            </div>
                        </div>
                    @endif

                    {{-- Pools Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($category->pools as $pool)
                            @php
                                $poolStatus = 'upcoming';
                                
                                if (in_array($pool->id, $earnedPoolIds)) {
                                    $poolStatus = 'completed';
                                } elseif ($catStatus == 'current' && $tracker->current_pool_id == $pool->id) {
                                    $poolStatus = 'active'; // Since we removed condition entry wait, if it's current pool, it's active.
                                }

                                $borderColor = $poolStatus == 'completed' ? 'border-emerald-200 shadow-sm' : ($poolStatus == 'active' ? 'border-indigo-400 shadow-md ring-2 ring-indigo-50 transform -translate-y-1' : 'border-slate-200 opacity-80');
                                $bgHeader = $poolStatus == 'completed' ? 'bg-gradient-to-r from-emerald-500 to-teal-500' : ($poolStatus == 'active' ? 'bg-gradient-to-r from-indigo-500 to-blue-600' : 'bg-slate-100');
                                $iconColor = $poolStatus == 'completed' ? 'text-emerald-600' : ($poolStatus == 'active' ? 'text-indigo-600' : 'text-slate-400');
                            @endphp

                            <div class="relative bg-white rounded-xl border transition-all duration-300 overflow-hidden {{ $borderColor }}">
                                
                                <div class="h-16 {{ $bgHeader }} flex items-center justify-center relative">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center shadow-sm text-xl mb-[-24px] z-10 bg-white {{ $iconColor }}">
                                        @if($poolStatus == 'completed') <i class="fas fa-check"></i>
                                        @elseif($poolStatus == 'active') <i class="fas fa-bolt text-amber-400 animate-pulse"></i>
                                        @else <i class="fas fa-lock"></i> @endif
                                    </div>

                                    <div class="absolute top-2 right-2">
                                        @if($poolStatus == 'completed')
                                            <span class="px-2 py-0.5 text-[10px] font-bold text-white bg-white/20 backdrop-blur-sm rounded-full border border-white/30">Achieved</span>
                                        @elseif($poolStatus == 'active')
                                            <span class="px-2 py-0.5 text-[10px] font-bold text-white bg-white/20 backdrop-blur-sm rounded-full border border-white/30 animate-pulse">In Progress</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="pt-8 pb-4 px-4 text-center">
                                    <h3 class="text-base font-bold mb-1 {{ $poolStatus == 'completed' || $poolStatus == 'active' ? 'text-slate-800' : 'text-slate-500' }}">
                                        Level {{ $pool->pool_level }}: {{ $pool->rank_name }}
                                    </h3>
                                    
                                    <div class="text-xs text-slate-500 mb-4 font-medium">
                                        Target: <span class="font-bold text-slate-700">{{ number_format($pool->required_pv) }} PV</span>
                                    </div>

                                    <div class="py-2.5 px-3 rounded-lg border border-dashed {{ $poolStatus == 'completed' ? 'bg-emerald-50 border-emerald-200 text-emerald-700 mb-0' : ($poolStatus == 'active' ? 'bg-indigo-50 border-indigo-200 text-indigo-700 mb-0' : 'bg-slate-50 border-slate-200 text-slate-500 mb-0') }}">
                                        <p class="text-[10px] uppercase tracking-wider font-bold mb-0.5">Reward Amount</p>
                                        <p class="text-xl font-black">₹{{ number_format($pool->income, 0) }}</p>
                                    </div>

                                    @if($poolStatus == 'active')
                                        @php
                                            $currSlPv = $tracker->single_leg_pv ?? 0;
                                            $reqSlPv = $pool->required_pv;
                                            $slPercent = $reqSlPv > 0 ? min(100, ($currSlPv / $reqSlPv) * 100) : 0;
                                            $accumulatedAmount = ($slPercent / 100) * $pool->income;
                                        @endphp
                                        <div class="mt-4 text-left">
                                            <div class="flex justify-between text-[10px] font-bold mb-1.5">
                                                <span class="text-slate-500">Progress: <span class="text-indigo-600">{{ number_format($slPercent, 1) }}%</span></span>
                                                <span class="text-emerald-600 bg-emerald-50 px-1.5 rounded">₹{{ number_format($accumulatedAmount, 2) }}</span>
                                            </div>
                                            <div class="w-full bg-slate-100 rounded-full h-2 shadow-inner overflow-hidden">
                                                <div class="bg-gradient-to-r from-indigo-400 to-blue-500 h-2 rounded-full transition-all duration-1000" style="width: {{ $slPercent }}%"></div>
                                            </div>
                                            <p class="text-[9px] text-center text-slate-400 mt-1.5 font-medium">₹{{ number_format($accumulatedAmount, 2) }} accumulated out of ₹{{ number_format($pool->income, 0) }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>
@endsection