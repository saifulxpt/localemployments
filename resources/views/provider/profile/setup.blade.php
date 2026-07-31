@extends('layouts.public')

@section('title', 'প্রোফাইল সেটআপ')

@section('content')
<div class="bg-gray-50 py-12 min-h-screen flex items-center">
    <div class="container mx-auto px-4 max-w-2xl">
        
        <div class="bg-white rounded-3xl p-8 md:p-12 shadow-xl border border-gray-100 relative overflow-hidden">
            {{-- Top Accent --}}
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-primary-600 to-accent-500"></div>
            
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary-50 rounded-2xl flex items-center justify-center text-primary-600 mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">স্বাগতম! আপনার প্রোফাইল সেটআপ করুন</h1>
                <p class="text-gray-500 mt-2">কাজ শুরু করার জন্য আপনার বিস্তারিত তথ্য প্রদান করুন</p>
            </div>

            <form action="{{ route('provider.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                {{-- Address Info --}}
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">ঠিকানা</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4" x-data="locationSelect('{{ url('ajax/areas') }}')">
                        <div class="form-group mb-0">
                            <label class="form-label">জেলা</label>
                            <select name="district_id" class="input" required @change="loadAreas($event.target.value)">
                                <option value="">নির্বাচন করুন</option>
                                @foreach($districts as $d)
                                    <option value="{{ $d->id }}">{{ $d->bn_name }}</option>
                                @endforeach
                            </select>
                            @error('district_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group mb-0">
                            <label class="form-label">এলাকা/থানা</label>
                            <select name="area_id" class="input" required>
                                <option value="">আগে জেলা নির্বাচন করুন</option>
                                <template x-for="a in areas" :key="a.id">
                                    <option :value="a.id" x-text="a.bn_name"></option>
                                </template>
                            </select>
                            @error('area_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">বিস্তারিত ঠিকানা</label>
                        <input type="text" name="address" class="input" placeholder="বাড়ি নং, রাস্তা, গ্রাম">
                        @error('address') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Professional Info --}}
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">পেশাগত তথ্য</h2>
                    
                    <div class="form-group">
                        <label class="form-label">নিজের সম্পর্কে (Bio)</label>
                        <textarea name="bio" rows="3" class="input" placeholder="আপনার কাজের অভিজ্ঞতা এবং দক্ষতা সম্পর্কে সংক্ষেপে লিখুন..."></textarea>
                        @error('bio') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">কাজের অভিজ্ঞতা (বছর)</label>
                        <input type="number" name="experience_years" class="input" min="0" max="50">
                        @error('experience_years') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Avatar --}}
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">প্রোফাইল ছবি</h2>
                    
                    <div class="form-group">
                        <label class="block mb-2 text-sm text-gray-600">সুন্দর এবং স্পষ্ট একটি ছবি আপলোড করুন</label>
                        <input type="file" name="avatar" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 transition-colors" accept="image/*">
                        @error('avatar') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-full py-3">সংরক্ষণ করুন এবং এগিয়ে যান</button>
            </form>
            
        </div>
    </div>
</div>
@endsection
