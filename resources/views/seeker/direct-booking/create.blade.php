@extends('layouts.seeker')

@section('title', 'সরাসরি সেবা বুক করুন - ' . $directService->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center gap-2 mb-2">
        <a href="{{ route('providers.show', $directService->provider_id) }}" class="text-gray-500 hover:text-primary-600 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">সরাসরি সেবা বুকিং</h1>
    </div>

    {{-- Service & Provider Summary Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="flex items-start gap-4">
            <img src="{{ $directService->provider->avatar_url }}" alt="" class="w-14 h-14 rounded-full object-cover ring-2 ring-primary-100 shrink-0">
            <div>
                <span class="bg-primary-50 text-primary-700 text-xs font-bold px-2.5 py-1 rounded-lg">
                    {{ $directService->subcategory->name }}
                </span>
                <h2 class="text-xl font-bold text-gray-900 mt-1.5">{{ $directService->title }}</h2>
                <p class="text-xs text-gray-500 mt-1">প্রোভাইডার: <span class="font-bold text-gray-800">{{ $directService->provider->name }}</span></p>
            </div>
        </div>
        <div class="text-left md:text-right border-t md:border-t-0 pt-4 md:pt-0 w-full md:w-auto">
            <div class="text-2xl font-extrabold text-primary-600">৳{{ number_format($directService->price) }}</div>
            <div class="text-xs font-semibold text-gray-400 uppercase">
                {{ $directService->price_type === 'hourly' ? 'প্রতি ঘন্টা' : ($directService->price_type === 'starting_from' ? 'থেকে শুরু' : 'ফিক্সড প্রাইস') }}
            </div>
        </div>
    </div>

    {{-- Booking Form --}}
    <form action="{{ route('seeker.direct-booking.store', $directService->id) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @csrf

        <div class="p-6 md:p-8 space-y-6">
            <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3">বুকিংয়ের তথ্য দিন</h2>

            {{-- Date & Time --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group mb-0">
                    <label class="form-label">কখন সেবা নিতে চান? (তারিখ) <span class="text-red-500">*</span></label>
                    <input type="date" name="service_date" min="{{ date('Y-m-d') }}" value="{{ old('service_date', date('Y-m-d')) }}" class="input" required>
                    @error('service_date') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-0">
                    <label class="form-label">পছন্দের সময় (ঐচ্ছিক)</label>
                    <input type="text" name="service_time" value="{{ old('service_time') }}" class="input" placeholder="যেমন: সকাল ১০টা / বিকাল ৩টা">
                    @error('service_time') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Location Detail --}}
            <div class="form-group mb-0">
                <label class="form-label">আপনার ঠিকানা / অবস্থান বিবরণ <span class="text-red-500">*</span></label>
                <input type="text" name="location_detail" value="{{ old('location_detail', auth()->user()->address) }}" class="input" placeholder="যেমন: বাসা #১২, রোড #৪, ব্লক #সি, বনশ্রী" required>
                @error('location_detail') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Seeker Note --}}
            <div class="form-group mb-0">
                <label class="form-label">প্রোভাইডারের জন্য কোনো বিশেষ নোট (ঐচ্ছিক)</label>
                <textarea name="seeker_note" rows="3" class="input" placeholder="আপনার কোনো বিশেষ নির্দেশনা থাকলে এখানে লিখুন...">{{ old('seeker_note') }}</textarea>
                @error('seeker_note') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="p-6 md:p-8 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('providers.show', $directService->provider_id) }}" class="text-sm font-medium text-gray-500 hover:text-gray-800">
                বাতিল করুন
            </a>
            <button type="submit" class="btn btn-primary w-full sm:w-auto px-8 py-3.5 text-base font-bold shadow-md hover:shadow-lg transition-all">
                বুকিং কনফার্ম করুন
            </button>
        </div>
    </form>
</div>
@endsection
