@extends('layouts.seeker')

@section('title', 'প্রোফাইল সেটিংস')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">প্রোফাইল সেটিংস</h1>
        <p class="text-sm text-gray-500">আপনার ব্যক্তিগত তথ্য এবং ঠিকানা আপডেট করুন।</p>
    </div>

    <form action="{{ route('seeker.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @csrf
        @method('PUT')
        
        <div class="p-6 md:p-8 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">সাধারণ তথ্য</h2>
            
            <div class="flex flex-col sm:flex-row gap-6 mb-8">
                <div class="shrink-0">
                    <img src="{{ $user->avatar_url }}" alt="" class="w-24 h-24 rounded-2xl object-cover border border-gray-200 shadow-sm">
                </div>
                <div class="flex-1">
                    <label class="block mb-2 text-sm font-semibold text-gray-700">প্রোফাইল ছবি পরিবর্তন করুন</label>
                    <input type="file" name="avatar" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 transition-colors" accept="image/*">
                    @error('avatar') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group mb-0">
                    <label class="form-label">নাম <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="input" value="{{ old('name', $user->name) }}" required>
                    @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">ফোন নম্বর (পরিবর্তনযোগ্য নয়)</label>
                    <input type="text" class="input bg-gray-50 cursor-not-allowed" value="{{ $user->phone }}" readonly>
                </div>
                <div class="form-group mb-0 md:col-span-2">
                    <label class="form-label">ইমেইল (ঐচ্ছিক)</label>
                    <input type="email" name="email" class="input" value="{{ old('email', $user->email) }}">
                    @error('email') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-xl font-bold text-gray-900 mb-6">লোকেশন</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4" x-data="locationSelect('{{ route('ajax.areas', '') }}')" x-init="if('{{ $user->district_id }}') loadAreas('{{ $user->district_id }}'); setTimeout(() => { $refs.areaSelect.value = '{{ $user->area_id }}' }, 500)">
                <div class="form-group mb-0">
                    <label class="form-label">জেলা</label>
                    <select name="district_id" class="input" @change="loadAreas($event.target.value)">
                        <option value="">নির্বাচন করুন</option>
                        @foreach($districts as $d)
                            <option value="{{ $d->id }}" @selected(old('district_id', $user->district_id) == $d->id)>{{ $d->bn_name }}</option>
                        @endforeach
                    </select>
                    @error('district_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group mb-0">
                    <label class="form-label">এলাকা/থানা</label>
                    <select name="area_id" class="input" x-ref="areaSelect">
                        <option value="">নির্বাচন করুন</option>
                        <template x-for="a in areas" :key="a.id">
                            <option :value="a.id" x-text="a.bn_name"></option>
                        </template>
                    </select>
                    @error('area_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="form-group mb-0">
                <label class="form-label">বিস্তারিত ঠিকানা</label>
                <input type="text" name="address" class="input" value="{{ old('address', $user->address) }}" placeholder="বাড়ি নং, রাস্তা, গ্রাম">
                @error('address') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-4 md:px-8 flex justify-end">
            <button type="submit" class="btn btn-primary px-8">পরিবর্তন সেভ করুন</button>
        </div>
    </form>

</div>
@endsection
