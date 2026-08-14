@extends('layouts.provider')

@section('title', 'বুকিং বিস্তারিত')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('provider.bookings.index') }}" class="text-gray-500 hover:text-primary-600 transition-colors flex items-center gap-1">
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
                            <span class="text-gray-900">{{ $booking->jobRequest->address }}, {{ $booking->jobRequest->area->bn_name }}, {{ $booking->jobRequest->district->bn_name }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="font-semibold text-gray-700 w-24">তারিখ ও সময়:</span>
                            <span class="text-gray-900">{{ $booking->jobRequest->preferred_date ? \Carbon\Carbon::parse($booking->jobRequest->preferred_date)->format('d M, Y') : 'যেকোনো দিন' }} ({{ $booking->jobRequest->preferred_time ?: 'যেকোনো সময়' }})</span>
                        </div>
                    </div>
                @elseif($booking->directService)
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 text-sm space-y-2">
                        <div class="flex gap-2">
                            <span class="font-semibold text-gray-700 w-24">লোকেশন:</span>
                            <span class="text-gray-900">{{ $booking->location_detail ?? 'ঠিকানা উল্লেখ নেই' }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="font-semibold text-gray-700 w-24">তারিখ ও সময়:</span>
                            <span class="text-gray-900">{{ $booking->service_date ? \Carbon\Carbon::parse($booking->service_date)->format('d M, Y') : 'যেকোনো দিন' }} ({{ $booking->service_time ?: 'যেকোনো সময়' }})</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="font-semibold text-gray-700 w-24">বুকিং নোট:</span>
                            <span class="text-gray-900">{{ $booking->seeker_note ?? 'কোনো নোট নেই' }}</span>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Action Box --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">আপনার করণীয়</h2>
                
                <div class="flex flex-wrap gap-4">
                    @if($booking->status === 'pending')
                        <form action="{{ route('provider.bookings.confirm', $booking->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary px-8">বুকিং নিশ্চিত করুন (Confirm)</button>
                        </form>
                        <button type="button" x-data @click="document.getElementById('cancelForm').classList.toggle('hidden')" class="btn btn-outline text-red-500 border-red-200 hover:bg-red-50">বাতিল করুন</button>
                    @elseif($booking->status === 'confirmed')
                        <form action="{{ route('provider.bookings.start', $booking->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary px-8">কাজ শুরু করুন (Start Job)</button>
                        </form>
                    @elseif($booking->status === 'in_progress')
                        <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl text-blue-800 text-sm w-full mb-4">
                            কাজটি বর্তমানে চলমান আছে। কাজ শেষ হলে নিচের বাটনে ক্লিক করুন।
                        </div>
                        <form action="{{ route('provider.bookings.complete', $booking->id) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="btn btn-primary px-8 w-full sm:w-auto" onclick="return confirm('আপনি কি নিশ্চিত যে কাজ শেষ হয়েছে?')">কাজ শেষ হয়েছে (Mark Complete)</button>
                        </form>
                    @elseif($booking->status === 'completed')
                        <div class="bg-green-50 border border-green-200 p-4 rounded-xl text-green-800 text-sm flex items-center gap-3 w-full">
                            <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            কাজটি সফলভাবে সম্পন্ন হয়েছে।
                        </div>
                    @elseif($booking->status === 'cancelled')
                        <div class="bg-red-50 border border-red-200 p-4 rounded-xl text-red-800 text-sm w-full">
                            বুকিংটি বাতিল করা হয়েছে। কারণ: {{ $booking->cancel_reason ?? 'উল্লেখ করা হয়নি' }}
                        </div>
                    @endif
                </div>

                {{-- Cancel Form (Hidden by default) --}}
                @if(in_array($booking->status, ['pending', 'confirmed']))
                    <form id="cancelForm" action="{{ route('provider.bookings.cancel', $booking->id) }}" method="POST" class="mt-4 hidden p-4 bg-gray-50 rounded-xl border border-gray-200">
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
                    <h2 class="text-xl font-bold text-gray-900">কাস্টমারের সাথে চ্যাট</h2>
                    <a href="{{ route('provider.messages.show', $booking->id) }}" class="btn btn-outline btn-sm">ফুলস্ক্রিন চ্যাট</a>
                </div>
                
                {{-- Alpine Chat Component placeholder --}}
                <div class="bg-gray-50 h-64 rounded-xl border border-gray-200 flex items-center justify-center flex-col gap-2">
                    <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <a href="{{ route('provider.messages.show', $booking->id) }}" class="text-primary-600 font-semibold hover:underline">মেসেজ অপেন করুন</a>
                </div>
            </div>

        </div>

        {{-- Sidebar Details --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- Customer Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">কাস্টমার তথ্য</h3>
                <div class="flex items-center gap-4 mb-4">
                    <img src="{{ $booking->seeker->avatar_url }}" alt="" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <h4 class="font-bold text-gray-900">{{ $booking->seeker->name }}</h4>
                        <p class="text-sm text-gray-500">কাস্টমার</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm text-gray-700">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        @if($booking->status === 'pending')
                            <span class="text-gray-400 italic">বুকিং নিশ্চিত করলে নম্বর দেখা যাবে</span>
                        @else
                            <a href="tel:{{ $booking->seeker->phone }}" class="hover:text-primary-600">{{ $booking->seeker->phone }}</a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Financial Summary --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">হিসাব-নিকাশ</h3>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center text-gray-600">
                        <span>কাজের মূল্য</span>
                        <span class="font-bold text-gray-900">৳{{ number_format($booking->service_amount) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-600 border-b border-gray-100 pb-3">
                        <span>সার্ভিস ফি ({{ setting('commission_rate', 12) }}%)</span>
                        <span class="text-red-500">- ৳{{ number_format($booking->platform_fee) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-lg font-bold text-primary-700 pt-1">
                        <span>আপনার আয়</span>
                        <span>৳{{ number_format($booking->provider_earning) }}</span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100">
                    <div class="text-xs text-gray-500 mb-1">পেমেন্ট মেথড:</div>
                    <div class="font-semibold text-gray-900">{{ $booking->payment_type === 'cash' ? 'ক্যাশে পেমেন্ট (Pay after service)' : 'অনলাইন পেমেন্ট' }}</div>
                    
                    @if($booking->payment)
                        <div class="mt-2 text-xs font-semibold {{ $booking->payment->status === 'completed' ? 'text-green-600' : 'text-yellow-600' }}">
                            পেমেন্ট স্ট্যাটাস: {{ $booking->payment->status }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
