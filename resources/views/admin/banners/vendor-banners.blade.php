@extends('layouts.layout')
@section('title', 'Manage Vendor Banners')

@section('container')
    <div class="container mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <div class="bg-indigo-50 p-3 rounded-full mr-4 text-indigo-600">
                    <i class="fas fa-store fa-lg"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-800">Vendor Banners</h3>
                    <p class="text-slate-500 text-sm">Manage banners displayed in the vendor section</p>
                </div>
            </div>
            <button onclick="openModal('create')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-plus"></i> Add New Banner
            </button>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center justify-between">
                <div class="flex items-center"><i class="fas fa-check-circle mr-3 text-xl"></i><p>{{ session('success') }}</p></div>
                <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900"><i class="fas fa-times"></i></button>
            </div>
        @endif

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h4 class="font-bold text-slate-700">All Banners</h4>
                <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">{{ $banners->count() }} Records</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                            <th class="p-3 font-semibold w-20">Sr No.</th>
                            <th class="p-3 font-semibold">Banner Preview</th>
                            <th class="p-3 font-semibold">Title / Link</th>
                            <th class="p-3 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($banners as $banner)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="p-3 text-slate-500 font-mono text-sm">{{ $loop->iteration }}</td>
                                <td class="p-3">
                                    <div class="h-16 w-32 rounded-lg overflow-hidden border border-slate-200 shadow-sm relative group">
                                        <img src="{{ asset($banner->banner_image) }}" class="h-full w-full object-cover transition-transform group-hover:scale-105">
                                    </div>
                                </td>
                                <td class="p-3">
                                    <p class="font-bold text-slate-700">{{ $banner->title ?? 'No Title' }}</p>
                                    <a href="{{ $banner->link }}" target="_blank" class="text-xs text-blue-500 hover:underline truncate max-w-[200px] block">{{ $banner->link ?? 'No Link' }}</a>
                                </td>
                                <td class="p-3 text-right flex justify-end gap-2">
                                    <button onclick='openModal("edit", @json($banner))' class="text-indigo-500 hover:text-indigo-700 p-2 rounded-full hover:bg-indigo-50" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.vendor.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Delete this banner?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 p-2 rounded-full hover:bg-red-50" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400">No Banners Found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="bannerModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal()"></div>
            <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full relative z-10">
                <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white" id="modalTitle"></h3>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200 focus:outline-none"><i class="fas fa-times text-xl"></i></button>
                </div>
                <form id="bannerForm" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf <div id="methodField"></div>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Banner Title (Optional)</label>
                            <input type="text" name="title" id="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Redirect Link (Optional)</label>
                            <input type="url" name="link" id="link" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="https://example.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Banner Image</label>
                            <input type="file" name="banner_image" id="banner_image" onchange="previewFile(this)" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-slate-50">
                            <p class="text-xs text-gray-500 mt-1">Allowed: jpg, png, webp. Max: 5MB</p>
                            
                            {{-- Preview --}}
                            <div id="image-preview-container" class="mt-3 hidden p-2 border border-dashed border-gray-300 rounded-lg bg-slate-50">
                                <p class="text-xs font-bold text-gray-700 mb-2" id="preview-text">Selected Image</p>
                                <img id="preview-img" src="" class="h-32 w-full object-cover rounded-md border border-slate-200">
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"><span id="btnText">Save</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const assetBaseUrl = "{{ asset('') }}";

        function openModal(type, data = null) {
            const modal = document.getElementById('bannerModal');
            const form = document.getElementById('bannerForm');
            const title = document.getElementById('modalTitle');
            const btnText = document.getElementById('btnText');
            const methodField = document.getElementById('methodField');
            const photoInput = document.getElementById('banner_image');
            const previewContainer = document.getElementById('image-preview-container');
            const previewImg = document.getElementById('preview-img');
            const previewText = document.getElementById('preview-text');

            modal.classList.remove('hidden');

            if (type === 'create') {
                title.innerText = 'Add New Vendor Banner';
                btnText.innerText = 'Upload Banner';
                form.action = "{{ route('admin.vendor.banners.store') }}";
                methodField.innerHTML = ''; 
                form.reset();
                photoInput.required = true;
                previewContainer.classList.add('hidden');
            } else {
                title.innerText = 'Edit Vendor Banner';
                btnText.innerText = 'Update Banner';
                form.action = `/admin/vendor-banners/update/${data.id}`;
                methodField.innerHTML = '@method("PUT")';
                
                document.getElementById('title').value = data.title || '';
                document.getElementById('link').value = data.link || '';
                photoInput.required = false;

                if (data.banner_image) {
                    previewImg.src = assetBaseUrl + data.banner_image;
                    previewText.innerText = "Current Banner";
                    previewContainer.classList.remove('hidden');
                } else {
                    previewContainer.classList.add('hidden');
                }
            }
        }

        function closeModal() { document.getElementById('bannerModal').classList.add('hidden'); }

        function previewFile(input) {
            const previewContainer = document.getElementById('image-preview-container');
            const previewImg = document.getElementById('preview-img');
            const previewText = document.getElementById('preview-text');

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewText.innerText = "New Selection";
                    previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection