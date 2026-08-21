@extends('layouts.public')

@section('title', 'সকল উন্মুক্ত কাজসমূহ (Browse Jobs)')
@section('meta_description', 'আপনার আশেপাশের মানুষের উন্মুক্ত কাজগুলোর বিস্তারিত বিবরণ দেখুন এবং সরাসরি প্রোভাইডার হিসেবে বিড করুন।')

@section('content')
<div class="bg-gray-50/60 py-8 md:py-12 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- ─────────────────────────────────────────── --}}
        {{-- Hero Header Banner --}}
        {{-- ─────────────────────────────────────────── --}}
        <div class="relative overflow-hidden rounded-3xl p-6 sm:p-10 mb-8 sm:mb-10 shadow-2xl border border-emerald-500/20 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #042f2e 50%, #064e3b 100%) !important; color: #ffffff !important;">
            {{-- Glowing Background Orbs --}}
            <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full blur-3xl pointer-events-none" style="background: rgba(13, 148, 136, 0.25);"></div>
            <div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full blur-3xl pointer-events-none" style="background: rgba(16, 185, 129, 0.2);"></div>

            <div class="relative z-10 max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-4" style="background: rgba(255, 255, 255, 0.12); color: #ffffff !important; border: 1px solid rgba(255, 255, 255, 0.2);">
                    <span class="w-2 h-2 rounded-full animate-pulse" style="background: #34d399;"></span>
                    উন্মুক্ত লোকাল জব মার্কেটপ্লেস
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight mb-4 leading-tight" style="color: #ffffff !important;">
                    আপনার আশেপাশের <span style="color: #34d399 !important;">উন্মুক্ত কাজসমূহ</span>
                </h1>
                <p class="text-base sm:text-lg leading-relaxed mb-6 font-medium" style="color: #e2e8f0 !important;">
                    মানুষ তাদের প্রাত্যহিক বিভিন্ন কাজের জন্য দক্ষ প্রোভাইডার খুঁজছেন। সরাসরি বিড করুন এবং আপনার অভিজ্ঞতা দিয়ে আজই সার্ভিস দিন।
                </p>

                <div class="flex flex-wrap items-center gap-4 text-xs sm:text-sm text-gray-300 pt-2 border-t border-white/10">
                    <div class="flex items-center gap-1.5 font-medium">
                        <span class="w-2 h-2 rounded-full bg-primary-400"></span>
                        <strong class="text-white font-bold">{{ $jobs->total() }}+</strong> টি কাজ উন্মুক্ত
                    </div>
                    <div class="hidden sm:block text-gray-600">•</div>
                    <div class="flex items-center gap-1.5 font-medium">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        তাত্ক্ষণিক বিডিং সুবিধা
                    </div>
                    <div class="hidden sm:block text-gray-600">•</div>
                    <div class="flex items-center gap-1.5 font-medium">
                        <svg class="w-4 h-4 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        ১০০% সেফ ও ভেরিফাইড পোস্ট
                    </div>
                </div>
            </div>
        </div>

        {{-- ─────────────────────────────────────────── --}}
        {{-- Main Section: Filters + Job Cards --}}
        {{-- ─────────────────────────────────────────── --}}
        <div x-data="{ showMobileFilter: false }" class="flex flex-col lg:flex-row gap-8 items-start">
            
            {{-- Mobile Filter Toggle Button --}}
            <div class="w-full lg:hidden flex justify-between items-center bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                <button @click="showMobileFilter = !showMobileFilter" class="flex items-center gap-2 font-bold text-gray-800 text-sm">
                    <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>ফিল্টার করুন {{ request()->anyFilled(['q', 'category', 'district']) ? '(সক্রিয়)' : '' }}</span>
                </button>
                <span class="text-xs font-semibold text-gray-500" x-text="showMobileFilter ? 'লুকান' : 'দেখান'"></span>
            </div>

            {{-- ───────── Left Sidebar Filters ───────── --}}
            <div :class="showMobileFilter ? 'block' : 'hidden lg:block'" class="w-full lg:w-1/4 shrink-0 lg:sticky lg:top-24">
                <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100/80 p-6">
                    <div class="flex items-center justify-between mb-5 pb-4 border-b border-gray-100">
                        <h3 class="font-extrabold text-lg text-gray-900 flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            </div>
                            ফিল্টার করুন
                        </h3>

                        @if(request()->anyFilled(['q', 'category', 'district']))
                            <a href="{{ route('jobs.index') }}" class="text-xs font-bold text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-2.5 py-1 rounded-lg transition-colors">
                                রিসেট
                            </a>
                        @endif
                    </div>
                    
                    <form action="{{ route('jobs.index') }}" method="GET" class="space-y-5">
                        
                        {{-- Search Input --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">কীওয়ার্ড দিয়ে খুঁজুন</label>
                            <div class="relative">
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="যেমন: এসি ক্লিন, প্লাম্বার..." class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-4 py-2.5 pl-10 text-sm focus:outline-none focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-200 font-medium transition-all">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>

                        {{-- Category Select --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">ক্যাটাগরি</label>
                            <select name="category" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-200 font-medium transition-all">
                                <option value="" class="text-gray-900 bg-white">সব ক্যাটাগরি</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" class="text-gray-900 bg-white" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name ?? 'অনামী ক্যাটাগরি' }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- District Select --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">জেলা</label>
                            <select name="district" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-200 font-medium transition-all">
                                <option value="" class="text-gray-900 bg-white">সব জেলা</option>
                                @foreach($districts as $dist)
                                    <option value="{{ $dist->id }}" class="text-gray-900 bg-white" {{ request('district') == $dist->id ? 'selected' : '' }}>{{ $dist->bn_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-primary-600/20 hover:shadow-primary-600/35 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            খুঁজুন
                        </button>
                    </form>
                </div>
            </div>

            {{-- ───────── Right Job Cards List ───────── --}}
            <div class="w-full lg:w-3/4 flex-1">
                
                {{-- Result Bar Header --}}
                <div class="flex items-center justify-between mb-4 px-1">
                    <div class="text-sm font-bold text-gray-700">
                        মোট <span class="text-primary-600 font-extrabold">{{ $jobs->total() }}</span> টি কাজ পাওয়া গেছে
                    </div>
                </div>

                @if($jobs->count() > 0)
                    <div class="space-y-5">
                        @foreach($jobs as $job)
                            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100/90 shadow-[0_8px_30px_rgb(0,0,0,0.03)] hover:shadow-[0_20px_40px_rgba(16,185,129,0.08)] hover:-translate-y-0.5 transition-all duration-300 relative group overflow-hidden">
                                
                                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                                    
                                    {{-- Image / Seeker Avatar Thumbnail --}}
                                    <div class="shrink-0 relative">
                                        @if($job->photos && count($job->photos) > 0)
                                            <div class="overflow-hidden rounded-2xl border border-gray-100 shadow-sm w-24 h-24 sm:w-28 sm:h-28">
                                                <img src="{{ Storage::url($job->photos[0]) }}" alt="{{ $job->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                            </div>
                                        @else
                                            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-gradient-to-br from-primary-50 via-emerald-50 to-primary-100/60 border border-primary-100 flex flex-col items-center justify-center p-3 text-center text-primary-600 shadow-xs group-hover:scale-105 transition-transform duration-500">
                                                @if($job->seeker && $job->seeker->avatar_url)
                                                    <img src="{{ $job->seeker->avatar_url }}" alt="{{ $job->seeker->name }}" class="w-12 h-12 rounded-full object-cover mb-1 border-2 border-white shadow-xs">
                                                @else
                                                    <div class="w-11 h-11 rounded-full bg-primary-600 text-white flex items-center justify-center font-bold text-base mb-1 shadow-sm">
                                                        {{ substr($job->seeker ? $job->seeker->name : 'J', 0, 1) }}
                                                    </div>
                                                @endif
                                                <span class="text-[11px] font-bold truncate max-w-full text-gray-700">{{ $job->seeker ? $job->seeker->name : 'পোস্টার' }}</span>
                                            </div>
                                        @endif

                                        {{-- Flexibility pill overlay if urgent --}}
                                        @if($job->flexibility === 'urgent')
                                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full shadow-md animate-pulse">
                                                জরুরী
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Job Details Content --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <span class="bg-primary-50 text-primary-700 text-xs font-bold px-3 py-1 rounded-full border border-primary-100/80 flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-primary-500"></span>
                                                {{ $job->subcategory->name }}
                                            </span>
                                            
                                            <span class="text-xs text-gray-400 flex items-center gap-1 font-medium">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ $job->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        
                                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 group-hover:text-primary-600 transition-colors mb-2 leading-snug">
                                            {{ $job->title }}
                                        </h2>
                                        
                                        <p class="text-gray-600 text-sm sm:text-base line-clamp-2 mb-4 leading-relaxed">
                                            {{ $job->description }}
                                        </p>
                                        
                                        <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-xs sm:text-sm text-gray-600 font-medium">
                                            <div class="flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                                                <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                <span>{{ $job->area ? $job->area->bn_name : '' }}, {{ $job->district->bn_name }}</span>
                                            </div>

                                            <div class="flex items-center gap-1.5 bg-emerald-50 text-emerald-800 px-3 py-1.5 rounded-xl border border-emerald-100/80 font-bold">
                                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span>বাজেট: ৳{{ number_format($job->budget_min) }} - ৳{{ number_format($job->budget_max) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Bid CTA Button Column --}}
                                    <div class="flex flex-col justify-center shrink-0 w-full md:w-auto pt-4 md:pt-0 border-t md:border-t-0 border-gray-100">
                                        @auth
                                            @if(auth()->user()->isProvider())
                                                <a href="{{ route('provider.jobs.show', $job->id) }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-primary-600 to-emerald-600 hover:from-primary-700 hover:to-emerald-700 text-white rounded-2xl font-bold shadow-md hover:shadow-lg hover:shadow-primary-600/25 active:scale-95 transition-all">
                                                    <span>বিড করুন</span>
                                                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                </a>
                                            @else
                                                <a href="{{ route('provider.jobs.index') }}" onclick="alert('কাজে বিড করার জন্য প্রোভাইডার একাউন্ট প্রয়োজন।');" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-900 hover:bg-primary-600 text-white rounded-2xl font-bold shadow-md hover:shadow-lg active:scale-95 transition-all">
                                                    <span>বিড করুন</span>
                                                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-900 hover:bg-primary-600 text-white rounded-2xl font-bold shadow-md hover:shadow-lg active:scale-95 transition-all">
                                                <span>বিড করতে লগইন করুন</span>
                                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
                                        @endauth
                                        <div class="text-center mt-2.5">
                                            <span class="text-xs font-bold text-gray-500 bg-gray-100/80 border border-gray-200/50 px-2.5 py-1 rounded-lg">মোট বিড: {{ $job->total_bids }}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-10">
                        {{ $jobs->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.03)]">
                        <div class="w-20 h-20 bg-primary-50 text-primary-500 rounded-3xl flex items-center justify-center mx-auto mb-4 border border-primary-100">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">কোনো উন্মুক্ত কাজ পাওয়া যায়নি</h3>
                        <p class="text-gray-500 max-w-md mx-auto mb-6">বর্তমানে আপনার সিলেক্ট করা ফিল্টারে কোনো কাজ খোলা নেই। অন্য কোনো কীওয়ার্ড, ক্যাটাগরি বা জেলা সিলেক্ট করে দেখুন।</p>
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-bold px-6 py-2.5 rounded-xl transition-all shadow-md">
                            সকল ফিল্টার রিক্লিয়ার করুন
                        </a>
                    </div>
                @endif

            </div>
        </div>
        
    </div>
</div>
@endsection
