@extends('layouts.public')

@section('title', 'লগইন')

@section('content')
<div class="min-h-[calc(100vh-80px)] bg-gradient-to-br from-primary-50 via-white to-emerald-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">স্বাগতম!</h1>
            <p class="text-gray-500">আপনার একাউন্টে লগইন করুন</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
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
                    <label for="phone" class="form-label">ফোন নম্বর</label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                           class="input @error('phone') border-red-400 @enderror"
                           placeholder="01XXXXXXXXX" required autofocus>
                </div>

                {{-- Password --}}
                <div class="form-group mb-5" x-data="{ show: false }">
                    <label for="password" class="form-label">পাসওয়ার্ড</label>
                    <div class="relative">
                        <input id="password" :type="show ? 'text' : 'password'" name="password"
                               class="input pr-10 @error('password') border-red-400 @enderror"
                               placeholder="••••••••" required>
                        <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 w-4 h-4">
                        মনে রাখুন
                    </label>
                </div>

                <button type="submit" :disabled="loading"
                        class="btn btn-primary w-full justify-center py-3 text-base shadow-lg hover:shadow-xl transition-all">
                    <span x-show="!loading">লগইন করুন</span>
                    <span x-show="loading" x-cloak class="flex items-center gap-2">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        লগইন হচ্ছে...
                    </span>
                </button>

                <p class="text-center text-sm text-gray-500 mt-6 pt-6 border-t border-gray-100">
                    একাউন্ট নেই? 
                    <a href="{{ route('register') }}" class="text-primary-600 font-bold hover:underline">নতুন একাউন্ট তৈরি করুন</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
