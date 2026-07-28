@extends('layouts.admin')

@section('title', 'কাজের বিস্তারিত: ' . $jobRequest->title)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('admin.job-requests.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            সব কাজের রিকোয়েস্ট
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 md:p-8">
                    
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex gap-2">
                            <span class="text-xs font-bold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-md border border-blue-100">
                                {{ $jobRequest->subcategory->name }}
                            </span>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-md border {{ $jobRequest->status === 'open' ? 'bg-green-50 text-green-700 border-green-200' : ($jobRequest->status === 'assigned' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-gray-100 text-gray-700 border-gray-200') }}">
                                {{ ucfirst($jobRequest->status) }}
                            </span>
                        </div>
                        <span class="text-sm text-gray-500">{{ $jobRequest->created_at->format('d M, Y h:i A') }}</span>
                    </div>

                    <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $jobRequest->title }}</h1>
                    
                    <div class="prose max-w-none text-gray-600 mb-8 text-sm">
                        {!! nl2br(e($jobRequest->description)) !!}
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 bg-gray-50 rounded-xl border border-gray-100 mb-6 text-sm">
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
                                <p class="text-gray-600">{{ $jobRequest->preferred_date ? \Carbon\Carbon::parse($jobRequest->preferred_date)->format('d M, Y') : '' }} {{ $jobRequest->preferred_time }}</p>
                            @endif
                        </div>
                    </div>

                    @if($jobRequest->photos && count($jobRequest->photos) > 0)
                        <div x-data="{ imgOpen: false, currentImg: '' }">
                            <h3 class="font-bold text-gray-900 mb-3 text-sm uppercase">সংযুক্ত ছবিসমূহ</h3>
                            <div class="flex flex-wrap gap-4">
                                @foreach($jobRequest->photos as $photo)
                                    <img src="{{ Storage::url($photo) }}" alt="Job Photo" 
                                         @click="imgOpen = true; currentImg = '{{ Storage::url($photo) }}'"
                                         class="w-24 h-24 rounded-lg object-cover border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity">
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
            
            {{-- Bids --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">জমাকৃত বিড ({{ $jobRequest->bids->count() }})</h2>
                
                @if($jobRequest->bids->count() > 0)
                    <div class="space-y-4">
                        @foreach($jobRequest->bids as $bid)
                            <div class="bg-gray-50 rounded-xl p-4 border {{ $bid->status === 'accepted' ? 'border-green-300 bg-green-50/50' : 'border-gray-100' }}">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex gap-3">
                                        <img src="{{ $bid->provider->avatar_url }}" class="w-8 h-8 rounded-full object-cover">
                                        <div>
                                            <a href="{{ route('admin.users.show', $bid->provider->id) }}" class="font-bold text-gray-900 hover:text-blue-600 text-sm">{{ $bid->provider->name }}</a>
                                            <div class="text-[10px] font-semibold {{ $bid->status === 'accepted' ? 'text-green-600' : 'text-gray-500' }}">স্ট্যাটাস: {{ ucfirst($bid->status) }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-blue-700">৳{{ number_format($bid->bid_amount) }}</div>
                                        <div class="text-[10px] text-gray-500">{{ $bid->created_at->format('d M y') }}</div>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-600 mt-2 bg-white p-2 rounded border border-gray-100">
                                    {{ Str::limit($bid->message, 100) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500 py-6 text-sm">কোনো বিড জমা পড়েনি।</div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            
            @if($jobRequest->booking)
                <div class="bg-blue-50 rounded-2xl shadow-sm border border-blue-200 p-6">
                    <h3 class="font-bold text-blue-900 mb-2">বুকিং তৈরি হয়েছে</h3>
                    <p class="text-sm text-blue-800 mb-4">এই কাজের জন্য একটি বুকিং চলমান আছে।</p>
                    <a href="{{ route('admin.bookings.show', $jobRequest->booking->id) }}" class="btn btn-primary w-full btn-sm">বুকিং বিস্তারিত দেখুন</a>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">সিকার (পোস্টদাতা)</h3>
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ $jobRequest->seeker->avatar_url }}" alt="" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <a href="{{ route('admin.users.show', $jobRequest->seeker->id) }}" class="font-bold text-gray-900 hover:text-blue-600">{{ $jobRequest->seeker->name }}</a>
                        <div class="text-xs text-gray-500">{{ $jobRequest->seeker->phone }}</div>
                    </div>
                </div>
                <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100">
                    <span class="block font-semibold mb-1">লোকেশন:</span>
                    {{ $jobRequest->address_detail ? $jobRequest->address_detail . ', ' : '' }}
                    {{ $jobRequest->area->bn_name }}, {{ $jobRequest->district->bn_name }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
