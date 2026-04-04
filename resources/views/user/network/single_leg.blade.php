@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')
@section('title', 'Single Leg Explorer')

@section('container')

    <style>
        /* Custom Scrollbar for Tree Container */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Single Leg Node Styling */
        .sl-node {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .sl-node:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-color: #10b981;
            /* emerald-500 */
        }

        .sl-active-node {
            background-color: #ECFDF5 !important;
            border: 2px solid #10b981 !important;
        }

        /* Vertical Line connecting nodes */
        .vertical-line {
            width: 2px;
            height: 10px;
            background-color: #a7f3d0;
            /* emerald-200 */
            margin: 0 auto;
        }
    </style>

    <div class="container mx-auto px-4 py-3">

        {{-- Header Section --}}
        <div class="flex items-center mb-3">
            <div class="bg-[#ECFDF5] p-2 rounded-xl mr-4 text-emerald-600 border border-emerald-100 shadow-sm">
                <i class="fa fa-link fa-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-800 tracking-tight">Single Leg Explorer</h3>
                <p class="text-slate-500 text-xs font-medium">Explore your global single leg downline</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 relative">

            {{-- LEFT PANEL: SINGLE LEG TREE --}}
            <div class="h-full">
                <div
                    class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col min-h-[600px]">

                    {{-- Top Banner indicating total team --}}
                    <div
                        class="bg-gradient-to-r from-emerald-500 to-teal-600 px-3 py-2.5 flex justify-between items-center text-white">
                        <span class="font-bold tracking-wide flex items-center gap-2">
                            <i class="fas fa-users"></i> Global Single Leg
                        </span>
                        <span class="bg-white/20 px-3 py-1 rounded-full text-xs font-bold tracking-wider text-center">
                            Total Team: {{ number_format($totalTeam) }}
                            <div>
                                Active: <span class="text-emerald-300">{{ number_format($ActiveTeam) }}</span> |
                                Inactive: <span class="text-rose-300">{{ number_format($InactiveTeam) }}</span>
                            </div>
                        </span>
                    </div>

                    {{-- Tree Container (Centered Content) --}}
                    <div
                        class="p-3 max-h-[600px] overflow-y-auto custom-scrollbar bg-slate-50 flex-grow flex flex-col items-center">

                        {{-- Root Node (Auth User) --}}
                        <div id="tree-container" class="flex flex-col items-center w-full">

                            {{-- Node Card - Added 'relative' --}}
                            <div class="sl-node bg-white border-2 border-slate-200 rounded-xl p-2 w-64 cursor-pointer z-10 flex items-center justify-center gap-2 relative"
                                id="node-{{ $user->ulid }}" onclick="loadUserDetails('{{ $user->ulid }}')">

                                {{-- Status Dot (Root User) --}}
                                @if($user->status === 'active')
                                    <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-emerald-500 rounded-full border border-white shadow-sm" title="Active"></span>
                                @else
                                    <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-amber-500 rounded-full border border-white shadow-sm" title="Inactive"></span>
                                @endif

                                <div
                                    class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-sm"></i>
                                </div>

                                <div class="font-bold text-slate-800 text-sm truncate">
                                    {{ $user->name }}
                                    <span class="text-xs text-slate-500 font-mono">({{ $user->ulid }})</span>
                                </div>

                            </div>

                            {{-- Children Wrapper --}}
                            <div id="children-wrapper-{{ $user->ulid }}" class="flex flex-col items-center w-full">
                                {{-- If total team > 0, show the toggle button to load next --}}
                                @if ($totalTeam > 0)
                                    <div class="vertical-line"></div>
                                    <button
                                        class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] z-10 border border-emerald-300 hover:bg-emerald-500 hover:text-white transition-colors toggle-btn shadow-sm"
                                        id="btn-{{ $user->ulid }}"
                                        onclick="fetchAndToggleNext('{{ $user->ulid }}', this)">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                @endif

                                {{-- Child node will be appended here via JS --}}
                                <div id="child-container-{{ $user->ulid }}"
                                    class="hidden flex-col items-center w-full mt-0"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT PANEL: USER DETAILS --}}
            <div class="hidden lg:block relative" id="detail_section" style="display: none;">
                <div class="sticky top-6 z-10">
                    <div
                        class="bg-white rounded-2xl shadow-[0_10px_40px_-15px_rgba(0,0,0,0.1)] border border-slate-200 overflow-hidden transition-all duration-300">
                        <div id="detail_header"
                            class="bg-slate-50 border-b border-slate-200 p-3 flex justify-between items-center transition-colors duration-300">
                            <div class="flex items-center gap-3">
                                <div class="bg-white p-2 rounded-full text-slate-400 border border-slate-200 shadow-sm flex items-center justify-center w-10 h-10"
                                    id="header_icon_wrapper">
                                    <i class="fa fa-user text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Selected
                                        User</p>
                                    <span id="header_name"
                                        class="font-extrabold text-slate-800 text-xl tracking-tight">Select a User</span>
                                </div>
                            </div>
                            <span id="header_ulid"
                                class="bg-white border border-slate-200 text-slate-500 text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm font-mono">ULID:
                                -</span>
                        </div>

                        <div class="p-0">
                            <table class="w-full text-left border-collapse">
                                <tbody class="divide-y divide-slate-100">
                                    <tr class="hover:bg-[#ECFDF5]/50 transition-colors group">
                                        <td class="p-3 text-slate-500 font-medium text-sm">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center">
                                                    <i class="far fa-envelope"></i>
                                                </div>
                                                Email
                                            </div>
                                        </td>
                                        <td class="p-3 text-slate-700 font-bold text-sm text-right" id="detail_email">-
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-[#ECFDF5]/50 transition-colors group">
                                        <td class="p-3 text-slate-500 font-medium text-sm">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center">
                                                    <i class="far fa-calendar-alt"></i>
                                                </div>
                                                Registered
                                            </div>
                                        </td>
                                        <td class="p-3 text-slate-700 font-bold text-sm text-right" id="detail_registered">-
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-[#ECFDF5]/50 transition-colors group">
                                        <td class="p-3 text-slate-500 font-medium text-sm">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center">
                                                    <i class="far fa-check-circle"></i>
                                                </div>
                                                Activated
                                            </div>
                                        </td>
                                        <td class="p-3 text-slate-700 font-bold text-sm text-right" id="detail_doa">-</td>
                                    </tr>
                                    <tr class="bg-slate-50">
                                        <td class="p-3 text-slate-500 font-bold text-sm">
                                            <div class="flex items-center gap-3"><i
                                                    class="fas fa-chart-line text-emerald-500"></i> Total Team Business
                                            </div>
                                        </td>
                                        <td class="p-3 text-emerald-700 font-black text-lg text-right" id="detail_business">
                                            -</td>
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
        // 1. AJAX logic to fetch the NEXT Single Leg Node
        function fetchAndToggleNext(parentUlid, btnElement) {
            const childContainer = document.getElementById(`child-container-${parentUlid}`);
            const icon = btnElement.querySelector('i');

            // If the container is hidden but already has content, just show it (Toggle Open)
            if (childContainer.classList.contains('hidden') && childContainer.innerHTML.trim() !== '') {
                childContainer.classList.remove('hidden');
                childContainer.classList.add('flex');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
                btnElement.classList.add('bg-emerald-500', 'text-white');
                btnElement.classList.remove('bg-emerald-100', 'text-emerald-600');
                return;
            }

            // If the container is open, hide it (Toggle Close)
            if (!childContainer.classList.contains('hidden')) {
                childContainer.classList.add('hidden');
                childContainer.classList.remove('flex');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
                btnElement.classList.remove('bg-emerald-500', 'text-white');
                btnElement.classList.add('bg-emerald-100', 'text-emerald-600');
                return;
            }

            // If empty, Fetch via AJAX
            // Change icon to loading spinner
            icon.className = 'fas fa-spinner fa-spin';

            fetch(`/fetch-next-single-leg/${parentUlid}`)
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        alert(data.message);
                        icon.className = 'fas fa-chevron-down'; // reset
                        return;
                    }

                    const user = data.user;
                    
                    // Status dot styling check based on user status
                    const statusColorClass = user.status === 'active' ? 'bg-emerald-500' : 'bg-amber-500';
                    const statusTitle = user.status === 'active' ? 'Active' : 'Inactive';

                    let nextHtml = `
    <div class="vertical-line"></div>

    <div class="sl-node bg-white border-2 border-slate-200 rounded-xl p-2 w-64 cursor-pointer z-10 animate-fade-in flex items-center justify-center gap-2 relative" 
         id="node-${user.ulid}"
         onclick="loadUserDetails('${user.ulid}')">

        <span class="absolute top-2 right-2 w-2.5 h-2.5 ${statusColorClass} rounded-full border border-white shadow-sm" title="${statusTitle}"></span>

        <div class="w-8 h-8 bg-sky-100 text-sky-600 rounded-full flex items-center justify-center">
            <i class="fas fa-user text-sm"></i>
        </div>

        <div class="font-bold text-slate-800 text-sm truncate" title="${user.name}">
            ${user.name}
            <span class="text-xs text-slate-500 font-mono">(${user.ulid})</span>
        </div>

    </div>
`;

                    // Add next toggle button if they have a downline
                    if (data.has_next) {
                        nextHtml += `
                            <div id="children-wrapper-${user.ulid}" class="flex flex-col items-center w-full">
                                <div class="vertical-line"></div>
                                <button class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] z-10 border border-emerald-300 hover:bg-emerald-500 hover:text-white transition-colors toggle-btn shadow-sm" 
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

                    // Update Button State to Opened
                    icon.className = 'fas fa-chevron-up';
                    btnElement.classList.add('bg-emerald-500', 'text-white');
                    btnElement.classList.remove('bg-emerald-100', 'text-emerald-600');
                })
                .catch(err => {
                    console.error('Error fetching next node:', err);
                    icon.className = 'fas fa-chevron-down'; // reset
                });
        }

        // 2. Fetch User Details & Update UI
        function loadUserDetails(ulid) {
            // Highlight Active Node in Tree
            document.querySelectorAll('.sl-node').forEach(node => {
                node.classList.remove('sl-active-node');
            });

            const selectedNode = document.getElementById(`node-${ulid}`);
            if (selectedNode) {
                selectedNode.classList.add('sl-active-node');
            }

            // Show Detail Section
            const detailSection = document.getElementById('detail_section');
            detailSection.style.display = "block";
            detailSection.classList.remove('hidden');

            // Fetch Data
            fetch('/get-user-details/' + ulid)
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

                    header.classList.remove('bg-[#ECFDF5]', 'border-emerald-200', 'bg-rose-50', 'border-rose-200',
                        'bg-slate-50', 'border-slate-200');
                    iconWrapper.classList.remove('text-emerald-600', 'border-emerald-200', 'text-rose-500',
                        'border-rose-200', 'text-slate-400');

                    if (data.activation_date) {
                        header.classList.add('bg-[#ECFDF5]', 'border-emerald-200');
                        iconWrapper.classList.add('text-emerald-600', 'border-emerald-200', 'bg-white');
                    } else {
                        header.classList.add('bg-rose-50', 'border-rose-200');
                        iconWrapper.classList.add('text-rose-500', 'border-rose-200', 'bg-white');
                    }
                })
                .catch(err => console.error('Error:', err));
        }
    </script>
@endpush