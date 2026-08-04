@extends('layouts.public')

@section('title', 'LocalEmployments — আপনার এলাকায়, আপনার মানুষ')
@section('meta_description', 'বাংলাদেশের সেরা লোকাল সার্ভিস মার্কেটপ্লেস। গৃহস্থালি কাজ, পরিষ্কার, ইলেকট্রিক, প্লাম্বিং সহ সব সেবার জন্য দক্ষ কর্মী খুঁজুন।')

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
                    {{ $stats['providers'] ?? 0 }}+ বিশ্বস্ত কর্মী আপনার সেবায় প্রস্তুত
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-[1.15] text-gray-900 tracking-tight">
                    ঘরের যেকোনো কাজে, <br class="hidden md:block">
                    আপনার ঠিকানায় <br class="hidden md:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-emerald-500">বিশ্বস্ত পেশাদার!</span>
                </h1>

                <p class="text-lg md:text-xl text-gray-600 mb-10 leading-relaxed max-w-lg mx-auto lg:mx-0">
                    প্লাম্বিং থেকে ক্লিনিং—সব সেবাই এখন এক ক্লিকে। যাচাইকৃত কর্মীদের দিয়ে নিশ্চিন্তে কাজ করিয়ে নিন।
                </p>

                {{-- Search Box --}}
                <form action="{{ route('search') }}" method="GET" class="glass rounded-2xl p-2.5 flex flex-col sm:flex-row gap-3 shadow-xl max-w-2xl mx-auto lg:mx-0 transition-transform hover:-translate-y-1 duration-300">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <select name="category" class="w-full pl-11 pr-4 py-3.5 text-gray-700 bg-transparent outline-none text-sm rounded-xl border-none focus:ring-0 appearance-none font-medium cursor-pointer" style="background-image: none;">
                            <option value="">কী সেবা খুঁজছেন?</option>
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
                            <option value="">আপনার জেলা নির্বাচন করুন</option>
                            @foreach(\App\Models\District::active()->get() as $d)
                                <option value="{{ $d->id }}">{{ $d->bn_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-3.5 rounded-xl font-semibold transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 whitespace-nowrap">
                        সার্চ করুন
                    </button>
                </form>

                {{-- Quick Tags --}}
                <div class="flex flex-wrap gap-2 justify-center lg:justify-start mt-6 text-sm font-medium">
                    <span class="text-gray-500 py-1">জনপ্রিয়:</span>
                    @foreach(['এসি সার্ভিসিং', 'হাউস ক্লিনিং', 'ইলেকট্রিশিয়ান', 'প্লাম্বার'] as $quick)
                        <a href="{{ route('search') }}?q={{ urlencode($quick) }}" class="px-3 py-1 bg-white border border-gray-200 text-gray-700 hover:border-primary-300 hover:text-primary-700 rounded-full transition-colors shadow-sm">
                            {{ $quick }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Right Composition (Floating Cards & Infographics) --}}
            <div class="hidden lg:block relative h-[520px] w-full animate-fade-in">
                <div class="absolute inset-0 bg-gradient-to-tr from-primary-200/40 to-transparent rounded-[3rem] transform rotate-3 scale-95"></div>
                
                {{-- Premium Hero Image --}}
                @php
                    $heroImage = setting('hero_image', 'https://images.unsplash.com/photo-1600320844754-07ed9222eb61?q=80&w=800&auto=format&fit=crop');
                    $isExternal = filter_var($heroImage, FILTER_VALIDATE_URL) !== false;
                @endphp
                <img src="{{ $isExternal ? $heroImage : asset($heroImage) }}" alt="Bangladesh Local Worker" class="absolute inset-0 w-full h-full object-cover rounded-[3rem] shadow-2xl object-center">
                <div class="absolute inset-0 bg-gradient-to-t from-primary-950/60 via-transparent to-black/20 rounded-[3rem]"></div>

                {{-- Floating Infographic Card 1: Verified Shield --}}
                <div class="absolute top-6 left-6 bg-white/90 backdrop-blur-md p-3.5 rounded-2xl shadow-xl border border-white/50 flex items-center gap-3 transform -rotate-2 hover:rotate-0 transition-transform">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-emerald-500/30">
                        ✓
                    </div>
                    <div>
                        <div class="text-xs font-black text-gray-900">১০০% ভেরিফাইড প্রোভাইডার</div>
                        <div class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                            NID যাচাইকৃত কর্মী
                        </div>
                    </div>
                </div>

                {{-- Floating Infographic Card 2: Rating Stack --}}
                <div class="absolute bottom-8 right-6 bg-white/95 backdrop-blur-md p-4 rounded-2xl shadow-2xl border border-white/60 flex items-center gap-3 transform rotate-1 hover:rotate-0 transition-transform">
                    <div class="flex -space-x-2 overflow-hidden">
                        <span class="inline-block h-8 w-8 rounded-full ring-2 ring-white bg-primary-600 text-white text-xs font-bold flex items-center justify-center">R</span>
                        <span class="inline-block h-8 w-8 rounded-full ring-2 ring-white bg-emerald-600 text-white text-xs font-bold flex items-center justify-center">S</span>
                        <span class="inline-block h-8 w-8 rounded-full ring-2 ring-white bg-blue-600 text-white text-xs font-bold flex items-center justify-center">M</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-1 text-amber-500 font-bold text-sm">
                            ★ ৪.৯ / ৫.০
                        </div>
                        <div class="text-[11px] font-medium text-gray-500">১,২০০+ সফল গ্রাহক মতামত</div>
                    </div>
                </div>

                {{-- Floating Infographic Card 3: Instant Bidding --}}
                <div class="absolute bottom-8 left-6 bg-gray-900/90 backdrop-blur-md text-white p-3.5 rounded-2xl shadow-2xl border border-white/20 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-primary-500 text-white flex items-center justify-center">
                        ⚡
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white">তাত্ক্ষণিক বিড রেসপন্স</div>
                        <div class="text-[11px] text-gray-300">গড় সময়: ৫-১০ মিনিট</div>
                    </div>
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
{{-- FEATURED PROVIDERS --}}
{{-- ─────────────────────────────────────────── --}}
@if(isset($featuredProviders) && $featuredProviders->count() > 0)
<section class="py-24 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <span class="inline-flex items-center gap-1.5 bg-accent-100 text-accent-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-3">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    সুপার হিরো
                </span>
                <h2 class="section-title text-4xl">শীর্ষ রেটেড কর্মী</h2>
                <p class="section-subtitle">আমাদের সবচেয়ে বিশ্বস্ত ও দক্ষ প্রফেশনালগণ</p>
            </div>
            <a href="{{ route('search') }}" class="btn btn-outline whitespace-nowrap">সব কর্মী দেখুন →</a>
        </div>
        
        <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($featuredProviders as $provider)
                <a href="{{ route('providers.show', $provider) }}" class="card group overflow-hidden border border-gray-100 flex flex-col">
                    <div class="card-accent-bar"></div>
                    <div class="p-6 text-center flex-1">
                        <div class="relative inline-block mb-4">
                            <img src="{{ $provider->avatar_url }}" alt="{{ $provider->name }}" class="w-24 h-24 rounded-full object-cover shadow-sm ring-4 ring-gray-50 group-hover:ring-primary-50 transition-all">
                            @if($provider->providerProfile?->is_verified)
                                <div class="absolute bottom-0 right-0 bg-green-500 text-white p-1 rounded-full border-2 border-white" title="Verified">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            @endif
                        </div>
                        <h3 class="font-bold text-lg text-gray-900 group-hover:text-primary-600 transition-colors truncate">{{ $provider->name }}</h3>
                        <p class="text-sm text-gray-500 flex items-center justify-center gap-1 mt-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $provider->district?->bn_name ?? 'অজানা জেলা' }}
                        </p>
                        
                        <div class="bg-gray-50 rounded-xl p-3 mt-4 flex justify-between items-center">
                            <div class="flex items-center gap-1">
                                <span class="text-accent-500 font-bold">{{ number_format($provider->providerProfile?->rating_avg ?? 0, 1) }}</span>
                                <svg class="w-4 h-4 text-accent-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <div class="text-xs text-gray-500 font-medium">{{ $provider->providerProfile?->total_reviews ?? 0 }} রিভিউ</div>
                        </div>

                        @if($provider->providerSkills->isNotEmpty())
                            <div class="flex flex-wrap justify-center gap-1.5 mt-4">
                                @foreach($provider->providerSkills->take(2) as $skill)
                                    <span class="text-[11px] font-semibold bg-primary-50 text-primary-700 px-2.5 py-1 rounded-md">{{ $skill->subcategory?->name }}</span>
                                @endforeach
                                @if($provider->providerSkills->count() > 2)
                                    <span class="text-[11px] font-semibold bg-gray-100 text-gray-600 px-2.5 py-1 rounded-md">+{{ $provider->providerSkills->count() - 2 }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ─────────────────────────────────────────── --}}
{{-- TRUST & TESTIMONIALS (NEW) --}}
{{-- ─────────────────────────────────────────── --}}
<section class="py-24 bg-primary-900 text-white relative overflow-hidden">
    {{-- Grid background --}}
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <pattern id="grid-pattern" width="20" height="20" patternUnits="userSpaceOnUse">
                <rect width="1" height="1" fill="white"/>
            </pattern>
            <rect width="100%" height="100%" fill="url(#grid-pattern)"/>
        </svg>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">হাজারো মানুষের আস্থার নাম</h2>
            <p class="text-primary-200">LocalEmployments-এর মাধ্যমে প্রতিদিন সম্পন্ন হচ্ছে শত শত সফল কাজ</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            {{-- Testimonial 1 --}}
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/10">
                <div class="flex items-center gap-1 text-accent-400 mb-4">
                    ★★★★★
                </div>
                <p class="text-primary-50 leading-relaxed mb-6">"বাসার এসি হঠাৎ নষ্ট হয়ে গিয়েছিল। LocalEmployments থেকে এক ঘণ্টার মধ্যে এক্সপার্ট পেয়েছি। কাজ খুব দারুণ ছিল!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary-700 flex items-center justify-center font-bold text-white">R</div>
                    <div>
                        <div class="font-bold">রাকিবুল হাসান</div>
                        <div class="text-xs text-primary-300">মিরপুর, ঢাকা</div>
                    </div>
                </div>
            </div>

            {{-- Testimonial 2 --}}
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/10">
                <div class="flex items-center gap-1 text-accent-400 mb-4">
                    ★★★★★
                </div>
                <p class="text-primary-50 leading-relaxed mb-6">"ক্লিনিং সার্ভিসের জন্য অনেকক্ষণ ধরে খুঁজছিলাম। এখানকার সিস্টেম খুব সহজ এবং পেমেন্টও বেশ নিরাপদ।"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary-700 flex items-center justify-center font-bold text-white">S</div>
                    <div>
                        <div class="font-bold">সাদিয়া আফরিন</div>
                        <div class="text-xs text-primary-300">ধানমন্ডি, ঢাকা</div>
                    </div>
                </div>
            </div>

            {{-- Testimonial 3 --}}
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/10">
                <div class="flex items-center gap-1 text-accent-400 mb-4">
                    ★★★★★
                </div>
                <p class="text-primary-50 leading-relaxed mb-6">"আমি একজন প্লাম্বার। এই প্ল্যাটফর্মের মাধ্যমে আমি আমার এলাকার আশেপাশেই প্রচুর কাজ পাচ্ছি। আমার আয় অনেক বেড়েছে।"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary-700 flex items-center justify-center font-bold text-white">J</div>
                    <div>
                        <div class="font-bold">জহিরুল ইসলাম</div>
                        <div class="text-xs text-primary-300">সার্ভিস প্রোভাইডার</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ─────────────────────────────────────────── --}}
{{-- CTA SECTION --}}
{{-- ─────────────────────────────────────────── --}}
<section class="py-24 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            {{-- Seeker CTA --}}
            <div class="bg-primary-50 rounded-[2rem] p-10 text-center hover:shadow-xl transition-all duration-300 border border-primary-100 group">
                <div class="w-20 h-20 bg-white text-primary-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-md transform group-hover:-translate-y-2 transition-transform">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-4">সেবা খুঁজছেন?</h3>
                <p class="text-gray-600 mb-8 leading-relaxed">যেকোনো কাজের জন্য আপনার রিকোয়েস্ট পোস্ট করুন। প্রোফাইল যাচাই করে বিশ্বস্ত কর্মী বেছে নিন।</p>
                <a href="{{ route('register') }}" class="btn btn-primary w-full sm:w-auto px-10">
                    অ্যাকাউন্ট খুলুন
                </a>
            </div>

            {{-- Provider CTA --}}
            <div class="bg-gray-900 rounded-[2rem] p-10 text-center text-white hover:shadow-xl transition-all duration-300 border border-gray-800 group">
                <div class="w-20 h-20 bg-gray-800 border border-gray-700 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-md transform group-hover:-translate-y-2 transition-transform">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-3xl font-bold mb-4">কাজ খুঁজছেন?</h3>
                <p class="text-gray-400 mb-8 leading-relaxed">আপনার দক্ষতা দিয়ে আয় শুরু করুন। এখনই প্রোফাইল তৈরি করে ক্লায়েন্টদের সাথে যুক্ত হোন।</p>
                <a href="{{ route('register') }}?role=provider" class="btn bg-white text-gray-900 hover:bg-gray-100 w-full sm:w-auto px-10">
                    কর্মী হিসেবে যুক্ত হোন
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
