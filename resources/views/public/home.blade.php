@extends('layouts.public')

@section('title', 'LocalEmployments — উন্মুক্ত লোকাল জব ও সার্ভিস মার্কেটপ্লেস')
@section('meta_description', 'বাংলাদেশের সেরা লোকাল সার্ভিস মার্কেটপ্লেস। আপনার এলাকার উন্মুক্ত কাজগুলো দেখুন, বিড করুন অথবা যেকোনো কাজের জন্য পোস্ট করুন।')

@section('content')

{{-- ─────────────────────────────────────────── --}}
{{-- HERO SECTION --}}
{{-- ─────────────────────────────────────────── --}}
<section class="relative overflow-hidden bg-gradient-to-br from-primary-50 via-white to-primary-100 pt-20 pb-32 md:pt-28 md:pb-40">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-primary-300/30 rounded-full blur-3xl animate-blob"></div>
    <div class="absolute bottom-0 left-10 w-72 h-72 bg-accent-400/20 rounded-full blur-3xl animate-blob" style="animation-delay: 2s;"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
            
            {{-- Left Content --}}
            <div class="max-w-2xl mx-auto lg:mx-0 text-center lg:text-left animate-slide-in-left">
                <div class="inline-flex items-center gap-2 bg-white/60 backdrop-blur-sm border border-primary-100 rounded-full px-4 py-1.5 text-sm mb-6 text-primary-800 font-semibold shadow-sm">
                    <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></span>
                    উন্মুক্ত লোকাল জব মার্কেটপ্লেস
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-[1.15] text-gray-900 tracking-tight">
                    আপনার এলাকায় <br class="hidden md:block">
                    কী কী কাজ আছে দেখুন, <br class="hidden md:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-emerald-500">সহজেই বিড করুন!</span>
                </h1>

                <p class="text-lg md:text-xl text-gray-600 mb-10 leading-relaxed max-w-lg mx-auto lg:mx-0">
                    মানুষের পোস্টকৃত গৃহস্থালি ও লোকাল সার্ভিস কাজগুলো দেখুন অথবা আপনার প্রয়োজনীয় কাজের জন্য নতুন রিকোয়েস্ট পোস্ট করুন।
                </p>

                {{-- Search Box for Jobs --}}
                <form action="{{ route('jobs.index') }}" method="GET" class="glass rounded-2xl p-2.5 flex flex-col sm:flex-row gap-3 shadow-xl max-w-2xl mx-auto lg:mx-0 transition-transform hover:-translate-y-1 duration-300">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <select name="category" class="w-full pl-11 pr-4 py-3.5 text-gray-700 bg-transparent outline-none text-sm rounded-xl border-none focus:ring-0 appearance-none font-medium cursor-pointer" style="background-image: none;">
                            <option value="">সব ক্যাটাগরির কাজ</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-px bg-gray-200 hidden sm:block self-stretch my-2"></div>
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <select name="district" class="w-full pl-11 pr-4 py-3.5 text-gray-700 bg-transparent outline-none text-sm rounded-xl border-none focus:ring-0 appearance-none font-medium cursor-pointer" style="background-image: none;">
                            <option value="">সব জেলা</option>
                            @foreach(\App\Models\District::active()->get() as $d)
                                <option value="{{ $d->id }}">{{ $d->bn_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-3.5 rounded-xl font-semibold transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 whitespace-nowrap">
                        কাজ খুঁজুন
                    </button>
                </form>

                {{-- Quick Tags --}}
                <div class="flex flex-wrap gap-2 justify-center lg:justify-start mt-6 text-sm font-medium">
                    <span class="text-gray-500 py-1">জনপ্রিয় কাজ:</span>
                    @foreach(['এসি সার্ভিসিং', 'হাউস ক্লিনিং', 'ইলেকট্রিশিয়ান', 'প্লাম্বার'] as $quick)
                        <a href="{{ route('jobs.index') }}?q={{ urlencode($quick) }}" class="px-3 py-1 bg-white border border-gray-200 text-gray-700 hover:border-primary-300 hover:text-primary-700 rounded-full transition-colors shadow-sm">
                            {{ $quick }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Right Composition (Clean Hero Image Card) --}}
            <div class="hidden lg:block relative h-[480px] w-full animate-fade-in">
                <div class="absolute inset-0 rounded-3xl transform rotate-1 scale-98 opacity-15 bg-gradient-to-tr from-primary-600 to-emerald-400"></div>
                
                {{-- Main Visual Container (Pure Image Display) --}}
                <div class="relative w-full h-full rounded-3xl overflow-hidden shadow-2xl border border-gray-200/80 bg-gray-100">
                    @php
                        $heroSetting = setting('hero_image');
                        $defaultHeroImage = asset('assets/images/hero-banner.png');
                        
                        if (empty($heroSetting)) {
                            $heroImageUrl = $defaultHeroImage;
                        } elseif (filter_var($heroSetting, FILTER_VALIDATE_URL)) {
                            $heroImageUrl = $heroSetting;
                        } else {
                            $heroImageUrl = asset(ltrim($heroSetting, '/'));
                        }
                    @endphp

                    <img src="{{ $heroImageUrl }}" 
                         alt="Hero Image" 
                         class="w-full h-full object-cover object-center rounded-3xl"
                         onerror="this.onerror=null; this.src='{{ $defaultHeroImage }}';">
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ─────────────────────────────────────────── --}}
{{-- STATS SECTION (Overlapping) --}}
{{-- ─────────────────────────────────────────── --}}
<section class="relative z-20 -mt-16 md:-mt-20">
    <div class="container mx-auto px-4">
        <div class="glass rounded-3xl p-6 md:p-8 shadow-2xl bg-white/90">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center divide-x divide-gray-100">
                @foreach([
                    ['value' => $stats['providers'] ?? 0, 'suffix' => '+', 'label' => 'যাচাইকৃত কর্মী', 'color' => 'text-primary-600'],
                    ['value' => $stats['districts'] ?? 0, 'suffix' => '', 'label' => 'জেলায় সেবা', 'color' => 'text-emerald-600'],
                    ['value' => $stats['jobs'] ?? 0, 'suffix' => '+', 'label' => 'কাজ সম্পন্ন', 'color' => 'text-blue-600'],
                    ['value' => $stats['rating'] ?? '0.0', 'suffix' => '/5', 'label' => 'গড় রেটিং', 'color' => 'text-accent-500'],
                ] as $stat)
                    <div class="px-2">
                        <div class="text-3xl md:text-4xl font-extrabold {{ $stat['color'] }} tracking-tight animate-counter-up">
                            {{ $stat['value'] }}<span class="text-lg md:text-xl">{{ $stat['suffix'] }}</span>
                        </div>
                        <div class="text-sm font-semibold text-gray-500 mt-2 uppercase tracking-wide">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ─────────────────────────────────────────── --}}
{{-- CATEGORIES SECTION --}}
{{-- ─────────────────────────────────────────── --}}
<section class="py-24 bg-gray-50/50">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <h2 class="section-title text-4xl">জনপ্রিয় সেবাসমূহ</h2>
                <p class="section-subtitle">আপনার দৈনন্দিন জীবনের যেকোনো প্রয়োজনে আমরা আছি</p>
            </div>
            <a href="{{ route('services.index') }}" class="btn btn-outline whitespace-nowrap">সব সেবা দেখুন →</a>
        </div>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
            @foreach($categories as $cat)
                <a href="{{ route('services.show', $cat->slug) }}" class="card group p-6 flex flex-col items-center text-center border border-gray-100">
                    <div class="w-16 h-16 bg-primary-50 group-hover:bg-primary-600 group-hover:text-white rounded-2xl flex items-center justify-center transition-all duration-300 text-primary-600 mb-4 shadow-sm group-hover:shadow-md transform group-hover:-translate-y-2">
                        @if($cat->icon)
                            {!! category_icon($cat->icon, 'w-8 h-8') !!}
                        @else
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        @endif
                    </div>
                    <span class="font-bold text-gray-800 group-hover:text-primary-700 transition-colors">{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ─────────────────────────────────────────── --}}
{{-- HOW IT WORKS --}}
{{-- ─────────────────────────────────────────── --}}
<section class="py-24 bg-white relative overflow-hidden">
    {{-- Background blobs --}}
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-30 pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-100 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-100 rounded-full blur-3xl"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center mb-16">
            <span class="text-primary-600 font-bold uppercase tracking-wider text-sm">প্রসেস</span>
            <h2 class="section-title text-4xl mt-2">কীভাবে কাজ করে?</h2>
            <p class="section-subtitle">মাত্র ৩টি সহজ ধাপে আপনার কাঙ্ক্ষিত সেবাটি বুঝে নিন</p>
        </div>

        <div class="grid md:grid-cols-3 gap-12 relative">
            {{-- Connecting Line for Desktop --}}
            <div class="hidden md:block absolute top-12 left-[15%] right-[15%] border-t-2 border-dashed border-primary-200"></div>

            @foreach([
                ['step' => '০১', 'title' => 'কাজ পোস্ট করুন', 'desc' => 'খুব সহজেই আপনার প্রয়োজনীয় কাজের বিবরণ এবং বাজেট উল্লেখ করে একটি রিকোয়েস্ট পোস্ট করুন।', 'color' => 'bg-primary-600 text-white', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                ['step' => '০২', 'title' => 'বিড পান ও যাচাই করুন', 'desc' => 'কাছাকাছি থাকা দক্ষ কর্মীরা বিড করবেন। তাদের প্রোফাইল ও আগের রেটিং দেখে সেরা জনকে বেছে নিন।', 'color' => 'bg-emerald-500 text-white', 'icon' => 'M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z'],
                ['step' => '০৩', 'title' => 'সেবা নিন ও পেমেন্ট করুন', 'desc' => 'নির্ধারিত সময়ে কর্মী এসে কাজ সম্পন্ন করবেন। কাজ শেষে পেমেন্ট করুন এবং রেটিং দিন।', 'color' => 'bg-accent-500 text-white', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ] as $i => $step)
                <div class="relative text-center group">
                    <div class="w-24 h-24 mx-auto rounded-3xl {{ $step['color'] }} shadow-xl flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 relative z-10 mb-6">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"/>
                        </svg>
                        <div class="absolute -top-3 -right-3 w-8 h-8 bg-white text-gray-900 font-bold rounded-full shadow-md flex items-center justify-center text-sm">
                            {{ $step['step'] }}
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $step['title'] }}</h3>
                    <p class="text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ─────────────────────────────────────────── --}}
{{-- LATEST OPEN JOBS --}}
{{-- ─────────────────────────────────────────── --}}
@if(isset($latestJobs) && $latestJobs->count() > 0)
<section class="py-24 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <span class="inline-flex items-center gap-1.5 bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    উন্মুক্ত কাজসমূহ
                </span>
                <h2 class="section-title text-4xl">সর্বশেষ পোস্টকৃত কাজ</h2>
                <p class="section-subtitle">আপনার এলাকায় মানুষ কী কী কাজ পোস্ট করেছেন তা দেখুন ও বিড করুন</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="btn btn-outline whitespace-nowrap">সব কাজ দেখুন →</a>
        </div>
        
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($latestJobs as $job)
                <div class="bg-white rounded-3xl p-6 border border-gray-100/90 shadow-[0_8px_30px_rgb(0,0,0,0.03)] hover:shadow-[0_20px_40px_rgba(16,185,129,0.08)] hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="bg-primary-50 text-primary-700 text-xs font-bold px-3 py-1 rounded-full border border-primary-100/80">
                                {{ $job->subcategory?->name }}
                            </span>
                            <span class="text-xs text-gray-400 font-medium">
                                {{ $job->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <h3 class="font-bold text-xl text-gray-900 group-hover:text-primary-600 transition-colors mb-2 line-clamp-1">
                            {{ $job->title }}
                        </h3>

                        <p class="text-sm text-gray-600 line-clamp-2 mb-4 leading-relaxed">
                            {{ $job->description }}
                        </p>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100 mb-4 font-medium">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $job->district?->bn_name }}
                            </span>
                            <span class="font-bold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100/60">
                                ৳{{ number_format($job->budget_min) }} - ৳{{ number_format($job->budget_max) }}
                            </span>
                        </div>

                        <a href="{{ route('jobs.index') }}" class="block text-center py-2.5 bg-gray-900 group-hover:bg-primary-600 text-white text-sm font-bold rounded-xl shadow-sm transition-colors">
                            কাজের বিস্তারিত ও বিড দেখুন
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif



{{-- ─────────────────────────────────────────── --}}
{{-- CTA SECTION --}}
{{-- ─────────────────────────────────────────── --}}
<section class="py-24 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            {{-- Seeker CTA --}}
            <div class="bg-primary-50 rounded-[2rem] p-10 text-center hover:shadow-xl transition-all duration-300 border border-primary-100 group">
                <div class="w-20 h-20 bg-white text-primary-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-md transform group-hover:-translate-y-2 transition-transform">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-4">সেবা প্রয়োজন?</h3>
                <p class="text-gray-600 mb-8 leading-relaxed">যেকোনো কাজের জন্য আপনার রিকোয়েস্ট পোস্ট করুন। যাচাইকৃত প্রোভাইডারদের থেকে দ্রুত অফার নিন।</p>
                <a href="{{ auth()->check() && auth()->user()->isSeeker() ? route('seeker.job-requests.create') : route('register') }}" class="btn btn-primary w-full sm:w-auto px-10">
                    কাজ পোস্ট করুন
                </a>
            </div>

            {{-- Provider CTA --}}
            <div class="bg-gray-900 rounded-[2rem] p-10 text-center text-white hover:shadow-xl transition-all duration-300 border border-gray-800 group">
                <div class="w-20 h-20 bg-gray-800 border border-gray-700 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-md transform group-hover:-translate-y-2 transition-transform">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-3xl font-bold mb-4">কাজ করতে চান?</h3>
                <p class="text-gray-400 mb-8 leading-relaxed">উন্মুক্ত কাজের তালিকা দেখুন এবং আপনার পছন্দের কাজে বিড করে আয় শুরু করুন।</p>
                <a href="{{ route('jobs.index') }}" class="btn bg-white text-gray-900 hover:bg-gray-100 w-full sm:w-auto px-10">
                    উন্মুক্ত কাজসমূহ দেখুন
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

