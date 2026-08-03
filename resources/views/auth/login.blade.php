@extends('layouts.public')

@section('title', 'প্রবেশ করুন')

@section('content')
<div class="min-h-[calc(100vh-64px)] md:min-h-screen flex bg-white">
    
    {{-- Left Side: Branding / Graphic (Hidden on Mobile) --}}
    <div class="hidden lg:flex lg:w-1/2 relative bg-primary-900 text-white overflow-hidden items-center justify-center p-12">
        {{-- Premium Background Image --}}
        <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=1200&auto=format&fit=crop" alt="Community working together" class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-overlay">
        
        <div class="absolute inset-0 bg-gradient-to-t from-primary-900 via-primary-900/80 to-primary-900/40"></div>

        <div class="relative z-10 max-w-lg text-center">
            <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 flex items-center justify-center mx-auto mb-8 shadow-2xl">
                <svg class="w-10 h-10 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <h1 class="text-4xl font-extrabold mb-6 leading-tight">আপনার এলাকার <br>সবচেয়ে বিশ্বস্ত মার্কেটপ্লেস</h1>
            <p class="text-primary-100 text-lg leading-relaxed">LocalEmployments-এ যুক্ত হয়ে আপনার প্রতিদিনের কাজগুলোকে করুন আরও সহজ এবং নিরাপদ।</p>
            
            <div class="mt-12 flex justify-center gap-4 text-sm text-primary-200">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>যাচাইকৃত কর্মী</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>নিরাপদ পেমেন্ট</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Side: Login Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-16 xl:p-24 bg-gray-50/50 lg:bg-white relative">
        <div class="w-full max-w-md">
            
            {{-- Mobile Title --}}
            <div class="mb-10 lg:mb-12 text-center lg:text-left">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-3 tracking-tight">স্বাগতম!</h2>
                <p class="text-gray-500 text-base">একাউন্টে প্রবেশ করতে আপনার ফোন নম্বর দিন</p>
            </div>

            <form method="POST" action="{{ route('login.attempt') }}" x-data="{ loading: false }" @submit="loading = true" class="space-y-6">
                @csrf

                {{-- Errors --}}
                @if($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm font-medium flex items-start gap-3 border border-red-100">
                        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">ফোন নম্বর</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-medium border-r border-gray-200 pr-3">+880</span>
                        </div>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                               class="w-full pl-[5.5rem] pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all font-medium text-gray-900 @error('phone') border-red-400 bg-red-50 @enderror"
                               placeholder="1XXXXXXXXX" required autofocus>
                    </div>
                </div>

                {{-- Password --}}
                <div x-data="{ show: false }">
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-sm font-bold text-gray-700">পাসওয়ার্ড</label>
                        <a href="#" class="text-sm font-bold text-primary-600 hover:text-primary-700">ভুলে গেছেন?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input id="password" :type="show ? 'text' : 'password'" name="password"
                               class="w-full pl-12 pr-12 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all font-medium text-gray-900 @error('password') border-red-400 bg-red-50 @enderror"
                               placeholder="••••••••" required>
                        <button type="button" @click="show = !show"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 transition-colors focus:outline-none">
                            <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="flex items-center">
                    <label class="flex items-center gap-2.5 text-sm font-medium text-gray-700 cursor-pointer group">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 w-4.5 h-4.5 transition-colors">
                        <span class="group-hover:text-gray-900 transition-colors">আমাকে মনে রাখুন</span>
                    </label>
                </div>

                <button type="submit" :disabled="loading"
                        class="w-full bg-primary-600 text-white font-bold text-lg py-3.5 rounded-xl shadow-[0_8px_16px_-6px_rgba(15,118,110,0.4)] hover:bg-primary-700 hover:shadow-[0_12px_20px_-6px_rgba(15,118,110,0.5)] transition-all transform active:scale-[0.98] flex items-center justify-center gap-2">
                    <span x-show="!loading">প্রবেশ করুন</span>
                    <span x-show="loading" x-cloak class="flex items-center gap-2">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        অপেক্ষা করুন...
                    </span>
                </button>
                
                <div class="mt-8 pt-8 border-t border-gray-100 text-center">
                    <p class="text-gray-600 text-sm font-medium">
                        একাউন্ট নেই? 
                        <a href="{{ route('register') }}" class="text-primary-600 font-extrabold hover:text-primary-700 ml-1 transition-colors">নতুন একাউন্ট খুলুন</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
