@extends('layouts.public')

@section('title', 'LocalEmployments — উন্মুক্ত লোকাল জব ও সার্ভিস মার্কেটপ্লেস')
@section('meta_description', 'বাংলাদেশের সেরা লোকাল সার্ভিস মার্কেটপ্লেস। আপনার এলাকার উন্মুক্ত কাজগুলো দেখুন, বিড করুন অথবা যেকোনো কাজের জন্য পোস্ট করুন।')

@section('content')
<div class="space-y-10 md:space-y-16 pb-20">

    {{-- ─────────────────────────────────────────── --}}
    {{-- 1. HERO & SMART SEARCH SECTION (SPACIOUS & ELEGANT) --}}
    {{-- ─────────────────────────────────────────── --}}
    <section class="bg-gradient-to-b from-primary-900 via-[#0f766e] to-[#115e59] text-white pt-12 pb-16 md:pt-20 md:pb-24 px-4 rounded-b-[2.5rem] shadow-xl relative overflow-hidden">
        {{-- Subtle background decoration --}}
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="container mx-auto max-w-5xl relative z-10">
            
            {{-- Tagline & Heading --}}
            <div class="text-center mb-8 md:mb-10 max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-4 py-1.5 rounded-full text-xs md:text-sm font-semibold text-primary-100 mb-4 shadow-sm">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    বাংলাদেশের বিশ্বস্ত লোকাল সার্ভিস ও ওপেন জব মার্কেটপ্লেস
                </span>
                
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-[1.15] mb-4">
                    আপনার এলাকায় <br class="hidden sm:inline">
                    কী সেবা বা কর্মী প্রয়োজন?
                </h1>
                
                <p class="text-sm sm:text-base md:text-lg text-teal-100 max-w-2xl mx-auto leading-relaxed">
                    সহজেই আপনার কাজের রিকোয়েস্ট পোস্ট করুন অথবা উন্মুক্ত কাজসমূহ খুঁজে সরাসরি বিড করুন।
                </p>
            </div>

            {{-- Smart Unified Search Bar --}}
            <form action="{{ route('jobs.index') }}" method="GET" class="bg-white p-3 md:p-3.5 rounded-2xl md:rounded-3xl shadow-2xl flex flex-col md:flex-row gap-3 border border-white/30 max-w-4xl mx-auto">
                
                {{-- District / Location Dropdown --}}
                <div class="flex-1 md:max-w-[240px] relative border-b md:border-b-0 md:border-r border-gray-200">
                    <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none text-teal-700">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <select name="district" class="w-full pl-11 pr-4 py-3 md:py-3.5 text-gray-800 bg-transparent text-sm md:text-base font-medium border-0 focus:ring-0 cursor-pointer">
                        <option value="">সকল জেলা</option>
                        @foreach(\App\Models\District::active()->get() as $d)
                            <option value="{{ $d->id }}" {{ request('district') == $d->id ? 'selected' : '' }}>{{ $d->bn_name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Keyword Search Input --}}
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="সার্ভিস বা কাজের নাম লিখুন (যেমন: এসি, প্লাম্বার, ক্লিনিং, শিফটিং...)" class="w-full pl-11 pr-4 py-3 md:py-3.5 text-gray-900 text-sm md:text-base placeholder-gray-400 border-0 focus:ring-0 outline-none">
                </div>

                {{-- Search Button --}}
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold px-8 py-3.5 rounded-xl md:rounded-2xl text-sm md:text-base transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>সার্চ করুন</span>
                </button>
            </form>

            {{-- Quick Filter Pills --}}
            <div class="flex flex-wrap items-center justify-center gap-2 mt-5 text-xs md:text-sm text-teal-100">
                <span class="text-teal-200 font-semibold">জনপ্রিয় কাজ:</span>
                @foreach(['এসি সার্ভিসিং', 'হাউস ক্লিনিং', 'ইলেকট্রিশিয়ান', 'প্লাম্বার', 'শিফটিং', 'রং মিস্ত্রি'] as $tag)
                    <a href="{{ route('jobs.index') }}?q={{ urlencode($tag) }}" class="bg-white/10 hover:bg-white/20 text-white px-3.5 py-1.5 rounded-full border border-white/15 backdrop-blur-sm transition-colors">
                        {{ $tag }}
                    </a>
                @endforeach
            </div>

        </div>
    </section>

    {{-- ─────────────────────────────────────────── --}}
    {{-- 2. QUICK ACTION BANNER (POST JOB CTA) --}}
    {{-- ─────────────────────────────────────────── --}}
    <section class="container mx-auto px-4 max-w-6xl">
        <div class="bg-gradient-to-r from-[#0f766e] via-[#0d9488] to-emerald-600 rounded-3xl p-6 sm:p-8 md:p-10 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-5 text-center md:text-left">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-white/15 rounded-2xl flex items-center justify-center shrink-0 border border-white/20 shadow-inner">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-white mb-1.5">জরুরি কোনো সার্ভিস বা কর্মী প্রয়োজন?</h2>
                    <p class="text-xs sm:text-sm md:text-base text-teal-100 leading-relaxed">
                        বিনামূল্যে আপনার কাজের বিস্তারিত উল্লেখ করে পোস্ট করুন এবং দক্ষ কর্মীদের থেকে সরাসরি সেরা অফার গ্রহণ করুন।
                    </p>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto shrink-0">
                <a href="{{ route('jobs.post') }}" class="w-full sm:w-auto text-center bg-white text-teal-900 hover:bg-gray-50 px-8 py-3.5 rounded-2xl font-extrabold text-sm md:text-base shadow-md hover:shadow-lg transition-transform active:scale-95 whitespace-nowrap">
                    + কাজ পোস্ট করুন
                </a>
                <a href="{{ route('jobs.index') }}" class="w-full sm:w-auto text-center bg-teal-950/40 hover:bg-teal-950/60 text-white px-6 py-3.5 rounded-2xl font-semibold text-sm md:text-base border border-white/25 transition-colors whitespace-nowrap">
                    উন্মুক্ত কাজ দেখুন
                </a>
            </div>
        </div>
    </section>

    {{-- ─────────────────────────────────────────── --}}
    {{-- 3. SHEBA-STYLE CATEGORIES GRID (SPACIOUS & CLEAN) --}}
    {{-- ─────────────────────────────────────────── --}}
    <section class="container mx-auto px-4 max-w-6xl">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6 md:mb-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-primary-700">ক্যাটাগরি সমূহ</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">জনপ্রিয় সেবাসমূহ</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">আপনার প্রয়োজনীয় ক্যাটাগরিটি বেছে নিয়ে সরাসরি বিস্তারিত জানুন</p>
            </div>
            <a href="{{ route('services.index') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-primary-700 hover:text-primary-900 group">
                সকল ক্যাটাগরি দেখুন
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        {{-- Categories Responsive Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 md:gap-5">
            @php
                $palette = [
                    ['bg' => 'bg-teal-50', 'text' => 'text-teal-700', 'border' => 'border-teal-100', 'hover' => 'hover:border-teal-300'],
                    ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-100', 'hover' => 'hover:border-blue-300'],
                    ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-100', 'hover' => 'hover:border-amber-300'],
                    ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-100', 'hover' => 'hover:border-rose-300'],
                    ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-100', 'hover' => 'hover:border-indigo-300'],
                    ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-100', 'hover' => 'hover:border-emerald-300'],
                    ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-100', 'hover' => 'hover:border-orange-300'],
                    ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-100', 'hover' => 'hover:border-purple-300'],
                ];
            @endphp

            @foreach($categories as $idx => $cat)
                @php
                    $color = $palette[$idx % count($palette)];
                @endphp
                <a href="{{ route('jobs.index') }}?category={{ $cat->id }}" class="bg-white rounded-2xl md:rounded-3xl p-5 md:p-6 border {{ $color['border'] }} {{ $color['hover'] }} shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col items-center text-center group">
                    <div class="w-14 h-14 md:w-16 md:h-16 {{ $color['bg'] }} {{ $color['text'] }} rounded-2xl flex items-center justify-center mb-3.5 transform group-hover:scale-110 transition-transform duration-300 shadow-xs">
                        @if($cat->icon)
                            {!! category_icon($cat->icon, 'w-7 h-7 md:w-8 md:h-8') !!}
                        @else
                            <svg class="w-7 h-7 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        @endif
                    </div>
                    <span class="text-sm md:text-base font-bold text-gray-800 group-hover:text-primary-700 transition-colors leading-snug">
                        {{ $cat->name }}
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ─────────────────────────────────────────── --}}
    {{-- 4. LIVE OPEN JOBS BOARD (REAL-TIME ON-DEMAND FEED) --}}
    {{-- ─────────────────────────────────────────── --}}
    <section class="container mx-auto px-4 max-w-6xl">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6 md:mb-8">
            <div class="flex items-center gap-3">
                <div class="w-3.5 h-3.5 rounded-full bg-emerald-500 animate-ping"></div>
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">লাইভ ওপেন জবস</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-0.5">চলমান উন্মুক্ত কাজসমূহ</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">আপনার এলাকায় সরাসরি গ্রাহকদের পোস্ট করা কাজ দেখুন ও বিড করুন</p>
                </div>
            </div>
            <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-primary-700 hover:text-primary-900 group">
                সকল উন্মুক্ত কাজ দেখুন →
            </a>
        </div>

        @if(isset($latestJobs) && $latestJobs->count() > 0)
            {{-- Job Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6">
                @foreach($latestJobs as $job)
                    <div class="bg-white rounded-2xl md:rounded-3xl p-5 md:p-6 border border-gray-100/90 shadow-sm hover:shadow-xl hover:border-primary-200 transition-all duration-300 flex flex-col justify-between group">
                        <div>
                            {{-- Top tags --}}
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="bg-teal-50 text-teal-800 text-xs font-bold px-3 py-1 rounded-full border border-teal-100">
                                    {{ $job->subcategory?->name ?? 'জেনারেল সার্ভিস' }}
                                </span>
                                <span class="text-xs text-gray-400 font-medium">
                                    {{ $job->created_at->diffForHumans() }}
                                </span>
                            </div>

                            {{-- Title --}}
                            <h3 class="font-bold text-base md:text-lg text-gray-900 group-hover:text-primary-700 transition-colors line-clamp-1 mb-2">
                                {{ $job->title }}
                            </h3>

                            {{-- Description --}}
                            <p class="text-xs sm:text-sm text-gray-600 line-clamp-2 mb-4 leading-relaxed">
                                {{ $job->description }}
                            </p>
                        </div>

                        <div>
                            {{-- Location & Budget --}}
                            <div class="flex items-center justify-between text-xs sm:text-sm pt-3 border-t border-gray-100 mb-4 font-medium">
                                <span class="flex items-center gap-1.5 text-gray-600">
                                    <svg class="w-4 h-4 text-primary-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    {{ $job->district?->bn_name ?? 'ঢাকা' }}
                                </span>
                                <span class="font-bold text-emerald-800 bg-emerald-50 px-3 py-1 rounded-lg border border-emerald-100 text-xs sm:text-sm">
                                    ৳{{ number_format($job->budget_min) }} - ৳{{ number_format($job->budget_max) }}
                                </span>
                            </div>

                            {{-- Action Button --}}
                            <a href="{{ route('jobs.index') }}" class="block text-center py-2.5 md:py-3 bg-gray-900 group-hover:bg-primary-700 text-white text-xs sm:text-sm font-bold rounded-xl transition-colors shadow-sm">
                                বিস্তারিত দেখুন ও বিড করুন
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State Card (When no jobs are open) --}}
            <div class="bg-white rounded-3xl p-8 md:p-12 border border-gray-100 shadow-sm text-center max-w-2xl mx-auto">
                <div class="w-16 h-16 bg-teal-50 text-teal-700 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">বর্তমানে নতুন কোনো উন্মুক্ত কাজ নেই</h3>
                <p class="text-sm text-gray-500 mb-6 leading-relaxed">
                    আপনার এলাকায় কোনো সেবার প্রয়োজন হলে এখনই নতুন কাজ পোস্ট করুন। প্রোভাইডারেরা সাথে সাথে বিড করবেন।
                </p>
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="{{ route('jobs.post') }}" class="btn btn-primary text-sm px-6 py-2.5">
                        + প্রথম কাজ পোস্ট করুন
                    </a>
                    <a href="{{ route('services.index') }}" class="btn btn-outline text-sm px-6 py-2.5">
                        সেবাসমূহ দেখুন
                    </a>
                </div>
            </div>
        @endif
    </section>

    {{-- ─────────────────────────────────────────── --}}
    {{-- 5. COMPACT TRUST & QUALITY HIGHLIGHTS --}}
    {{-- ─────────────────────────────────────────── --}}
    <section class="container mx-auto px-4 max-w-6xl">
        <div class="bg-gray-50 border border-gray-200/80 rounded-3xl p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 divide-y md:divide-y-0 md:divide-x divide-gray-200">
                
                {{-- Point 1 --}}
                <div class="flex items-start gap-4 p-2 md:pr-4">
                    <div class="w-12 h-12 bg-teal-100 text-teal-800 rounded-2xl flex items-center justify-center shrink-0 font-extrabold text-lg">
                        ✓
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-gray-900 mb-1">যাচাইকৃত দক্ষ কর্মী</h4>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">এনআইডি, মোবাইল নম্বর ও ব্যাকগ্রাউন্ড যাচাইকৃত প্রোভাইডার।</p>
                    </div>
                </div>

                {{-- Point 2 --}}
                <div class="flex items-start gap-4 p-2 md:px-6 pt-4 md:pt-2">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-800 rounded-2xl flex items-center justify-center shrink-0 font-extrabold text-lg">
                        ⚡
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-gray-900 mb-1">দ্রুত রেসপন্স ও অফার</h4>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">কয়েক মিনিটের মধ্যেই একাধিক প্রোভাইডারের বিড পেয়ে সেরা রেট বেছে নিন।</p>
                    </div>
                </div>

                {{-- Point 3 --}}
                <div class="flex items-start gap-4 p-2 md:pl-6 pt-4 md:pt-2">
                    <div class="w-12 h-12 bg-blue-100 text-blue-800 rounded-2xl flex items-center justify-center shrink-0 font-extrabold text-lg">
                        🔒
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-gray-900 mb-1">নিরাপদ সেবা ও লেনদেন</h4>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">কাজ সম্পন্ন হওয়ার পর সন্তুষ্টি অনুযায়ী ক্যাশ অথবা অনলাইন পেমেন্ট করুন।</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>
@endsection
