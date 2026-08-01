@extends('layouts.public')

@section('title', 'নিবন্ধন')

@section('content')
<div class="min-h-[calc(100vh-80px)] flex bg-white">
    
    {{-- Left Side: Branding / Marketing --}}
    <div class="hidden lg:flex lg:w-1/2 relative bg-primary-900 items-center justify-center overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <pattern id="grid-register" width="10" height="10" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1" fill="white"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#grid-register)"/>
            </svg>
        </div>
        
        <div class="relative z-10 max-w-lg px-12 text-white">
            <h2 class="text-4xl font-extrabold mb-6 leading-tight">নতুন সুযোগের <br><span class="text-yellow-400">খোঁজে?</span></h2>
            <p class="text-primary-100 text-lg mb-8">এখনই যুক্ত হোন আমাদের বিশাল কমিউনিটিতে। কাজ খুঁজুন অথবা আপনার সেবা দিন, সবই এক প্ল্যাটফর্মে।</p>
            
            <div class="flex flex-col gap-5">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-white mb-1">প্রোভাইডার হোন</h4>
                        <p class="text-sm text-primary-200 leading-relaxed">আপনার দক্ষতা অনুযায়ী কাজ পান এবং আয় করুন স্বাধীনভাবে।</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-white mb-1">কর্মী খুঁজুন</h4>
                        <p class="text-sm text-primary-200 leading-relaxed">আপনার দৈনন্দিন কাজের জন্য যাচাইকৃত ও বিশ্বস্ত কর্মী খুঁজে নিন।</p>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Decorative Circles --}}
        <div class="absolute top-1/4 -right-24 w-64 h-64 bg-emerald-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
        <div class="absolute -bottom-24 left-1/4 w-80 h-80 bg-blue-600 rounded-full mix-blend-multiply filter blur-3xl opacity-40"></div>
    </div>

    {{-- Right Side: Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center px-4 sm:px-6 lg:px-12 py-12 overflow-y-auto">
        <div class="w-full max-w-xl">
            
            <div class="text-center lg:text-left mb-10">
                <h1 class="text-3xl font-extrabold text-gray-900 mb-2">নতুন একাউন্ট তৈরি করুন</h1>
                <p class="text-gray-500">আপনার তথ্য দিয়ে শুরু করুন</p>
            </div>

            <form method="POST" action="{{ route('register.store') }}"
                  x-data="{
                      role: '{{ old('role', 'seeker') }}',
                      districtId: '{{ old('district_id', '') }}',
                      areas: [],
                      loading: false,
                      loadAreas(id) {
                          if (!id) { this.areas = []; return; }
                          fetch('/ajax/areas/' + id)
                              .then(r => r.json())
                              .then(data => { this.areas = data; });
                      }
                  }"
                  @submit="loading = true; $el.querySelector('button[type=submit]').disabled = true">
                @csrf

                @if($errors->any())
                    <div class="alert alert-danger mb-6">{{ $errors->first() }}</div>
                @endif

                {{-- Role Toggle --}}
                <div class="form-group mb-6">
                    <label class="form-label block text-sm font-semibold text-gray-700 mb-3">আমি কী করতে চাই?</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex flex-col items-center gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all hover:-translate-y-0.5"
                               :class="role === 'seeker' ? 'border-primary-600 bg-primary-50 shadow-md shadow-primary-100' : 'border-gray-200 hover:border-gray-300 bg-white'">
                            <input type="radio" name="role" value="seeker" x-model="role" class="hidden">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-colors"
                                 :class="role === 'seeker' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-400'">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <div class="text-center">
                                <p class="font-bold text-sm" :class="role === 'seeker' ? 'text-primary-900' : 'text-gray-700'">কর্মী খুঁজছি</p>
                                <p class="text-[11px] font-medium" :class="role === 'seeker' ? 'text-primary-600' : 'text-gray-400'">আমি সেবা নিতে চাই</p>
                            </div>
                            
                            {{-- Checkmark Icon for Active State --}}
                            <div x-show="role === 'seeker'" class="absolute top-3 right-3 text-primary-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </div>
                        </label>
                        
                        <label class="relative flex flex-col items-center gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all hover:-translate-y-0.5"
                               :class="role === 'provider' ? 'border-primary-600 bg-primary-50 shadow-md shadow-primary-100' : 'border-gray-200 hover:border-gray-300 bg-white'">
                            <input type="radio" name="role" value="provider" x-model="role" class="hidden">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-colors"
                                 :class="role === 'provider' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-400'">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div class="text-center">
                                <p class="font-bold text-sm" :class="role === 'provider' ? 'text-primary-900' : 'text-gray-700'">কাজ করতে চাই</p>
                                <p class="text-[11px] font-medium" :class="role === 'provider' ? 'text-primary-600' : 'text-gray-400'">আমি সেবা দিতে চাই</p>
                            </div>
                            
                            {{-- Checkmark Icon for Active State --}}
                            <div x-show="role === 'provider'" class="absolute top-3 right-3 text-primary-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    {{-- Name --}}
                    <div class="form-group mb-0">
                        <label for="name" class="form-label block text-sm font-semibold text-gray-700 mb-2">পুরো নাম</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}"
                               class="input w-full bg-gray-50 focus:bg-white border-gray-200 focus:border-primary-500 @error('name') border-red-400 @enderror"
                               placeholder="আপনার নাম লিখুন" required>
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Phone --}}
                    <div class="form-group mb-0">
                        <label for="phone" class="form-label block text-sm font-semibold text-gray-700 mb-2">ফোন নম্বর</label>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                               class="input w-full bg-gray-50 focus:bg-white border-gray-200 focus:border-primary-500 @error('phone') border-red-400 @enderror"
                               placeholder="01XXXXXXXXX" required>
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    {{-- District --}}
                    <div class="form-group mb-0">
                        <label for="district_id" class="form-label block text-sm font-semibold text-gray-700 mb-2">জেলা <span class="text-gray-400 text-xs">(ঐচ্ছিক)</span></label>
                        <select id="district_id" name="district_id"
                                class="input w-full bg-gray-50 focus:bg-white border-gray-200 focus:border-primary-500 @error('district_id') border-red-400 @enderror"
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
                    <div class="form-group mb-0" x-show="areas.length > 0" x-transition>
                        <label for="area_id" class="form-label block text-sm font-semibold text-gray-700 mb-2">এলাকা / থানা</label>
                        <select id="area_id" name="area_id" class="input w-full bg-gray-50 focus:bg-white border-gray-200 focus:border-primary-500">
                            <option value="">এলাকা বেছে নিন</option>
                            <template x-for="a in areas" :key="a.id">
                                <option :value="a.id" x-text="a.bn_name + ' (' + a.name + ')'"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    {{-- Password --}}
                    <div class="form-group mb-0" x-data="{ show: false }">
                        <label for="password" class="form-label block text-sm font-semibold text-gray-700 mb-2">পাসওয়ার্ড</label>
                        <div class="relative">
                            <input id="password" :type="show ? 'text' : 'password'" name="password"
                                   class="input w-full pr-10 bg-gray-50 focus:bg-white border-gray-200 focus:border-primary-500 @error('password') border-red-400 @enderror"
                                   placeholder="কমপক্ষে ৮ অক্ষর" required>
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none p-1 rounded-md">
                                <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="form-group mb-0">
                        <label for="password_confirmation" class="form-label block text-sm font-semibold text-gray-700 mb-2">পাসওয়ার্ড নিশ্চিত করুন</label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                               class="input w-full bg-gray-50 focus:bg-white border-gray-200 focus:border-primary-500" placeholder="আবার পাসওয়ার্ড দিন" required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="flex items-start gap-2 text-sm text-gray-600 cursor-pointer group">
                        <div class="relative flex items-center justify-center mt-0.5">
                            <input type="checkbox" required class="peer appearance-none w-5 h-5 border-2 border-gray-300 rounded-md checked:bg-primary-600 checked:border-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1 transition-all cursor-pointer">
                            <svg class="absolute w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="group-hover:text-gray-900 transition-colors">আমি এই ওয়েবসাইটের <a href="{{ route('terms') }}" class="text-primary-600 font-bold hover:underline" target="_blank">শর্তাবলী</a> ও <a href="{{ route('privacy') }}" class="text-primary-600 font-bold hover:underline" target="_blank">গোপনীয়তা নীতির</a> সাথে একমত।</span>
                    </label>
                </div>

                <button type="submit" :disabled="loading" class="btn btn-primary w-full justify-center py-3.5 text-base mt-2 shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 hover:-translate-y-0.5 transition-all">
                    <span x-show="!loading">একাউন্ট তৈরি করুন</span>
                    <span x-show="loading" x-cloak class="flex items-center gap-2">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        প্রসেস হচ্ছে...
                    </span>
                </button>

                <p class="text-center text-sm text-gray-500 mt-8 pt-6 border-t border-gray-100">
                    ইতিমধ্যে একাউন্ট আছে? 
                    <a href="{{ route('login') }}" class="text-primary-600 font-bold hover:text-primary-700 transition-colors">লগইন করুন</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
