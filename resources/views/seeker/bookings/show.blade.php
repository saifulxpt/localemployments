@extends('layouts.seeker')

@section('title', 'বুকিং বিস্তারিত')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('seeker.bookings.index') }}" class="text-gray-500 hover:text-primary-600 transition-colors flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            সব বুকিং
        </a>
        <x-status-badge :status="$booking->status" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Main Details --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Job Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6 md:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4 border-b border-gray-100 pb-4">কাজের বিবরণ</h2>
                
                <h3 class="font-bold text-gray-900 text-lg mb-2">
                    {{ $booking->jobRequest ? $booking->jobRequest->title : ($booking->directService ? $booking->directService->title : 'অজানা কাজ') }}
                </h3>
                
                @if($booking->jobRequest)
                    <p class="text-gray-600 text-sm mb-4">{{ $booking->jobRequest->description }}</p>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 text-sm space-y-2">
                        <div class="flex gap-2">
                            <span class="font-semibold text-gray-700 w-24">লোকেশন:</span>
                            <span class="text-gray-900">{{ $booking->jobRequest->address_detail }}, {{ $booking->jobRequest->area->bn_name }}, {{ $booking->jobRequest->district->bn_name }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="font-semibold text-gray-700 w-24">তারিখ ও সময়:</span>
                            <span class="text-gray-900">{{ $booking->jobRequest->preferred_date ? \Carbon\Carbon::parse($booking->jobRequest->preferred_date)->format('d M, Y') : 'যেকোনো দিন' }} ({{ $booking->jobRequest->preferred_time ?: 'যেকোনো সময়' }})</span>
                        </div>
                    </div>
                @elseif($booking->directService)
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 text-sm space-y-2">
                        <div class="flex gap-2">
                            <span class="font-semibold text-gray-700 w-24">আপনার নোট:</span>
                            <span class="text-gray-900">{{ $booking->jobRequest->description ?? 'কোনো নোট নেই' }}</span> 
                        </div>
                    </div>
                @endif
            </div>

            {{-- Action Box --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">আপনার করণীয়</h2>
                
                <div class="flex flex-wrap gap-4">
                    @if($booking->status === 'pending')
                        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-xl text-yellow-800 text-sm w-full">
                            প্রোভাইডার বুকিংটি কনফার্ম করার জন্য অপেক্ষা করা হচ্ছে। প্রোভাইডার কনফার্ম করলে আপনি কাজ শুরু করার নোটিফিকেশন পাবেন।
                        </div>
                        <button type="button" x-data @click="document.getElementById('cancelForm').classList.toggle('hidden')" class="btn btn-outline text-red-500 border-red-200 hover:bg-red-50">বুকিং বাতিল করুন</button>
                    @elseif($booking->status === 'confirmed')
                        <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl text-blue-800 text-sm w-full">
                            প্রোভাইডার বুকিংটি গ্রহণ করেছেন। আপনার নির্দিষ্ট সময়ে প্রোভাইডার কাজ শুরু করবেন।
                        </div>
                        <button type="button" x-data @click="document.getElementById('cancelForm').classList.toggle('hidden')" class="btn btn-outline text-red-500 border-red-200 hover:bg-red-50">বুকিং বাতিল করুন</button>
                    @elseif($booking->status === 'in_progress')
                        <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl text-blue-800 text-sm w-full mb-4">
                            কাজটি বর্তমানে চলমান আছে। প্রোভাইডার কাজ সম্পন্ন করলে আপনি পেমেন্ট এবং রিভিউ দেওয়ার অপশন পাবেন।
                        </div>
                        <form action="{{ route('seeker.bookings.complete', $booking->id) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="btn btn-primary px-8 w-full sm:w-auto" onclick="return confirm('আপনি কি নিশ্চিত যে কাজ শেষ হয়েছে?')">কাজ শেষ হয়েছে (Mark Complete)</button>
                        </form>
                    @elseif($booking->status === 'completed')
                        @if($booking->payment_type === 'online' && (!$booking->payment || $booking->payment->status !== 'completed'))
                            <div class="bg-orange-50 border border-orange-200 p-4 rounded-xl text-orange-800 text-sm w-full mb-4">
                                কাজ সম্পন্ন হয়েছে। অনুগ্রহ করে আপনার পেমেন্ট সম্পন্ন করুন।
                            </div>
                            <form action="{{ route('seeker.payments.initiate', $booking->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary px-8">এখনই পেমেন্ট করুন</button>
                            </form>
                        @elseif(!$booking->review)
                            <div class="bg-green-50 border border-green-200 p-4 rounded-xl text-green-800 text-sm w-full mb-4">
                                পেমেন্ট সম্পন্ন হয়েছে। প্রোভাইডারের কাজের অভিজ্ঞতা কেমন ছিল তা জানিয়ে একটি রিভিউ দিন।
                            </div>
                            <a href="{{ route('seeker.reviews.create', $booking->id) }}" class="btn btn-primary px-8">রিভিউ দিন</a>
                        @else
                            <div class="bg-green-50 border border-green-200 p-4 rounded-xl text-green-800 text-sm flex items-center gap-3 w-full">
                                <svg class="w-6 h-6 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div>
                                    <strong class="block">বুকিং সম্পন্ন</strong>
                                    আপনি এই বুকিংয়ের কাজ, পেমেন্ট এবং রিভিউ সব সফলভাবে সম্পন্ন করেছেন।
                                </div>
                            </div>
                        @endif

                        {{-- Disupute Option if completed --}}
                        <div class="w-full mt-4 border-t border-gray-100 pt-4">
                            <a href="{{ route('seeker.disputes.create', $booking->id) }}" class="text-sm font-medium text-red-500 hover:underline">কাজে কোনো সমস্যা হলে রিপোর্ট করুন (Dispute)</a>
                        </div>
                    @elseif($booking->status === 'cancelled')
                        <div class="bg-red-50 border border-red-200 p-4 rounded-xl text-red-800 text-sm w-full">
                            বুকিংটি বাতিল করা হয়েছে। কারণ: {{ $booking->cancel_reason ?? 'উল্লেখ করা হয়নি' }}
                        </div>
                    @endif
                </div>

                {{-- Cancel Form (Hidden by default) --}}
                @if(in_array($booking->status, ['pending', 'confirmed']))
                    <form id="cancelForm" action="{{ route('seeker.bookings.cancel', $booking->id) }}" method="POST" class="mt-4 hidden p-4 bg-gray-50 rounded-xl border border-gray-200">
                        @csrf
                        <label class="block text-sm font-semibold text-gray-700 mb-2">বাতিল করার কারণ লিখুন</label>
                        <input type="text" name="cancel_reason" class="input mb-3" required placeholder="কেন বাতিল করছেন?">
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="document.getElementById('cancelForm').classList.add('hidden')" class="btn btn-outline btn-sm">বন্ধ করুন</button>
                            <button type="submit" class="btn bg-red-600 text-white hover:bg-red-700 btn-sm">বুকিং বাতিল করুন</button>
                        </div>
                    </form>
                @endif
            </div>

            {{-- Messaging --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900">প্রোভাইডারের সাথে চ্যাট</h2>
                    <a href="{{ route('seeker.messages.show', $booking->id) }}" class="btn btn-outline btn-sm">ফুলস্ক্রিন চ্যাট</a>
                </div>
                
                {{-- Placeholder link to messaging --}}
                <div class="bg-gray-50 h-64 rounded-xl border border-gray-200 flex items-center justify-center flex-col gap-2">
                    <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <a href="{{ route('seeker.messages.show', $booking->id) }}" class="text-primary-600 font-semibold hover:underline">মেসেজ অপেন করুন</a>
                </div>
            </div>

        </div>

        {{-- Sidebar Details --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- Provider Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">প্রোভাইডার তথ্য</h3>
                <div class="flex items-start gap-4 mb-4">
                    <a href="{{ route('providers.show', $booking->provider->id) }}">
                        <img src="{{ $booking->provider->avatar_url }}" alt="" class="w-12 h-12 rounded-full object-cover">
                    </a>
                    <div>
                        <h4 class="font-bold text-gray-900"><a href="{{ route('providers.show', $booking->provider->id) }}" class="hover:text-primary-600">{{ $booking->provider->name }}</a></h4>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                            <span class="flex items-center gap-1"><span class="text-yellow-500">★</span> {{ number_format($booking->provider->providerProfile?->rating_avg ?? 0, 1) }}</span>
                            <span>•</span>
                            <span>{{ $booking->provider->providerProfile?->total_jobs ?? 0 }} কাজ</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-3 text-sm text-gray-700">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        @if(in_array($booking->status, ['pending', 'cancelled']))
                            <span class="text-gray-400 italic">বুকিং কনফার্ম হলে নম্বর দেখা যাবে</span>
                        @else
                            <a href="tel:{{ $booking->provider->phone }}" class="font-semibold text-primary-700 hover:underline">{{ $booking->provider->phone }}</a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Financial Summary --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">হিসাব-নিকাশ</h3>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center text-lg font-bold text-primary-700">
                        <span>কাজের মূল্য</span>
                        <span>৳{{ number_format($booking->service_amount) }}</span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100">
                    <div class="text-xs text-gray-500 mb-1">পেমেন্ট মেথড:</div>
                    <div class="font-semibold text-gray-900">{{ $booking->payment_type === 'cash' ? 'ক্যাশে পেমেন্ট (Pay to provider)' : 'অনলাইন পেমেন্ট' }}</div>
                    
                    @if($booking->payment)
                        <div class="mt-2 text-xs font-semibold px-3 py-1.5 inline-block rounded-md {{ $booking->payment->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            স্ট্যাটাস: {{ $booking->payment->status }}
                        </div>
                    @else
                        <div class="mt-2 text-xs font-semibold px-3 py-1.5 inline-block rounded-md bg-yellow-100 text-yellow-700">
                            স্ট্যাটাস: unpaid
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
