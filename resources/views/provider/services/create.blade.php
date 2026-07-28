@extends('layouts.provider')

@section('title', 'নতুন সেবা যোগ করুন')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center gap-2 mb-2">
        <a href="{{ route('provider.services.index') }}" class="text-gray-500 hover:text-primary-600 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">নতুন সেবা যোগ করুন</h1>
    </div>

    <form action="{{ route('provider.services.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @csrf

        <div class="p-6 md:p-8 space-y-6">
            
            {{-- Category --}}
            <div class="form-group mb-0">
                <label class="form-label">সেবার ক্যাটাগরি <span class="text-red-500">*</span></label>
                <select name="subcategory_id" class="input" required>
                    <option value="">নির্বাচন করুন</option>
                    @foreach($categories as $category)
                        @if($category->activeSubcategories->count() > 0)
                            <optgroup label="{{ $category->name }}">
                                @foreach($category->activeSubcategories as $sub)
                                    <option value="{{ $sub->id }}" @selected(old('subcategory_id') == $sub->id)>{{ $sub->name }}</option>
                                @endforeach
                            </optgroup>
                        @endif
                    @endforeach
                </select>
                @error('subcategory_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Title --}}
            <div class="form-group mb-0">
                <label class="form-label">সেবার শিরোনাম <span class="text-red-500">*</span></label>
                <input type="text" name="title" class="input" value="{{ old('title') }}" placeholder="যেমন: ২ টনের এসির ফুল ক্লিনিং ও সার্ভিসিং" required>
                @error('title') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Description --}}
            <div class="form-group mb-0">
                <label class="form-label">সেবার বিবরণ <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5" class="input" placeholder="এই সেবায় আপনি কী কী কাজ করবেন, কী কী অন্তর্ভুক্ত নয় ইত্যাদি বিস্তারিত লিখুন..." required minlength="20">{{ old('description') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">অন্তত ২০টি অক্ষর হতে হবে।</p>
                @error('description') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Price & Duration --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="form-group mb-0">
                    <label class="form-label">মূল্যের ধরন <span class="text-red-500">*</span></label>
                    <select name="price_type" class="input" required>
                        <option value="fixed" @selected(old('price_type') == 'fixed')>ফিক্সড (Fixed)</option>
                        <option value="hourly" @selected(old('price_type') == 'hourly')>ঘণ্টা প্রতি (Hourly)</option>
                        <option value="starting_from" @selected(old('price_type') == 'starting_from')>থেকে শুরু (Starting from)</option>
                    </select>
                    @error('price_type') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group mb-0">
                    <label class="form-label">মূল্য (৳) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" class="input" value="{{ old('price') }}" min="50" placeholder="500" required>
                    @error('price') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-0">
                    <label class="form-label">আনুমানিক সময় (ঐচ্ছিক)</label>
                    <input type="text" name="estimated_duration" class="input" value="{{ old('estimated_duration') }}" placeholder="যেমন: ২-৩ ঘন্টা">
                    @error('estimated_duration') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Service Areas --}}
            <div class="form-group mb-0">
                <label class="form-label mb-3">যেসব জেলায় এই সেবা দেবেন (একাধিক নির্বাচন করা যাবে) <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 max-h-48 overflow-y-auto p-4 border border-gray-200 rounded-xl bg-gray-50">
                    @foreach($districts as $district)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="service_areas[]" value="{{ $district->id }}" 
                                   class="rounded text-primary-600 focus:ring-primary-500 border-gray-300"
                                   {{ (is_array(old('service_areas')) && in_array($district->id, old('service_areas'))) || auth()->user()->district_id == $district->id ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-700">{{ $district->bn_name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('service_areas') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Photos --}}
            <div class="form-group mb-0">
                <label class="form-label block mb-2">সেবার ছবি (সর্বোচ্চ ৫টি)</label>
                <input type="file" name="photos[]" multiple class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition-colors" accept="image/*">
                <p class="text-xs text-gray-400 mt-1">সুন্দর ছবি দিলে বুকিং পাওয়ার সম্ভাবনা বাড়ে।</p>
                @error('photos.*') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

        </div>

        <div class="bg-gray-50 px-6 py-4 md:px-8 flex justify-end">
            <button type="submit" class="btn btn-primary px-8">সেবা যোগ করুন</button>
        </div>
    </form>

</div>
@endsection
