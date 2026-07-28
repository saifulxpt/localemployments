@extends('layouts.seeker')

@section('title', 'নতুন কাজ পোস্ট করুন')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center gap-2 mb-2">
        <a href="{{ route('seeker.job-requests.index') }}" class="text-gray-500 hover:text-primary-600 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">নতুন কাজ পোস্ট করুন</h1>
    </div>

    <form action="{{ route('seeker.job-requests.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @csrf

        <div class="p-6 md:p-8 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">কাজের প্রাথমিক তথ্য</h2>
            
            {{-- Category --}}
            <div class="form-group mb-6">
                <label class="form-label">কাজের ধরন (Category) <span class="text-red-500">*</span></label>
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
            <div class="form-group mb-6">
                <label class="form-label">কাজের শিরোনাম <span class="text-red-500">*</span></label>
                <input type="text" name="title" class="input" value="{{ old('title') }}" placeholder="যেমন: বাসার এসি পরিষ্কার করানো প্রয়োজন" required>
                @error('title') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Description --}}
            <div class="form-group mb-0">
                <label class="form-label">কাজের বিস্তারিত বিবরণ <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5" class="input" placeholder="আপনার কী কী কাজ করানো প্রয়োজন তা বিস্তারিত লিখুন। এতে প্রোভাইডাররা সঠিক বিড করতে পারবে।" required minlength="20">{{ old('description') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">অন্তত ২০টি অক্ষর হতে হবে।</p>
                @error('description') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="p-6 md:p-8 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-xl font-bold text-gray-900 mb-6">লোকেশন</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4" x-data="locationSelect('{{ route('ajax.areas', '') }}')" x-init="if('{{ auth()->user()->district_id }}') loadAreas('{{ auth()->user()->district_id }}'); setTimeout(() => { $refs.areaSelect.value = '{{ auth()->user()->area_id }}' }, 500)">
                <div class="form-group mb-0">
                    <label class="form-label">জেলা <span class="text-red-500">*</span></label>
                    <select name="district_id" class="input" required @change="loadAreas($event.target.value)">
                        <option value="">নির্বাচন করুন</option>
                        @foreach($districts as $d)
                            <option value="{{ $d->id }}" @selected(old('district_id', auth()->user()->district_id) == $d->id)>{{ $d->bn_name }}</option>
                        @endforeach
                    </select>
                    @error('district_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group mb-0">
                    <label class="form-label">এলাকা/থানা <span class="text-red-500">*</span></label>
                    <select name="area_id" class="input" required x-ref="areaSelect">
                        <option value="">আগে জেলা নির্বাচন করুন</option>
                        <template x-for="a in areas" :key="a.id">
                            <option :value="a.id" x-text="a.bn_name"></option>
                        </template>
                    </select>
                    @error('area_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="form-group mb-0">
                <label class="form-label">বিস্তারিত ঠিকানা (ঐচ্ছিক)</label>
                <input type="text" name="address_detail" class="input" value="{{ old('address_detail', auth()->user()->address) }}" placeholder="যেমন: রোড ১০, হাউজ ৫">
                <p class="text-xs text-gray-500 mt-1">প্রোভাইডার বুকিং নিশ্চিত হওয়ার পর আপনার ঠিকানা দেখতে পারবে।</p>
                @error('address_detail') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="p-6 md:p-8 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">সময় ও বাজেট</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="form-group mb-0">
                    <label class="form-label">কাজের সময়সীমা <span class="text-red-500">*</span></label>
                    <select name="flexibility" class="input" required>
                        <option value="flexible" @selected(old('flexibility') == 'flexible')>যেকোনো সময় (Flexible)</option>
                        <option value="fixed" @selected(old('flexibility') == 'fixed')>নির্দিষ্ট তারিখে</option>
                        <option value="urgent" @selected(old('flexibility') == 'urgent')>জরুরী (Urgent)</option>
                    </select>
                    @error('flexibility') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group mb-0">
                    <label class="form-label">সম্ভাব্য তারিখ (ঐচ্ছিক)</label>
                    <input type="date" name="preferred_date" class="input" value="{{ old('preferred_date') }}" min="{{ date('Y-m-d') }}">
                    @error('preferred_date') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-0">
                    <label class="form-label">সম্ভাব্য সময় (ঐচ্ছিক)</label>
                    <input type="text" name="preferred_time" class="input" value="{{ old('preferred_time') }}" placeholder="যেমন: সকাল ১০টা">
                    @error('preferred_time') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 max-w-md">
                <div class="form-group mb-0">
                    <label class="form-label">সর্বনিম্ন বাজেট (৳)</label>
                    <input type="number" name="budget_min" class="input" value="{{ old('budget_min') }}" min="0" placeholder="0">
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">সর্বোচ্চ বাজেট (৳)</label>
                    <input type="number" name="budget_max" class="input" value="{{ old('budget_max') }}" min="0" placeholder="1000">
                </div>
                <div class="col-span-2">
                    <p class="text-xs text-gray-500">বাজেট উল্লেখ না করলে "আলোচনা সাপেক্ষে" হিসেবে গণ্য হবে।</p>
                    @error('budget_min') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    @error('budget_max') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">ছবি সংযুক্ত করুন</h2>
            
            <div class="form-group mb-0">
                <label class="block mb-2 text-sm text-gray-700">কাজের সমস্যার ছবি বা স্যাম্পল ছবি দিন (সর্বোচ্চ ৫টি)</label>
                <input type="file" name="photos[]" multiple class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 transition-colors" accept="image/*">
                @error('photos.*') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-4 md:px-8 flex justify-end">
            <button type="submit" class="btn btn-primary px-8">কাজ পোস্ট করুন</button>
        </div>
    </form>

</div>
@endsection
