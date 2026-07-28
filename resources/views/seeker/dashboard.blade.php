@extends('layouts.seeker')

@section('title', 'কাস্টমার ড্যাশবোর্ড')

@section('content')

{{-- Welcome Bar --}}
<div class="bg-primary-900 rounded-3xl p-6 md:p-8 text-white mb-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-lg">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold mb-2">স্বাগতম, {{ $user->name }}!</h1>
        <p class="text-primary-100">আপনার আজকের দিনের সংক্ষিপ্ত বিবরণ নিচে দেওয়া হলো।</p>
    </div>
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('public.search') }}" class="btn bg-primary-800 text-white hover:bg-primary-700 border-none shadow-sm px-6">সার্ভিস খুঁজুন</a>
        <a href="{{ route('seeker.jobs.create') }}" class="btn bg-white text-primary-900 hover:bg-gray-50 border-none shadow-sm px-6">নতুন কাজ পোস্ট করুন</a>
    </div>
</div>

{{-- Stats Grid --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col">
        <div class="text-gray-500 text-sm font-medium mb-1">সক্রিয় জব রিকোয়েস্ট</div>
        <div class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">{{ $stats['active_requests'] }}</div>
        <a href="{{ route('seeker.jobs.index') }}" class="text-xs text-primary-600 font-semibold mt-auto hover:underline">সব জব দেখুন →</a>
    </div>
    
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col">
        <div class="text-gray-500 text-sm font-medium mb-1">মোট বুকিং</div>
        <div class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">{{ $stats['total_bookings'] }}</div>
        <a href="{{ route('seeker.bookings.index') }}" class="text-xs text-primary-600 font-semibold mt-auto hover:underline">বুকিং হিস্ট্রি →</a>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col">
        <div class="text-gray-500 text-sm font-medium mb-1">সম্পন্ন কাজ</div>
        <div class="text-2xl md:text-3xl font-extrabold text-green-600 mb-2">{{ $stats['completed'] }}</div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-orange-100 bg-orange-50/30 flex flex-col">
        <div class="text-orange-800 text-sm font-medium mb-1 flex items-center gap-1">
            রিভিউ বাকি আছে
            @if($stats['pending_review'] > 0)
                <span class="w-2 h-2 rounded-full bg-orange-500"></span>
            @endif
        </div>
        <div class="text-2xl md:text-3xl font-extrabold text-orange-600 mb-2">{{ $stats['pending_review'] }}</div>
        <a href="{{ route('seeker.bookings.index', ['status' => 'completed']) }}" class="text-xs text-orange-700 font-semibold mt-auto hover:underline">রিভিউ দিন →</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    {{-- Recent Bookings --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-lg font-bold text-gray-900">সাম্প্রতিক বুকিং</h2>
            <a href="{{ route('seeker.bookings.index') }}" class="text-sm font-semibold text-primary-600 hover:underline">সব দেখুন</a>
        </div>

        @forelse($recentBookings as $booking)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="font-bold text-gray-900 line-clamp-1 flex-1 pr-4">
                        <a href="{{ route('seeker.bookings.show', $booking->id) }}" class="hover:text-primary-600">
                            {{ $booking->jobRequest ? $booking->jobRequest->title : ($booking->directService ? $booking->directService->title : 'বুকিং #' . $booking->id) }}
                        </a>
                    </h3>
                    <x-status-badge :status="$booking->status" />
                </div>
                
                <div class="flex items-center gap-3 text-sm text-gray-600 border-t border-gray-50 pt-3">
                    <div class="flex items-center gap-2">
                        <img src="{{ $booking->provider->avatar_url }}" class="w-6 h-6 rounded-full object-cover">
                        <span class="font-medium text-gray-900">{{ $booking->provider->name }}</span>
                    </div>
                    <span class="text-gray-300">|</span>
                    <span class="font-bold text-primary-700">৳{{ number_format($booking->service_amount) }}</span>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-8 border border-gray-100 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <p class="text-gray-500 text-sm">আপনার কোনো বুকিং নেই।</p>
                <a href="{{ route('public.search') }}" class="btn btn-outline btn-sm mt-3">সার্ভিস খুঁজুন</a>
            </div>
        @endforelse
    </div>

    {{-- Recent Job Requests --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-lg font-bold text-gray-900">আপনার পোস্ট করা কাজ</h2>
            <a href="{{ route('seeker.jobs.index') }}" class="text-sm font-semibold text-primary-600 hover:underline">সব দেখুন</a>
        </div>

        @forelse($recentRequests as $job)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow flex flex-col">
                <div class="flex justify-between items-start mb-2">
                    <div class="text-xs font-bold text-primary-700 bg-primary-50 px-2 py-0.5 rounded border border-primary-100">
                        {{ $job->subcategory->name }}
                    </div>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded {{ $job->status === 'open' ? 'bg-green-100 text-green-700' : ($job->status === 'assigned' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                        {{ ucfirst($job->status) }}
                    </span>
                </div>
                
                <h3 class="font-bold text-gray-900 mb-1 line-clamp-1">
                    <a href="{{ route('seeker.jobs.show', $job->id) }}" class="hover:text-primary-600">{{ $job->title }}</a>
                </h3>
                
                <div class="text-sm text-gray-500 flex justify-between items-center mt-auto pt-3 border-t border-gray-50">
                    <span>বাজেট: <span class="font-medium text-gray-900">{{ $job->budget_range ?: 'আলোচনা সাপেক্ষে' }}</span></span>
                    <span class="font-medium {{ $job->bids_count > 0 ? 'text-primary-600' : 'text-gray-400' }}">{{ $job->bids_count ?? 0 }} টি বিড</span>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-8 border border-gray-100 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <p class="text-gray-500 text-sm">আপনি এখনও কোনো কাজ পোস্ট করেননি।</p>
                <a href="{{ route('seeker.jobs.create') }}" class="btn btn-outline btn-sm mt-3">কাজ পোস্ট করুন</a>
            </div>
        @endforelse
    </div>

</div>

@endsection
