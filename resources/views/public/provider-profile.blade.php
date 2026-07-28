@extends('layouts.public')

@section('title', $user->name . ' — প্রোফাইল')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-5xl">
        
        {{-- Profile Header --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 mb-8 relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-32 bg-gradient-to-r from-primary-800 to-emerald-600"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row gap-6 items-start mt-8">
                {{-- Avatar --}}
                <div class="relative flex-shrink-0 mx-auto md:mx-0">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                         class="w-32 h-32 md:w-40 md:h-40 rounded-3xl object-cover border-4 border-white shadow-xl bg-white">
                    @if($user->providerProfile?->is_verified)
                        <div class="absolute -bottom-2 right-2 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full border-2 border-white shadow-md flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            যাচাইকৃত
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 text-center md:text-left mt-2">
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $user->name }}</h1>
                    
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-sm text-gray-600 mb-4">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $user->district?->bn_name }} @if($user->area) , {{ $user->area->bn_name }} @endif
                        </div>
                        <div class="flex items-center gap-1 text-yellow-600 font-bold bg-yellow-50 px-2 py-0.5 rounded-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            {{ number_format($user->providerProfile?->rating_avg ?? 0, 1) }} 
                            <span class="text-gray-500 font-normal">({{ $user->providerProfile?->total_reviews ?? 0 }} রিভিউ)</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $user->providerProfile?->total_jobs ?? 0 }} কাজ সম্পন্ন
                        </div>
                        @if($user->providerProfile?->experience_years)
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $user->providerProfile->experience_years }} বছরের অভিজ্ঞতা
                        </div>
                        @endif
                    </div>
                    
                    @if($user->providerProfile?->bio)
                        <p class="text-gray-600 text-sm leading-relaxed mb-4 max-w-3xl mx-auto md:mx-0">
                            {{ $user->providerProfile->bio }}
                        </p>
                    @endif
                </div>

                {{-- Action / Hire button (if seeker) --}}
                <div class="flex-shrink-0 w-full md:w-auto text-center">
                    @auth
                        @if(auth()->user()->isSeeker())
                            <a href="{{ route('seeker.job-requests.create') }}" class="btn btn-primary w-full shadow-lg hover:shadow-xl py-3 px-8 text-lg">
                                কাজ দিন
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary w-full shadow-lg py-3 px-8 text-lg">
                            কাজ দিতে লগইন করুন
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Column: Skills & Details --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Skills --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-900 text-lg mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        দক্ষতাসমূহ
                    </h3>
                    @if($user->providerSkills->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($user->providerSkills as $skill)
                                <span class="bg-primary-50 text-primary-700 px-3 py-1.5 rounded-xl text-sm font-medium border border-primary-100">
                                    {{ $skill->subcategory?->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">কোনো দক্ষতা যোগ করা হয়নি।</p>
                    @endif
                </div>

                {{-- Portfolio Photos --}}
                @if($user->providerProfile?->portfolio_photos)
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100" x-data="{ imgOpen: false, currentImg: '' }">
                        <h3 class="font-bold text-gray-900 text-lg mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            পোর্টফোলিও
                        </h3>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($user->providerProfile->portfolio_photos as $photo)
                                <img src="{{ Storage::url($photo) }}" alt="Portfolio" 
                                     @click="imgOpen = true; currentImg = '{{ Storage::url($photo) }}'"
                                     class="w-full h-24 object-cover rounded-xl cursor-pointer hover:opacity-80 transition-opacity">
                            @endforeach
                        </div>

                        {{-- Lightbox Modal --}}
                        <div x-show="imgOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4">
                            <button @click="imgOpen = false" class="absolute top-4 right-4 text-white hover:text-gray-300">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <img :src="currentImg" class="max-w-full max-h-[90vh] rounded-lg">
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right Column: Direct Services & Reviews --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- Direct Services --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-900 text-xl mb-6">সরাসরি সেবা অফার</h3>
                    
                    @if($user->directServices->count() > 0)
                        <div class="space-y-4">
                            @foreach($user->directServices as $service)
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-4 rounded-2xl border border-gray-100 hover:border-primary-200 hover:bg-gray-50 transition-colors">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900 text-lg">{{ $service->title }}</h4>
                                        <div class="flex items-center gap-4 mt-1 text-sm text-gray-500">
                                            <span class="bg-gray-100 px-2 py-0.5 rounded">{{ $service->subcategory->name }}</span>
                                            @if($service->estimated_duration)
                                                <span>সময়: {{ $service->estimated_duration }}</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $service->description }}</p>
                                    </div>
                                    <div class="text-left sm:text-right w-full sm:w-auto">
                                        <div class="text-xl font-extrabold text-primary-700 mb-2">
                                            ৳{{ number_format($service->price) }} 
                                            <span class="text-xs font-normal text-gray-500">
                                                {{ $service->price_type === 'hourly' ? '/ঘন্টা' : ($service->price_type === 'starting_from' ? 'থেকে শুরু' : '(ফিক্সড)') }}
                                            </span>
                                        </div>
                                        @auth
                                            @if(auth()->user()->isSeeker())
                                                <a href="{{ route('seeker.direct-booking.create', $service->id) }}" class="btn btn-primary btn-sm w-full sm:w-auto">বুক করুন</a>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-outline btn-sm w-full sm:w-auto">বুক করতে লগইন করুন</a>
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-2xl">
                            কোনো সরাসরি সেবা যোগ করা হয়নি।
                        </div>
                    @endif
                </div>

                {{-- Reviews --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-900 text-xl mb-6 flex items-center gap-2">
                        কাস্টমার রিভিউ 
                        <span class="bg-yellow-100 text-yellow-700 text-sm px-2 py-0.5 rounded-lg">{{ $user->reviewsReceived->count() }}</span>
                    </h3>

                    @if($user->reviewsReceived->count() > 0)
                        <div class="space-y-6">
                            @foreach($user->reviewsReceived as $review)
                                <div class="border-b border-gray-100 last:border-0 pb-6 last:pb-0">
                                    <div class="flex items-start justify-between gap-4 mb-2">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $review->reviewer->avatar_url }}" alt="" class="w-10 h-10 rounded-full object-cover bg-gray-100">
                                            <div>
                                                <h5 class="font-bold text-gray-900 text-sm">{{ $review->reviewer->name }}</h5>
                                                <div class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                        <div class="flex text-yellow-400 text-sm">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                        </div>
                                    </div>
                                    @if($review->comment)
                                        <p class="text-gray-600 text-sm leading-relaxed mt-2 pl-13">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-2xl text-sm">
                            এখনও কোনো রিভিউ নেই।
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
