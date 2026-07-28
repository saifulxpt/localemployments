@extends('layouts.provider')

@section('title', 'বুকিং সমূহ')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">আমার বুকিং সমূহ</h1>
        <p class="text-sm text-gray-500">আপনার সমস্ত চলমান, সম্পন্ন এবং বাতিল বুকিং এর তালিকা।</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Filter Tabs (Visual only for now, could add query params) --}}
        <div class="flex overflow-x-auto border-b border-gray-100 p-2 gap-2">
            <a href="{{ route('provider.bookings.index') }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ !request('status') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50' }}">সব বুকিং</a>
            <a href="{{ route('provider.bookings.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request('status') === 'pending' ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50' }}">অপেক্ষমাণ</a>
            <a href="{{ route('provider.bookings.index', ['status' => 'in_progress']) }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request('status') === 'in_progress' ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50' }}">চলমান</a>
            <a href="{{ route('provider.bookings.index', ['status' => 'completed']) }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request('status') === 'completed' ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50' }}">সম্পন্ন</a>
        </div>

        @if($bookings->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">কাজের বিবরণ</th>
                            <th class="px-6 py-4">কাস্টমার</th>
                            <th class="px-6 py-4">বুকিং তারিখ</th>
                            <th class="px-6 py-4 text-center">স্ট্যাটাস</th>
                            <th class="px-6 py-4 text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">#{{ $booking->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 max-w-[200px] truncate">
                                        {{ $booking->jobRequest ? $booking->jobRequest->title : ($booking->directService ? $booking->directService->title : 'অজানা কাজ') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                        <span class="font-bold text-primary-700">৳{{ number_format($booking->service_amount) }}</span>
                                        <span class="px-1 text-gray-300">•</span>
                                        <span>{{ $booking->payment_type === 'cash' ? 'ক্যাশে' : 'অনলাইনে' }} পেমেন্ট</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $booking->seeker->avatar_url }}" alt="" class="w-6 h-6 rounded-full object-cover">
                                        <span class="font-medium text-gray-900">{{ $booking->seeker->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $booking->created_at->format('d M, Y') }}
                                    <div class="text-xs text-gray-400">{{ $booking->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <x-status-badge :status="$booking->status" />
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('provider.bookings.show', $booking->id) }}" class="btn btn-outline btn-sm">বিস্তারিত</a>
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
                    <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">কোনো বুকিং নেই</h3>
                <p class="text-gray-500">আপনার কোনো বুকিং পাওয়া যায়নি।</p>
            </div>
        @endif
    </div>

</div>
@endsection
