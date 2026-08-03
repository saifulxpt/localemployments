@extends('layouts.public')

@section('title', 'নতুন একাউন্ট খুলুন')

@section('content')
<div class="min-h-[calc(100vh-64px)] md:min-h-screen flex bg-white">
    
    {{-- Left Side: Branding / Graphic (Hidden on Mobile) --}}
    <div class="hidden lg:flex lg:w-1/2 relative bg-primary-900 text-white overflow-hidden items-center justify-center p-12">
        {{-- Premium Background Image --}}
        <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32d7?q=80&w=1200&auto=format&fit=crop" alt="Customer service handshake" class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-overlay">
        
        <div class="absolute inset-0 bg-gradient-to-t from-primary-900 via-primary-900/80 to-primary-900/40"></div>

        <div class="relative z-10 max-w-lg text-center">
            <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 flex items-center justify-center mx-auto mb-8 shadow-2xl">
                <svg class="w-10 h-10 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <h1 class="text-4xl font-extrabold mb-6 leading-tight">দক্ষ কর্মীদের বিশাল<br>কমিউনিটিতে স্বাগতম</h1>
            <p class="text-primary-100 text-lg leading-relaxed">সেবা দিতে চান বা নিতে চান? আজই একাউন্ট খুলে যুক্ত হোন দেশের সবচেয়ে দ্রুত বর্ধনশীল সার্ভিস প্ল্যাটফর্মে।</p>
            
            <div class="mt-12 grid grid-cols-2 gap-4 text-left">
                <div class="bg-white/10 backdrop-blur border border-white/10 p-4 rounded-xl">
                    <div class="font-bold text-lg text-white mb-1">হাজারো কাজ</div>
                    <div class="text-sm text-primary-200">আপনার স্কিল অনুযায়ী কাজ খুঁজুন</div>
                </div>
                <div class="bg-white/10 backdrop-blur border border-white/10 p-4 rounded-xl">
                    <div class="font-bold text-lg text-white mb-1">বিশ্বস্ত কর্মী</div>
                    <div class="text-sm text-primary-200">যাচাইকৃত লোকাল সার্ভিস প্রোভাইডার</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Side: Register Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-16 bg-gray-50/50 lg:bg-white relative">
        <div class="w-full max-w-md">
            
            {{-- Mobile Title --}}
            <div class="mb-10 text-center lg:text-left">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-3 tracking-tight">নতুন একাউন্ট</h2>
                <p class="text-gray-500 text-base">আপনার সঠিক তথ্য দিয়ে যুক্ত হোন</p>
            </div>

            <form method="POST" action="{{ route('register.store') }}" class="space-y-5"
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
                  @submit="loading = true">
                @csrf

                {{-- Errors --}}
                @if($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm font-medium flex items-start gap-3 border border-red-100 mb-6">
                        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                {{-- Role Selection (Premium Toggle) --}}
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-3">আপনি কী করতে চান?</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex flex-col items-center gap-2 p-4 rounded-2xl border-2 cursor-pointer transition-all duration-300"
                               :class="role === 'seeker' ? 'border-primary-500 bg-primary-50 shadow-[0_4px_12px_-4px_rgba(15,118,110,0.2)]' : 'border-gray-200 hover:border-gray-300 bg-white'">
                            <input type="radio" name="role" value="seeker" x-model="role" class="hidden">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-colors"
                                 :class="role === 'seeker' ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-400'">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <div class="text-center">
                                <p class="font-bold text-sm" :class="role === 'seeker' ? 'text-primary-700' : 'text-gray-700'">কর্মী খুঁজছি</p>
                            </div>
                            
                            {{-- Checkmark --}}
                            <div x-show="role === 'seeker'" x-transition class="absolute top-2 right-2 text-primary-500">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </div>
                        </label>

                        <label class="relative flex flex-col items-center gap-2 p-4 rounded-2xl border-2 cursor-pointer transition-all duration-300"
                               :class="role === 'provider' ? 'border-primary-500 bg-primary-50 shadow-[0_4px_12px_-4px_rgba(15,118,110,0.2)]' : 'border-gray-200 hover:border-gray-300 bg-white'">
                            <input type="radio" name="role" value="provider" x-model="role" class="hidden">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-colors"
                                 :class="role === 'provider' ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-400'">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div class="text-center">
                                <p class="font-bold text-sm" :class="role === 'provider' ? 'text-primary-700' : 'text-gray-700'">কাজ করতে চাই</p>
                            </div>

                            {{-- Checkmark --}}
                            <div x-show="role === 'provider'" x-transition class="absolute top-2 right-2 text-primary-500">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">আপনার পূর্ণ নাম</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                           class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all font-medium text-gray-900 @error('name') border-red-400 bg-red-50 @enderror"
                           placeholder="উদাঃ মোঃ রাকিবুল হাসান" required>
                    @error('name')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">ফোন নম্বর</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-medium border-r border-gray-200 pr-3">+880</span>
                        </div>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                               class="w-full pl-[5.5rem] pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all font-medium text-gray-900 @error('phone') border-red-400 bg-red-50 @enderror"
                               placeholder="1XXXXXXXXX" required>
                    </div>
                    @error('phone')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- District & Area --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="district_id" class="block text-sm font-bold text-gray-700 mb-2">জেলা <span class="text-gray-400 font-normal">(ঐচ্ছিক)</span></label>
                        <select id="district_id" name="district_id"
                                class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all font-medium text-gray-900 @error('district_id') border-red-400 bg-red-50 @enderror"
                                x-model="districtId"
                                @change="loadAreas($event.target.value)">
                            <option value="">বেছে নিন</option>
                            @foreach($districts as $d)
                                <option value="{{ $d->id }}">{{ $d->bn_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div x-show="areas.length > 0" x-transition style="display: none;">
                        <label for="area_id" class="block text-sm font-bold text-gray-700 mb-2">উপজেলা/থানা</label>
                        <select id="area_id" name="area_id" class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all font-medium text-gray-900">
                            <option value="">বেছে নিন</option>
                            <template x-for="a in areas" :key="a.id">
                                <option :value="a.id" x-text="a.bn_name"></option>
                            </template>
                        </select>
                    </div>
                </div>

                {{-- Password Section --}}
                <div class="grid sm:grid-cols-2 gap-4">
                    <div x-data="{ show: false }">
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-2">পাসওয়ার্ড</label>
                        <div class="relative">
                            <input id="password" :type="show ? 'text' : 'password'" name="password"
                                   class="w-full pl-4 pr-10 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all font-medium text-gray-900 @error('password') border-red-400 bg-red-50 @enderror"
                                   placeholder="কমপক্ষে ৮ অক্ষর" required>
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700">
                                <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        @error('password')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">পুনরায় পাসওয়ার্ড</label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                               class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all font-medium text-gray-900"
                               placeholder="নিশ্চিত করুন" required>
                    </div>
                </div>

                {{-- Terms --}}
                <div class="flex items-start mt-4">
                    <label class="flex items-start gap-2.5 text-sm font-medium text-gray-600 cursor-pointer group">
                        <input type="checkbox" required class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500 w-4.5 h-4.5 transition-colors">
                        <span class="leading-snug">আমি <a href="{{ route('terms') }}" class="text-primary-600 hover:underline">শর্তাবলী</a> এবং <a href="{{ route('privacy') }}" class="text-primary-600 hover:underline">গোপনীয়তা নীতির</a> সাথে একমত।</span>
                    </label>
                </div>

                <button type="submit" :disabled="loading"
                        class="w-full bg-primary-600 text-white font-bold text-lg py-3.5 mt-2 rounded-xl shadow-[0_8px_16px_-6px_rgba(15,118,110,0.4)] hover:bg-primary-700 hover:shadow-[0_12px_20px_-6px_rgba(15,118,110,0.5)] transition-all transform active:scale-[0.98] flex items-center justify-center gap-2">
                    <span x-show="!loading">একাউন্ট তৈরি করুন</span>
                    <span x-show="loading" x-cloak class="flex items-center gap-2">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        প্রসেসিং...
                    </span>
                </button>
                
                <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                    <p class="text-gray-600 text-sm font-medium">
                        ইতিমধ্যে একাউন্ট আছে? 
                        <a href="{{ route('login') }}" class="text-primary-600 font-extrabold hover:text-primary-700 ml-1 transition-colors">লগিন করুন</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
