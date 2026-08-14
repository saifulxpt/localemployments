@extends('layouts.seeker')

@section('title', 'আমার বুকিং সমূহ')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">আমার বুকিং সমূহ</h1>
        <p class="text-sm text-gray-500">আপনার সমস্ত সার্ভিসের বুকিং হিস্ট্রি দেখুন।</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="flex overflow-x-auto border-b border-gray-100 p-2 gap-2">
            <a href="{{ route('seeker.bookings.index') }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ !request('status') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50' }}">সব বুকিং</a>
            <a href="{{ route('seeker.bookings.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request('status') === 'pending' ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50' }}">অপেক্ষমাণ</a>
            <a href="{{ route('seeker.bookings.index', ['status' => 'in_progress']) }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request('status') === 'in_progress' ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50' }}">চলমান</a>
            <a href="{{ route('seeker.bookings.index', ['status' => 'completed']) }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request('status') === 'completed' ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50' }}">সম্পন্ন</a>
        </div>

        @if($bookings->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">সার্ভিসের নাম</th>
                            <th class="px-6 py-4">প্রোভাইডার</th>
                            <th class="px-6 py-4">মূল্য</th>
                            <th class="px-6 py-4">বুকিং তারিখ</th>
                            <th class="px-6 py-4 text-center">স্ট্যাটাস</th>
                            <th class="px-6 py-4 text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 line-clamp-1 max-w-[200px]">
                                        {{ $booking->jobRequest ? $booking->jobRequest->title : ($booking->directService ? $booking->directService->title : 'অজানা কাজ') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">বুকিং #{{ $booking->id }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('providers.show', $booking->provider->id) }}">
                                            <img src="{{ $booking->provider->avatar_url }}" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                        </a>
                                        <div>
                                            <a href="{{ route('providers.show', $booking->provider->id) }}" class="font-medium text-gray-900 hover:text-primary-600">{{ $booking->provider->name }}</a>
                                            <div class="text-xs text-gray-500 flex items-center gap-1">
                                                <span class="text-yellow-500">★</span> {{ number_format($booking->provider->providerProfile?->rating_avg ?? 0, 1) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-gray-900">৳{{ number_format($booking->service_amount) }}</span>
                                    <div class="text-xs text-gray-500">{{ $booking->payment_type === 'cash' ? 'ক্যাশে' : 'অনলাইনে' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $booking->created_at->format('d M, Y') }}
                                    <div class="text-xs text-gray-400">{{ $booking->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <x-status-badge :status="$booking->status" />
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('seeker.bookings.show', $booking->id) }}" class="btn btn-outline btn-sm">বিস্তারিত</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-6 border-t border-gray-100">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">কোনো বুকিং নেই</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">আপনার কোনো বুকিং পাওয়া যায়নি। আপনার প্রয়োজনীয় সার্ভিস খুঁজে বের করে বুকিং করুন।</p>
                <a href="{{ route('search') }}" class="btn btn-primary">সার্ভিস খুঁজুন</a>
            </div>
        @endif
    </div>

</div>
@endsection
