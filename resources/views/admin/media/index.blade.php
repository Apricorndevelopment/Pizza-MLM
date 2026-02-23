@extends('layouts.layout')
@section('title', 'Media Library (Audio & Video)')

@section('container')
    <div class="container mx-auto px-4">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-2xl font-bold text-slate-800">Media Library</h3>
                <p class="text-slate-500 text-sm">Upload and manage audio/video files</p>
            </div>
            <button onclick="openModal()"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-cloud-upload-alt"></i> Upload Media
            </button>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded flex items-center justify-between shadow-sm">
                <p><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</p>
                <i class="fas fa-times-circle text-green-700 hover:text-green-900 cursor-pointer ml-2" onclick="this.closest('.bg-green-50').remove()"></i>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 flex items-center justify-between rounded shadow-sm">
               <p><i class="fas fa-times-circle mr-2"></i> {{ session('error') }}</p>
               <i class="fas fa-times-circle text-red-700 hover:text-red-900 cursor-pointer ml-2" onclick="this.closest('.bg-red-50').remove()"></i>
            </div>
        @endif

        {{-- TABS --}}
        <div x-data="{ activeTab: 'audio' }" class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">

            {{-- Tab Headers --}}
            <div class="flex border-b border-slate-200">
                <button @click="activeTab = 'audio'"
                    :class="activeTab === 'audio' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' :
                        'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-50'"
                    class="w-1/2 py-4 px-6 text-center font-bold text-sm uppercase tracking-wide border-b-2 transition-all">
                    <i class="fas fa-music mr-2"></i> Audio Gallery
                </button>
                <button @click="activeTab = 'video'"
                    :class="activeTab === 'video' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' :
                        'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-50'"
                    class="w-1/2 py-4 px-6 text-center font-bold text-sm uppercase tracking-wide border-b-2 transition-all">
                    <i class="fas fa-video mr-2"></i> Video Gallery
                </button>
            </div>

            {{-- Tab Content --}}
            <div class="p-6">

                {{-- AUDIO SECTION --}}
                <div x-show="activeTab === 'audio'" class="space-y-4">
                    @if ($audios->isEmpty())
                        <p class="text-center text-slate-400 py-10">No Audio Files Found.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($audios as $audio)
                                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 shadow-sm relative group">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="bg-indigo-100 text-indigo-600 p-3 rounded-full">
                                            <i class="fas fa-music"></i>
                                        </div>
                                        <h4 class="font-bold text-slate-700 truncate w-full" title="{{ $audio->title }}">
                                            {{ $audio->title }}</h4>
                                    </div>

                                    {{-- Audio Player --}}
                                    <audio controls class="w-full h-8 mb-3">
                                        <source src="{{ asset($audio->file_path) }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>

                                    <div class="flex justify-between items-center text-xs text-slate-400">
                                        <span>{{ $audio->created_at->format('d M, Y') }}</span>
                                        <form action="{{ route('admin.media.destroy', $audio->id) }}" method="POST"
                                            onsubmit="return confirm('Delete this audio?');">
                                            @csrf @method('DELETE')
                                            <button class="text-red-400 hover:text-red-600 transition-colors"
                                                title="Delete">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- VIDEO SECTION --}}
                <div x-show="activeTab === 'video'" style="display: none;">
                    @if ($videos->isEmpty())
                        <p class="text-center text-slate-400 py-10">No Video Files Found.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($videos as $video)
                                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 shadow-sm relative">
                                    <h4 class="font-bold text-slate-700 mb-2 truncate">{{ $video->title }}</h4>

                                    {{-- Video Player --}}
                                    <div class="rounded-lg overflow-hidden bg-black mb-3">
                                        <video controls class="w-full h-40 object-cover">
                                            <source src="{{ asset($video->file_path) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>

                                    <div class="flex justify-between items-center text-xs text-slate-400">
                                        <span>{{ $video->created_at->format('d M, Y') }}</span>
                                        <form action="{{ route('admin.media.destroy', $video->id) }}" method="POST"
                                            onsubmit="return confirm('Delete this video?');">
                                            @csrf @method('DELETE')
                                            <button class="text-red-400 hover:text-red-600 transition-colors"
                                                title="Delete">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- UPLOAD MODAL --}}
    <div id="mediaModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal()">
            </div>

            <div
                class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full relative z-10">
                <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Upload New Media</h3>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200"><i
                            class="fas fa-times"></i></button>
                </div>

                <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data"
                    class="p-6 space-y-5">
                    @csrf

                    {{-- Type Selection --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Media Type</label>
                        <div class="flex gap-4">
                            <label
                                class="flex items-center gap-2 cursor-pointer border p-3 rounded-lg w-1/2 hover:bg-slate-50 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                <input type="radio" name="type" value="audio" checked
                                    class="text-indigo-600 focus:ring-indigo-500">
                                <span class="font-bold text-slate-700"><i class="fas fa-music mr-1"></i> Audio</span>
                            </label>
                            <label
                                class="flex items-center gap-2 cursor-pointer border p-3 rounded-lg w-1/2 hover:bg-slate-50 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                <input type="radio" name="type" value="video"
                                    class="text-indigo-600 focus:ring-indigo-500">
                                <span class="font-bold text-slate-700"><i class="fas fa-video mr-1"></i> Video</span>
                            </label>
                        </div>
                    </div>

                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"
                            placeholder="Enter Media title" required>
                    </div>

                    {{-- File Input --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select File</label>
                        <input type="file" name="file"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-slate-50 focus:ring-2 focus:ring-indigo-500"
                            required>
                        <p class="text-xs text-gray-500 mt-1">Supported: mp3, wav, mp4, webm, mkv (Max 250MB)</p>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 mr-3 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" id="uploadBtn"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-md flex items-center">
                            <span id="btnText">Upload</span>
                            <svg id="btnSpinner" class="hidden animate-spin ml-2 h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script for Tabs (Alpine.js is best here, but vanilla JS backup) --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function openModal() {
            document.getElementById('mediaModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('mediaModal').classList.add('hidden');
        }
        // Show spinner on submit
        document.getElementById('mediaUploadForm').addEventListener('submit', function() {
            const uploadBtn = document.getElementById('uploadBtn');
            const cancelBtn = document.getElementById('cancelBtn');

            uploadBtn.disabled = true;
            cancelBtn.disabled = true;
            uploadBtn.classList.add('opacity-75', 'cursor-not-allowed');

            document.getElementById('btnText').innerText = 'Uploading...';
            document.getElementById('btnSpinner').classList.remove('hidden');
        });
    </script>
@endsection
