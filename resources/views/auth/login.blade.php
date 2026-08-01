@extends('layouts.public')

@section('title', 'লগইন')

@section('content')
<div class="min-h-[calc(100vh-80px)] flex bg-white">
    
    {{-- Left Side: Branding / Marketing --}}
    <div class="hidden lg:flex lg:w-1/2 relative bg-primary-900 items-center justify-center overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <pattern id="grid-login" width="10" height="10" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1" fill="white"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#grid-login)"/>
            </svg>
        </div>
        
        <div class="relative z-10 max-w-lg px-12 text-white">
            <h2 class="text-4xl font-extrabold mb-6 leading-tight">আপনার লোকাল সার্ভিস কমিউনিটিতে <br><span class="text-yellow-400">স্বাগতম!</span></h2>
            <p class="text-primary-100 text-lg mb-8">সবচেয়ে দ্রুত এবং সহজে আপনার এলাকার বিশ্বস্ত কর্মীদের খুঁজে পেতে বা কাজ পেতে লগইন করুন।</p>
            
            <div class="flex items-center gap-4 text-sm font-medium text-primary-200">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    বিশ্বস্ত কর্মী
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    দ্রুত সার্ভিস
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    নিরাপদ পেমেন্ট
                </div>
            </div>
        </div>
        
        {{-- Decorative Circles --}}
        <div class="absolute -top-24 -left-24 w-64 h-64 bg-primary-600 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
        <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-emerald-600 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
    </div>

    {{-- Right Side: Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center px-4 sm:px-6 lg:px-12 py-12">
        <div class="w-full max-w-md">
            
            <div class="text-center lg:text-left mb-10">
                <h1 class="text-3xl font-extrabold text-gray-900 mb-2">ফিরে আসার জন্য ধন্যবাদ!</h1>
                <p class="text-gray-500">আপনার একাউন্টে লগইন করুন</p>
            </div>

            <form method="POST" action="{{ route('login.attempt') }}" x-data="{ loading: false }" @submit="loading = true">
                @csrf

                {{-- Errors --}}
                @if($errors->any())
                    <div class="alert alert-danger mb-6">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- Phone --}}
                <div class="form-group mb-5">
                    <label for="phone" class="form-label block text-sm font-semibold text-gray-700 mb-2">ফোন নম্বর</label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                           class="input w-full bg-gray-50 focus:bg-white border-gray-200 focus:border-primary-500 transition-colors @error('phone') border-red-400 @enderror"
                           placeholder="01XXXXXXXXX" required autofocus>
                </div>

                {{-- Password --}}
                <div class="form-group mb-5" x-data="{ show: false }">
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="form-label block text-sm font-semibold text-gray-700">পাসওয়ার্ড</label>
                        <a href="#" class="text-xs font-semibold text-primary-600 hover:text-primary-700">পাসওয়ার্ড ভুলে গেছেন?</a>
                    </div>
                    <div class="relative">
                        <input id="password" :type="show ? 'text' : 'password'" name="password"
                               class="input w-full pr-10 bg-gray-50 focus:bg-white border-gray-200 focus:border-primary-500 transition-colors @error('password') border-red-400 @enderror"
                               placeholder="••••••••" required>
                        <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none p-1 rounded-md">
                            <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="flex items-center mb-8">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer group">
                        <div class="relative flex items-center justify-center">
                            <input type="checkbox" name="remember" class="peer appearance-none w-5 h-5 border-2 border-gray-300 rounded-md checked:bg-primary-600 checked:border-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1 transition-all cursor-pointer">
                            <svg class="absolute w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="group-hover:text-gray-900 transition-colors">আমাকে মনে রাখুন</span>
                    </label>
                </div>

                <button type="submit" :disabled="loading"
                        class="btn btn-primary w-full justify-center py-3.5 text-base shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 hover:-translate-y-0.5 transition-all">
                    <span x-show="!loading">লগইন করুন</span>
                    <span x-show="loading" x-cloak class="flex items-center gap-2">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        লগইন হচ্ছে...
                    </span>
                </button>

                <p class="text-center text-sm text-gray-500 mt-8 pt-6 border-t border-gray-100">
                    একাউন্ট নেই? 
                    <a href="{{ route('register') }}" class="text-primary-600 font-bold hover:text-primary-700 transition-colors">নতুন একাউন্ট তৈরি করুন</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
