@extends('layouts.layout')
@section('title', 'Wallet Management')
@section('container')

    <div class="min-h-screen bg-slate-50 py-8 font-sans text-slate-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- 1. Header Card with Search --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6 overflow-hidden">
                <div class="px-6 py-4 bg-indigo-600 text-white flex flex-col md:flex-row justify-between items-center gap-4">
                    <h5 class="text-lg font-bold flex items-center gap-2">
                        <i class="fas fa-exchange-alt"></i> Wallet1 Transactions
                    </h5>

                    <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
                        {{-- Search Input (AJAX) --}}
                        <div class="relative w-full md:w-64">
                            <input type="text" id="searchInput"
                                class="w-full pl-4 pr-10 py-2 rounded-lg text-sm text-slate-700 bg-white/90 border-0 focus:ring-2 focus:ring-white/50 placeholder-slate-400 shadow-sm transition-all"
                                placeholder="Search by ULID..." autocomplete="off">

                            <div class="absolute right-2 top-1.5 text-slate-400">
                                <i class="fa fa-search" id="searchIcon"></i>
                                <i class="fa fa-circle-notch fa-spin hidden text-indigo-600" id="loadingIcon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Transactions Table --}}
                {{-- Added 'no-scrollbar' class here --}}
                <div class="overflow-x-auto min-h-[400px] no-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead
                            class="bg-slate-50 text-xs uppercase font-bold text-slate-500 tracking-wider sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">ULID</th>
                                <th class="px-6 py-4">User</th>
                                <th class="px-6 py-4 text-right">Wallet1</th>
                                <th class="px-6 py-4">Notes</th>
                            </tr>
                        </thead>
                        <tbody id="transactionTableBody" class="divide-y divide-slate-100">
                            @forelse ($wallet1Transactions as $transaction)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-medium">
                                        {{ $transaction->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-block bg-slate-100 text-slate-500 text-xs font-mono font-bold px-2 py-1 rounded border border-slate-200">
                                            {{ $transaction->user->ulid }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-700">
                                        {{ $transaction->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span
                                            class="text-sm font-bold {{ $transaction->wallet1 >= 0 ? 'text-emerald-600' : 'text-indigo-600' }}">
                                            {{ $transaction->wallet1 >= 0 ? '+' : '' }}{{ $transaction->wallet1 }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 text-xs text-slate-500 min-w-[200px] max-w-xs break-words leading-relaxed">
                                        {{ $transaction->notes ?? 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                                        No transactions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Infinite Scroll Sentinel --}}
                <div id="sentinel" class="py-4 text-center">
                    <p id="endOfData" class="hidden text-xs text-slate-400 font-medium">No more records</p>
                </div>
            </div>

        </div>
    </div>

    {{-- CSS to Hide Scrollbar --}}
    <style>
        /* Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* IE and Edge */
        .no-scrollbar {
            -ms-overflow-style: none;
            /* Firefox */
            scrollbar-width: none;
        }

        /* Fade In Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }
    </style>

    {{-- JavaScript Logic --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let nextPageUrl = "{{ $wallet1Transactions->nextPageUrl() }}";
            let isLoading = false;
            let searchTimeout = null;

            const searchInput = document.getElementById('searchInput');
            const tableBody = document.getElementById('transactionTableBody');
            const endOfData = document.getElementById('endOfData');
            const loadingIcon = document.getElementById('loadingIcon');
            const searchIcon = document.getElementById('searchIcon');

            // --- 1. Skeleton Row Template ---
            function getSkeletonHtml(count = 5) {
                let html = '';
                for (let i = 0; i < count; i++) {
                    html += `
                        <tr class="animate-pulse border-b border-slate-100 bg-white skeleton-row">
                            <td class="px-6 py-4"><div class="h-4 bg-slate-200 rounded w-24"></div></td>
                            <td class="px-6 py-4"><div class="h-6 bg-slate-200 rounded w-16"></div></td>
                            <td class="px-6 py-4"><div class="h-4 bg-slate-200 rounded w-32"></div></td>
                            <td class="px-6 py-4"><div class="h-4 bg-slate-200 rounded w-12 ml-auto"></div></td>
                            <td class="px-6 py-4"><div class="h-3 bg-slate-200 rounded w-48"></div></td>
                        </tr>`;
                }
                return html;
            }

            // --- 2. Remove Skeleton Rows ---
            function removeSkeletons() {
                const skeletons = document.querySelectorAll('.skeleton-row');
                skeletons.forEach(row => row.remove());
            }

            // --- 3. HTML Row Generator ---
            function getTableRowHtml(t) {
                const date = new Date(t.created_at).toLocaleDateString('en-GB', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
                const amountClass = t.wallet1 >= 0 ? 'text-emerald-600' : 'text-indigo-600';
                const sign = t.wallet1 >= 0 ? '+' : '';

                return `
                    <tr class="hover:bg-slate-50/50 transition-colors animate-fade-in">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-medium">${date}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-block bg-slate-100 text-slate-500 text-xs font-mono font-bold px-2 py-1 rounded border border-slate-200">
                                ${t.user.ulid}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-700">${t.user.name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="text-sm font-bold ${amountClass}">
                                ${sign}${t.wallet1}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 min-w-[200px] max-w-xs break-words leading-relaxed">
                            ${t.notes ? t.notes : '<span class="text-slate-300 italic">N/A</span>'}
                        </td>
                    </tr>
                `;
            }

            // --- 4. Fetch Data Function ---
            async function fetchData(url, replace = false) {
                if (!url || isLoading) return;

                isLoading = true;

                if (replace) {
                    tableBody.innerHTML = getSkeletonHtml(5);
                    loadingIcon.classList.remove('hidden');
                    searchIcon.classList.add('hidden');
                    endOfData.classList.add('hidden');
                } else {
                    tableBody.insertAdjacentHTML('beforeend', getSkeletonHtml(3));
                }

                try {
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    let html = '';
                    if (data.data.length > 0) {
                        data.data.forEach(transaction => {
                            html += getTableRowHtml(transaction);
                        });
                    } else if (replace) {
                        html =
                            `<tr><td colspan="5" class="px-6 py-16 text-center text-slate-400">No transactions found.</td></tr>`;
                    }

                    if (replace) {
                        tableBody.innerHTML = html;
                        window.scrollTo(0, 0);
                    } else {
                        removeSkeletons();
                        tableBody.insertAdjacentHTML('beforeend', html);
                    }

                    nextPageUrl = data.next_page_url;

                    if (!nextPageUrl) {
                        if (!replace) endOfData.classList.remove('hidden');
                        if (observer) observer.unobserve(document.querySelector('#sentinel'));
                    } else {
                        endOfData.classList.add('hidden');
                        if (observer) observer.observe(document.querySelector('#sentinel'));
                    }

                } catch (error) {
                    console.error('Error:', error);
                    removeSkeletons();
                } finally {
                    isLoading = false;
                    loadingIcon.classList.add('hidden');
                    searchIcon.classList.remove('hidden');
                }
            }

            // --- 5. Search Logic ---
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value;
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    let searchUrl = "{{ route('admin.wallet-transactions') }}";
                    if (query.length > 0) {
                        searchUrl += `?search=${query}`;
                    }
                    fetchData(searchUrl, true);
                }, 400);
            });

            // --- 6. Infinite Scroll Logic ---
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && nextPageUrl) {
                        let url = nextPageUrl;
                        const query = searchInput.value;
                        if (query && !url.includes('search=')) {
                            url += `&search=${query}`;
                        }
                        fetchData(url, false);
                    }
                });
            }, {
                rootMargin: '200px',
                threshold: 0.1
            });

            if (document.querySelector('#sentinel')) {
                observer.observe(document.querySelector('#sentinel'));
            }
        });
    </script>

@endsection
