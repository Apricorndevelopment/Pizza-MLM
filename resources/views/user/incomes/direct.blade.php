@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')

@section('container')
    <div class="container px-6 mx-auto grid">
        <div class="my-6 flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-gray-700">Direct Income Report</h2>
            <span class="px-3 py-1 text-xs font-medium leading-none text-emerald-700 bg-emerald-100 rounded-full">
                History
            </span>
        </div>

        <div class="w-full overflow-hidden rounded-xl shadow-sm border border-gray-100 bg-white">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">From User</th>
                            <th class="px-4 py-3 text-right">Purchase Amt</th>
                            <th class="px-4 py-3 text-right">PV</th>
                            <th class="px-4 py-3 text-center">Percentage</th>
                            <th class="px-4 py-3 text-right">Income</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($incomes as $income)
                            <tr class="text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-4 py-3">
                                    <div class="flex items-center text-sm">
                                        <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                            <div
                                                class="absolute inset-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                                                <i class="bi bi-calendar-event"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="font-semibold">{{ $income->created_at->format('d M Y') }}</p>
                                            <p class="text-xs text-gray-600">{{ $income->created_at->format('h:i A') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="font-medium">{{ $income->from_name }}</div>
                                    <div class="text-xs text-gray-500 bg-gray-100 rounded px-1.5 py-0.5 inline-block mt-1">
                                        {{ $income->from_ulid }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-right font-medium">
                                    ₹{{ number_format($income->purchase_amount, 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-right">
                                    <span
                                        class="px-2 py-1 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full text-xs">
                                        {{ $income->purchase_pv }} PV
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-center">
                                    {{ $income->percentage }}%
                                </td>
                                <td class="px-4 py-3 text-sm text-right">
                                    <span class="text-emerald-600 font-bold text-base">
                                        +₹{{ number_format($income->income_amount, 2) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="bi bi-inbox text-4xl mb-2 text-gray-300"></i>
                                        <p>No direct income records found.</p>
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
