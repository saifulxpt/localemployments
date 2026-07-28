@extends('layouts.admin')

@section('title', 'ক্যাটাগরি এডিট: ' . $category->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-4 mb-4">
        <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">ক্যাটাগরি এডিট</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">ক্যাটাগরির নাম <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="input w-full" value="{{ old('name', $category->name) }}" required>
                    @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">আইকন (Emoji/SVG/Class) <span class="text-red-500">*</span></label>
                    <input type="text" name="icon" class="input w-full" value="{{ old('icon', $category->icon) }}" required>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="text-xs text-gray-500">বর্তমান আইকন:</span>
                        <div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center text-xl">
                            {!! $category->icon !!}
                        </div>
                    </div>
                    @error('icon') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">বিবরণ (ঐচ্ছিক)</label>
                    <textarea name="description" rows="3" class="input w-full">{{ old('description', $category->description) }}</textarea>
                    @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">সর্ট অর্ডার (Sort Order)</label>
                        <input type="number" name="sort_order" class="input w-full" value="{{ old('sort_order', $category->sort_order) }}" min="0">
                        @error('sort_order') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">স্ট্যাটাস</label>
                        <div class="flex items-center gap-4 mt-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="is_active" value="1" class="text-blue-600 focus:ring-blue-500" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="is_active" value="0" class="text-blue-600 focus:ring-blue-500" {{ !old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Inactive</span>
                            </label>
                        </div>
                        @error('is_active') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex gap-4 justify-end">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline border-gray-200">বাতিল</a>
                <button type="submit" class="btn btn-primary bg-blue-600 hover:bg-blue-700">আপডেট করুন</button>
            </div>
        </form>
    </div>

</div>
@endsection
