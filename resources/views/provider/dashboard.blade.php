@extends('layouts.provider')

@section('title', 'Provider Dashboard')

@section('content')

{{-- Welcome Bar --}}
<div class="bg-primary-900 rounded-3xl p-6 md:p-8 text-white mb-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-lg">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold mb-2">Welcome, {{ $user->name }}!</h1>
        <p class="text-primary-100">Here is your daily activity summary.</p>
    </div>
    <div class="flex gap-4">
        <a href="{{ route('provider.jobs.index') }}" class="btn bg-white text-primary-900 hover:bg-gray-50 border-none shadow-sm px-6">Search Jobs</a>
    </div>
</div>

{{-- Stats Grid --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col">
        <div class="text-gray-500 text-sm font-medium mb-1">Total Earned</div>
        <div class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">৳{{ number_format($stats['total_earned']) }}</div>
        <a href="{{ route('provider.earnings.index') }}" class="text-xs text-primary-600 font-semibold mt-auto hover:underline">View Earnings →</a>
    </div>
    
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col">
        <div class="text-gray-500 text-sm font-medium mb-1">Active Bookings</div>
        <div class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">{{ $stats['active_bookings'] }}</div>
        <a href="{{ route('provider.bookings.index') }}" class="text-xs text-primary-600 font-semibold mt-auto hover:underline">View Bookings →</a>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col">
        <div class="text-gray-500 text-sm font-medium mb-1">Pending Bids</div>
        <div class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">{{ $stats['pending_bids'] }}</div>
        <a href="{{ route('provider.bids.index') }}" class="text-xs text-primary-600 font-semibold mt-auto hover:underline">My Bids →</a>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col">
        <div class="text-gray-500 text-sm font-medium mb-1">Rating</div>
        <div class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2 flex items-baseline gap-2">
            <span class="text-yellow-500">★</span> 
            {{ number_format($stats['rating'], 1) }}
        </div>
        <div class="text-xs text-gray-400 mt-auto">{{ $stats['total_reviews'] }} Reviews</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    {{-- Left: New Job Requests --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">New Jobs in Your Area</h2>
            <a href="{{ route('provider.jobs.index') }}" class="text-sm font-semibold text-primary-600 hover:underline">View All</a>
        </div>

        @forelse($newJobs as $job)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start gap-4 mb-3">
                    <h3 class="font-bold text-gray-900 text-lg">
                        <a href="{{ route('provider.jobs.show', $job->id) }}" class="hover:text-primary-600 transition-colors">{{ $job->title }}</a>
                    </h3>
                    @if($job->budget_range)
                        <span class="bg-green-50 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg shrink-0 border border-green-100">
                            বাজেট: {{ $job->budget_range }}
                        </span>
                    @endif
                </div>
                
                <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-4">
                    <div class="flex items-center gap-1.5 bg-gray-50 px-2 py-1 rounded">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        {{ $job->subcategory->name }}
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $job->district->bn_name }} ({{ $job->area->bn_name }})
                    </div>
                    <div class="flex items-center gap-1.5 text-gray-500">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $job->created_at->diffForHumans() }}
                    </div>
                </div>

                <p class="text-sm text-gray-600 line-clamp-2 mb-4">{{ $job->description }}</p>

                <div class="flex justify-between items-center border-t border-gray-100 pt-4 mt-2">
                    <div class="text-sm text-gray-500">
                        বিড করেছেন: <span class="font-bold text-gray-900">{{ $job->bids_count ?? 0 }} জন</span>
                    </div>
                    <a href="{{ route('provider.jobs.show', $job->id) }}" class="btn btn-primary btn-sm px-4">বিস্তারিত ও বিড</a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-8 border border-gray-100 text-center text-gray-500">
                আপনার স্কিল এবং এলাকার সাথে মিল রেখে বর্তমানে কোনো নতুন কাজ নেই।
            </div>
        @endforelse
    </div>

    {{-- Right: Recent Bookings & Profile Completeness --}}
    <div class="space-y-6">
        
        {{-- Profile Completeness --}}
        @if($stats['profile_complete'] < 100)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-orange-100">
                <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    প্রোফাইল অসম্পূর্ণ
                </h3>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                    <div class="bg-orange-500 h-2.5 rounded-full" style="width: {{ $stats['profile_complete'] }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mb-4">{{ $stats['profile_complete'] }}% সম্পন্ন হয়েছে। বেশি কাজ পেতে প্রোফাইল ১০০% সম্পন্ন করুন।</p>
                <a href="{{ route('provider.profile.edit') }}" class="btn btn-outline btn-sm w-full">প্রোফাইল আপডেট করুন</a>
            </div>
        @endif

        {{-- Unread Messages --}}
        @if($unreadMessages > 0)
            <a href="{{ route('provider.messages.index') }}" class="block bg-blue-50 rounded-2xl p-4 border border-blue-100 hover:bg-blue-100 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-blue-900 text-sm">নতুন মেসেজ</h4>
                        <p class="text-xs text-blue-700 mt-0.5">আপনার {{ $unreadMessages }} টি অপঠিত মেসেজ আছে</p>
                    </div>
                </div>
            </a>
        @endif

        {{-- Recent Bookings --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900 text-sm uppercase tracking-wide">Recent Bookings</h3>
            </div>
            
            <div class="space-y-4">
                @forelse($recentBookings as $booking)
                    <div class="flex items-start gap-3 pb-4 border-b border-gray-50 last:border-0 last:pb-0">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center shrink-0 text-gray-500 font-bold text-sm">
                            {{ mb_substr($booking->seeker->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('provider.bookings.show', $booking->id) }}" class="font-semibold text-sm text-gray-900 hover:text-primary-600 truncate block">
                                {{ $booking->jobRequest ? $booking->jobRequest->title : ($booking->directService ? $booking->directService->title : 'বুকিং #' . $booking->id) }}
                            </a>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-xs text-gray-500">{{ $booking->created_at->format('d M, Y') }}</span>
                                <x-status-badge :status="$booking->status" />
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">কোনো সাম্প্রতিক বুকিং নেই।</p>
                @endforelse
            </div>
        </div>
        
    </div>
</div>

@endsection
