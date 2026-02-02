@extends('layouts.layout')

@section('title', 'Manage Complaints')

@section('container')
    <div class="min-h-screen bg-slate-50 py-8 font-sans text-slate-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Support Tickets</h1>
                    <p class="text-sm text-slate-500 mt-1">Manage and resolve user inquiries.</p>
                </div>

                <div class="flex gap-3">
                    <div
                        class="bg-amber-50 text-amber-700 px-4 py-2 rounded-xl border border-amber-100 text-xs font-bold uppercase tracking-wide">
                        Pending: {{ \App\Models\Complaint::where('status', 'pending')->count() }}
                    </div>
                    <div
                        class="bg-emerald-50 text-emerald-700 px-4 py-2 rounded-xl border border-emerald-100 text-xs font-bold uppercase tracking-wide">
                        Resolved: {{ \App\Models\Complaint::where('status', 'resolved')->count() }}
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div
                    class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-lg"></i>
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700"><i
                            class="fas fa-times"></i></button>
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 p-4">
                <form method="GET" action="{{ route('admin.complaints.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-3 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by ID, User Name, or Subject..."
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div class="w-full md:w-48 relative">
                        <select name="status" onchange="this.form.submit()"
                            class="w-full pl-3 pr-8 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 appearance-none bg-white">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In
                                Progress</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                            </option>
                        </select>
                        <i
                            class="fas fa-chevron-down absolute right-3 top-3.5 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-slate-50 text-[10px] uppercase text-slate-500 font-bold border-b border-slate-200 tracking-wider">
                                <th class="px-6 py-4 w-16 text-center">Ticket ID</th>
                                <th class="px-6 py-4">User Details</th>
                                <th class="px-6 py-4">Issue Category</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Created</th>
                                <th class="px-6 py-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($complaints as $ticket)
                                <tr class="hover:bg-[#ECFDF5]/30 transition-colors">
                                    <td class="px-6 py-4 text-center text-xs font-mono font-bold text-slate-400">
                                        #{{ $ticket->id }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs">
                                                {{ substr($ticket->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-800">{{ $ticket->user->name }}</p>
                                                <p class="text-xs text-slate-400 font-mono">
                                                    {{ $ticket->user->ulid ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="block text-sm font-medium text-slate-700">{{ $ticket->subject }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($ticket->status == 'pending')
                                            <span
                                                class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200 uppercase tracking-wide">Pending</span>
                                        @elseif($ticket->status == 'resolved')
                                            <span
                                                class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 uppercase tracking-wide">Resolved</span>
                                        @elseif($ticket->status == 'in_progress')
                                            <span
                                                class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-200 uppercase tracking-wide">Processing</span>
                                        @else
                                            <span
                                                class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-50 text-red-600 border border-red-200 uppercase tracking-wide">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right text-xs font-bold text-slate-500">
                                        {{ $ticket->created_at->format('M d, Y') }}<br>
                                        <span
                                            class="text-[10px] font-normal text-slate-400">{{ $ticket->created_at->format('h:i A') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick="openReplyModal({{ json_encode($ticket) }})"
                                            class="bg-white border border-slate-200 hover:border-emerald-500 hover:text-emerald-600 text-slate-500 px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition-all">
                                            Manage
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-sm">No tickets
                                        found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $complaints->withQueryString()->links('pagination::tailwind') }}
                </div>
            </div>
        </div>

        <div id="replyModal" class="fixed inset-0 z-50 hidden" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeReplyModal()">
            </div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div
                        class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden">

                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-slate-800">Manage Ticket #<span id="modalTicketId"></span>
                            </h3>
                            <button onclick="closeReplyModal()" class="text-slate-400 hover:text-slate-600 transition"><i
                                    class="fas fa-times text-lg"></i></button>
                        </div>

                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase">User Issue</label>
                                    <p class="text-sm text-slate-700 bg-slate-50 p-3 rounded-lg border border-slate-100 mt-1 leading-relaxed"
                                        id="modalMessage"></p>
                                </div>

                                <div id="modalAttachmentSection" class="hidden">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase">Attachment</label>
                                    <a id="modalImageLink" href="#" target="_blank" class="block mt-1">
                                        <div
                                            class="bg-slate-50 border border-slate-200 rounded-lg p-2 flex items-center gap-2 hover:bg-slate-100 transition">
                                            <i class="fas fa-image text-emerald-600"></i>
                                            <span class="text-xs font-bold text-emerald-600">View Image</span>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <form id="replyForm" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Update
                                        Status</label>
                                    <div class="relative">
                                        <select name="status" id="modalStatus"
                                            class="w-full appearance-none rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm font-medium focus:border-emerald-500 focus:ring-emerald-500">
                                            <option value="pending">Pending</option>
                                            <option value="in_progress">In Progress</option>
                                            <option value="resolved">Resolved</option>
                                            <option value="rejected">Rejected</option>
                                        </select>
                                        <i
                                            class="fas fa-chevron-down absolute right-3 top-3 text-slate-400 text-xs pointer-events-none"></i>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Admin
                                        Reply</label>
                                    <textarea name="admin_reply" id="modalReply" rows="5"
                                        class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 text-sm focus:border-emerald-500 focus:ring-emerald-500 placeholder-slate-400"
                                        placeholder="Type your response here..."></textarea>
                                </div>

                                <button type="submit"
                                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl shadow-md transition-all active:scale-95">
                                    Update Ticket
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function openReplyModal(ticket) {
            document.getElementById('modalTicketId').textContent = ticket.id;
            document.getElementById('modalMessage').textContent = ticket.message;
            document.getElementById('modalStatus').value = ticket.status;
            document.getElementById('modalReply').value = ticket.admin_reply || ''; // Pre-fill existing reply

            // Handle Form Action
            const form = document.getElementById('replyForm');
            form.action = `/admin/complaints/${ticket.id}`;

            // Handle Image
            const imgSection = document.getElementById('modalAttachmentSection');
            const imgLink = document.getElementById('modalImageLink');

            if (ticket.image) {
                imgSection.classList.remove('hidden');
                // Assuming your asset helper logic works as discussed previously
                imgLink.href = window.location.origin + '/' + ticket.image;
            } else {
                imgSection.classList.add('hidden');
            }

            // Show Modal
            const modal = document.getElementById('replyModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeReplyModal() {
            const modal = document.getElementById('replyModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection
