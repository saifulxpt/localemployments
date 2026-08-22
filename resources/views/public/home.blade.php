@extends('layouts.public')

@section('title', 'LocalEmployments — উন্মুক্ত লোকাল জব ও সার্ভিস মার্কেটপ্লেস')
@section('meta_description', 'বাংলাদেশের সেরা লোকাল সার্ভিস মার্কেটপ্লেস। আপনার এলাকার উন্মুক্ত কাজগুলো দেখুন, বিড করুন অথবা যেকোনো কাজের জন্য পোস্ট করুন।')

@section('content')
<div class="space-y-8 md:space-y-12 pb-16">

    {{-- ─────────────────────────────────────────── --}}
    {{-- 1. SHEBA-STYLE LIGHT HERO & SEARCH HEADER --}}
    {{-- ─────────────────────────────────────────── --}}
    <section class="bg-gradient-to-b from-teal-50/80 via-emerald-50/40 to-transparent pt-8 pb-10 md:pt-14 md:pb-16 px-4">
        <div class="container mx-auto max-w-4xl text-center">
            
            {{-- Top Badge --}}
            <span class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-800 border border-emerald-200 px-3.5 py-1 rounded-full text-xs md:text-sm font-bold mb-3 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                বাংলাদেশের সেরা লোকাল সার্ভিস ও জব মার্কেটপ্লেস
            </span>

            {{-- Main Title --}}
            <h1 class="text-2xl sm:text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight leading-tight mb-3">
                আপনার এলাকায় <span class="text-[#0f766e]">কী সেবা বা কর্মী</span> প্রয়োজন?
            </h1>

            {{-- Subtitle --}}
            <p class="text-xs sm:text-sm md:text-base text-gray-600 max-w-xl mx-auto mb-6 leading-relaxed">
                হাজারো যাচাইকৃত দক্ষ কর্মী প্রস্তুত আছেন আপনার প্রয়োজনে। কাজ পোস্ট করুন অথবা সরাসরি সেবা বেছে নিন।
            </p>

            {{-- Smart Unified Search Bar --}}
            <form action="{{ route('jobs.index') }}" method="GET" class="bg-white p-2.5 md:p-3 rounded-2xl md:rounded-3xl shadow-lg border border-gray-200 flex flex-col md:flex-row gap-2 max-w-3xl mx-auto">
                
                {{-- District Dropdown --}}
                <div class="flex-1 md:max-w-[220px] relative border-b md:border-b-0 md:border-r border-gray-200">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-[#0f766e]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <select name="district" class="w-full pl-9 pr-4 py-2.5 md:py-3 text-gray-800 bg-transparent text-xs sm:text-sm font-medium border-0 focus:ring-0 cursor-pointer">
                        <option value="">সকল জেলা</option>
                        @foreach(\App\Models\District::active()->get() as $d)
                            <option value="{{ $d->id }}" {{ request('district') == $d->id ? 'selected' : '' }}>{{ $d->bn_name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Keyword Input --}}
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="কাজের নাম লিখুন (যেমন: এসি, প্লাম্বার, ক্লিনিং, শিফটিং...)" class="w-full pl-9 pr-4 py-2.5 md:py-3 text-gray-900 text-xs sm:text-sm placeholder-gray-400 border-0 focus:ring-0 outline-none">
                </div>

                {{-- Search Button --}}
                <button type="submit" class="bg-[#0f766e] hover:bg-[#115e59] text-white font-bold px-7 py-2.5 md:py-3 rounded-xl md:rounded-2xl text-xs sm:text-sm transition-all shadow-md flex items-center justify-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>সার্চ করুন</span>
                </button>
            </form>

            {{-- Quick Filter Pills --}}
            <div class="flex flex-wrap items-center justify-center gap-1.5 sm:gap-2 mt-4 text-xs">
                <span class="text-gray-500 font-semibold">জনপ্রিয় কাজ:</span>
                @foreach(['এসি সার্ভিসিং', 'হাউস ক্লিনিং', 'ইলেকট্রিশিয়ান', 'প্লাম্বার', 'শিফটিং', 'রং মিস্ত্রি'] as $tag)
                    <a href="{{ route('jobs.index') }}?q={{ urlencode($tag) }}" class="bg-white hover:bg-teal-50 text-gray-700 hover:text-teal-800 px-3 py-1 rounded-full border border-gray-200 shadow-xs transition-colors font-medium">
                        {{ $tag }}
                    </a>
                @endforeach
            </div>

        </div>
    </section>

    {{-- ─────────────────────────────────────────── --}}
    {{-- 2. QUICK ACTION BANNER (POST JOB CTA) --}}
    {{-- ─────────────────────────────────────────── --}}
    <section class="container mx-auto px-4 max-w-5xl">
        <div class="rounded-2xl md:rounded-3xl p-5 sm:p-7 text-white shadow-md flex flex-col md:flex-row items-center justify-between gap-4" style="background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);">
            <div class="flex items-center gap-4 text-center md:text-left">
                <div class="w-12 h-12 md:w-14 md:h-14 bg-white/20 rounded-2xl flex items-center justify-center shrink-0 border border-white/20">
                    <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg md:text-xl font-bold text-white mb-1">জরুরি কোনো সার্ভিস বা কর্মী প্রয়োজন?</h2>
                    <p class="text-xs sm:text-sm text-teal-100 leading-relaxed">
                        বিনামূল্যে কাজ পোস্ট করে স্থানীয় দক্ষ কর্মীদের থেকে সরাসরি সেরা অফার গ্রহণ করুন।
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-3 w-full md:w-auto shrink-0">
                <a href="{{ route('jobs.post') }}" class="flex-1 md:flex-initial text-center bg-white text-[#0f766e] hover:bg-gray-100 px-6 py-3 rounded-xl font-extrabold text-xs sm:text-sm shadow-md transition-transform active:scale-95 whitespace-nowrap" style="color: #0f766e; background-color: #ffffff;">
                    + কাজ পোস্ট করুন
                </a>
                <a href="{{ route('jobs.index') }}" class="flex-1 md:flex-initial text-center bg-black/20 hover:bg-black/30 text-white px-5 py-3 rounded-xl font-semibold text-xs sm:text-sm border border-white/25 transition-colors whitespace-nowrap">
                    উন্মুক্ত কাজ দেখুন
                </a>
            </div>
        </div>
    </section>

    {{-- ─────────────────────────────────────────── --}}
    {{-- 3. SHEBA-STYLE CATEGORIES GRID (APP ICONS) --}}
    {{-- ─────────────────────────────────────────── --}}
    <section class="container mx-auto px-4 max-w-5xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900">সেবার ক্যাটাগরি</h2>
                <p class="text-xs text-gray-500 mt-0.5">আপনার প্রয়োজনীয় সেবাটি এক ট্যাপে বেছে নিন</p>
            </div>
            <a href="{{ route('services.index') }}" class="text-xs sm:text-sm font-bold text-[#0f766e] hover:text-[#115e59] flex items-center gap-1">
                সব ক্যাটাগরি
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        {{-- Categories App Grid --}}
        <div class="grid grid-cols-4 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2.5 sm:gap-3.5">
            @php
                $palette = [
                    ['bg' => '#f0fdfa', 'text' => '#0f766e', 'border' => '#ccfbf1'],
                    ['bg' => '#eff6ff', 'text' => '#1d4ed8', 'border' => '#dbeafe'],
                    ['bg' => '#fffbeb', 'text' => '#b45309', 'border' => '#fef3c7'],
                    ['bg' => '#fff1f2', 'text' => '#be123c', 'border' => '#ffe4e6'],
                    ['bg' => '#eef2ff', 'text' => '#4338ca', 'border' => '#e0e7ff'],
                    ['bg' => '#ecfdf5', 'text' => '#047857', 'border' => '#d1fae5'],
                    ['bg' => '#fff7ed', 'text' => '#c2410c', 'border' => '#ffedd5'],
                    ['bg' => '#faf5ff', 'text' => '#7e22ce', 'border' => '#f3e8ff'],
                ];
            @endphp

            @foreach($categories as $idx => $cat)
                @php
                    $color = $palette[$idx % count($palette)];
                @endphp
                <a href="{{ route('jobs.index') }}?category={{ $cat->id }}" class="group flex flex-col items-center text-center p-2.5 sm:p-3 bg-white rounded-2xl border shadow-xs hover:shadow-md transition-all active:scale-95" style="border-color: {{ $color['border'] }};">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center mb-1.5 transition-transform group-hover:scale-105 shadow-xs" style="background-color: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                        @if($cat->icon)
                            {!! category_icon($cat->icon, 'w-6 h-6') !!}
                        @else
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        @endif
                    </div>
                    <span class="text-[11px] sm:text-xs font-bold text-gray-800 line-clamp-1 group-hover:text-[#0f766e]">
                        {{ $cat->name }}
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ─────────────────────────────────────────── --}}
    {{-- 4. LIVE OPEN JOBS BOARD (REAL-TIME ON-DEMAND FEED) --}}
    {{-- ─────────────────────────────────────────── --}}
    <section class="container mx-auto px-4 max-w-5xl">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900">চলমান উন্মুক্ত কাজসমূহ</h2>
                    <p class="text-xs text-gray-500">আপনার এলাকায় সরাসরি গ্রাহকদের পোস্ট করা কাজ দেখুন ও বিড করুন</p>
                </div>
            </div>
            <a href="{{ route('jobs.index') }}" class="text-xs sm:text-sm font-bold text-[#0f766e] hover:text-[#115e59] flex items-center gap-1">
                সকল কাজ →
            </a>
        </div>

        @if(isset($latestJobs) && $latestJobs->count() > 0)
            {{-- Job Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-4">
                @foreach($latestJobs as $job)
                    <div class="bg-white rounded-2xl p-4 border border-gray-200/90 shadow-xs hover:shadow-md hover:border-[#0f766e] transition-all flex flex-col justify-between group">
                        <div>
                            {{-- Top tags --}}
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="bg-teal-50 text-teal-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-teal-100">
                                    {{ $job->subcategory?->name ?? 'জেনারেল সার্ভিস' }}
                                </span>
                                <span class="text-[10px] text-gray-400 font-medium">
                                    {{ $job->created_at->diffForHumans() }}
                                </span>
                            </div>

                            {{-- Title --}}
                            <h3 class="font-bold text-sm sm:text-base text-gray-900 group-hover:text-[#0f766e] transition-colors line-clamp-1 mb-1">
                                {{ $job->title }}
                            </h3>

                            {{-- Short Description --}}
                            <p class="text-xs text-gray-500 line-clamp-2 mb-3 leading-relaxed">
                                {{ $job->description }}
                            </p>
                        </div>

                        <div>
                            {{-- Location & Budget --}}
                            <div class="flex items-center justify-between text-xs pt-2.5 border-t border-gray-100 mb-3 font-medium">
                                <span class="flex items-center gap-1 text-gray-600">
                                    <svg class="w-3.5 h-3.5 text-[#0f766e] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    {{ $job->district?->bn_name ?? 'ঢাকা' }}
                                </span>
                                <span class="font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md text-xs border border-emerald-100">
                                    ৳{{ number_format($job->budget_min) }} - ৳{{ number_format($job->budget_max) }}
                                </span>
                            </div>

                            {{-- Action Button --}}
                            <a href="{{ route('jobs.index') }}" class="block text-center py-2 bg-gray-900 group-hover:bg-[#0f766e] text-white text-xs font-bold rounded-xl transition-colors shadow-xs">
                                বিস্তারিত দেখুন ও বিড করুন
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State Card --}}
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200 text-center max-w-xl mx-auto shadow-xs">
                <div class="w-12 h-12 bg-teal-50 text-[#0f766e] rounded-xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-1">বর্তমানে কোনো উন্মুক্ত কাজ নেই</h3>
                <p class="text-xs text-gray-500 mb-4">
                    আপনার এলাকায় কোনো সেবার প্রয়োজন হলে এখনই কাজ পোস্ট করুন।
                </p>
                <a href="{{ route('jobs.post') }}" class="inline-block bg-[#0f766e] hover:bg-[#115e59] text-white font-bold px-6 py-2.5 rounded-xl text-xs shadow-sm">
                    + প্রথম কাজ পোস্ট করুন
                </a>
            </div>
        @endif
    </section>

    {{-- ─────────────────────────────────────────── --}}
    {{-- 5. COMPACT TRUST BADGES --}}
    {{-- ─────────────────────────────────────────── --}}
    <section class="container mx-auto px-4 max-w-5xl">
        <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-xs">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-center sm:text-left divide-y sm:divide-y-0 sm:divide-x divide-gray-100">
                <div class="flex items-center gap-3 p-2">
                    <div class="w-9 h-9 bg-teal-50 text-[#0f766e] rounded-xl flex items-center justify-center shrink-0 font-extrabold text-sm border border-teal-100">
                        ✓
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-gray-900">যাচাইকৃত দক্ষ কর্মী</h4>
                        <p class="text-[11px] text-gray-500">এনআইডি ও ব্যাকগ্রাউন্ড ভেরিফাইড</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-2 sm:pl-4">
                    <div class="w-9 h-9 bg-emerald-50 text-emerald-700 rounded-xl flex items-center justify-center shrink-0 font-extrabold text-sm border border-emerald-100">
                        ⚡
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-gray-900">দ্রুত রেসপন্স ও অফার</h4>
                        <p class="text-[11px] text-gray-500">কয়েক মিনিটেই একাধিক বিড পান</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-2 sm:pl-4">
                    <div class="w-9 h-9 bg-blue-50 text-blue-700 rounded-xl flex items-center justify-center shrink-0 font-extrabold text-sm border border-blue-100">
                        🔒
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-gray-900">নিরাপদ সেবা ও লেনদেন</h4>
                        <p class="text-[11px] text-gray-500">কাজ শেষে সন্তুষ্টি অনুযায়ী পেমেন্ট</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
