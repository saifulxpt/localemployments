@extends('layouts.public')

@section('title', 'অ্যাকাউন্ট সম্পূর্ণ করুন')

@section('content')
<div class="min-h-[calc(100vh-64px)] md:min-h-screen flex bg-white">
    
    {{-- Left Side: Branding / Graphic --}}
    <div class="hidden lg:flex lg:w-1/2 relative bg-primary-900 text-white overflow-hidden items-center justify-center p-12">
        <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32d7?q=80&w=1200&auto=format&fit=crop" alt="Customer service handshake" class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-overlay">
        
        <div class="absolute inset-0 bg-gradient-to-t from-primary-900 via-primary-900/80 to-primary-900/40"></div>

        <div class="relative z-10 max-w-lg text-center">
            <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 flex items-center justify-center mx-auto mb-8 shadow-2xl">
                <svg class="w-10 h-10 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <h1 class="text-4xl font-extrabold mb-6 leading-tight">আর মাত্র একটি ধাপ</h1>
            <p class="text-primary-100 text-lg leading-relaxed">Google এর মাধ্যমে আপনার বেসিক তথ্য পেয়েছি। অ্যাকাউন্টের নিরাপত্তা এবং সেবার মান নিশ্চিতে আপনার ফোন নম্বরটি প্রয়োজন।</p>
        </div>
    </div>

    {{-- Right Side: Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-16 bg-gray-50/50 lg:bg-white relative">
        <div class="w-full max-w-md">
            
            <div class="mb-10 text-center lg:text-left">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-3 tracking-tight">ফোন নম্বর যোগ করুন</h2>
                <p class="text-gray-500 text-base">OTP যাচাইয়ের জন্য আপনার সচল মোবাইল নম্বর দিন</p>
            </div>

            <form method="POST" action="{{ route('auth.google.phone.store') }}" class="space-y-5"
                  x-data="{
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

                @if($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm font-medium flex items-start gap-3 border border-red-100 mb-6">
                        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">ফোন নম্বর</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-medium border-r border-gray-200 pr-3">+88</span>
                        </div>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                               class="w-full pl-[5.5rem] pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all font-medium text-gray-900 @error('phone') border-red-400 bg-red-50 @enderror"
                               placeholder="017XXXXXXXX" required autofocus>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">সঠিক ফরম্যাটে দিন (যেমন: 01712345678)</p>
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

                <button type="submit" :disabled="loading"
                        class="w-full bg-primary-600 text-white font-bold text-lg py-3.5 mt-2 rounded-xl shadow-[0_8px_16px_-6px_rgba(15,118,110,0.4)] hover:bg-primary-700 hover:shadow-[0_12px_20px_-6px_rgba(15,118,110,0.5)] transition-all transform active:scale-[0.98] flex items-center justify-center gap-2">
                    <span x-show="!loading">এগিয়ে যান</span>
                    <span x-show="loading" x-cloak class="flex items-center gap-2">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        প্রসেসিং...
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
