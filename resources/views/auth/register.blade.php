@extends('layouts.auth')

@section('title', 'নিবন্ধন')

@section('content')
    <h1 class="text-2xl font-bold text-gray-900 mb-1">নতুন একাউন্ট তৈরি করুন</h1>
    <p class="text-gray-500 text-sm mb-6">আপনার তথ্য দিয়ে শুরু করুন</p>

    @if($errors->any())
        <div class="alert alert-danger mb-4">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('register.store') }}"
          x-data="{
              role: '{{ old('role', 'seeker') }}',
              districtId: '{{ old('district_id', '') }}',
              areas: [],
              loadAreas(id) {
                  if (!id) { this.areas = []; return; }
                  fetch('/ajax/areas/' + id)
                      .then(r => r.json())
                      .then(data => { this.areas = data; });
              }
          }"
          @submit="$el.querySelector('button[type=submit]').disabled = true">
        @csrf

        {{-- Role Toggle --}}
        <div class="form-group">
            <label class="form-label">আমি কাজ করতে চাই / কর্মী খুঁজছি</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all"
                       :class="role === 'seeker' ? 'border-primary-500 bg-primary-50' : 'border-gray-200 hover:border-gray-300'">
                    <input type="radio" name="role" value="seeker" x-model="role" class="hidden">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                         :class="role === 'seeker' ? 'bg-primary-500' : 'bg-gray-100'">
                        <svg class="w-4 h-4" :class="role === 'seeker' ? 'text-white' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-medium text-sm" :class="role === 'seeker' ? 'text-primary-700' : 'text-gray-700'">কর্মী খুঁজছি</p>
                        <p class="text-xs text-gray-400">Seeker</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all"
                       :class="role === 'provider' ? 'border-primary-500 bg-primary-50' : 'border-gray-200 hover:border-gray-300'">
                    <input type="radio" name="role" value="provider" x-model="role" class="hidden">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                         :class="role === 'provider' ? 'bg-primary-500' : 'bg-gray-100'">
                        <svg class="w-4 h-4" :class="role === 'provider' ? 'text-white' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <p class="font-medium text-sm" :class="role === 'provider' ? 'text-primary-700' : 'text-gray-700'">কাজ করতে চাই</p>
                        <p class="text-xs text-gray-400">Provider</p>
                    </div>
                </label>
            </div>
        </div>

        {{-- Name --}}
        <div class="form-group">
            <label for="name" class="form-label">পুরো নাম</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   class="input @error('name') border-red-400 @enderror"
                   placeholder="আপনার নাম লিখুন" required>
            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Phone --}}
        <div class="form-group">
            <label for="phone" class="form-label">ফোন নম্বর</label>
            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                   class="input @error('phone') border-red-400 @enderror"
                   placeholder="01XXXXXXXXX" required>
            @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- District --}}
        <div class="form-group">
            <label for="district_id" class="form-label">জেলা <span class="text-gray-400 text-xs">(ঐচ্ছিক)</span></label>
            <select id="district_id" name="district_id"
                    class="input @error('district_id') border-red-400 @enderror"
                    x-model="districtId"
                    @change="loadAreas($event.target.value)">
                <option value="">জেলা বেছে নিন</option>
                @foreach($districts as $d)
                    <option value="{{ $d->id }}" {{ old('district_id') == $d->id ? 'selected' : '' }}>
                        {{ $d->bn_name }} ({{ $d->name }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Area --}}
        <div class="form-group" x-show="areas.length > 0" x-transition>
            <label for="area_id" class="form-label">এলাকা / থানা</label>
            <select id="area_id" name="area_id" class="input">
                <option value="">এলাকা বেছে নিন</option>
                <template x-for="a in areas" :key="a.id">
                    <option :value="a.id" x-text="a.bn_name + ' (' + a.name + ')'"></option>
                </template>
            </select>
        </div>

        {{-- Password --}}
        <div class="form-group" x-data="{ show: false }">
            <label for="password" class="form-label">পাসওয়ার্ড</label>
            <div class="relative">
                <input id="password" :type="show ? 'text' : 'password'" name="password"
                       class="input pr-10 @error('password') border-red-400 @enderror"
                       placeholder="কমপক্ষে ৮ অক্ষর" required>
                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
            </div>
            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Confirm Password --}}
        <div class="form-group">
            <label for="password_confirmation" class="form-label">পাসওয়ার্ড নিশ্চিত করুন</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="input" placeholder="আবার পাসওয়ার্ড দিন" required>
        </div>

        <button type="submit" class="btn btn-primary w-full justify-center py-3 text-base mt-2">
            নিবন্ধন করুন
        </button>

        <p class="text-center text-sm text-gray-500 mt-4">
            ইতিমধ্যে একাউন্ট আছে?
            <a href="{{ route('login') }}" class="text-primary-600 font-medium hover:underline">লগইন করুন</a>
        </p>
    </form>
@endsection
