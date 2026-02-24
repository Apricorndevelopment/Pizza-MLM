@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')

@section('container')
    <div class="container px-6 mx-auto grid">
        <div class="my-6">
            <h2 class="text-2xl font-semibold text-gray-700">Repurchase Income</h2>
            <p class="text-sm text-gray-500">Commissions from team repurchases</p>
        </div>

        <div class="w-full overflow-hidden rounded-xl shadow-sm border border-gray-100 bg-white">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Source Details</th>
                            <th class="px-4 py-3 text-center">Level</th>
                            <th class="px-4 py-3 text-right">Order Value</th>
                            <th class="px-4 py-3 text-right">PV</th>
                            <th class="px-4 py-3 text-right">Commission</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($incomes as $income)
                            <tr class="text-gray-700 hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm">
                                    <p class="font-semibold">{{ $income->created_at->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-600">{{ $income->created_at->format('h:i A') }}</p>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="font-medium text-gray-800">{{ $income->from_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $income->from_ulid }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-center">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-teal-50 text-teal-700">
                                        L-{{ $income->level }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-right text-gray-600">
                                    {{ $income->purchase_amount }}
                                </td>
                                <td class="px-4 py-3 text-sm text-right font-medium">
                                    {{ $income->purchase_pv }}
                                </td>
                                <td class="px-4 py-3 text-sm text-right">
                                    <span class="text-emerald-600 font-bold">
                                        +₹{{ number_format($income->commission, 2) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    <i class="bi bi-cart-x text-2xl mb-2 block"></i>
                                    No repurchase income records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t bg-gray-50">
                {{ $incomes->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
