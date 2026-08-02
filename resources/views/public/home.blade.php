@extends('layouts.public')

@section('title', 'LocalEmployments — আপনার এলাকায়, আপনার মানুষ')
@section('meta_description', 'বাংলাদেশের সেরা লোকাল সার্ভিস মার্কেটপ্লেস। গৃহস্থালি কাজ, পরিষ্কার, ইলেকট্রিক, প্লাম্বিং সহ সব সেবার জন্য দক্ষ কর্মী খুঁজুন।')

@section('content')

{{-- ─────────────────────────────────────────── --}}
{{-- APP STYLE HEADER (Mobile Focus) --}}
{{-- ─────────────────────────────────────────── --}}
<div class="bg-white px-4 pt-6 pb-4 shadow-sm sticky top-0 z-40">
    {{-- Location Pin --}}
    <div class="flex items-start gap-3 mb-4">
        <div class="mt-1 text-primary-600">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <div class="font-bold text-gray-900 text-sm">বর্তমান লোকেশন</div>
            <div class="text-xs text-gray-500 flex items-center gap-1 cursor-pointer hover:text-primary-600 transition-colors">
                ঢাকা, বাংলাদেশ 
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>
    </div>

    {{-- Search Bar --}}
    <form action="{{ route('search') }}" method="GET" class="relative">
        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <input type="text" name="q" placeholder="Search Services" class="w-full bg-gray-100 border-none rounded-xl pl-10 pr-4 py-3 text-sm text-gray-700 focus:ring-1 focus:ring-primary-500 transition-all placeholder-gray-500">
    </form>
</div>

<div class="container mx-auto px-4 py-6 max-w-lg md:max-w-4xl">
    
    {{-- ─────────────────────────────────────────── --}}
    {{-- PROMO BANNER --}}
    {{-- ─────────────────────────────────────────── --}}
    <div class="bg-gray-100 rounded-2xl overflow-hidden relative h-40 md:h-56 mb-8 shadow-sm group cursor-pointer flex items-center">
        {{-- Banner Content --}}
        <div class="absolute inset-0 z-10 flex flex-col justify-center p-6 md:p-10 w-2/3">
            <h2 class="text-xl md:text-3xl font-extrabold text-gray-900 leading-tight mb-2 group-hover:text-primary-600 transition-colors">Home/Office<br>Cleaning</h2>
            <p class="text-[10px] md:text-xs text-gray-600 mb-4 font-medium uppercase tracking-wide">Furniture | Floor | Rooms</p>
            <a href="{{ route('search') }}?q=cleaning" class="inline-block bg-primary-600 text-white text-xs font-bold px-4 py-2 rounded-lg shadow-sm hover:bg-primary-700 transition-colors w-max">
                Book Now
            </a>
        </div>
        {{-- Banner Image/Graphic --}}
        <div class="absolute right-0 bottom-0 h-full w-1/2 bg-gradient-to-l from-primary-100 to-transparent flex items-end justify-end overflow-hidden rounded-r-2xl">
            {{-- Placeholder for actual image: <img src="cleaning.png" class="h-full object-cover"> --}}
            <svg class="w-full h-full text-primary-200 opacity-50 transform translate-x-4 translate-y-4" fill="currentColor" viewBox="0 0 24 24"><path d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
        </div>
        {{-- Pagination Dots --}}
        <div class="absolute bottom-3 left-0 right-0 flex justify-center gap-1.5 z-20">
            <span class="w-1.5 h-1.5 rounded-full bg-primary-600"></span>
            <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
            <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
        </div>
    </div>

    {{-- ─────────────────────────────────────────── --}}
    {{-- CATEGORIES GRID --}}
    {{-- ─────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-8">
        <div class="grid grid-cols-4 md:grid-cols-6 gap-y-6 gap-x-2">
            @foreach($categories->take(8) as $cat)
                <a href="{{ route('services.show', $cat->slug) }}" class="flex flex-col items-center group">
                    <div class="w-12 h-12 md:w-16 md:h-16 flex items-center justify-center text-gray-700 group-hover:text-primary-600 transition-colors mb-2">
                        @if($cat->icon)
                            {!! category_icon($cat->icon, 'w-6 h-6 md:w-8 md:h-8 stroke-1') !!}
                        @else
                            <svg class="w-6 h-6 md:w-8 md:h-8 stroke-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        @endif
                    </div>
                    <span class="text-[10px] md:text-xs font-semibold text-gray-800 text-center leading-tight truncate w-full px-1">{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>
        <div class="text-center mt-6 pt-4 border-t border-gray-100">
            <a href="{{ route('services.index') }}" class="text-primary-600 text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-1 hover:text-primary-700">
                More Categories
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </a>
        </div>
    </div>

    {{-- ─────────────────────────────────────────── --}}
    {{-- ALERT COMPONENT --}}
    {{-- ─────────────────────────────────────────── --}}
    @guest
        <div class="bg-primary-600 rounded-2xl p-4 text-white flex items-center justify-between shadow-md mb-8">
            <div class="flex items-start gap-3">
                <div class="mt-0.5">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <div class="font-bold text-sm">কাজ খুঁজছেন?</div>
                    <div class="text-[11px] text-primary-100">আজই আমাদের সাথে প্রোভাইডার হিসেবে যুক্ত হোন।</div>
                </div>
            </div>
            <a href="{{ route('register') }}?role=provider" class="bg-white text-primary-700 text-xs font-bold px-3 py-2 rounded-lg whitespace-nowrap shadow-sm hover:bg-gray-50 transition-colors">
                Apply Now
            </a>
        </div>
    @endguest

    {{-- ─────────────────────────────────────────── --}}
    {{-- RUNNING CAMPAIGN --}}
    {{-- ─────────────────────────────────────────── --}}
    <div class="mb-4 flex items-center justify-between">
        <h3 class="text-lg font-extrabold text-gray-900">Running Campaign</h3>
        <a href="#" class="text-xs font-bold text-primary-600">See All</a>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="h-32 bg-gradient-to-r from-blue-900 to-indigo-900 relative">
            <div class="absolute inset-0 p-5 flex flex-col justify-center">
                <div class="text-white text-2xl font-black italic tracking-wider mb-1">30% <span class="text-sm font-normal not-italic">OFF</span></div>
                <div class="text-white text-sm font-bold uppercase">Online Service Market</div>
                <div class="text-blue-200 text-[10px] mt-1">PROMO: <span class="bg-white text-blue-900 px-1 py-0.5 rounded font-mono font-bold">BDSERVICE30</span></div>
            </div>
        </div>
        <div class="p-4">
            <h4 class="font-bold text-gray-900 text-sm mb-1">Enjoy 30% discount on all services!</h4>
            <p class="text-xs text-gray-500 mb-4 line-clamp-2">Book your required service from our app or website before it gets expired. Save money now. Safe Payment, Free Registration.</p>
            <a href="#" class="block w-full bg-primary-600 text-white text-center font-bold text-xs py-3 rounded-xl hover:bg-primary-700 transition-colors shadow-sm">
                View Details
            </a>
        </div>
    </div>

    {{-- ─────────────────────────────────────────── --}}
    {{-- FEATURED PROVIDERS (Ongoing Order Style) --}}
    {{-- ─────────────────────────────────────────── --}}
    @if(isset($featuredProviders) && $featuredProviders->count() > 0)
    <div class="mb-4 flex items-center justify-between mt-8">
        <h3 class="text-lg font-extrabold text-gray-900">Featured Providers</h3>
        <a href="{{ route('search') }}" class="text-xs font-bold text-primary-600">See All</a>
    </div>

    <div class="flex flex-col gap-4">
        @foreach($featuredProviders->take(3) as $provider)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 relative">
                <div class="absolute top-4 right-4 bg-primary-50 text-primary-700 font-bold text-[10px] px-2 py-1 rounded-md">
                    ★ {{ number_format($provider->providerProfile?->rating_avg ?? 0, 1) }}
                </div>
                
                <div class="flex items-start gap-4">
                    <img src="{{ $provider->avatar_url }}" alt="{{ $provider->name }}" class="w-12 h-12 rounded-xl object-cover border border-gray-100">
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 text-sm">{{ $provider->name }}</h4>
                        <p class="text-xs text-gray-500 mb-2">{{ $provider->district?->bn_name ?? 'অজানা জেলা' }}</p>
                        
                        @if($provider->providerSkills->isNotEmpty())
                            <div class="text-[10px] text-gray-600 font-medium line-clamp-1 mb-3">
                                {{ $provider->providerSkills->pluck('subcategory.name')->implode(', ') }}
                            </div>
                        @endif
                        
                        <div class="flex gap-2">
                            <a href="tel:{{ $provider->phone }}" class="flex-1 text-center text-primary-600 border border-primary-600 font-bold text-[10px] py-2 rounded-lg hover:bg-primary-50 transition-colors">
                                CALL
                            </a>
                            <a href="{{ route('providers.show', $provider) }}" class="flex-1 text-center bg-primary-600 text-white font-bold text-[10px] py-2 rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                                DETAILS
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif

</div>

@endsection
