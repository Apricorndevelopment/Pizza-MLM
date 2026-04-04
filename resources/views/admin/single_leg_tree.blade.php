@extends('layouts.layout')
@section('title', 'Global Single Leg Explorer')

@section('container')

    <style>
        /* Custom Scrollbar for Tree Container */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #64748b; }

        /* Single Leg Node Styling */
        .sl-node {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .sl-node:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-color: #3b82f6; /* blue-500 */
        }
        .sl-active-node {
            background-color: #eff6ff !important; /* blue-50 */
            border: 2px solid #3b82f6 !important; /* blue-500 */
        }

        /* Vertical Line connecting nodes */
        .vertical-line {
            width: 2px;
            height: 10px;
            background-color: #bfdbfe; /* blue-200 */
            margin: 0 auto;
        }

        .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="container mx-auto">

        {{-- Header Section --}}
        <div class="flex items-center mb-3">
            <div class="bg-blue-50 p-2.5 rounded-xl mr-4 text-blue-600 border border-blue-100 shadow-sm">
                <i class="fa fa-project-diagram fa-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-800 tracking-tight">Global Single Leg Explorer</h3>
                <p class="text-slate-500 text-xs font-medium">Analyze the complete system-wide single leg structure</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 relative">

            {{-- LEFT PANEL: SINGLE LEG TREE (BLUE THEME) --}}
            <div class="h-full">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden h-full flex flex-col min-h-[600px]">

                    {{-- Top Banner --}}
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-3 flex justify-between items-center text-white">
                        <span class="font-bold tracking-wide flex items-center gap-2">
                            <i class="fas fa-globe"></i> System Root
                        </span>
                        <span class="bg-white/20 px-3 py-1 rounded-full text-xs font-bold tracking-wider">
                            Total System Users: {{ number_format($totalTeam + 1) }}
                            <div>
                                Active: <span class="text-emerald-300">{{ number_format($ActiveTeam + 1) }}</span> |
                                Inactive: <span class="text-rose-300">{{ number_format($InactiveTeam) }}</span>
                            </div>
                        </span>
                    </div>

                    {{-- Tree Container --}}
                    <div class="p-3 max-h-[600px] overflow-y-auto custom-scrollbar bg-slate-50 flex-grow flex flex-col items-center">

                        {{-- Root Node (Top User) --}}
                        <div id="tree-container" class="flex flex-col items-center w-full mt-2">
                            
                            {{-- Node Card --}}
                            {{-- Added 'relative' class here to position the status dot correctly --}}
                            <div class="sl-node bg-white border-2 border-slate-200 rounded-xl p-2 w-64 cursor-pointer z-10 flex items-center justify-center gap-2 relative"
                                id="node-{{ $user->ulid }}" onclick="loadUserDetails('{{ $user->ulid }}')">

                                {{-- Status Dot (Root User) --}}
                                @if($user->status === 'active')
                                    <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-emerald-500 rounded-full border border-white shadow-sm" title="Active"></span>
                                @else
                                    <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-amber-500 rounded-full border border-white shadow-sm" title="Inactive"></span>
                                @endif

                                <div class="w-7 h-7 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-crown text-sm"></i>
                                </div>

                                <div class="font-bold text-slate-800 text-sm truncate text-left">
                                    {{ $user->name }}
                                    <span class="text-xs text-slate-500 font-mono">({{ $user->ulid }})</span>
                                </div>
                            </div>

                            {{-- Children Wrapper --}}
                            <div id="children-wrapper-{{ $user->ulid }}" class="flex flex-col items-center w-full">
                                @if ($totalTeam > 0)
                                    <div class="vertical-line"></div>
                                    <button class="w-5 h-5 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] z-10 border border-blue-300 hover:bg-blue-500 hover:text-white transition-colors toggle-btn shadow-sm"
                                        id="btn-{{ $user->ulid }}"
                                        onclick="fetchAndToggleNext('{{ $user->ulid }}', this)">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                @endif

                                {{-- Child node container --}}
                                <div id="child-container-{{ $user->ulid }}" class="hidden flex-col items-center w-full mt-0"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT PANEL: USER DETAILS --}}
            <div class="hidden lg:block relative" id="detail_section" style="display: none;">
                <div class="sticky top-6 z-10">
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden transition-all duration-300">
                        <div id="detail_header" class="bg-slate-50 border-b border-slate-200 p-3 flex justify-between items-center transition-colors duration-300">
                            <div class="flex items-center gap-3">
                                <div class="bg-white p-2 rounded-full text-slate-400 border border-slate-200 shadow-sm flex items-center justify-center w-12 h-12" id="header_icon_wrapper">
                                    <i class="fa fa-user text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Selected Member</p>
                                    <span id="header_name" class="font-extrabold text-slate-800 text-lg tracking-tight">Select a User</span>
                                </div>
                            </div>
                            <span id="header_ulid" class="bg-white border border-slate-200 text-slate-500 text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm font-mono">ULID: -</span>
                        </div>

                        <div class="p-0">
                            <table class="w-full text-left border-collapse">
                                <tbody class="divide-y divide-slate-100">
                                    <tr class="hover:bg-blue-50/50 transition-colors">
                                        <td class="p-3 text-slate-500 font-medium text-sm">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center"><i class="far fa-envelope"></i></div>
                                                Email
                                            </div>
                                        </td>
                                        <td class="p-3 text-slate-700 font-bold text-sm text-right" id="detail_email">-</td>
                                    </tr>
                                    <tr class="hover:bg-blue-50/50 transition-colors">
                                        <td class="p-3 text-slate-500 font-medium text-sm">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center"><i class="far fa-calendar-alt"></i></div>
                                                Registered
                                            </div>
                                        </td>
                                        <td class="p-3 text-slate-700 font-bold text-sm text-right" id="detail_registered">-</td>
                                    </tr>
                                    <tr class="hover:bg-blue-50/50 transition-colors">
                                        <td class="p-3 text-slate-500 font-medium text-sm">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center"><i class="far fa-check-circle"></i></div>
                                                Activated
                                            </div>
                                        </td>
                                        <td class="p-3 text-slate-700 font-bold text-sm text-right" id="detail_doa">-</td>
                                    </tr>
                                    <tr class="bg-slate-50">
                                        <td class="p-3 text-slate-500 font-bold text-sm">
                                            <div class="flex items-center gap-3"><i class="fas fa-chart-line text-blue-500"></i> Total Business</div>
                                        </td>
                                        <td class="p-3 text-blue-700 font-black text-lg text-right" id="detail_business">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // 1. AJAX logic to fetch the NEXT Single Leg Node (Admin specific URL)
        function fetchAndToggleNext(parentUlid, btnElement) {
            const childContainer = document.getElementById(`child-container-${parentUlid}`);
            const icon = btnElement.querySelector('i');

            if (childContainer.classList.contains('hidden') && childContainer.innerHTML.trim() !== '') {
                childContainer.classList.remove('hidden');
                childContainer.classList.add('flex');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
                btnElement.classList.add('bg-blue-500', 'text-white');
                btnElement.classList.remove('bg-blue-100', 'text-blue-600');
                return;
            }

            if (!childContainer.classList.contains('hidden')) {
                childContainer.classList.add('hidden');
                childContainer.classList.remove('flex');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
                btnElement.classList.remove('bg-blue-500', 'text-white');
                btnElement.classList.add('bg-blue-100', 'text-blue-600');
                return;
            }

            icon.className = 'fas fa-spinner fa-spin';

            // Pointing to Admin Route
            fetch(`/admin/fetch-next-single-leg/${parentUlid}`)
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        alert(data.message);
                        icon.className = 'fas fa-chevron-down'; 
                        return;
                    }

                    const user = data.user;
                    
                    // Status dot styling check based on user status
                    const statusColorClass = user.status === 'active' ? 'bg-emerald-500' : 'bg-amber-500';
                    const statusTitle = user.status === 'active' ? 'Active' : 'Inactive';

                    let nextHtml = `
                        <div class="vertical-line"></div>
                        <div class="sl-node bg-white border-2 border-slate-200 rounded-xl p-2 w-64 cursor-pointer z-10 animate-fade-in flex items-center justify-center gap-3 relative" 
                             id="node-${user.ulid}"
                             onclick="loadUserDetails('${user.ulid}')">
                             
                            <span class="absolute top-2 right-2 w-2.5 h-2.5 ${statusColorClass} rounded-full border border-white shadow-sm" title="${statusTitle}"></span>

                            <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                            <div class="font-bold text-slate-800 text-sm truncate text-left" title="${user.name}">
                                ${user.name}
                                <span class="text-[10px] text-slate-500 font-mono">(${user.ulid})</span>
                            </div>
                        </div>
                    `;

                    if (data.has_next) {
                        nextHtml += `
                            <div id="children-wrapper-${user.ulid}" class="flex flex-col items-center w-full">
                                <div class="vertical-line"></div>
                                <button class="w-5 h-5 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] z-10 border border-blue-300 hover:bg-blue-500 hover:text-white transition-colors toggle-btn shadow-sm" 
                                        id="btn-${user.ulid}"
                                        onclick="fetchAndToggleNext('${user.ulid}', this)">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div id="child-container-${user.ulid}" class="hidden flex-col items-center w-full mt-0"></div>
                            </div>
                        `;
                    }

                    childContainer.innerHTML = nextHtml;
                    childContainer.classList.remove('hidden');
                    childContainer.classList.add('flex');

                    icon.className = 'fas fa-chevron-up';
                    btnElement.classList.add('bg-blue-500', 'text-white');
                    btnElement.classList.remove('bg-blue-100', 'text-blue-600');
                })
                .catch(err => {
                    console.error('Error:', err);
                    icon.className = 'fas fa-chevron-down';
                });
        }

        // 2. Fetch User Details & Update UI
        function loadUserDetails(ulid) {
            document.querySelectorAll('.sl-node').forEach(node => {
                node.classList.remove('sl-active-node');
            });

            const selectedNode = document.getElementById(`node-${ulid}`);
            if (selectedNode) {
                selectedNode.classList.add('sl-active-node');
            }

            const detailSection = document.getElementById('detail_section');
            detailSection.style.display = "block";
            detailSection.classList.remove('hidden');

            fetch('/admin/get-user-details/' + ulid)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    document.getElementById('header_name').textContent = data.name || 'N/A';
                    document.getElementById('header_ulid').textContent = 'ULID: ' + (data.ulid || '-');
                    document.getElementById('detail_email').textContent = data.email || '-';
                    document.getElementById('detail_registered').textContent = data.registered_date || '-';
                    document.getElementById('detail_doa').textContent = data.activation_date || 'Not Active';
                    document.getElementById('detail_business').textContent = data.total_business;

                    const header = document.getElementById('detail_header');
                    const iconWrapper = document.getElementById('header_icon_wrapper');

                    // Reset styling
                    header.classList.remove('bg-blue-50', 'border-blue-200', 'bg-rose-50', 'border-rose-200', 'bg-slate-50', 'border-slate-200');
                    iconWrapper.classList.remove('text-blue-600', 'border-blue-200', 'text-rose-500', 'border-rose-200', 'text-slate-400');

                    if (data.activation_date) {
                        header.classList.add('bg-blue-50', 'border-blue-200');
                        iconWrapper.classList.add('text-blue-600', 'border-blue-200', 'bg-white');
                    } else {
                        header.classList.add('bg-rose-50', 'border-rose-200');
                        iconWrapper.classList.add('text-rose-500', 'border-rose-200', 'bg-white');
                    }
                })
                .catch(err => console.error('Error:', err));
        }
    </script>
@endpush