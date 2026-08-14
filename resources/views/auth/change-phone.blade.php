@extends('layouts.auth')

@section('title', 'ফোন নম্বর পরিবর্তন করুন')

@section('content')
    <div class="text-center mb-6">
        <div class="w-14 h-14 bg-primary-50 border border-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-3.5 text-primary-600 shadow-sm">
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">ফোন নম্বর পরিবর্তন</h1>
        <p class="text-slate-600 text-sm mt-1.5 leading-relaxed">
            আপনার সঠিক ও সচল ১১-সংখ্যার মোবাইল নম্বর দিন। আমরা একটি নতুন OTP পাঠাব।
        </p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm mb-5 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('otp.change-phone.store') }}" class="space-y-5">
        @csrf

        {{-- Phone Input --}}
        <div>
            <label for="phone" class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">নতুন ফোন নম্বর</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <span class="text-slate-500 font-medium text-sm border-r border-slate-200 pr-2.5">+88</span>
                </div>
                <input id="phone" type="tel" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                       class="w-full pl-16 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-600 focus:ring-4 focus:ring-primary-500/10 transition-all font-medium text-slate-900 text-base @error('phone') border-red-400 bg-red-50 @enderror"
                       placeholder="017XXXXXXXX" required autofocus>
            </div>
            <p class="text-[11px] text-slate-500 mt-1">সঠিক ফরম্যাটে দিন (যেমন: 01712345678)</p>
            @error('phone')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 active:scale-[0.99] text-white font-semibold text-base py-3.5 px-4 rounded-xl shadow-md shadow-primary-600/20 transition-all flex items-center justify-center gap-2">
            নতুন নম্বর সংরক্ষণ ও OTP পাঠান
        </button>

        <div class="text-center pt-2">
            <a href="{{ route('otp.show') }}" class="text-xs sm:text-sm font-medium text-slate-600 hover:text-primary-700 hover:underline">
                ← OTP যাচাই পেজে ফিরে যান
            </a>
        </div>
    </form>
@endsection
