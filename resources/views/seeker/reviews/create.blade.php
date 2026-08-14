@extends('layouts.seeker')

@section('title', 'রিভিউ দিন - ' . $booking->booking_ref)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-2 mb-2">
        <a href="{{ route('seeker.bookings.show', $booking->id) }}" class="text-gray-500 hover:text-primary-600 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">কাজের অভিজ্ঞতা ও রিভিউ দিন</h1>
    </div>

    {{-- Provider & Booking Info Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
        <img src="{{ $booking->provider->avatar_url }}" alt="" class="w-14 h-14 rounded-full object-cover ring-2 ring-primary-100">
        <div>
            <h2 class="text-lg font-bold text-gray-900">{{ $booking->provider->name }}</h2>
            <p class="text-xs text-gray-500">বুকিং রেফারেন্স: <strong class="font-mono text-gray-700">{{ $booking->booking_ref }}</strong></p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $booking->jobRequest ? $booking->jobRequest->title : ($booking->directService ? $booking->directService->title : 'সার্ভিস') }}</p>
        </div>
    </div>

    {{-- Review Form --}}
    <form action="{{ route('seeker.reviews.store', $booking->id) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ rating: 5, hoverRating: 0 }">
        @csrf

        <div class="p-6 md:p-8 space-y-6 text-center">
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-2">সার্ভিসটি আপনার কেমন লেগেছে?</h3>
                <p class="text-xs text-gray-500 mb-4">অনুগ্রহ করে রেটিং দিন (১ থেকে ৫ স্টার)</p>

                {{-- Interactive Star Selector --}}
                <div class="flex justify-center items-center gap-3">
                    <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                        <button type="button" 
                                @click="rating = star" 
                                @mouseenter="hoverRating = star" 
                                @mouseleave="hoverRating = 0"
                                class="p-1 text-3xl focus:outline-none transition-transform hover:scale-125">
                            <span :class="(hoverRating || rating) >= star ? 'text-amber-400' : 'text-gray-200'">★</span>
                        </button>
                    </template>
                </div>
                <input type="hidden" name="rating" :value="rating">
                @error('rating') <span class="text-xs text-red-500 block mt-2">{{ $message }}</span> @enderror
            </div>

            {{-- Comment Field --}}
            <div class="text-left">
                <label class="form-label">আপনার মতামত বা মন্তব্য (ঐচ্ছিক)</label>
                <textarea name="comment" rows="4" class="input" placeholder="প্রোভাইডারের কাজের দক্ষতা, ব্যবহার এবং সময়ানুবর্তিতা সম্পর্কে আপনার মন্তব্য লিখুন...">{{ old('comment') }}</textarea>
                @error('comment') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Footer Buttons --}}
        <div class="p-6 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('seeker.bookings.show', $booking->id) }}" class="text-sm font-medium text-gray-500 hover:text-gray-800">
                পরে দেব
            </a>
            <button type="submit" class="btn btn-primary w-full sm:w-auto px-8 py-3 font-bold shadow-md hover:shadow-lg transition-all">
                রিভিউ জমা দিন
            </button>
        </div>
    </form>
</div>
@endsection
