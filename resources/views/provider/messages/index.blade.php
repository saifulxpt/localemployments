@extends('layouts.provider')

@section('title', 'মেসেজসমূহ')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">মেসেজসমূহ</h1>
        <p class="text-sm text-gray-500">কাস্টমারদের সাথে আপনার কথোপকথন।</p>
    </div>

    @if($bookings->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden divide-y divide-gray-100">
            @foreach($bookings as $booking)
                @php
                    $lastMessage = $booking->messages->first();
                    $hasUnread = $lastMessage && $lastMessage->receiver_id === auth()->id() && !$lastMessage->is_read;
                @endphp
                
                <a href="{{ route('provider.messages.show', $booking->id) }}" class="flex items-start gap-4 p-4 md:p-6 hover:bg-gray-50 transition-colors {{ $hasUnread ? 'bg-primary-50/30' : '' }}">
                    <div class="relative shrink-0">
                        <img src="{{ $booking->seeker->avatar_url }}" alt="" class="w-12 h-12 md:w-14 md:h-14 rounded-full object-cover border border-gray-200">
                        @if($hasUnread)
                            <div class="absolute top-0 right-0 w-3 h-3 bg-primary-500 border-2 border-white rounded-full"></div>
                        @endif
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold text-gray-900 truncate {{ $hasUnread ? 'text-primary-900' : '' }}">{{ $booking->seeker->name }}</h3>
                            @if($lastMessage)
                                <span class="text-xs text-gray-500 whitespace-nowrap ml-2">{{ $lastMessage->created_at->diffForHumans(null, true, true) }}</span>
                            @endif
                        </div>
                        
                        <div class="text-xs font-semibold text-gray-500 mb-1 truncate flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $booking->jobRequest ? $booking->jobRequest->title : ($booking->directService ? $booking->directService->title : 'বুকিং #' . $booking->id) }}
                        </div>
                        
                        @if($lastMessage)
                            <p class="text-sm truncate {{ $hasUnread ? 'font-bold text-gray-900' : 'text-gray-600' }}">
                                @if($lastMessage->sender_id === auth()->id())
                                    <span class="text-gray-400">আপনি:</span>
                                @endif
                                {{ $lastMessage->message }}
                            </p>
                        @else
                            <p class="text-sm text-gray-400 italic">এখনও কোনো মেসেজ হয়নি।</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">কোনো মেসেজ নেই</h3>
            <p class="text-gray-500">আপনার নিশ্চিতকৃত কোনো বুকিং নেই অথবা মেসেজ ইনবক্স খালি।</p>
        </div>
    @endif

</div>
@endsection
