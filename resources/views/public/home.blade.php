@extends('layouts.public')

@section('title', 'LocalEmployments — উন্মুক্ত লোকাল জব ও সার্ভিস মার্কেটপ্লেস')
@section('meta_description', 'বাংলাদেশের সেরা লোকাল সার্ভিস মার্কেটপ্লেস। আপনার এলাকার উন্মুক্ত কাজগুলো দেখুন, বিড করুন অথবা যেকোনো কাজের জন্য পোস্ট করুন।')

@section('content')
<div class="space-y-5 sm:space-y-7 pb-16">

    {{-- ─────────────────────────────────────────── --}}
    {{-- 1. APP-STYLE SMART SEARCH HEADER (HIGH CONTRAST) --}}
    {{-- ─────────────────────────────────────────── --}}
    <section class="bg-[#0f766e] text-white pt-6 pb-8 px-4 rounded-b-[2rem] shadow-md" style="background: linear-gradient(180deg, #134e4a 0%, #0f766e 100%);">
        <div class="container mx-auto max-w-4xl">
            
            {{-- Tagline & Heading --}}
            <div class="text-center mb-5">
                <span class="inline-flex items-center gap-1.5 bg-white/15 text-white border border-white/20 px-3 py-0.5 rounded-full text-xs font-semibold mb-2 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                    লোকাল সার্ভিস ও জব মার্কেটপ্লেস
                </span>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-white tracking-tight">
                    আপনার এলাকায় কী কাজ করাতে চান?
                </h1>
                <p class="text-xs sm:text-sm text-teal-100 mt-1 max-w-md mx-auto">
                    দক্ষ প্রোভাইডার খুঁজে নিন অথবা আপনার কাজের রিকোয়েস্ট পোস্ট করুন।
                </p>
            </div>

            {{-- Smart Unified Search Bar --}}
            <form action="{{ route('jobs.index') }}" method="GET" class="bg-white p-2 rounded-2xl shadow-xl flex flex-col sm:flex-row gap-2 border border-gray-100">
                {{-- District / Location --}}
                <div class="flex-1 sm:max-w-[200px] relative border-b sm:border-b-0 sm:border-r border-gray-200">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-teal-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <select name="district" class="w-full pl-9 pr-3 py-2.5 text-gray-800 bg-transparent text-xs sm:text-sm font-medium border-0 focus:ring-0 cursor-pointer">
                        <option value="">সকল জেলা</option>
                        @foreach(\App\Models\District::active()->get() as $d)
                            <option value="{{ $d->id }}" {{ request('district') == $d->id ? 'selected' : '' }}>{{ $d->bn_name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Keyword / Search input --}}
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="সার্ভিস বা কাজের নাম (যেমন: এসি, প্লাম্বার, ক্লিনিং...)" class="w-full pl-9 pr-3 py-2.5 text-gray-900 text-xs sm:text-sm placeholder-gray-400 border-0 focus:ring-0 outline-none">
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="bg-[#0f766e] hover:bg-[#115e59] text-white font-bold px-6 py-2.5 rounded-xl text-xs sm:text-sm transition-all shadow flex items-center justify-center gap-1.5">
                    <span>সার্চ করুন</span>
                </button>
            </form>

            {{-- Quick Filter Pills --}}
            <div class="flex flex-wrap items-center justify-center gap-1.5 mt-3 text-xs text-teal-100">
                <span class="text-teal-200 text-[11px] font-semibold">জনপ্রিয়:</span>
                @foreach(['এসি সার্ভিসিং', 'হাউস ক্লিনিং', 'ইলেকট্রিশিয়ান', 'প্লাম্বার', 'শিফটিং', 'রং মিস্ত্রি'] as $tag)
                    <a href="{{ route('jobs.index') }}?q={{ urlencode($tag) }}" class="bg-white/15 hover:bg-white/25 text-white px-2.5 py-0.5 rounded-full border border-white/20 transition-colors text-[11px]">
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
        <div class="rounded-2xl p-4 sm:p-5 text-white shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3" style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);">
            <div class="flex items-center gap-3 text-center sm:text-left">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-white">জরুরি সার্ভিস বা কর্মী প্রয়োজন?</h2>
                    <p class="text-[11px] sm:text-xs text-teal-100">বিনামূল্যে কাজ পোস্ট করে দ্রুত দক্ষ কর্মীদের থেকে অফার নিন।</p>
                </div>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <a href="{{ route('jobs.post') }}" class="flex-1 sm:flex-initial text-center bg-white text-teal-900 hover:bg-gray-50 px-4 py-2 rounded-xl font-bold text-xs shadow-sm transition-transform active:scale-95 whitespace-nowrap">
                    + কাজ পোস্ট করুন
                </a>
                <a href="{{ route('jobs.index') }}" class="flex-1 sm:flex-initial text-center bg-teal-800/80 hover:bg-teal-800 text-white px-3.5 py-2 rounded-xl font-semibold text-xs border border-white/20 transition-colors whitespace-nowrap">
                    কাজ খুঁজুন
                </a>
            </div>
        </div>
    </section>

    {{-- ─────────────────────────────────────────── --}}
    {{-- 3. SHEBA-STYLE CATEGORIES GRID (APP ICONS) --}}
    {{-- ─────────────────────────────────────────── --}}
    <section class="container mx-auto px-4 max-w-5xl">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-gray-900">সেবার ক্যাটাগরি</h2>
                <p class="text-[11px] sm:text-xs text-gray-500">আপনার প্রয়োজনীয় ক্যাটাগরি বেছে নিন</p>
            </div>
            <a href="{{ route('services.index') }}" class="text-xs font-bold text-teal-700 hover:text-teal-900 flex items-center gap-1">
                সব দেখুন
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        {{-- Categories App Grid (Robust 4-column layout on mobile) --}}
        <div class="grid grid-cols-4 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2 sm:gap-3" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(76px, 1fr)); gap: 10px;">
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
                <a href="{{ route('jobs.index') }}?category={{ $cat->id }}" class="group flex flex-col items-center text-center p-2 bg-white rounded-2xl border shadow-xs hover:shadow-md transition-all active:scale-95" style="border-color: {{ $color['border'] }};">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-1.5 transition-transform group-hover:scale-105" style="background-color: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                        @if($cat->icon)
                            {!! category_icon($cat->icon, 'w-6 h-6') !!}
                        @else
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        @endif
                    </div>
                    <span class="text-[11px] font-bold text-gray-800 line-clamp-1 group-hover:text-teal-700">
                        {{ $cat->name }}
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ─────────────────────────────────────────── --}}
    {{-- 4. LIVE OPEN JOBS BOARD (CURRENT ON-DEMAND FEED) --}}
    {{-- ─────────────────────────────────────────── --}}
    @if(isset($latestJobs) && $latestJobs->count() > 0)
    <section class="container mx-auto px-4 max-w-5xl">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-gray-900">চলমান উন্মুক্ত কাজসমূহ</h2>
                    <p class="text-[11px] sm:text-xs text-gray-500">আপনার এলাকায় সরাসরি পোস্ট হওয়া কাজে বিড করুন</p>
                </div>
            </div>
            <a href="{{ route('jobs.index') }}" class="text-xs font-bold text-teal-700 hover:text-teal-900 flex items-center gap-1">
                সকল কাজ ({{ $latestJobs->count() }}+) →
            </a>
        </div>

        {{-- Job Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
            @foreach($latestJobs as $job)
                <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-xs hover:shadow-md hover:border-teal-300 transition-all flex flex-col justify-between group">
                    <div>
                        {{-- Top tags --}}
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="bg-teal-50 text-teal-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-teal-100">
                                {{ $job->subcategory?->name ?? 'জেনারেল সার্ভিস' }}
                            </span>
                            <span class="text-[10px] text-gray-400 font-medium">
                                {{ $job->created_at->diffForHumans() }}
                            </span>
                        </div>

                        {{-- Title --}}
                        <h3 class="font-bold text-sm text-gray-900 group-hover:text-teal-700 transition-colors line-clamp-1 mb-1">
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
                                <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                {{ $job->district?->bn_name ?? 'ঢাকা' }}
                            </span>
                            <span class="font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md text-xs border border-emerald-100">
                                ৳{{ number_format($job->budget_min) }} - ৳{{ number_format($job->budget_max) }}
                            </span>
                        </div>

                        {{-- Action Button --}}
                        <a href="{{ route('jobs.index') }}" class="block text-center py-2 bg-gray-900 group-hover:bg-[#0f766e] text-white text-xs font-bold rounded-xl transition-colors">
                            বিস্তারিত ও বিড করুন
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ─────────────────────────────────────────── --}}
    {{-- 5. COMPACT MICRO-TRUST BAR (CLEAN CHIPS) --}}
    {{-- ─────────────────────────────────────────── --}}
    <section class="container mx-auto px-4 max-w-5xl">
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-3 sm:p-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-center sm:text-left divide-y sm:divide-y-0 sm:divide-x divide-gray-200">
                <div class="flex items-center gap-2.5 p-2">
                    <div class="w-8 h-8 bg-teal-100 text-teal-800 rounded-xl flex items-center justify-center shrink-0 font-bold text-xs">
                        ✓
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-gray-900">যাচাইকৃত দক্ষ কর্মী</h4>
                        <p class="text-[10px] text-gray-500">এনআইডি ও ব্যাকগ্রাউন্ড ভেরিফাইড</p>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 p-2 sm:pl-3">
                    <div class="w-8 h-8 bg-emerald-100 text-emerald-800 rounded-xl flex items-center justify-center shrink-0 font-bold text-xs">
                        ⚡
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-gray-900">দ্রুত রেসপন্স ও অফার</h4>
                        <p class="text-[10px] text-gray-500">কয়েক মিনিটেই একাধিক বিড পান</p>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 p-2 sm:pl-3">
                    <div class="w-8 h-8 bg-blue-100 text-blue-800 rounded-xl flex items-center justify-center shrink-0 font-bold text-xs">
                        🔒
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-gray-900">নিরাপদ সেবা ও লেনদেন</h4>
                        <p class="text-[10px] text-gray-500">কাজ শেষে সন্তুষ্টি অনুযায়ী পেমেন্ট</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
