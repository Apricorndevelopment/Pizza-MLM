@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')

@section('title', 'Network Explorer')

@section('container')

    {{-- Design & Tree Structure CSS --}}
    <style>
        /* Tree Base Styles */
        .tree,
        .tree ul {
            list-style: none;
            padding-left: 1.5rem;
        }

        .tree li {
            margin: 0.5rem 0;
            position: relative;
        }

        /* Tree Connecting Lines */
        .tree li::before {
            content: '';
            position: absolute;
            top: 0;
            left: -15px;
            bottom: 50%;
            width: 15px;
            border-left: 1px solid #d1fae5;
            /* Emerald-100 */
        }

        .nested {
            display: none;
        }

        /* Scrollbar Styling */
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

        /* ACTIVE USER HIGHLIGHT - The #ECFDF5 Theme */
        .active-user {
            background-color: #ECFDF5 !important;
            /* The requested color */
            border: 1px solid #10b981 !important;
            /* Emerald-500 border */
            color: #064e3b !important;
            /* Emerald-900 text */
            font-weight: 700 !important;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        /* Default Node Style */
        .tree-node {
            padding: 4px 8px;
            border-radius: 6px;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .tree-node:hover {
            background-color: #f1f5f9;
            border-color: #e2e8f0;
        }
    </style>

    <div class="container mx-auto px-4 py-6">

        <div class="flex items-center mb-8">
            <div class="bg-[#ECFDF5] p-3 rounded-xl mr-4 text-emerald-600 border border-emerald-100 shadow-sm">
                <i class="fa fa-network-wired fa-lg"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Network Explorer</h3>
                <p class="text-slate-500 text-sm font-medium">Visualize and manage your team structure</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 relative">

            <div class="h-full">
                <div
                    class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col min-h-[600px]">
                    <div class="bg-white border-b border-slate-100 px-6 py-4 flex justify-between items-center">
                        <span class="text-slate-800 font-bold tracking-wide flex items-center gap-2">
                            <span
                                class="bg-emerald-100 text-emerald-600 w-8 h-8 rounded-lg flex items-center justify-center text-xs">
                                <i class="fa fa-sitemap"></i>
                            </span>
                            My Network Tree
                        </span>
                        <span
                            class="bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full border border-slate-200">
                            Interactive
                        </span>
                    </div>

                    <div class="p-6 max-h-[600px] overflow-y-auto custom-scrollbar bg-white flex-grow">
                        <div class="text-sm text-slate-600 font-medium leading-loose">
                            {!! $treeHtml !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden lg:block relative" id="detail_section" style="display: none;">
                <div class="sticky top-6 z-10">
                    <div
                        class="bg-white rounded-2xl shadow-[0_10px_40px_-15px_rgba(0,0,0,0.1)] border border-slate-200 overflow-hidden transition-all duration-300">

                        <div id="detail_header"
                            class="bg-slate-50 border-b border-slate-200 px-6 py-6 flex justify-between items-center transition-colors duration-300">
                            <div class="flex items-center gap-4">
                                <div class="bg-white p-2 rounded-full text-slate-400 border border-slate-200 shadow-sm flex items-center justify-center w-12 h-12"
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
                                class="bg-white border border-slate-200 text-slate-500 text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm font-mono">
                                ULID: -
                            </span>
                        </div>

                        <div class="p-0">
                            <table class="w-full text-left border-collapse">
                                <tbody class="divide-y divide-slate-100">
                                    <tr class="hover:bg-[#ECFDF5]/50 transition-colors group">
                                        <td class="p-4 text-slate-500 font-medium text-sm w-5/12">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center">
                                                    <i class="far fa-calendar-alt"></i>
                                                </div>
                                                Registered
                                            </div>
                                        </td>
                                        <td class="p-4 text-slate-700 font-bold text-sm text-right" id="detail_registered">-
                                        </td>
                                    </tr>

                                    <tr class="hover:bg-[#ECFDF5]/50 transition-colors group">
                                        <td class="p-4 text-slate-500 font-medium text-sm">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center">
                                                    <i class="far fa-check-circle"></i>
                                                </div>
                                                Activated
                                            </div>
                                        </td>
                                        <td class="p-4 text-slate-700 font-bold text-sm text-right" id="detail_doa">-</td>
                                    </tr>

                                    <tr class="hover:bg-[#ECFDF5]/50 transition-colors group">
                                        <td class="p-4 text-slate-500 font-medium text-sm">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-violet-50 text-violet-500 flex items-center justify-center">
                                                    <i class="fas fa-layer-group"></i>
                                                </div>
                                                Level
                                            </div>
                                        </td>
                                        <td class="p-4 text-slate-700 font-bold text-sm text-right" id="detail_level">-</td>
                                    </tr>

                                    <tr class="hover:bg-[#ECFDF5]/50 transition-colors group">
                                        <td class="p-4 text-slate-500 font-medium text-sm">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center">
                                                    <i class="fas fa-crown"></i>
                                                </div>
                                                Designation
                                            </div>
                                        </td>
                                        <td class="p-4 text-right">
                                            <span id="detail_rank"
                                                class="bg-slate-100 text-slate-600 text-xs font-bold px-3 py-1 rounded-md border border-slate-200 uppercase tracking-wide">N/A</span>
                                        </td>
                                    </tr>

                                    <tr class="bg-slate-50">
                                        <td class="p-4 text-slate-500 font-bold text-sm">
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-chart-line text-emerald-500"></i>
                                                Total Team Business
                                            </div>
                                        </td>
                                        <td class="p-4 text-emerald-700 font-black text-lg text-right" id="detail_business">
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
        // 1. Tree Toggle Logic (Standard)
        function toggleNode(el) {
            // el is likely the icon or span clicked
            // Find the closest li parent
            const li = el.closest('li');
            const nested = li.querySelector('ul.nested');

            // Find the visual icon inside the clicked element
            // Assuming el is the clickable span containing the icon

            if (nested) {
                if (nested.style.display === "block") {
                    nested.style.display = "none";
                    // Update Icon to Plus
                    el.innerHTML = '<i class="fas fa-plus-square text-emerald-400 hover:text-emerald-600 text-lg"></i>';
                } else {
                    nested.style.display = "block";
                    // Update Icon to Minus
                    el.innerHTML = '<i class="fas fa-minus-square text-emerald-600 text-lg"></i>';
                }
            }
        }

        // 2. Fetch User Details & Update UI
        function loadUserDetails(ulid) {

            // A. Highlight Active Node in Tree
            document.querySelectorAll('.tree-node').forEach(node => {
                node.classList.remove('active-user');
            });

            // Find node by data attribute and add active class
            // Note: Ensure your controller outputs data-user-ulid in the HTML
            const selectedNode = document.querySelector(`.tree-node[data-user-ulid="${ulid}"]`);
            if (selectedNode) {
                selectedNode.classList.add('active-user');
            }

            // B. Show Detail Section
            const detailSection = document.getElementById('detail_section');
            detailSection.style.display = "block";
            detailSection.classList.remove('hidden');

            // C. Fetch Data
            fetch('/get-user-details/' + ulid)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    // D. Update Text Content
                    document.getElementById('header_name').textContent = data.name || 'N/A';
                    document.getElementById('header_ulid').textContent = 'ULID: ' + (data.ulid || '-');
                    document.getElementById('detail_registered').textContent = data.registered_date || '-';
                    document.getElementById('detail_doa').textContent = data.activation_date || 'Not Active';
                    document.getElementById('detail_level').textContent = data.level !== null ? data.level : '-';
                    document.getElementById('detail_rank').textContent = data.rank || 'Associate';

                    let total_business = parseFloat(data.left_business || 0) + parseFloat(data.right_business || 0);
                    document.getElementById('detail_business').textContent = '₹' + total_business.toFixed(2);

                    // E. Dynamic Header Styling (Active vs Inactive)
                    const header = document.getElementById('detail_header');
                    const iconWrapper = document.getElementById('header_icon_wrapper');
                    const icon = iconWrapper.querySelector('i');

                    // Reset Classes
                    header.classList.remove('bg-[#ECFDF5]', 'border-emerald-200', 'bg-rose-50', 'border-rose-200',
                        'bg-slate-50', 'border-slate-200');
                    iconWrapper.classList.remove('text-emerald-600', 'border-emerald-200', 'text-rose-500',
                        'border-rose-200', 'text-slate-400');

                    if (data.activation_date) {
                        // ACTIVE USER: Emerald Theme
                        header.classList.add('bg-[#ECFDF5]', 'border-emerald-200');
                        iconWrapper.classList.add('text-emerald-600', 'border-emerald-200', 'bg-white');
                    } else {
                        // INACTIVE USER: Rose/Red Theme
                        header.classList.add('bg-rose-50', 'border-rose-200');
                        iconWrapper.classList.add('text-rose-500', 'border-rose-200', 'bg-white');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                });
        }
    </script>
@endpush
