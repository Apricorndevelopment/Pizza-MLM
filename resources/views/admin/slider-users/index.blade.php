@extends('layouts.layout')
@section('title', 'Manage Slider Users')

@section('container')
    <div class="container mx-auto px-4 py-8">

        {{-- Page Header --}}
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <div class="bg-indigo-50 p-3 rounded-full mr-4 text-indigo-600">
                    <i class="fas fa-users fa-lg"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-800">Slider Users</h3>
                    <p class="text-slate-500 text-sm">Manage users displayed on the home page slider</p>
                </div>
            </div>

            {{-- Add New Button --}}
            <button onclick="openModal('create')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-plus"></i> Add New User
            </button>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-xl"></i>
                    <p>{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900"><i class="fas fa-times"></i></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- TABLE --}}
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h4 class="font-bold text-slate-700">All Users</h4>
                <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">
                    {{ $users->count() }} Records
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                            <th class="px-6 py-3 font-semibold w-20">Sr No.</th>
                            <th class="px-6 py-3 font-semibold">Photo</th>
                            <th class="px-6 py-3 font-semibold">Name</th>
                            <th class="px-6 py-3 font-semibold">Rank</th>
                            <th class="px-6 py-3 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-slate-500 font-mono text-sm">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <div class="h-12 w-12 rounded-full overflow-hidden border border-slate-200 shadow-sm">
                                        <img src="{{ asset($user->photo) }}" alt="User Photo" class="h-full w-full object-cover">
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-700">{{ $user->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                        {{ $user->rank }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2">
                                    <button onclick='openModal("edit", @json($user))'
                                        class="text-indigo-500 hover:text-indigo-700 p-2 rounded-full hover:bg-indigo-50 transition-colors" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('admin.slider.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 p-2 rounded-full hover:bg-red-50 transition-colors" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    <p class="text-lg font-medium">No Users Found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- UNIFIED MODAL --}}
    <div id="userModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full relative z-10">
                <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white" id="modalTitle"></h3>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form id="userForm" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <div id="methodField"></div>

                    <div class="space-y-5">
                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                        </div>

                        {{-- Rank --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rank / Designation</label>
                            <input type="text" name="rank" id="rank" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                        </div>

                        {{-- Photo Input with Preview --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                            
                            {{-- Input --}}
                            <input type="file" name="photo" id="photo" 
                                onchange="previewFile(this)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-slate-50">
                            
                            <p class="text-xs text-gray-500 mt-1">Allowed: jpg, png, webp. Max: 5MB</p>

                            {{-- Image Preview Container --}}
                            <div id="image-preview-container" class="mt-3 hidden p-2 border border-dashed border-gray-300 rounded-lg bg-slate-50 flex items-center gap-3">
                                <img id="preview-img" src="" alt="Preview" class="h-16 w-16 rounded-full object-cover border border-slate-200 shadow-sm">
                                <div>
                                    <p class="text-xs font-bold text-gray-700" id="preview-text">Current Photo</p>
                                    <p class="text-[10px] text-gray-500">This image will be used.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium shadow-md">
                            <span id="btnText">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Store base asset URL for JS use
        const assetBaseUrl = "{{ asset('') }}";

        function openModal(type, data = null) {
            const modal = document.getElementById('userModal');
            const form = document.getElementById('userForm');
            const title = document.getElementById('modalTitle');
            const btnText = document.getElementById('btnText');
            const methodField = document.getElementById('methodField');
            const photoInput = document.getElementById('photo');
            
            // Preview Elements
            const previewContainer = document.getElementById('image-preview-container');
            const previewImg = document.getElementById('preview-img');
            const previewText = document.getElementById('preview-text');

            modal.classList.remove('hidden');

            if (type === 'create') {
                title.innerText = 'Add New User';
                btnText.innerText = 'Save User';
                form.action = "{{ route('admin.slider.store') }}";
                methodField.innerHTML = ''; 
                form.reset();
                
                // Hide preview on create initially
                previewContainer.classList.add('hidden');
                previewImg.src = '';
                photoInput.required = true; 

            } else {
                title.innerText = 'Edit User';
                btnText.innerText = 'Update User';
                form.action = `/admin/slider-users/update/${data.id}`;
                methodField.innerHTML = '@method("PUT")';
                
                document.getElementById('name').value = data.name;
                document.getElementById('rank').value = data.rank;
                photoInput.required = false; 

                // Show Current Image
                if (data.photo) {
                    previewImg.src = assetBaseUrl + data.photo;
                    previewText.innerText = "Current Photo";
                    previewContainer.classList.remove('hidden');
                } else {
                    previewContainer.classList.add('hidden');
                }
            }
        }

        function closeModal() {
            document.getElementById('userModal').classList.add('hidden');
        }

        // Live Preview Function
        function previewFile(input) {
            const previewContainer = document.getElementById('image-preview-container');
            const previewImg = document.getElementById('preview-img');
            const previewText = document.getElementById('preview-text');

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewText.innerText = "Selected Photo"; // Update text to indicate change
                    previewContainer.classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection