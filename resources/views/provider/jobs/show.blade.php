@extends('layouts.provider')

@section('title', 'কাজের বিস্তারিত')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center gap-2 mb-2">
        <a href="{{ route('provider.jobs.index') }}" class="text-gray-500 hover:text-primary-600 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">কাজের বিস্তারিত</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 md:p-8">
            
            <div class="flex flex-wrap justify-between items-start gap-4 mb-6 border-b border-gray-100 pb-6">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-xs font-bold text-primary-700 bg-primary-50 px-2.5 py-1 rounded-md border border-primary-100">
                            {{ $jobRequest->subcategory->name }}
                        </span>
                        <span class="text-sm text-gray-500">{{ $jobRequest->created_at->diffForHumans() }}</span>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ $jobRequest->title }}</h2>
                    <div class="flex items-center gap-2 text-gray-600 font-medium">
                        <img src="{{ $jobRequest->seeker->avatar_url }}" class="w-6 h-6 rounded-full object-cover">
                        {{ $jobRequest->seeker->name }}
                    </div>
                </div>
                
                @if($jobRequest->budget_range)
                    <div class="bg-green-50 text-green-700 font-bold px-4 py-2 rounded-xl border border-green-100 text-lg">
                        বাজেট: {{ $jobRequest->budget_range }}
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase">লোকেশন</h4>
                        <p class="text-gray-900 font-medium">{{ $jobRequest->address }}</p>
                        <p class="text-sm text-gray-600">{{ $jobRequest->area->bn_name }}, {{ $jobRequest->district->bn_name }}</p>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase">সম্ভাব্য তারিখ ও সময়</h4>
                        <p class="text-gray-900 font-medium">{{ $jobRequest->preferred_date ? \Carbon\Carbon::parse($jobRequest->preferred_date)->format('d M, Y') : 'যেকোনো দিন' }}</p>
                        <p class="text-sm text-gray-600">{{ $jobRequest->preferred_time ?: 'যেকোনো সময়' }}</p>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-3">কাজের বিস্তারিত বিবরণ</h3>
                <div class="prose max-w-none text-gray-600 leading-relaxed">
                    {!! nl2br(e($jobRequest->description)) !!}
                </div>
            </div>

            @if($jobRequest->photos && count($jobRequest->photos) > 0)
                <div class="mb-8" x-data="{ imgOpen: false, currentImg: '' }">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">সংযুক্ত ছবিসমূহ</h3>
                    <div class="flex flex-wrap gap-4">
                        @foreach($jobRequest->photos as $photo)
                            <img src="{{ Storage::url($photo) }}" alt="Job Photo" 
                                 @click="imgOpen = true; currentImg = '{{ Storage::url($photo) }}'"
                                 class="w-24 h-24 rounded-xl object-cover border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity">
                        @endforeach
                    </div>

                    {{-- Lightbox --}}
                    <div x-show="imgOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4">
                        <button @click="imgOpen = false" class="absolute top-4 right-4 text-white hover:text-gray-300">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <img :src="currentImg" class="max-w-full max-h-[90vh] rounded-lg">
                    </div>
                </div>
            @endif

        </div>

        {{-- Bid Section --}}
        <div class="bg-gray-50 p-6 md:p-8 border-t border-gray-100">
            @if(!auth()->user()->providerProfile?->is_verified)
                <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-xl text-yellow-800 text-sm">
                    <strong>বিড করতে পারছেন না:</strong> আপনার অ্যাকাউন্টটি এখনও যাচাইকৃত নয়। কাজ পেতে প্রথমে আপনার <a href="{{ route('provider.verification.show') }}" class="underline font-bold text-yellow-900">অ্যাকাউন্ট যাচাই</a> করুন।
                </div>
            @elseif($myBid)
                <div class="bg-blue-50 border border-blue-200 p-6 rounded-2xl">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-blue-900 text-lg flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                আপনি এই কাজে বিড করেছেন
                            </h3>
                            <p class="text-sm text-blue-700 mt-1">কাস্টমার আপনার বিড গ্রহণ করলে আপনাকে নোটিফিকেশন দেওয়া হবে।</p>
                        </div>
                        <div class="text-xl font-black text-blue-900">৳{{ number_format($myBid->bid_amount) }}</div>
                    </div>
                    
                    <div class="bg-white p-4 rounded-xl border border-blue-100 text-sm text-gray-700 mb-4">
                        <strong class="block mb-1 text-gray-900">আপনার মেসেজ:</strong>
                        {!! nl2br(e($myBid->message)) !!}
                        
                        @if($myBid->estimated_hours)
                            <div class="mt-2 text-gray-500">আনুমানিক সময়: {{ $myBid->estimated_hours }} ঘন্টা</div>
                        @endif
                    </div>
                    
                    <a href="{{ route('provider.bids.index') }}" class="btn bg-white text-blue-700 hover:bg-blue-100 border border-blue-200 w-full sm:w-auto">সব বিড ম্যানেজ করুন</a>
                </div>
            @else
                
                @if($jobRequest->bids_count >= setting('max_bid_per_job', 10))
                    <div class="bg-red-50 border border-red-200 p-4 rounded-xl text-red-700 text-center">
                        এই কাজে সর্বোচ্চ পরিমাণ বিড জমা হয়ে গেছে। আপনি আর বিড করতে পারবেন না।
                    </div>
                @else
                    <div x-data="{ openBidForm: false }">
                        <div x-show="!openBidForm" class="text-center">
                            <h3 class="font-bold text-gray-900 text-lg mb-2">এই কাজটি করতে আগ্রহী?</h3>
                            <p class="text-gray-500 text-sm mb-4">কাস্টমারকে আপনার অফার এবং দাম জানিয়ে বিড করুন।</p>
                            <button @click="openBidForm = true" class="btn btn-primary px-8 py-3 text-lg shadow-md hover:shadow-lg w-full sm:w-auto">এখনই বিড করুন</button>
                        </div>

                        <form x-show="openBidForm" x-cloak action="{{ route('provider.bids.store', $jobRequest->id) }}" method="POST" class="bg-white p-6 rounded-2xl border border-primary-100 shadow-sm">
                            @csrf
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="font-bold text-gray-900 text-xl">আপনার বিড জমা দিন</h3>
                                <button type="button" @click="openBidForm = false" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="form-group mb-0">
                                    <label class="form-label">আপনার অফার করা মূল্য (৳) <span class="text-red-500">*</span></label>
                                    <input type="number" name="bid_amount" class="input text-lg font-bold text-primary-700" placeholder="যেমন: ১০০০" min="50" required value="{{ old('bid_amount') }}">
                                    @error('bid_amount') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group mb-0">
                                    <label class="form-label">আনুমানিক কত ঘন্টা লাগবে? (ঐচ্ছিক)</label>
                                    <input type="number" name="estimated_hours" class="input" placeholder="যেমন: ৫" min="1" value="{{ old('estimated_hours') }}">
                                </div>
                            </div>

                            <div class="form-group mb-6">
                                <label class="form-label">কাস্টমারকে মেসেজ লিখুন <span class="text-red-500">*</span></label>
                                <textarea name="message" rows="4" class="input" required minlength="20" placeholder="কেন আপনি এই কাজের জন্য উপযুক্ত এবং কীভাবে কাজ করবেন তা বিস্তারিত লিখুন...">{{ old('message') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">অন্তত ২০টি অক্ষর হতে হবে। সুন্দর মেসেজ বিড জেতার সম্ভাবনা বাড়ায়।</p>
                                @error('message') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex justify-end gap-3">
                                <button type="button" @click="openBidForm = false" class="btn btn-outline">বাতিল</button>
                                <button type="submit" class="btn btn-primary px-8">বিড জমা দিন</button>
                            </div>
                        </form>
                    </div>
                @endif
            @endif

        </div>
    </div>

</div>
@endsection
