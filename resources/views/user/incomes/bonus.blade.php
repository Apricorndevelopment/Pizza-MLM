@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')

@section('container')
    <div class="container px-6 mx-auto grid">
        <div class="my-6 flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-gray-700">Bonus Income Report</h2>
        </div>

        <div class="w-full overflow-hidden rounded-xl shadow-sm border border-gray-100 bg-white">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3 text-right">Self Purchase Amt</th>
                            <th class="px-4 py-3 text-right">Total PV</th>
                            <th class="px-4 py-3 text-center">Bonus %</th>
                            <th class="px-4 py-3 text-right">Income Earned</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($incomes as $income)
                            <tr class="text-gray-700 hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm">
                                    <span
                                        class="font-medium">{{ $income->created_at ? $income->created_at->format('d M Y') : '-' }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-right">
                                    ₹{{ number_format($income->purchase_amount, 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-right">
                                    <span
                                        class="px-2 py-1 text-xs font-semibold text-purple-700 bg-purple-100 rounded-full">
                                        {{ $income->purchase_pv }} PV
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-center">
                                    <span class="px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-md">
                                        {{ $income->percentage }}%
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-right">
                                    <div class="flex items-center justify-end space-x-1 text-emerald-600 font-bold">
                                        <i class="bi bi-arrow-up-short"></i>
                                        <span>₹{{ number_format($income->income_amount, 2) }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    No bonus income records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t bg-gray-50">
                {{ $incomes->links() }}
            </div>
        </div>
    </div>
@endsection
