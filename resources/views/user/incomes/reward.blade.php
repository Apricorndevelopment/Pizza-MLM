@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')

@section('container')
    <div class="container px-4 mx-auto">
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-gray-800">Reward Roadmap</h2>
            <p class="text-sm text-gray-500 mt-1">Unlock ranks and earn cash rewards.</p>

            <div class="inline-flex items-center mt-4 px-5 py-2 bg-white rounded-full shadow-sm border border-emerald-100">
                <div class="p-1.5 bg-emerald-100 rounded-full text-emerald-600 mr-3">
                    <i class="bi bi-graph-up-arrow text-sm"></i>
                </div>
                <div class="text-left">
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold">Total Business</p>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($user->total_business) }} PV</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
            @foreach ($allRewards as $reward)
                @php
                    $isUnlocked = $user->total_business >= $reward->achievement;
                    $earnedDate = $earnedRewards[$reward->id] ?? null;

                    if ($isUnlocked) {
                        $percentage = 100;
                    } else {
                        $percentage = min(100, max(0, ($user->total_business / $reward->achievement) * 100));
                    }
                @endphp

                <div
                    class="relative group bg-white rounded-xl border transition-all duration-300 overflow-hidden
                {{ $isUnlocked ? 'border-emerald-200 shadow-sm hover:shadow-md' : 'border-gray-200 opacity-95' }}">

                    <div
                        class="h-20 {{ $isUnlocked ? 'bg-gradient-to-r from-emerald-500 to-teal-500' : 'bg-gray-100' }} 
                            flex items-center justify-center relative">
                        <div
                            class="w-12 h-12 rounded-full flex items-center justify-center shadow-sm text-lg mb-[-24px] z-10
                        {{ $isUnlocked ? 'bg-white text-emerald-600' : 'bg-gray-200 text-gray-400' }}">
                            @if ($isUnlocked)
                                <i class="bi bi-trophy-fill"></i>
                            @else
                                <i class="bi bi-lock-fill"></i>
                            @endif
                        </div>

                        <div class="absolute top-2 right-2">
                            @if ($isUnlocked)
                                <span
                                    class="px-2 py-0.5 text-[10px] font-bold text-white bg-white/20 backdrop-blur-sm rounded-full border border-white/30">
                                    <i class="bi bi-check-circle-fill mr-1"></i> Achieved
                                </span>
                            @else
                                <span class="px-2 py-0.5 text-[10px] font-bold text-gray-500 bg-gray-200/80 rounded-full">
                                    Locked
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="pt-8 pb-5 px-5 text-center">
                        <h3 class="text-lg font-bold {{ $isUnlocked ? 'text-gray-800' : 'text-gray-500' }}">
                            {{ $reward->rank }}
                        </h3>

                        <div class="text-xs text-gray-500 mb-3">
                            Target: <span class="font-semibold">{{ number_format($reward->achievement) }} PV</span>
                        </div>

                        <div
                            class="py-2 px-3 rounded-lg {{ $isUnlocked ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-50 text-gray-400' }} mb-4">
                            <p class="text-[10px] uppercase tracking-wide font-semibold">Bonus</p>
                            <p class="text-xl font-bold">₹{{ number_format($reward->reward) }}</p>
                        </div>

                        <div class="relative w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="absolute top-0 left-0 h-full rounded-full transition-all duration-1000 ease-out
                                    {{ $isUnlocked ? 'bg-emerald-500' : 'bg-yellow-400' }}"
                                style="width: {{ $percentage }}%">
                            </div>
                        </div>

                        <div class="flex justify-between mt-2 text-[10px] font-medium text-gray-400">
                            <span>{{ number_format($percentage, 0) }}% Done</span>
                            @if (!$isUnlocked)
                                <span>{{ number_format($reward->achievement - $user->total_business) }} PV Left</span>
                            @endif
                        </div>

                        @if ($isUnlocked && $earnedDate)
                            <div class="mt-4 pt-3 border-t border-gray-50">
                                <p class="text-[10px] text-emerald-600 font-medium">
                                    <i class="bi bi-calendar-check mr-1"></i> Unlocked: {{ $earnedDate->format('d M, Y') }}
                                </p>
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach

        </div>

        <div class="text-center text-gray-400 text-xs mb-8">
            <i class="bi bi-info-circle mr-1"></i> Rewards are automatically credited to your wallet.
        </div>

    </div>
@endsection
