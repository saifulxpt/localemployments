@extends('layouts.seeker')

@section('title', 'কাজের বিস্তারিত')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('seeker.job-requests.index') }}" class="text-gray-500 hover:text-primary-600 transition-colors flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            সব জব রিকোয়েস্ট
        </a>
        <div class="flex gap-2">
            @if($jobRequest->status === 'open')
                <form action="{{ route('seeker.job-requests.cancel', $jobRequest->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই রিকোয়েস্টটি বাতিল করতে চান?')">
                    @csrf
                    <button type="submit" class="btn btn-outline text-red-500 hover:bg-red-50 border-red-200 btn-sm">রিকোয়েস্ট বাতিল করুন</button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Job Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 md:p-8">
                    
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-primary-700 bg-primary-50 px-2.5 py-1 rounded-md border border-primary-100">
                                {{ $jobRequest->subcategory->name }}
                            </span>
                            <span class="text-xs font-semibold px-2 py-1 rounded border {{ $jobRequest->status === 'open' ? 'bg-green-50 text-green-700 border-green-200' : ($jobRequest->status === 'assigned' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-gray-50 text-gray-700 border-gray-200') }}">
                                {{ ucfirst($jobRequest->status) }}
                            </span>
                        </div>
                        <span class="text-sm text-gray-500">{{ $jobRequest->created_at->format('d M, Y h:i A') }}</span>
                    </div>

                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-4">{{ $jobRequest->title }}</h1>
                    
                    <div class="prose max-w-none text-gray-600 mb-8 leading-relaxed">
                        {!! nl2br(e($jobRequest->description)) !!}
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 bg-gray-50 rounded-2xl border border-gray-100 mb-6">
                        <div>
                            <h4 class="text-xs font-bold text-gray-500 uppercase mb-1">বাজেট</h4>
                            <p class="text-gray-900 font-bold">{{ $jobRequest->budget_range ?: 'আলোচনা সাপেক্ষে' }}</p>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-500 uppercase mb-1">সময়সীমা</h4>
                            <p class="text-gray-900 font-medium">
                                @if($jobRequest->flexibility === 'urgent')
                                    <span class="text-red-600 font-bold">জরুরী (Urgent)</span>
                                @elseif($jobRequest->flexibility === 'fixed')
                                    নির্দিষ্ট তারিখে
                                @else
                                    যেকোনো সময় (Flexible)
                                @endif
                            </p>
                            @if($jobRequest->preferred_date || $jobRequest->preferred_time)
                                <p class="text-sm text-gray-600">{{ $jobRequest->preferred_date ? \Carbon\Carbon::parse($jobRequest->preferred_date)->format('d M, Y') : '' }} {{ $jobRequest->preferred_time }}</p>
                            @endif
                        </div>
                    </div>

                    @if($jobRequest->photos && count($jobRequest->photos) > 0)
                        <div x-data="{ imgOpen: false, currentImg: '' }">
                            <h3 class="font-bold text-gray-900 mb-3">সংযুক্ত ছবিসমূহ</h3>
                            <div class="flex flex-wrap gap-4">
                                @foreach($jobRequest->photos as $photo)
                                    <img src="{{ Storage::url($photo) }}" alt="Job Photo" 
                                         @click="imgOpen = true; currentImg = '{{ Storage::url($photo) }}'"
                                         class="w-24 h-24 rounded-xl object-cover border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity">
                                @endforeach
                            </div>

                            {{-- Lightbox --}}
                            <div x-show="imgOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4">
                                <button @click="imgOpen = false" class="absolute top-4 right-4 text-white hover:text-gray-300">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                                <img :src="currentImg" class="max-w-full max-h-[90vh] rounded-lg">
                            </div>
                        </div>
                    @endif

                </div>
            </div>
            
            {{-- Bids List --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-2">প্রোভাইডারদের বিড ({{ $jobRequest->bids->count() }})</h2>
                <p class="text-sm text-gray-500 mb-6">যে প্রোভাইডারকে আপনার উপযুক্ত মনে হয়, তার বিড গ্রহণ (Accept) করুন।</p>
                
                @if($jobRequest->bids->count() > 0)
                    <div class="space-y-6">
                        @foreach($jobRequest->bids->sortByDesc('created_at') as $bid)
                            <div class="bg-gray-50 rounded-2xl p-5 border {{ $bid->status === 'accepted' ? 'border-green-300 bg-green-50/30' : 'border-gray-200' }}">
                                
                                <div class="flex flex-col md:flex-row justify-between gap-4 mb-4">
                                    <div class="flex gap-4">
                                        <a href="{{ route('public.providers.show', $bid->provider->id) }}">
                                            <img src="{{ $bid->provider->avatar_url }}" class="w-12 h-12 rounded-full object-cover border border-gray-200 bg-white">
                                        </a>
                                        <div>
                                            <h3 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                                                <a href="{{ route('public.providers.show', $bid->provider->id) }}" class="hover:text-primary-600">{{ $bid->provider->name }}</a>
                                                @if($bid->provider->providerProfile?->is_verified)
                                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20" title="Verified"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                @endif
                                            </h3>
                                            <div class="flex items-center gap-3 text-xs text-gray-500 mt-1">
                                                <span class="flex items-center gap-1"><span class="text-yellow-500">★</span> {{ number_format($bid->provider->providerProfile?->rating_avg ?? 0, 1) }}</span>
                                                <span>{{ $bid->provider->providerProfile?->total_jobs ?? 0 }} কাজ সম্পন্ন</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-left md:text-right">
                                        <div class="text-xl font-black text-primary-700">৳{{ number_format($bid->bid_amount) }}</div>
                                        @if($bid->estimated_hours)
                                            <div class="text-xs text-gray-500 mt-0.5">সময় লাগবে: {{ $bid->estimated_hours }} ঘন্টা</div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="bg-white p-4 rounded-xl border border-gray-100 text-sm text-gray-700 mb-4 leading-relaxed">
                                    {!! nl2br(e($bid->message)) !!}
                                </div>
                                
                                <div class="flex flex-wrap items-center justify-between gap-4 mt-2">
                                    <div class="text-xs text-gray-400">{{ $bid->created_at->diffForHumans() }}</div>
                                    
                                    @if($jobRequest->status === 'open' && $bid->status === 'pending')
                                        <div class="flex gap-2 w-full sm:w-auto">
                                            <form action="{{ route('seeker.bids.reject', $bid->id) }}" method="POST" class="flex-1 sm:flex-none">
                                                @csrf
                                                <button type="submit" class="btn btn-outline text-red-500 hover:bg-red-50 border-red-200 btn-sm w-full" onclick="return confirm('আপনি কি এই বিডটি বাতিল করতে চান?')">বাতিল</button>
                                            </form>
                                            <form action="{{ route('seeker.bids.accept', $bid->id) }}" method="POST" class="flex-1 sm:flex-none">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm w-full" onclick="return confirm('বিড গ্রহণ করলে প্রোভাইডারের জন্য একটি বুকিং তৈরি হবে। আপনি কি নিশ্চিত?')">বিড গ্রহণ করুন (Accept)</button>
                                            </form>
                                        </div>
                                    @elseif($bid->status === 'accepted')
                                        <div class="flex items-center gap-2 text-green-600 font-bold bg-green-100 px-4 py-2 rounded-lg text-sm w-full sm:w-auto justify-center">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            আপনি এই বিডটি গ্রহণ করেছেন
                                        </div>
                                    @elseif($bid->status === 'rejected')
                                        <span class="text-red-500 text-sm font-semibold">বাতিলকৃত</span>
                                    @elseif($bid->status === 'withdrawn')
                                        <span class="text-gray-500 text-sm font-semibold">প্রোভাইডার বিড তুলে নিয়েছে</span>
                                    @endif
                                </div>

                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 rounded-2xl p-8 text-center border border-gray-100">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <p class="text-gray-500">এখনও কেউ এই কাজে বিড করেনি।</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar Info --}}
        <div class="lg:col-span-1 space-y-6">
            
            @if($jobRequest->status === 'assigned' && $jobRequest->booking)
                <div class="bg-primary-50 rounded-2xl shadow-sm border border-primary-200 p-6">
                    <h3 class="font-bold text-primary-900 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        কাজটি প্রদান করা হয়েছে
                    </h3>
                    <p class="text-sm text-primary-800 mb-4">এই কাজের জন্য একটি বুকিং তৈরি করা হয়েছে। প্রোভাইডার কাজ শেষ করার পর আপনাকে পেমেন্ট করতে হবে।</p>
                    <a href="{{ route('seeker.bookings.show', $jobRequest->booking->id) }}" class="btn btn-primary w-full text-sm">বুকিং বিস্তারিত দেখুন</a>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">লোকেশন তথ্য</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex gap-2">
                        <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <div>
                            <div class="font-medium text-gray-900">{{ $jobRequest->area->bn_name }}, {{ $jobRequest->district->bn_name }}</div>
                            @if($jobRequest->address_detail)
                                <div class="text-gray-500 mt-1">{{ $jobRequest->address_detail }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="mt-4 p-3 bg-gray-50 rounded-lg text-xs text-gray-500">
                    বিড গ্রহণ করার পর প্রোভাইডার আপনার সম্পূর্ণ ঠিকানা ও ফোন নম্বর দেখতে পারবে।
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-2">স্ট্যাটাস ও মেয়াদ</h3>
                <div class="space-y-2 mt-4 text-sm">
                    <div class="flex justify-between items-center text-gray-600">
                        <span>পোস্ট করা হয়েছে</span>
                        <span class="font-medium text-gray-900">{{ $jobRequest->created_at->format('d M, Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-600">
                        <span>মেয়াদ শেষ হবে</span>
                        <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($jobRequest->expires_at)->format('d M, Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-600">
                        <span>মোট বিড</span>
                        <span class="font-bold text-primary-600">{{ $jobRequest->bids_count }} জন</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
