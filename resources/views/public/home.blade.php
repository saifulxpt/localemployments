@extends('layouts.public')

@section('title', 'LocalEmployments — আপনার এলাকায়, আপনার মানুষ')
@section('meta_description', 'বাংলাদেশের সেরা লোকাল সার্ভিস মার্কেটপ্লেস। গৃহস্থালি কাজ, পরিষ্কার, ইলেকট্রিক, প্লাম্বিং সহ সব সেবার জন্য দক্ষ কর্মী খুঁজুন।')

@section('content')

{{-- ─────────────────────────────────────────── --}}
{{-- HERO SECTION --}}
{{-- ─────────────────────────────────────────── --}}
<section class="relative overflow-hidden bg-gradient-to-br from-primary-700 via-primary-600 to-emerald-600 text-white">
    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                <circle cx="1" cy="1" r="1" fill="white"/>
            </pattern>
            <rect width="100%" height="100%" fill="url(#grid)"/>
        </svg>
    </div>

    <div class="container mx-auto px-4 py-20 md:py-28 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 rounded-full px-4 py-1.5 text-sm mb-6 animate-fadeInDown">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                {{ $stats['providers'] }}+ দক্ষ কর্মী অপেক্ষায় আছেন
            </div>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight animate-fadeInUp">
                আপনার এলাকায়<br>
                <span class="text-yellow-300">বিশ্বস্ত সেবা</span> খুঁজুন
            </h1>

            <p class="text-xl text-white/80 mb-10 animate-fadeInUp">
                গৃহস্থালি কাজ থেকে শুরু করে যেকোনো সেবার জন্য দক্ষ ও যাচাইকৃত কর্মী পান<br class="hidden md:block"> মাত্র কয়েক মিনিটে।
            </p>

            {{-- Search Box --}}
            <form action="{{ route('search') }}" method="GET"
                  class="bg-white rounded-2xl p-2 flex flex-col sm:flex-row gap-2 shadow-2xl max-w-2xl mx-auto animate-fadeInUp">
                <select name="category" class="flex-1 px-4 py-3 text-gray-700 bg-transparent outline-none text-sm rounded-xl">
                    <option value="">সব ক্যাটাগরি</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                <div class="w-px bg-gray-200 hidden sm:block self-stretch my-1"></div>
                <select name="district" class="flex-1 px-4 py-3 text-gray-700 bg-transparent outline-none text-sm rounded-xl">
                    <option value="">সব জেলা</option>
                    @foreach(\App\Models\District::active()->get() as $d)
                        <option value="{{ $d->id }}">{{ $d->bn_name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    খুঁজুন
                </button>
            </form>

            {{-- Quick Tags --}}
            <div class="flex flex-wrap gap-2 justify-center mt-5 animate-fadeInUp">
                @foreach(['Home Cleaning', 'Plumbing', 'Electrical Work', 'AC Servicing', 'Home Cooking'] as $quick)
                    <a href="{{ route('search') }}?q={{ urlencode($quick) }}"
                       class="px-3 py-1 bg-white/10 hover:bg-white/20 border border-white/20 rounded-full text-xs transition-colors">
                        {{ $quick }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Wave --}}
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 80" preserveAspectRatio="none" class="w-full h-12 md:h-16 fill-gray-50">
            <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z"/>
        </svg>
    </div>
</section>

{{-- ─────────────────────────────────────────── --}}
{{-- STATS SECTION --}}
{{-- ─────────────────────────────────────────── --}}
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach([
                ['value' => $stats['providers'], 'suffix' => '+', 'label' => 'দক্ষ কর্মী', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'color' => 'text-primary-600', 'bg' => 'bg-primary-100'],
                ['value' => $stats['districts'], 'suffix' => '', 'label' => 'জেলায় সেবা', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'text-blue-600', 'bg' => 'bg-blue-100'],
                ['value' => $stats['jobs'], 'suffix' => '+', 'label' => 'কাজ সম্পন্ন', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'text-green-600', 'bg' => 'bg-green-100'],
                ['value' => $stats['rating'], 'suffix' => '/5', 'label' => 'গড় রেটিং', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z', 'color' => 'text-yellow-600', 'bg' => 'bg-yellow-100'],
            ] as $stat)
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 {{ $stat['bg'] }} rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 {{ $stat['color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                        </svg>
                    </div>
                    <div class="text-3xl font-extrabold text-gray-900">{{ $stat['value'] }}<span class="text-lg text-gray-400">{{ $stat['suffix'] }}</span></div>
                    <div class="text-sm text-gray-500 mt-1">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ─────────────────────────────────────────── --}}
{{-- CATEGORIES SECTION --}}
{{-- ─────────────────────────────────────────── --}}
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900">সেবার ধরন</h2>
            <p class="text-gray-500 mt-2">আপনার প্রয়োজনীয় সেবাটি বেছে নিন</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($categories as $cat)
                <a href="{{ route('services.show', $cat->slug) }}"
                   class="group flex flex-col items-center gap-3 p-4 rounded-2xl border-2 border-transparent hover:border-primary-200 hover:bg-primary-50 transition-all text-center cursor-pointer">
                    <div class="w-14 h-14 bg-primary-100 group-hover:bg-primary-200 rounded-2xl flex items-center justify-center transition-colors text-2xl">
                        {{ $cat->icon ? '⚡' : '🏠' }}
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-primary-700 transition-colors leading-tight">{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('services.index') }}" class="btn btn-outline">সব সেবা দেখুন →</a>
        </div>
    </div>
</section>

{{-- ─────────────────────────────────────────── --}}
{{-- HOW IT WORKS --}}
{{-- ─────────────────────────────────────────── --}}
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">কীভাবে কাজ করে</h2>
            <p class="text-gray-500 mt-2">মাত্র ৩টি ধাপে সেবা পান</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach([
                ['step' => '১', 'title' => 'কাজ পোস্ট করুন', 'desc' => 'আপনার প্রয়োজনীয় কাজের বিবরণ দিন এবং বাজেট উল্লেখ করুন।', 'color' => 'bg-primary-600', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                ['step' => '২', 'title' => 'বিড পান', 'desc' => 'কাছাকাছি দক্ষ কর্মীরা বিড করবেন। সেরা প্রস্তাবটি বেছে নিন।', 'color' => 'bg-blue-600', 'icon' => 'M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z'],
                ['step' => '৩', 'title' => 'সেবা উপভোগ করুন', 'desc' => 'কর্মী কাজ করবেন এবং সম্পন্ন হলে রেটিং দিন।', 'color' => 'bg-green-600', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ] as $i => $step)
                <div class="text-center relative">
                    @if($i < 2)
                        <div class="hidden md:block absolute top-8 left-2/3 w-1/3 border-t-2 border-dashed border-gray-300"></div>
                    @endif
                    <div class="w-16 h-16 {{ $step['color'] }} rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"/>
                        </svg>
                    </div>
                    <div class="text-4xl font-black text-gray-100 -mt-2 mb-2">{{ $step['step'] }}</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ─────────────────────────────────────────── --}}
{{-- FEATURED PROVIDERS --}}
{{-- ─────────────────────────────────────────── --}}
@if($featuredProviders->count() > 0)
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold mb-3">
                ⭐ ফিচার্ড
            </div>
            <h2 class="text-3xl font-bold text-gray-900">শীর্ষ কর্মী</h2>
            <p class="text-gray-500 mt-2">যাচাইকৃত ও রেটেড কর্মীদের সাথে কাজ করুন</p>
        </div>
        <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($featuredProviders as $provider)
                <a href="{{ route('providers.show', $provider) }}"
                   class="bg-white rounded-2xl border border-gray-100 hover:border-primary-200 hover:shadow-lg transition-all overflow-hidden group">
                    <div class="bg-gradient-to-br from-primary-50 to-emerald-50 p-6 text-center">
                        <img src="{{ $provider->avatar_url }}" alt="{{ $provider->name }}"
                             class="w-16 h-16 rounded-2xl object-cover mx-auto ring-4 ring-white shadow-md">
                        @if($provider->providerProfile?->is_verified)
                            <div class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full mt-2">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                যাচাইকৃত
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 group-hover:text-primary-600 transition-colors truncate">{{ $provider->name }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $provider->district?->bn_name ?? '—' }}</p>
                        <div class="flex items-center gap-1 mt-2">
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span class="text-sm font-bold text-gray-900">{{ number_format($provider->providerProfile?->rating_avg ?? 0, 1) }}</span>
                            <span class="text-xs text-gray-400">({{ $provider->providerProfile?->total_reviews ?? 0 }})</span>
                        </div>
                        @if($provider->providerSkills->isNotEmpty())
                            <div class="flex flex-wrap gap-1 mt-3">
                                @foreach($provider->providerSkills->take(2) as $skill)
                                    <span class="text-xs bg-primary-50 text-primary-700 px-2 py-0.5 rounded-full">{{ $skill->subcategory?->name }}</span>
                                @endforeach
                                @if($provider->providerSkills->count() > 2)
                                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">+{{ $provider->providerSkills->count() - 2 }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('search') }}" class="btn btn-outline">সব কর্মী দেখুন →</a>
        </div>
    </div>
</section>
@endif

{{-- ─────────────────────────────────────────── --}}
{{-- WHY US --}}
{{-- ─────────────────────────────────────────── --}}
<section class="py-16 bg-gradient-to-br from-primary-700 to-emerald-600 text-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold">কেন LocalEmployments?</h2>
            <p class="text-white/70 mt-2">আমরা যা নিশ্চিত করি</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon' => '🔒', 'title' => 'যাচাইকৃত কর্মী', 'desc' => 'NID যাচাই করে প্রতিটি প্রোভাইডার অনুমোদন করা হয়।'],
                ['icon' => '💳', 'title' => 'নিরাপদ পেমেন্ট', 'desc' => 'SSLCommerz এর মাধ্যমে এনক্রিপ্টেড পেমেন্ট।'],
                ['icon' => '💬', 'title' => 'সরাসরি যোগাযোগ', 'desc' => 'বুকিংয়ের মধ্যে সরাসরি চ্যাট করুন।'],
                ['icon' => '⭐', 'title' => 'রেটিং সিস্টেম', 'desc' => 'প্রতিটি সেবার পর রেটিং দিন এবং মানের নিশ্চয়তা পান।'],
            ] as $feature)
                <div class="text-center p-6 bg-white/10 backdrop-blur rounded-2xl border border-white/20">
                    <div class="text-4xl mb-4">{{ $feature['icon'] }}</div>
                    <h3 class="font-bold text-lg mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-white/70 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ─────────────────────────────────────────── --}}
{{-- CTA SECTION --}}
{{-- ─────────────────────────────────────────── --}}
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            {{-- Seeker CTA --}}
            <div class="bg-gradient-to-br from-primary-50 to-emerald-50 rounded-3xl p-8 border border-primary-100 text-center">
                <div class="text-5xl mb-4">🔍</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">সেবা খুঁজছেন?</h3>
                <p class="text-gray-600 mb-6 text-sm leading-relaxed">আপনার কাজ পোস্ট করুন এবং বিডের অপেক্ষা করুন অথবা সরাসরি কর্মী বুক করুন।</p>
                <a href="{{ route('register') }}" class="btn btn-primary w-full justify-center">
                    কাজ পোস্ট করুন
                </a>
            </div>

            {{-- Provider CTA --}}
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl p-8 text-center text-white">
                <div class="text-5xl mb-4">💼</div>
                <h3 class="text-2xl font-bold mb-3">কাজ খুঁজছেন?</h3>
                <p class="text-white/70 mb-6 text-sm leading-relaxed">আপনার দক্ষতা দিয়ে উপার্জন করুন। বিড করুন অথবা সরাসরি সেবা অফার করুন।</p>
                <a href="{{ route('register') }}?role=provider" class="btn bg-white text-gray-900 hover:bg-gray-100 w-full justify-center">
                    কাজ শুরু করুন
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
