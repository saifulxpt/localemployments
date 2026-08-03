@extends('layouts.public')

@section('title', $user->name . ' — প্রোফাইল')

@section('content')
<div class="bg-gray-50/50 min-h-screen pb-16">
    
    {{-- Immersive Header Background --}}
    <div class="h-64 md:h-80 w-full relative overflow-hidden bg-gray-900">
        <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=2000&auto=format&fit=crop" alt="Cover Background" class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay object-top">
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent"></div>
    </div>

    <div class="container mx-auto px-4 max-w-5xl -mt-32 md:-mt-40 relative z-10">
        
        {{-- Profile Main Card --}}
        <div class="bg-white rounded-[2rem] p-6 md:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-gray-100 mb-8">
            <div class="flex flex-col md:flex-row gap-8 items-start">
                
                {{-- Avatar --}}
                <div class="relative flex-shrink-0 mx-auto md:mx-0 -mt-16 md:-mt-20">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                         class="w-36 h-36 md:w-44 md:h-44 rounded-[2rem] object-cover border-[6px] border-white shadow-2xl bg-white">
                    @if($user->providerProfile?->is_verified)
                        <div class="absolute -bottom-2 -right-2 bg-emerald-500 text-white text-sm font-extrabold px-4 py-1.5 rounded-full border-[3px] border-white shadow-lg flex items-center gap-1.5 transform hover:scale-105 transition-transform cursor-default">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            যাচাইকৃত
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 text-center md:text-left mt-2 md:mt-0 w-full">
                    <h1 class="text-3xl md:text-4xl font-black text-gray-900 mb-3">{{ $user->name }}</h1>
                    
                    <div class="flex items-center justify-center md:justify-start gap-2 text-gray-500 mb-6 font-medium text-sm md:text-base">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $user->district?->bn_name }} @if($user->area) , {{ $user->area->bn_name }} @endif
                    </div>
                    
                    {{-- Premium Stats Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-8">
                        {{-- Rating --}}
                        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 text-center hover:bg-gray-100 transition-colors">
                            <div class="flex items-center justify-center gap-1 text-yellow-500 mb-1">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-2xl font-black text-gray-900">{{ number_format($user->providerProfile?->rating_avg ?? 0, 1) }}</span>
                            </div>
                            <div class="text-xs font-bold text-gray-500 uppercase tracking-wide">{{ $user->providerProfile?->total_reviews ?? 0 }} রিভিউ</div>
                        </div>

                        {{-- Jobs --}}
                        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 text-center hover:bg-gray-100 transition-colors">
                            <div class="text-2xl font-black text-primary-600 mb-1">{{ $user->providerProfile?->total_jobs ?? 0 }}</div>
                            <div class="text-xs font-bold text-gray-500 uppercase tracking-wide">কাজ সম্পন্ন</div>
                        </div>

                        {{-- Experience --}}
                        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 text-center hover:bg-gray-100 transition-colors">
                            <div class="text-2xl font-black text-emerald-600 mb-1">{{ $user->providerProfile?->experience_years ?? 0 }} <span class="text-base font-bold text-gray-400">বছর</span></div>
                            <div class="text-xs font-bold text-gray-500 uppercase tracking-wide">অভিজ্ঞতা</div>
                        </div>

                        {{-- Hourly Rate --}}
                        @if($user->providerProfile?->hourly_rate_min)
                        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 text-center hover:bg-gray-100 transition-colors">
                            <div class="text-xl font-black text-gray-900 mb-1">
                                ৳{{ number_format($user->providerProfile->hourly_rate_min) }}<span class="text-sm text-gray-400 font-bold">/ঘ.</span>
                            </div>
                            <div class="text-xs font-bold text-gray-500 uppercase tracking-wide">শুরুর মূল্য</div>
                        </div>
                        @endif
                    </div>
                    
                    @if($user->providerProfile?->bio)
                        <div class="relative">
                            <svg class="absolute top-0 left-0 w-8 h-8 text-gray-200 transform -translate-x-2 -translate-y-4" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                            <p class="text-gray-700 text-base md:text-lg leading-relaxed max-w-3xl mx-auto md:mx-0 italic pl-6 border-l-4 border-primary-200">
                                {{ $user->providerProfile->bio }}
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Action / Hire button --}}
                <div class="flex-shrink-0 w-full md:w-64">
                    @auth
                        @if(auth()->user()->isSeeker())
                            <a href="{{ route('seeker.job-requests.create') }}" class="group flex items-center justify-center gap-3 bg-gray-900 text-white w-full rounded-2xl py-4 px-6 text-lg font-bold shadow-xl shadow-gray-900/20 hover:bg-primary-600 hover:shadow-primary-600/30 transition-all active:scale-95">
                                কাজ দিন
                                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="flex items-center justify-center gap-3 bg-gray-900 text-white w-full rounded-2xl py-4 px-6 text-lg font-bold shadow-xl hover:bg-gray-800 transition-all active:scale-95">
                            লগইন করুন
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Column: Skills & Details --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Skills --}}
                <div class="bg-white rounded-[2rem] p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100">
                    <h3 class="font-extrabold text-gray-900 text-xl mb-6 flex items-center gap-3">
                        <div class="p-2 bg-primary-50 text-primary-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        </div>
                        দক্ষতাসমূহ
                    </h3>
                    @if($user->providerSkills->count() > 0)
                        <div class="flex flex-wrap gap-2.5">
                            @foreach($user->providerSkills as $skill)
                                <span class="bg-white border-2 border-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-bold shadow-sm hover:border-primary-500 hover:text-primary-600 transition-colors cursor-default">
                                    {{ $skill->subcategory?->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 bg-gray-50 p-4 rounded-xl text-center">কোনো দক্ষতা যোগ করা হয়নি।</p>
                    @endif
                </div>

                {{-- Portfolio Photos --}}
                @if($user->providerProfile?->portfolio_photos)
                    <div class="bg-white rounded-[2rem] p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100" x-data="{ imgOpen: false, currentImg: '' }">
                        <h3 class="font-extrabold text-gray-900 text-xl mb-6 flex items-center gap-3">
                            <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            পোর্টফোলিও
                        </h3>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($user->providerProfile->portfolio_photos as $photo)
                                <img src="{{ Storage::url($photo) }}" alt="Portfolio" 
                                     @click="imgOpen = true; currentImg = '{{ Storage::url($photo) }}'"
                                     class="w-full h-28 object-cover rounded-2xl cursor-pointer hover:opacity-80 transition-opacity border border-gray-100 shadow-sm">
                            @endforeach
                        </div>

                        {{-- Lightbox Modal --}}
                        <div x-show="imgOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 p-4" x-transition.opacity>
                            <button @click="imgOpen = false" class="absolute top-6 right-6 text-white hover:text-gray-300 transition-colors bg-gray-800 rounded-full p-2">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <img :src="currentImg" class="max-w-full max-h-[90vh] rounded-2xl shadow-2xl">
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right Column: Direct Services & Reviews --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- Direct Services --}}
                <div class="bg-white rounded-[2rem] p-6 md:p-10 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100">
                    <h3 class="font-extrabold text-gray-900 text-2xl mb-8 flex items-center gap-3">
                        সরাসরি সেবা অফার
                    </h3>
                    
                    @if($user->directServices->count() > 0)
                        <div class="space-y-6">
                            @foreach($user->directServices as $service)
                                <div class="group flex flex-col md:flex-row justify-between items-start md:items-center gap-6 p-6 rounded-[1.5rem] bg-gray-50 hover:bg-white border-2 border-transparent hover:border-primary-100 hover:shadow-xl hover:shadow-primary-900/5 transition-all">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="bg-primary-100 text-primary-800 text-xs font-bold px-3 py-1 rounded-lg uppercase tracking-wide">{{ $service->subcategory->name }}</span>
                                            @if($service->estimated_duration)
                                                <span class="text-xs font-bold text-gray-500 bg-gray-200 px-3 py-1 rounded-lg">⏱ {{ $service->estimated_duration }}</span>
                                            @endif
                                        </div>
                                        <h4 class="font-extrabold text-gray-900 text-xl mb-3 group-hover:text-primary-700 transition-colors">{{ $service->title }}</h4>
                                        <p class="text-gray-600 leading-relaxed text-sm line-clamp-2">{{ $service->description }}</p>
                                    </div>
                                    
                                    <div class="flex flex-row md:flex-col items-center md:items-end justify-between w-full md:w-auto gap-4 md:gap-3 border-t md:border-t-0 md:border-l border-gray-200 pt-4 md:pt-0 md:pl-6">
                                        <div class="text-left md:text-right">
                                            <div class="text-2xl font-black text-gray-900">
                                                ৳{{ number_format($service->price) }} 
                                            </div>
                                            <div class="text-xs font-bold text-gray-500 uppercase">
                                                {{ $service->price_type === 'hourly' ? 'প্রতি ঘন্টা' : ($service->price_type === 'starting_from' ? 'থেকে শুরু' : 'ফিক্সড প্রাইস') }}
                                            </div>
                                        </div>
                                        @auth
                                            @if(auth()->user()->isSeeker())
                                                <a href="{{ route('seeker.direct-booking.create', $service->id) }}" class="bg-primary-600 text-white font-bold py-2.5 px-6 rounded-xl hover:bg-primary-700 transition-colors shadow-md active:scale-95 whitespace-nowrap">বুক করুন</a>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="bg-gray-200 text-gray-800 font-bold py-2.5 px-6 rounded-xl hover:bg-gray-300 transition-colors shadow-sm whitespace-nowrap">লগইন করুন</a>
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 px-4 bg-gray-50 rounded-[1.5rem] border-2 border-dashed border-gray-200">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-gray-400">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">কোনো সেবা নেই</h3>
                            <p class="text-gray-500">এই প্রোভাইডার এখনও কোনো সরাসরি সেবা যোগ করেননি।</p>
                        </div>
                    @endif
                </div>

                {{-- Reviews --}}
                <div class="bg-white rounded-[2rem] p-6 md:p-10 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100">
                    <h3 class="font-extrabold text-gray-900 text-2xl mb-8 flex items-center gap-3">
                        কাস্টমার রিভিউ 
                        <span class="bg-yellow-100 text-yellow-700 text-base px-3 py-1 rounded-xl font-bold">{{ $user->reviewsReceived->count() }}</span>
                    </h3>

                    @if($user->reviewsReceived->count() > 0)
                        <div class="space-y-6">
                            @foreach($user->reviewsReceived as $review)
                                <div class="bg-gray-50 p-6 rounded-[1.5rem] relative">
                                    <div class="flex items-start justify-between gap-4 mb-4">
                                        <div class="flex items-center gap-4">
                                            <img src="{{ $review->reviewer->avatar_url }}" alt="" class="w-12 h-12 rounded-full object-cover shadow-sm bg-white border border-gray-100">
                                            <div>
                                                <h5 class="font-bold text-gray-900">{{ $review->reviewer->name }}</h5>
                                                <div class="text-xs font-semibold text-gray-500 mt-0.5">{{ $review->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                        <div class="flex gap-0.5 text-yellow-400 bg-white px-3 py-1.5 rounded-full shadow-sm border border-gray-100">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                        </div>
                                    </div>
                                    @if($review->comment)
                                        <p class="text-gray-700 text-base leading-relaxed pl-16">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 px-4 bg-gray-50 rounded-[1.5rem] border-2 border-dashed border-gray-200">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-yellow-400">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">কোনো রিভিউ নেই</h3>
                            <p class="text-gray-500">এই প্রোভাইডারের প্রোফাইলে এখনও কোনো রিভিউ যুক্ত হয়নি।</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
