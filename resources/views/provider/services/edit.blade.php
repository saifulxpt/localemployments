@extends('layouts.provider')

@section('title', 'সেবা এডিট করুন')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center gap-2 mb-2">
        <a href="{{ route('provider.services.index') }}" class="text-gray-500 hover:text-primary-600 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">সেবা আপডেট করুন</h1>
    </div>

    <form action="{{ route('provider.services.update', $service->id) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @csrf
        @method('PUT')

        <div class="p-6 md:p-8 space-y-6">
            
            {{-- Service Status --}}
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">সেবার অবস্থা (Status)</h3>
                    <p class="text-xs text-gray-500 mt-1">এই সেবাটি কি বর্তমানে কাস্টমারদের জন্য খোলা আছে?</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" @checked(old('is_active', $service->is_active))>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                </label>
            </div>

            {{-- Category (readonly for edit, usually shouldn't change core category of existing service) --}}
            <div class="form-group mb-0">
                <label class="form-label">সেবার ক্যাটাগরি</label>
                <input type="text" class="input bg-gray-50 cursor-not-allowed text-gray-600" value="{{ $service->subcategory->category->name }} > {{ $service->subcategory->name }}" readonly>
            </div>

            {{-- Title --}}
            <div class="form-group mb-0">
                <label class="form-label">সেবার শিরোনাম <span class="text-red-500">*</span></label>
                <input type="text" name="title" class="input" value="{{ old('title', $service->title) }}" required>
                @error('title') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Description --}}
            <div class="form-group mb-0">
                <label class="form-label">সেবার বিবরণ <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5" class="input" required minlength="20">{{ old('description', $service->description) }}</textarea>
                <p class="text-xs text-gray-400 mt-1">অন্তত ২০টি অক্ষর হতে হবে।</p>
                @error('description') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Price & Duration --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="form-group mb-0">
                    <label class="form-label">মূল্যের ধরন <span class="text-red-500">*</span></label>
                    <select name="price_type" class="input" required>
                        <option value="fixed" @selected(old('price_type', $service->price_type) == 'fixed')>ফিক্সড (Fixed)</option>
                        <option value="hourly" @selected(old('price_type', $service->price_type) == 'hourly')>ঘণ্টা প্রতি (Hourly)</option>
                        <option value="starting_from" @selected(old('price_type', $service->price_type) == 'starting_from')>থেকে শুরু (Starting from)</option>
                    </select>
                    @error('price_type') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group mb-0">
                    <label class="form-label">মূল্য (৳) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" class="input" value="{{ old('price', $service->price) }}" min="50" required>
                    @error('price') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-0">
                    <label class="form-label">আনুমানিক সময় (ঐচ্ছিক)</label>
                    <input type="text" name="estimated_duration" class="input" value="{{ old('estimated_duration', $service->estimated_duration) }}">
                    @error('estimated_duration') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Service Areas --}}
            <div class="form-group mb-0">
                <label class="form-label mb-3">যেসব জেলায় এই সেবা দেবেন <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 max-h-48 overflow-y-auto p-4 border border-gray-200 rounded-xl bg-gray-50">
                    @foreach($districts as $district)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="service_areas[]" value="{{ $district->id }}" 
                                   class="rounded text-primary-600 focus:ring-primary-500 border-gray-300"
                                   {{ (is_array(old('service_areas', $service->service_areas)) && in_array($district->id, old('service_areas', $service->service_areas))) ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-700">{{ $district->bn_name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('service_areas') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Note about photos --}}
            <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl flex gap-3 text-blue-800 text-sm">
                <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p>ছবি আপডেট করার সুবিধা এই মুহূর্তে বন্ধ আছে। যদি ছবি পরিবর্তন করতে হয় তবে সেবাটি ডিলিট করে নতুন করে যোগ করুন।</p>
            </div>

        </div>

        <div class="bg-gray-50 px-6 py-4 md:px-8 flex justify-end gap-3">
            <a href="{{ route('provider.services.index') }}" class="btn btn-outline px-6">বাতিল</a>
            <button type="submit" class="btn btn-primary px-8">আপডেট করুন</button>
        </div>
    </form>

</div>
@endsection
