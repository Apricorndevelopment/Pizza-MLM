@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')
@section('title', 'Vendor Network Income')

@section('container')
    <div class="container px-4 mx-auto grid">
        <div class="my-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Vendor Network Income</h2>
                <p class="text-sm text-gray-500 mt-0.5">Earnings received from sales made by vendors in your direct downline.</p>
            </div>
            <span class="px-3 py-1 text-[12px] font-bold tracking-wider uppercase text-emerald-700 bg-emerald-100 border border-emerald-200 rounded-full shadow-sm w-max">
                <i class="bi bi-diagram-3-fill mr-1"></i> Network History
            </span>
        </div>

        <div class="w-full overflow-hidden rounded-xl shadow-sm border border-gray-200 bg-white mb-6">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap border-collapse">
                    <thead>
                        <tr class="text-[12px] font-extrabold tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                            {{-- Reduced Header Padding --}}
                            <th class="px-4 py-3">Date & Time</th>
                            <th class="px-4 py-3">Order ID</th>
                            <th class="px-4 py-3">From Vendor</th>
                            <th class="px-4 py-3 text-right">Order Amount</th>
                            <th class="px-4 py-3 text-center">Commission</th>
                            <th class="px-4 py-3 text-right">Your Income</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($incomes as $income)
                            <tr class="text-gray-700 hover:bg-emerald-50/50 transition-colors duration-200 group">
                                
                                {{-- Date & Time (Reduced Padding & Icon Size) --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center text-md gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                                            <i class="bi bi-calendar-check text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800 text-sm">{{ $income->created_at->format('d M Y') }}</p>
                                            <p class="text-[12px] text-gray-500 font-medium">{{ $income->created_at->format('h:i A') }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Order ID --}}
                                <td class="px-4 py-3 text-sm font-bold text-slate-700">
                                    #{{ $income->order_id ?? 'N/A' }}
                                </td>

                                {{-- From Vendor Details --}}
                                <td class="px-4 py-3 text-sm">
                                    <div class="font-bold text-gray-800">{{ $income->from_vendor_name }}</div>
                                    <div class="text-[12px] font-mono text-gray-500 bg-gray-100 border border-gray-200 rounded px-1.5 py-0.5 inline-block mt-0.5">
                                        {{ $income->from_vendor_ulid }}
                                    </div>
                                </td>

                                {{-- Purchase Amount & PV --}}
                                <td class="px-4 py-3 text-sm text-right">
                                    <div class="font-bold text-gray-800">
                                        ₹{{ number_format($income->purchase_amount, 2) }}
                                    </div>
                                    @if($income->purchase_pv > 0)
                                        <div class="text-[12px] font-bold text-blue-600 bg-blue-50 border border-blue-100 rounded px-1.5 py-0.5 inline-block mt-0.5">
                                            {{ $income->purchase_pv }} PV
                                        </div>
                                    @endif
                                </td>

                                {{-- Percentage --}}
                                <td class="px-4 py-3 text-sm text-center">
                                    <span class="px-2 py-0.5 text-[12px] font-bold text-orange-700 bg-orange-100 rounded-md border border-orange-200">
                                        {{ $income->percentage }}%
                                    </span>
                                </td>

                                {{-- Final Income --}}
                                <td class="px-4 py-3 text-md text-right">
                                    <span class="text-emerald-600 font-black text-md">
                                        +₹{{ number_format($income->income_amount, 2) }}
                                    </span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-gray-500 bg-gray-50/50">
                                    <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                        <div class="w-16 h-16 bg-white border border-gray-200 shadow-sm rounded-full flex items-center justify-center mb-3">
                                            <i class="bi bi-shop text-2xl text-gray-400"></i>
                                        </div>
                                        <h4 class="text-md font-bold text-gray-700">No Vendor Income Yet</h4>
                                        <p class="text-sm mt-1.5 text-gray-500 leading-relaxed">
                                            You do not have any vendors in your direct downline yet, or they haven't started receiving orders.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">
                {{ $incomes->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection