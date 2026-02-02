@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')

@section('title', 'Help Desk')

@section('container')
    <div class="min-h-screen bg-slate-50 py-4 sm:py-8 font-sans text-slate-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-6 mb-6 md:mb-8">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-slate-900 tracking-tight">Help & Support</h1>
                    <p class="text-sm text-slate-500 mt-1">Track issues and submit new support requests.</p>
                </div>

                <button onclick="toggleModal('createComplaintModal')"
                    class="w-full md:w-auto bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold py-3 md:py-2.5 px-6 rounded-xl shadow-md shadow-emerald-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <i class="fas fa-plus"></i> New Ticket
                </button>
            </div>

            @if (session('success'))
                <div
                    class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-lg flex-shrink-0"></i>
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 p-1">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total</p>
                    <p class="text-xl md:text-2xl font-extrabold text-slate-800">{{ $complaints->total() }}</p>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-emerald-100 shadow-sm bg-emerald-50/30">
                    <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Resolved</p>
                    <p class="text-xl md:text-2xl font-extrabold text-emerald-700">
                        {{ $complaints->where('status', 'resolved')->count() }}
                    </p>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-amber-100 shadow-sm bg-amber-50/30">
                    <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">Pending</p>
                    <p class="text-xl md:text-2xl font-extrabold text-amber-700">
                        {{ $complaints->where('status', 'pending')->count() }}
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div
                    class="px-4 md:px-6 py-4 md:py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Ticket History</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[600px] md:min-w-full">
                        <thead>
                            <tr
                                class="bg-slate-50 text-[10px] uppercase text-slate-500 font-bold border-b border-slate-200 tracking-wider">
                                <th class="px-4 md:px-6 py-4 w-16 text-center whitespace-nowrap">ID</th>
                                <th class="px-4 md:px-6 py-4">Issue</th>
                                <th class="px-4 md:px-6 py-4 text-center whitespace-nowrap">Status</th>
                                <th class="px-4 md:px-6 py-4 text-right whitespace-nowrap">Date</th>
                                <th class="px-4 md:px-6 py-4 w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($complaints as $ticket)
                                <tr class="hover:bg-[#ECFDF5]/30 transition-colors group">
                                    <td class="px-4 md:px-6 py-4 text-center text-xs font-mono font-bold text-slate-400">
                                        #{{ $ticket->id }}
                                    </td>
                                    <td class="px-4 md:px-6 py-4">
                                        <span
                                            class="block text-sm font-bold text-slate-800 break-words">{{ $ticket->subject }}</span>
                                        <span
                                            class="block text-xs text-slate-500 mt-0.5 truncate max-w-[150px] md:max-w-xs">{{ Str::limit($ticket->message, 50) }}</span>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 text-center whitespace-nowrap">
                                        @if ($ticket->status == 'pending')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200 uppercase tracking-wide">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                            </span>
                                        @elseif($ticket->status == 'in_progress')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-200 uppercase tracking-wide">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                                Processing
                                            </span>
                                        @elseif($ticket->status == 'resolved')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 uppercase tracking-wide">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Solved
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200 uppercase tracking-wide">
                                                Closed
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 md:px-6 py-4 text-right whitespace-nowrap">
                                        <span
                                            class="text-xs font-bold text-slate-600">{{ $ticket->created_at->format('M d, Y') }}</span>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 text-right">
                                        <button onclick="toggleDetails('ticket-{{ $ticket->id }}', this)"
                                            class="text-slate-400 hover:text-emerald-600 transition p-2">
                                            <i class="fas fa-chevron-down transition-transform duration-300"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr id="ticket-{{ $ticket->id }}" class="hidden bg-slate-50/50 shadow-inner">
                                    <td colspan="5" class="px-4 md:px-6 py-4 md:py-6 border-b border-slate-200">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                            <div class="space-y-2">
                                                <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                                    Your Message</h5>
                                                <div
                                                    class="bg-white p-3 md:p-4 rounded-xl border border-slate-200 text-sm text-slate-600 leading-relaxed whitespace-normal">
                                                    {{ $ticket->message }}
                                                </div>
                                                @if ($ticket->image)
                                                    <div class="mt-2">
                                                        <a href="{{ asset($ticket->image) }}" target="_blank"
                                                            class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1">
                                                            <i class="fas fa-paperclip"></i> View Attachment
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="space-y-2">
                                                <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                                    Admin Response</h5>
                                                @if ($ticket->admin_reply)
                                                    <div
                                                        class="bg-emerald-50 p-3 md:p-4 rounded-xl border border-emerald-100 text-sm text-emerald-900 leading-relaxed whitespace-normal">
                                                        {{ $ticket->admin_reply }}
                                                    </div>
                                                    <div class="text-right text-[10px] text-emerald-600 font-bold mt-1">
                                                        Replied: {{ $ticket->updated_at->diffForHumans() }}
                                                    </div>
                                                @else
                                                    <div
                                                        class="bg-slate-100 p-3 md:p-4 rounded-xl border border-slate-200 text-sm text-slate-400 italic text-center">
                                                        Waiting for response...
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div
                                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-3">
                                            <i class="fas fa-inbox text-slate-300 text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-bold text-slate-500">No tickets found</p>
                                        <p class="text-xs text-slate-400 mt-1">Have an issue? Create a new ticket.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($complaints->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $complaints->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>
        </div>

        <div id="createComplaintModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"
                onclick="toggleModal('createComplaintModal')"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-lg border border-slate-100 my-auto">

                        <div
                            class="bg-white px-4 md:px-6 py-4 md:py-5 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-slate-800">Submit Ticket</h3>
                            <button type="button" class="text-slate-400 hover:text-slate-600 transition"
                                onclick="toggleModal('createComplaintModal')">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>

                        <form action="{{ route('user.complaints.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="px-4 md:px-6 py-4 md:py-6 space-y-4 md:space-y-5">

                                <div class="space-y-1.5">
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wide">Category</label>
                                    <div class="relative">
                                        <select name="subject"
                                            class="w-full appearance-none rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm font-medium focus:border-emerald-500 focus:ring-emerald-500 transition-shadow">
                                            <option value="Payment Issue">Payment Issue</option>
                                            <option value="Product Quality">Product Quality</option>
                                            <option value="Technical Support">Technical Support</option>
                                            <option value="Account Issue">Account Issue</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <div
                                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-1.5">
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wide">Description</label>
                                    <textarea name="message" rows="4" required
                                        class="w-full rounded-xl border-slate-200 bg-slate-50 p-4 text-sm focus:border-emerald-500 focus:ring-emerald-500 transition-shadow placeholder-slate-400"
                                        placeholder="Describe your issue in detail..."></textarea>
                                </div>

                                <div class="space-y-1.5">
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wide">Attachment
                                        (Optional)</label>

                                    <label for="imageInput"
                                        class="flex flex-col justify-center w-full h-24 md:h-28 px-4 transition bg-white border-2 border-slate-200 border-dashed rounded-xl appearance-none cursor-pointer hover:border-emerald-400 hover:bg-emerald-50/30 focus:outline-none relative overflow-hidden">

                                        <div id="uploadPlaceholder"
                                            class="flex flex-col items-center justify-center pt-2">
                                            <i
                                                class="fas fa-cloud-upload-alt text-slate-400 text-xl md:text-2xl mb-1 md:mb-2"></i>
                                            <p class="text-xs font-medium text-slate-500 text-center">
                                                <span class="text-emerald-600 underline font-bold">Upload</span> or
                                                drag<br>
                                                <span class="text-[10px] text-slate-400 font-normal">PNG, JPG (Max
                                                    2MB)</span>
                                            </p>
                                        </div>

                                        <div id="filePreview"
                                            class="hidden flex-col items-center justify-center w-full h-full">
                                            <p class="text-sm font-bold text-emerald-600 truncate max-w-[200px]"
                                                id="fileName">filename.jpg</p>
                                            <p class="text-[10px] text-slate-400 mt-1">(Click to change)</p>
                                        </div>

                                        <input type="file" name="image" id="imageInput" accept="image/*"
                                            class="hidden" onchange="previewFile(this)">
                                    </label>
                                </div>

                            </div>

                            <div class="bg-slate-50 px-4 md:px-6 py-4 flex flex-col sm:flex-row-reverse gap-3">
                                <button type="submit"
                                    class="w-full sm:w-auto inline-flex justify-center rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all active:scale-95">
                                    Submit Ticket
                                </button>
                                <button type="button"
                                    class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-slate-200 bg-white px-6 py-2.5 text-sm font-bold text-slate-600 shadow-sm hover:bg-slate-50 focus:outline-none transition-all"
                                    onclick="toggleModal('createComplaintModal')">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        function toggleDetails(id, btn) {
            const content = document.getElementById(id);
            const icon = btn.querySelector('i');

            content.classList.toggle('hidden');

            if (content.classList.contains('hidden')) {
                icon.classList.remove('rotate-180');
            } else {
                icon.classList.add('rotate-180');
            }
        }

        function previewFile(input) {
            const placeholder = document.getElementById('uploadPlaceholder');
            const preview = document.getElementById('filePreview');
            const fileNameText = document.getElementById('fileName');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                fileNameText.textContent = file.name;
                placeholder.classList.add('hidden');
                preview.classList.remove('hidden');
                preview.classList.add('flex');
                input.parentElement.classList.add('border-emerald-400', 'bg-emerald-50/20');
                input.parentElement.classList.remove('border-slate-200');
            }
        }
    </script>
@endsection
