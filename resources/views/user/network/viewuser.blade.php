@extends('userlayouts.layouts')

@section('title', 'Network Explorer')

@section('container')

{{-- Minimal CSS for Dynamic Tree Structure (Cannot be replaced by Tailwind classes as HTML is injected) --}}
<style>
    .tree, .tree ul { list-style: none; padding-left: 1.5rem; }
    .tree li { margin: 0.5rem 0; }
    .nested { display: none; } /* Hidden by default */
    
    /* Highlight state for active user node */
    .active-user { 
        background-color: #eff6ff; /* bg-blue-50 */
        border: 1px solid #bfdbfe; /* border-blue-200 */
        border-radius: 0.375rem; /* rounded-md */
    }
</style>

<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <div class="bg-indigo-50 p-3 rounded-full mr-4 text-indigo-600">
            <i class="fa fa-network-wired fa-lg"></i>
        </div>
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Network Explorer</h3>
            <p class="text-slate-500 text-sm">Visualize and manage your team structure</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 relative">
        
        <div class="h-full">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden h-full flex flex-col">
                <div class="bg-gradient-to-r from-indigo-600 to-blue-500 px-6 py-3 flex justify-between items-center">
                    <span class="text-white font-semibold tracking-wide flex items-center gap-2">
                        <i class="fa fa-sitemap"></i> My Network Tree
                    </span>
                    <span class="bg-white/20 text-white text-xs px-3 py-1 rounded-full backdrop-blur-sm">
                        Interactive
                    </span>
                </div>

                <div class="p-2 max-h-[600px] overflow-y-auto custom-scrollbar bg-slate-50 flex-grow">
                    <div class="text-sm text-slate-700">
                        {!! $treeHtml !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden lg:block relative" id="detail_section" style="display: none;">
            <div class="sticky top-6 z-10">
                <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transition-all duration-300">
                    
                    <div id="detail_header" class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-2 flex justify-between items-center transition-colors duration-300">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 p-2 rounded-full text-white flex items-center justify-center w-10 h-10 backdrop-blur-sm">
                                <i class="fa fa-user text-lg"></i>
                            </div>
                            <span id="header_name" class="font-bold text-white text-md tracking-tight">Select a User</span>
                        </div>
                        <span id="header_ulid" class="bg-white/20 backdrop-blur-md border border-white/30 text-white text-xs font-medium px-3 py-1 rounded-full shadow-sm">
                            ULID: -
                        </span>
                    </div>

                    <div class="p-0">
                        <table class="w-full text-left border-collapse">
                            <tbody class="divide-y divide-slate-100">
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="p-3 text-slate-500 font-medium text-sm w-5/12">
                                        <i class="far fa-calendar-alt mr-2 text-indigo-400 group-hover:text-indigo-600 transition-colors"></i> 
                                        Registered Date
                                    </td>
                                    <td class="p-3 text-slate-800 font-semibold text-sm text-right" id="detail_registered">-</td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="p-3 text-slate-500 font-medium text-sm">
                                        <i class="far fa-check-circle mr-2 text-emerald-400 group-hover:text-emerald-600 transition-colors"></i> 
                                        Activation Date
                                    </td>
                                    <td class="p-3 text-slate-800 font-semibold text-sm text-right" id="detail_doa">-</td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="p-3 text-slate-500 font-medium text-sm">
                                        <i class="fas fa-layer-group mr-2 text-purple-400 group-hover:text-purple-600 transition-colors"></i> 
                                        Level
                                    </td>
                                    <td class="p-3 text-slate-800 font-semibold text-sm text-right" id="detail_level">-</td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="p-3 text-slate-500 font-medium text-sm">
                                        <i class="fas fa-shopping-bag mr-2 text-pink-400 group-hover:text-pink-600 transition-colors"></i> 
                                        Purchase Amount
                                    </td>
                                    <td class="p-3 text-indigo-600 font-bold text-sm text-right" id="detail_purchase">-</td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="p-3 text-slate-500 font-medium text-sm">
                                        <i class="fas fa-crown mr-2 text-yellow-400 group-hover:text-yellow-600 transition-colors"></i> 
                                        Designation
                                    </td>
                                    <td class="p-3 text-right">
                                        <span id="detail_rank" class="bg-slate-100 text-slate-600 text-xs font-bold px-2 py-1 rounded border border-slate-200">N/A</span>
                                    </td>
                                </tr>
                                <tr class="bg-emerald-50/50 hover:bg-emerald-50 transition-colors group">
                                    <td class="p-3 text-emerald-600 font-semibold text-sm">
                                        <i class="fas fa-chart-line mr-2"></i> 
                                        Total Team Business
                                    </td>
                                    <td class="p-3 text-emerald-700 font-bold text-base text-right" id="detail_business">-</td>
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
        // 1. Tree Toggle Logic (Matches Admin)
        function toggleNode(el) {
            const li = el.closest('li');
            const nested = li.querySelector('ul.nested');

            // Find the icon element specifically inside the toggle wrapper
            // Since we pass 'this' (the span wrapper), we can change textContent directly 
            // OR find the inner icon depending on your controller's HTML structure.
            // Based on previous code, 'el' is the <span> containing the +/-.
            
            if (nested) {
                if (nested.style.display === "block") {
                    nested.style.display = "none";
                    el.textContent = "➕";
                } else {
                    nested.style.display = "block";
                    el.textContent = "➖";
                }
            }
        }

        // 2. Fetch User Details
        function loadUserDetails(ulid) {
            // Highlight logic using Tailwind utility class replacement or specific CSS
            document.querySelectorAll('.tree-node').forEach(node => {
                node.classList.remove('active-user'); // Removes custom CSS class
                // Optional: You can remove Tailwind bg classes here if you attached them dynamically
            });

            const selectedNode = document.querySelector(`.tree-node[data-user-ulid="${ulid}"]`);
            if (selectedNode) {
                selectedNode.classList.add('active-user'); // Adds custom CSS class
            }

            // Show section logic
            const detailSection = document.getElementById('detail_section');
            detailSection.style.display = "block";
            // Ensure tailwind grid layout handles it
            detailSection.classList.remove('hidden');

            // Fetch Data
            fetch('/get-user-details/' + ulid)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    // Update UI Texts
                    document.getElementById('header_name').textContent = data.name || 'N/A';
                    document.getElementById('header_ulid').textContent = 'ULID: ' + (data.ulid || '-');
                    document.getElementById('detail_registered').textContent = data.registered_date || '-';
                    document.getElementById('detail_doa').textContent = data.activation_date || 'Not Active';
                    document.getElementById('detail_level').textContent = data.level !== null ? data.level : '-';
                    document.getElementById('detail_rank').textContent = data.rank || 'N/A';
                    document.getElementById('detail_purchase').textContent = data.purchase_amount;
                    
                    let total_business = parseFloat(data.left_business || 0) + parseFloat(data.right_business || 0);
                    document.getElementById('detail_business').textContent = total_business.toFixed(2);

                    // Dynamic Header Color
                    const header = document.getElementById('detail_header');
                    
                    // Reset gradients first (Tailwind classes)
                    header.classList.remove('from-blue-500', 'to-blue-600', 'from-emerald-500', 'to-emerald-600', 'from-rose-500', 'to-rose-600');

                    if (data.activation_date) {
                        // Active: Green Gradient
                        header.classList.add('bg-gradient-to-r', 'from-emerald-500', 'to-emerald-600');
                        header.style.background = ''; // Clear inline styles if any
                    } else {
                        // Inactive: Red/Rose Gradient
                        header.classList.add('bg-gradient-to-r', 'from-rose-500', 'to-rose-600');
                        header.style.background = ''; 
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                });
        }
    </script>
@endpush