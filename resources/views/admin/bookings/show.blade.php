@extends('layouts.admin')

@section('title', 'বুকিং বিস্তারিত #' . $booking->id)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.bookings.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">বুকিং #{{ $booking->id }}</h1>
            
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'confirmed' => 'bg-blue-100 text-blue-800',
                    'in_progress' => 'bg-indigo-100 text-indigo-800',
                    'completed' => 'bg-green-100 text-green-800',
                    'cancelled' => 'bg-red-100 text-red-800',
                ];
                $color = $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800';
            @endphp
            <span class="text-sm font-semibold px-3 py-1 rounded-full {{ $color }}">
                {{ ucfirst($booking->status) }}
            </span>
        </div>
        
        <div class="text-sm text-gray-500">
            তৈরি হয়েছে: {{ $booking->created_at->format('d M, Y h:i A') }}
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Service Details --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
                <h2 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">সার্ভিস বিস্তারিত</h2>
                
                @if($booking->jobRequest)
                    <div class="mb-4">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-1">কাজের রিকোয়েস্ট</span>
                        <a href="{{ route('admin.job-requests.show', $booking->jobRequest->id) }}" class="text-lg font-bold text-blue-600 hover:underline">{{ $booking->jobRequest->title }}</a>
                    </div>
                @elseif($booking->directService)
                    <div class="mb-4">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-1">ডাইরেক্ট সার্ভিস</span>
                        <span class="text-lg font-bold text-gray-900">{{ $booking->directService->title }}</span>
                    </div>
                @endif
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <div class="text-xs text-gray-500 mb-1">সার্ভিস অ্যামাউন্ট</div>
                        <div class="text-lg font-bold text-gray-900">৳{{ number_format($booking->service_amount) }}</div>
                    </div>
                    <div class="bg-primary-50 p-4 rounded-xl border border-primary-100">
                        <div class="text-xs text-primary-700 mb-1">প্লাটফর্ম ফি (Platform Fee)</div>
                        <div class="text-lg font-bold text-primary-900">৳{{ number_format($booking->platform_fee) }}</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-xl border border-green-100">
                        <div class="text-xs text-green-700 mb-1">প্রোভাইডার পাবে</div>
                        <div class="text-lg font-bold text-green-900">৳{{ number_format($booking->provider_earning) }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <div class="text-xs text-gray-500 mb-1">পেমেন্ট স্ট্যাটাস</div>
                        <div class="text-sm font-bold mt-1 {{ $booking->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ ucfirst($booking->payment_status) }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Message History (Preview) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">কথোপকথন (Messages: {{ $booking->messages->count() }})</h2>
                
                @if($booking->messages->count() > 0)
                    <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                        @foreach($booking->messages as $msg)
                            <div class="flex gap-3 {{ $msg->sender_id === $booking->seeker_id ? 'flex-row' : 'flex-row-reverse' }}">
                                <img src="{{ $msg->sender->avatar_url }}" class="w-8 h-8 rounded-full object-cover shrink-0">
                                <div class="flex flex-col {{ $msg->sender_id === $booking->seeker_id ? 'items-start' : 'items-end' }}">
                                    <div class="px-4 py-2 rounded-2xl text-sm max-w-md {{ $msg->sender_id === $booking->seeker_id ? 'bg-gray-100 text-gray-800 rounded-tl-none' : 'bg-blue-100 text-blue-900 rounded-tr-none' }}">
                                        {{ $msg->message }}
                                    </div>
                                    <div class="text-[10px] text-gray-400 mt-1">{{ $msg->created_at->format('d M h:i A') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500 py-4 text-sm">কোনো মেসেজ আদান-প্রদান হয়নি।</div>
                @endif
            </div>

            {{-- Review & Dispute --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h2 class="font-bold text-gray-900 mb-3 text-sm uppercase">রিভিউ</h2>
                    @if($booking->review)
                        <div class="flex items-center gap-1 text-yellow-500 mb-2 text-lg">
                            @for($i = 1; $i <= 5; $i++)
                                {!! $i <= $booking->review->rating ? '★' : '<span class="text-gray-300">★</span>' !!}
                            @endfor
                        </div>
                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100">{{ $booking->review->comment ?? 'কোনো মন্তব্য নেই' }}</p>
                    @else
                        <p class="text-sm text-gray-500">কোনো রিভিউ দেওয়া হয়নি।</p>
                    @endif
                </div>

                <div class="bg-white rounded-2xl shadow-sm border {{ $booking->dispute ? 'border-red-200' : 'border-gray-200' }} p-6">
                    <h2 class="font-bold text-gray-900 mb-3 text-sm uppercase">ডিসপুট (Dispute)</h2>
                    @if($booking->dispute)
                        <div class="bg-red-50 border border-red-100 p-3 rounded-lg">
                            <div class="text-red-700 font-bold mb-1 text-sm">স্ট্যাটাস: {{ ucfirst($booking->dispute->status) }}</div>
                            <p class="text-xs text-red-600 mb-2">{{ Str::limit($booking->dispute->reason, 50) }}</p>
                            <a href="{{ route('admin.disputes.show', $booking->dispute->id) }}" class="text-xs font-bold text-red-700 hover:underline">বিস্তারিত দেখুন &rarr;</a>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">কোনো ডিসপুট নেই।</p>
                    @endif
                </div>
            </div>

        </div>

        {{-- Sidebar Users Info --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- Seeker --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">সিকার (ক্লায়েন্ট)</h3>
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ $booking->seeker->avatar_url }}" alt="" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <a href="{{ route('admin.users.show', $booking->seeker->id) }}" class="font-bold text-gray-900 hover:text-blue-600">{{ $booking->seeker->name }}</a>
                        <div class="text-xs text-gray-500">{{ $booking->seeker->phone }}</div>
                    </div>
                </div>
            </div>

            {{-- Provider --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">প্রোভাইডার</h3>
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ $booking->provider->avatar_url }}" alt="" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <a href="{{ route('admin.users.show', $booking->provider->id) }}" class="font-bold text-gray-900 hover:text-blue-600">{{ $booking->provider->name }}</a>
                        <div class="text-xs text-gray-500">{{ $booking->provider->phone }}</div>
                    </div>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">টাইমলাইন</h3>
                
                <div class="space-y-4 relative before:absolute before:inset-0 before:ml-2.5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">
                    
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full border-2 border-white bg-blue-500 text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10"></div>
                        <div class="w-[calc(100%-2.5rem)] md:w-[calc(50%-1.5rem)] p-3 rounded border border-gray-200 bg-gray-50 text-sm shadow">
                            <div class="font-bold text-gray-900 mb-1">বুকিং তৈরি</div>
                            <div class="text-xs text-gray-500">{{ $booking->created_at->format('d M, Y h:i A') }}</div>
                        </div>
                    </div>

                    @if($booking->status !== 'pending' && $booking->status !== 'cancelled')
                        <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                            <div class="flex items-center justify-center w-6 h-6 rounded-full border-2 border-white bg-blue-500 text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10"></div>
                            <div class="w-[calc(100%-2.5rem)] md:w-[calc(50%-1.5rem)] p-3 rounded border border-gray-200 bg-gray-50 text-sm shadow">
                                <div class="font-bold text-gray-900 mb-1">প্রোভাইডার কনফার্ম করেছে</div>
                            </div>
                        </div>
                    @endif

                    @if($booking->status === 'completed')
                        <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                            <div class="flex items-center justify-center w-6 h-6 rounded-full border-2 border-white bg-green-500 text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div class="w-[calc(100%-2.5rem)] md:w-[calc(50%-1.5rem)] p-3 rounded border border-gray-200 bg-green-50 text-sm shadow">
                                <div class="font-bold text-green-700 mb-1">বুকিং সম্পন্ন</div>
                            </div>
                        </div>
                    @endif

                    @if($booking->status === 'cancelled')
                        <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                            <div class="flex items-center justify-center w-6 h-6 rounded-full border-2 border-white bg-red-500 text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                            <div class="w-[calc(100%-2.5rem)] md:w-[calc(50%-1.5rem)] p-3 rounded border border-gray-200 bg-red-50 text-sm shadow">
                                <div class="font-bold text-red-700 mb-1">বুকিং বাতিল</div>
                            </div>
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </div>

</div>
@endsection
