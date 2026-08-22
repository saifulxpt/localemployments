@extends('layouts.public')

@section('title', 'মেনু ও অ্যাকাউন্ট — ' . setting('site_name', 'LocalEmployments'))

@section('content')
<div class="min-h-screen bg-gray-50/50 py-6 md:py-10 pb-24">
    <div class="container mx-auto px-4 max-w-xl">

        {{-- ─────────────────────────────────────────── --}}
        {{-- 1. TOP ACCOUNT PROFILE CARD (HERO BANNER) --}}
        {{-- ─────────────────────────────────────────── --}}
        <div class="bg-gradient-to-br from-primary-900 via-primary-800 to-emerald-800 rounded-3xl p-6 text-white shadow-xl mb-6 relative overflow-hidden">
            {{-- Background Subtle Glow --}}
            <div class="absolute -top-12 -right-12 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[11px] uppercase tracking-widest font-extrabold text-teal-200 bg-white/10 px-3 py-1 rounded-full border border-white/15">
                        YOUR ACCOUNT
                    </span>

                    {{-- Role Badge --}}
                    @if($user->isSeeker())
                        <span class="text-xs font-bold bg-emerald-400 text-gray-950 px-3 py-1 rounded-full shadow-sm">
                            সেবা গ্রহীতা (Seeker)
                        </span>
                    @elseif($user->isProvider())
                        <span class="text-xs font-bold bg-amber-400 text-gray-950 px-3 py-1 rounded-full shadow-sm">
                            সার্ভিস প্রোভাইডার (Provider)
                        </span>
                    @elseif($user->isAdmin())
                        <span class="text-xs font-bold bg-red-400 text-gray-950 px-3 py-1 rounded-full shadow-sm">
                            সুপার এডমিন (Admin)
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    {{-- Avatar / Initials --}}
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-2xl object-cover ring-4 ring-white/20 shadow-md">
                    @else
                        @php
                            $initials = collect(explode(' ', $user->name))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->join('');
                        @endphp
                        <div class="w-16 h-16 rounded-2xl bg-teal-950/60 border-2 border-white/30 flex items-center justify-center text-white font-extrabold text-2xl shadow-inner">
                            {{ $initials ?: 'LE' }}
                        </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <h1 class="text-xl font-extrabold text-white truncate leading-tight">
                            {{ $user->name }}
                        </h1>
                        <p class="text-xs text-teal-100 mt-1 truncate font-medium">
                            {{ $user->phone ? format_bd_phone($user->phone) : $user->email }}
                        </p>
                        <p class="text-[11px] text-teal-200/80 mt-0.5">
                            যোগদান: {{ $user->created_at->format('M Y') }}
                        </p>
                    </div>
                </div>

                {{-- Seeker Upsell to Become Provider --}}
                @if($user->isSeeker())
                    <div class="mt-4 pt-3 border-t border-white/15 flex items-center justify-between">
                        <span class="text-xs text-teal-100">দক্ষ কর্মী হিসেবে আয় করতে চান?</span>
                        <a href="{{ route('seeker.become-provider') }}" class="text-xs font-bold text-emerald-300 hover:text-white underline flex items-center gap-1">
                            প্রোভাইডার হিসেবে যুক্ত হোন →
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- ─────────────────────────────────────────── --}}
        {{-- 2. ROLE SPECIFIC ACTION GROUPS --}}
        {{-- ─────────────────────────────────────────── --}}
        <div class="space-y-6">

            {{-- ============================================== --}}
            {{-- CASE A: EMPLOYER / SEEKER (সেবা গ্রহীতা) --}}
            {{-- ============================================== --}}
            @if($user->isSeeker())

                {{-- GROUP 1: ACTIVITY (কাজের কার্যক্রম) --}}
                <div>
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-gray-500 mb-2.5 px-1">
                        ACTIVITY (কাজের কার্যক্রম)
                    </h2>
                    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs divide-y divide-gray-100 overflow-hidden">
                        
                        {{-- 1. My Job Requests --}}
                        <a href="{{ route('seeker.job-requests.index') }}" class="flex items-center gap-4 p-4 hover:bg-teal-50/50 transition-colors group">
                            <div class="w-11 h-11 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform shadow-xs">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-bold text-sm text-gray-900 group-hover:text-primary-700">আমার পোস্টকৃত কাজসমূহ</h3>
                                    @if(isset($counts['jobRequests']) && $counts['jobRequests'] > 0)
                                        <span class="bg-teal-100 text-teal-800 text-[11px] font-bold px-2 py-0.5 rounded-full">
                                            {{ $counts['jobRequests'] }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5">আপনার রিকোয়েস্টসমূহ ও প্রোভাইডারদের বিড দেখুন</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 shrink-0 group-hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        {{-- 2. Post a Job --}}
                        <a href="{{ route('seeker.job-requests.create') }}" class="flex items-center gap-4 p-4 hover:bg-emerald-50/50 transition-colors group">
                            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform shadow-xs">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-gray-900 group-hover:text-emerald-700">নতুন কাজ পোস্ট করুন</h3>
                                <p class="text-xs text-gray-500 mt-0.5">২ মিনিটে যেকোনো কাজের বিবরণ দিয়ে ফ্রি পোস্ট করুন</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        {{-- 3. Bookings --}}
                        <a href="{{ route('seeker.bookings.index') }}" class="flex items-center gap-4 p-4 hover:bg-blue-50/50 transition-colors group">
                            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform shadow-xs">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-gray-900 group-hover:text-blue-700">আমার বুকিং ও সার্ভিস</h3>
                                <p class="text-xs text-gray-500 mt-0.5">চলমান ও সম্পন্ন হওয়া সার্ভিসের রেকর্ড</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        {{-- 4. Messages --}}
                        <a href="{{ route('seeker.messages.index') }}" class="flex items-center gap-4 p-4 hover:bg-indigo-50/50 transition-colors group">
                            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform shadow-xs">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-gray-900 group-hover:text-indigo-700">মেসেজ ও ইনবক্স</h3>
                                <p class="text-xs text-gray-500 mt-0.5">কর্মী ও টেকনিশিয়ানদের সাথে সরাসরি যোগাযোগ</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                    </div>
                </div>

                {{-- GROUP 2: ACCOUNT & SETTINGS --}}
                <div>
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-gray-500 mb-2.5 px-1">
                        ACCOUNT & SETTINGS (অ্যাকাউন্ট ও সেটিংস)
                    </h2>
                    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs divide-y divide-gray-100 overflow-hidden">
                        
                        <a href="{{ route('seeker.profile.edit') }}" class="flex items-center gap-4 p-4 hover:bg-gray-50 transition-colors group">
                            <div class="w-11 h-11 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-gray-900">প্রোফাইল সেটিংস</h3>
                                <p class="text-xs text-gray-500 mt-0.5">আপনার নাম, ফোন নম্বর ও ছবি আপডেট করুন</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        <a href="{{ route('seeker.become-provider') }}" class="flex items-center gap-4 p-4 hover:bg-amber-50/50 transition-colors group">
                            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-gray-900 group-hover:text-amber-800">কর্মী হতে চান? (Become a Provider)</h3>
                                <p class="text-xs text-gray-500 mt-0.5">আপনার দক্ষতা দিয়ে লোকাল সার্ভিসে আয় শুরু করুন</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                    </div>
                </div>

            {{-- ============================================== --}}
            {{-- CASE B: EMPLOYEE / PROVIDER (কর্মী / টেকনিশিয়ান) --}}
            {{-- ============================================== --}}
            @elseif($user->isProvider())

                {{-- GROUP 1: JOBS & EARNINGS --}}
                <div>
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-gray-500 mb-2.5 px-1">
                        JOBS & EARNINGS (কাজ ও আয়)
                    </h2>
                    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs divide-y divide-gray-100 overflow-hidden">
                        
                        {{-- 1. Find Jobs --}}
                        <a href="{{ route('provider.jobs.index') }}" class="flex items-center gap-4 p-4 hover:bg-teal-50/50 transition-colors group">
                            <div class="w-11 h-11 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform shadow-xs">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-gray-900 group-hover:text-primary-700">উন্মুক্ত কাজ খুঁজুন</h3>
                                <p class="text-xs text-gray-500 mt-0.5">আপনার এলাকার গ্রাহকদের পোস্ট করা কাজে বিড করুন</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        {{-- 2. My Bids --}}
                        <a href="{{ route('provider.bids.index') }}" class="flex items-center gap-4 p-4 hover:bg-blue-50/50 transition-colors group">
                            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform shadow-xs">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-gray-900 group-hover:text-blue-700">আমার বিডসমূহ</h3>
                                <p class="text-xs text-gray-500 mt-0.5">আপনার পাঠানো অফার ও গৃহীত কাজের স্ট্যাটাস</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        {{-- 3. Bookings --}}
                        <a href="{{ route('provider.bookings.index') }}" class="flex items-center gap-4 p-4 hover:bg-purple-50/50 transition-colors group">
                            <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform shadow-xs">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-gray-900 group-hover:text-purple-700">চলমান বুকিং ও কাজ</h3>
                                <p class="text-xs text-gray-500 mt-0.5">নির্ধারিত বুকিং, কাজের টাইমার ও সমাপ্তি</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        {{-- 4. Earnings & Withdrawals --}}
                        <a href="{{ route('provider.earnings.index') }}" class="flex items-center gap-4 p-4 hover:bg-emerald-50/50 transition-colors group">
                            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform shadow-xs">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-gray-900 group-hover:text-emerald-700">আমার আয় ও টাকা উত্তোলন</h3>
                                <p class="text-xs text-gray-500 mt-0.5">মোট আয় ব্যালেন্স ও বিকাশ/নগদ উত্তোলন</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                    </div>
                </div>

                {{-- GROUP 2: SKILLS & VERIFICATION --}}
                <div>
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-gray-500 mb-2.5 px-1">
                        SKILLS & SERVICES (দক্ষতা ও ভেরিফিকেশন)
                    </h2>
                    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs divide-y divide-gray-100 overflow-hidden">
                        
                        <a href="{{ route('provider.skills.manage') }}" class="flex items-center gap-4 p-4 hover:bg-orange-50/50 transition-colors group">
                            <div class="w-11 h-11 rounded-xl bg-orange-50 text-orange-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-gray-900 group-hover:text-orange-700">আমার দক্ষতাসমূহ</h3>
                                <p class="text-xs text-gray-500 mt-0.5">যেসব কাজের বিজ্ঞপ্তি আপনি পেতে চান</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        <a href="{{ route('provider.verification.show') }}" class="flex items-center gap-4 p-4 hover:bg-yellow-50/50 transition-colors group">
                            <div class="w-11 h-11 rounded-xl bg-yellow-50 text-yellow-700 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-gray-900 group-hover:text-yellow-800">আইডি ও এনআইডি ভেরিফিকেশন</h3>
                                <p class="text-xs text-gray-500 mt-0.5">যাচাইকৃত কর্মী ব্যাজ পেয়ে কাজের সম্ভাবনা বাড়ান</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                    </div>
                </div>

            {{-- ============================================== --}}
            {{-- CASE C: ADMIN (এডমিন) --}}
            {{-- ============================================== --}}
            @elseif($user->isAdmin())

                <div>
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-gray-500 mb-2.5 px-1">
                        ADMINISTRATION (এডমিন প্যানেল)
                    </h2>
                    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs divide-y divide-gray-100 overflow-hidden">
                        
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 p-4 hover:bg-gray-50 transition-colors group">
                            <div class="w-11 h-11 rounded-xl bg-gray-900 text-white flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-gray-900">এডমিন ড্যাশবোর্ড</h3>
                                <p class="text-xs text-gray-500 mt-0.5">প্ল্যাটফর্ম সামগ্রিক রিপোর্ট ও স্ট্যাটাস</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-4 p-4 hover:bg-gray-50 transition-colors group">
                            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-gray-900">ইউজার ম্যানেজমেন্ট</h3>
                                <p class="text-xs text-gray-500 mt-0.5">সকল গ্রাহক ও প্রোভাইডার প্রোফাইল</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-4 p-4 hover:bg-gray-50 transition-colors group">
                            <div class="w-11 h-11 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm text-gray-900">সিস্টেম সেটিংস</h3>
                                <p class="text-xs text-gray-500 mt-0.5">সাইট নাম, লোগো, API ও কনফিগারেশন</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                    </div>
                </div>

            @endif

            {{-- ─────────────────────────────────────────── --}}
            {{-- 3. SUPPORT & LEGAL (COMMON) --}}
            {{-- ─────────────────────────────────────────── --}}
            <div>
                <h2 class="text-xs font-extrabold uppercase tracking-wider text-gray-500 mb-2.5 px-1">
                    SUPPORT & INFORMATION (সহায়তা ও তথ্য)
                </h2>
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs divide-y divide-gray-100 overflow-hidden">
                    
                    <a href="{{ route('contact') }}" class="flex items-center gap-4 p-4 hover:bg-gray-50 transition-colors group">
                        <div class="w-11 h-11 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-sm text-gray-900">যোগাযোগ ও হেল্পলাইন</h3>
                            <p class="text-xs text-gray-500 mt-0.5">যেকোনো প্রশ্ন বা সমস্যায় সরাসরি মেসেজ পাঠান</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <a href="{{ route('faq') }}" class="flex items-center gap-4 p-4 hover:bg-gray-50 transition-colors group">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-sm text-gray-900">সাধারণ প্রশ্নাবলী (FAQ)</h3>
                            <p class="text-xs text-gray-500 mt-0.5">সবচেয়ে বেশি জিজ্ঞাসিত প্রশ্নের উত্তর</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <a href="{{ route('terms') }}" class="flex items-center gap-4 p-4 hover:bg-gray-50 transition-colors group">
                        <div class="w-11 h-11 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-sm text-gray-900">শর্তাবলী ও প্রাইভেসী পলিসি</h3>
                            <p class="text-xs text-gray-500 mt-0.5">ব্যবহারবিধি ও নিয়মকানুন</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                </div>
            </div>

            {{-- ─────────────────────────────────────────── --}}
            {{-- 4. LOGOUT BUTTON CARD --}}
            {{-- ─────────────────────────────────────────── --}}
            <div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-white hover:bg-red-50 border border-red-200/80 rounded-2xl p-4 flex items-center justify-center gap-3 text-red-600 font-bold text-sm shadow-xs transition-all cursor-pointer">
                        <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>লগআউট করুন (Logout)</span>
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>
@endsection
