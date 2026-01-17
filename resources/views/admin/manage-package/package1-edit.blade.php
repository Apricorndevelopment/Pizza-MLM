@extends('layouts.layout')
@section('title', 'Edit Package 1')
@section('container')

<div class="min-h-screen bg-gray-50 px-3 sm:px-4 lg:px-6">
    <div class="max-w-4xl mx-auto">
        
        {{-- Header Section --}}
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Package Management</h1>
                <p class="text-sm text-gray-500 mt-1">Update details for <span class="font-semibold text-blue-600">{{ $package->package_name }}</span></p>
            </div>
            <a href="{{ route('admin.package') }}" class="hidden sm:flex items-center text-gray-600 hover:text-blue-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 flex items-center">
                <div class="bg-white/20 p-2 rounded-lg mr-3">
                    <i class="fas fa-edit text-white text-lg"></i>
                </div>
                <h2 class="text-lg font-semibold text-white tracking-wide">Edit Package Type 1</h2>
            </div>

            <div class="p-6 md:p-8">
                <form action="{{ route('admin.package1.update', $package->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {{-- Package Name --}}
                        <div class="col-span-1">
                            <label for="package_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Package Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                id="package_name" 
                                name="package_name" 
                                value="{{ old('package_name', $package->package_name) }}" 
                                class="w-full px-4 py-2.5 rounded-lg border {{ $errors->has('package_name') ? 'border-red-300 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-200' }} focus:ring-2 outline-none transition-all shadow-sm"
                                required>
                            @error('package_name')
                                <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Price (Based on your provided code, Edit uses Price instead of Price) --}}
                        <div class="col-span-1">
                            <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                                Price
                            </label>
                            <div class="relative">
                                <input type="number" 
                                    step="0.01"
                                    id="price" 
                                    name="price" 
                                    value="{{ old('price', $package->price) }}" 
                                    class="w-full px-4 py-2.5 rounded-lg border {{ $errors->has('price') ? 'border-red-300 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-200' }} focus:ring-2 outline-none transition-all shadow-sm"
                                    placeholder="0">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">$</span>
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="col-span-1 md:col-span-2">
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="4" 
                                class="w-full px-4 py-2.5 rounded-lg border {{ $errors->has('description') ? 'border-red-300 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-200' }} focus:ring-2 outline-none transition-all shadow-sm placeholder-gray-400">{{ old('description', $package->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.package') }}" 
                           class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 hover:text-gray-900 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 shadow-md shadow-blue-500/30 transition-all transform active:scale-95">
                            Update Package
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection