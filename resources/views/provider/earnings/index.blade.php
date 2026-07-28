@extends('layouts.provider')

@section('title', 'আয় ও পেমেন্ট')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">আপনার আয়</h1>
            <p class="text-sm text-gray-500">আপনার সমস্ত আয় এবং লেনদেনের বিস্তারিত।</p>
        </div>
        <a href="{{ route('provider.withdrawals.create') }}" class="btn btn-primary whitespace-nowrap">
            টাকা উত্তোলন (Withdraw)
        </a>
    </div>

    {{-- Earnings Overview --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-primary-600 rounded-2xl p-6 shadow-sm border border-primary-700 text-white flex flex-col justify-between">
            <div class="text-primary-100 text-sm font-medium mb-2">সর্বমোট আয়</div>
            <div class="text-2xl md:text-3xl font-extrabold mb-1">৳{{ number_format($totalEarned) }}</div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="text-gray-500 text-sm font-medium mb-2">উত্তোলন করা হয়েছে</div>
            <div class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-1">৳{{ number_format($withdrawn) }}</div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="text-gray-500 text-sm font-medium mb-2">অপেক্ষমাণ (Pending)</div>
            <div class="text-2xl md:text-3xl font-extrabold text-orange-500 mb-1">৳{{ number_format($pending) }}</div>
        </div>

        <div class="bg-green-50 rounded-2xl p-6 shadow-sm border border-green-100 flex flex-col justify-between">
            <div class="text-green-800 text-sm font-medium mb-2">উত্তোলনযোগ্য ব্যালেন্স</div>
            <div class="text-2xl md:text-3xl font-extrabold text-green-700 mb-1">৳{{ number_format($available) }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Recent Completed Bookings --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">সাম্প্রতিক সম্পন্ন কাজ থেকে আয়</h2>
                
                @if($recentBookings->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentBookings as $booking)
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 bg-gray-50 rounded-xl border border-gray-100 hover:bg-white hover:border-gray-200 transition-colors gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-bold bg-green-100 text-green-700 px-2 py-0.5 rounded">সম্পন্ন</span>
                                        <span class="text-xs text-gray-500">{{ $booking->completed_at ? \Carbon\Carbon::parse($booking->completed_at)->format('d M, Y') : $booking->updated_at->format('d M, Y') }}</span>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-sm mb-1">
                                        <a href="{{ route('provider.bookings.show', $booking->id) }}" class="hover:text-primary-600 hover:underline">
                                            {{ $booking->jobRequest ? $booking->jobRequest->title : ($booking->directService ? $booking->directService->title : 'বুকিং #' . $booking->id) }}
                                        </a>
                                    </h4>
                                    <p class="text-xs text-gray-500 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        {{ $booking->seeker->name }}
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <div class="text-lg font-bold text-primary-700">+ ৳{{ number_format($booking->provider_earning) }}</div>
                                    <div class="text-[11px] text-gray-400">কাজের মূল্য: ৳{{ number_format($booking->service_amount) }} - ফি: ৳{{ number_format($booking->platform_fee) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        {{ $recentBookings->links() }}
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                        </div>
                        <p class="text-gray-500">এখনও কোনো কাজ সম্পন্ন হয়নি।</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Monthly Stats & Withdrawal Links --}}
        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2 flex items-center justify-between">
                    <span>টাকা উত্তোলন</span>
                    <a href="{{ route('provider.withdrawals.index') }}" class="text-xs font-semibold text-primary-600 hover:underline">হিস্ট্রি দেখুন</a>
                </h3>
                
                <p class="text-sm text-gray-600 mb-4">আপনার ব্যালেন্স থেকে সরাসরি ব্যাংক একাউন্ট বা মোবাইল ব্যাংকিংয়ে টাকা উত্তোলন করতে পারেন।</p>
                
                @if($available > 0)
                    <a href="{{ route('provider.withdrawals.create') }}" class="btn btn-primary w-full shadow-sm text-sm py-2.5">এখনই উত্তোলন করুন</a>
                @else
                    <button disabled class="btn bg-gray-100 text-gray-400 w-full cursor-not-allowed text-sm py-2.5 border-none">পর্যাপ্ত ব্যালেন্স নেই</button>
                    <p class="text-xs text-gray-400 text-center mt-2">উত্তোলনের জন্য ন্যুনতম ব্যালেন্স প্রয়োজন।</p>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">মাসিক আয় (গত ১২ মাস)</h3>
                
                @if($monthlyEarnings->count() > 0)
                    <div class="space-y-3">
                        @foreach($monthlyEarnings as $month)
                            @php
                                $dateObj = \Carbon\Carbon::create($month->year, $month->month, 1);
                                $monthName = $dateObj->locale('bn')->translatedFormat('F Y');
                            @endphp
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">{{ $monthName }}</span>
                                <span class="font-bold text-gray-900">৳{{ number_format($month->total) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 text-center py-4">কোনো ডাটা পাওয়া যায়নি।</p>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
