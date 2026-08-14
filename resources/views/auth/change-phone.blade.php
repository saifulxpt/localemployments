@extends('layouts.auth')

@section('title', 'ফোন নম্বর পরিবর্তন করুন')

@section('content')
    <div class="text-center mb-6">
        <div class="w-16 h-16 bg-primary-50 border border-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-primary-600 shadow-sm">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">ফোন নম্বর পরিবর্তন</h1>
        <p class="text-gray-500 text-sm mt-2">
            আপনার সঠিক ও সচল ১১-সংখ্যার মোবাইল নম্বর দিন। আমরা একটি নতুন OTP পাঠাব।
        </p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-5 flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('otp.change-phone.store') }}" class="space-y-5">
        @csrf

        {{-- Phone Input --}}
        <div>
            <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">নতুন ফোন নম্বর</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-gray-500 font-medium border-r border-gray-200 pr-3">+88</span>
                </div>
                <input id="phone" type="tel" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                       class="w-full pl-[5.5rem] pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-600 focus:ring-4 focus:ring-primary-500/10 transition-all font-medium text-gray-900 text-base @error('phone') border-red-400 bg-red-50 @enderror"
                       placeholder="017XXXXXXXX" required autofocus>
            </div>
            <p class="text-xs text-gray-500 mt-1.5">সঠিক ফরম্যাটে দিন (যেমন: 01712345678)</p>
            @error('phone')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="btn btn-primary w-full justify-center py-3.5 text-base font-bold shadow-md hover:shadow-lg transition-all">
            নতুন নম্বর সংরক্ষণ ও OTP পাঠান
        </button>

        <div class="text-center pt-3">
            <a href="{{ route('otp.show') }}" class="text-sm font-medium text-gray-600 hover:text-primary-700 hover:underline">
                ← OTP যাচাই পেজে ফিরে যান
            </a>
        </div>
    </form>
@endsection
