@extends('layouts.admin')

@section('title', 'এলাকা এডিট: ' . $area->bn_name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-4 mb-4">
        <a href="{{ route('admin.locations.areas.index', $district) }}" class="text-gray-500 hover:text-blue-600 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">উপজেলা/এলাকা এডিট করুন</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
        <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-100 flex items-center gap-3">
            <div class="text-gray-500 text-sm">জেলা:</div>
            <div class="font-bold text-gray-900">{{ $district->bn_name }}</div>
        </div>

        <form action="{{ route('admin.areas.update', $area) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">এলাকার নাম (English) <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="input w-full" value="{{ old('name', $area->name) }}" required>
                        @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">এলাকার নাম (বাংলা) <span class="text-red-500">*</span></label>
                        <input type="text" name="bn_name" class="input w-full" value="{{ old('bn_name', $area->bn_name) }}" required>
                        @error('bn_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Latitude (অক্ষাংশ) <span class="text-gray-400 font-normal text-xs">(ঐচ্ছিক)</span></label>
                        <input type="number" step="any" name="latitude" class="input w-full" value="{{ old('latitude', $area->latitude) }}">
                        @error('latitude') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Longitude (দ্রাঘিমাংশ) <span class="text-gray-400 font-normal text-xs">(ঐচ্ছিক)</span></label>
                        <input type="number" step="any" name="longitude" class="input w-full" value="{{ old('longitude', $area->longitude) }}">
                        @error('longitude') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">স্ট্যাটাস</label>
                    <div class="flex items-center gap-4 mt-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="is_active" value="1" class="text-blue-600 focus:ring-blue-500" {{ old('is_active', $area->is_active) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="is_active" value="0" class="text-blue-600 focus:ring-blue-500" {{ !old('is_active', $area->is_active) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Inactive</span>
                        </label>
                    </div>
                    @error('is_active') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex gap-4 justify-end">
                <a href="{{ route('admin.locations.areas.index', $district) }}" class="btn btn-outline border-gray-200">বাতিল</a>
                <button type="submit" class="btn btn-primary bg-blue-600 hover:bg-blue-700">আপডেট করুন</button>
            </div>
        </form>
    </div>

</div>
@endsection
