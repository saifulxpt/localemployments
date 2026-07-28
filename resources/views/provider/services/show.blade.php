@extends('layouts.provider')

@section('title', 'সেবার বিস্তারিত')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('provider.services.index') }}" class="text-gray-500 hover:text-primary-600 transition-colors flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            ফিরে যান
        </a>
        <div class="flex gap-2">
            <a href="{{ route('provider.services.edit', $service->id) }}" class="btn btn-outline btn-sm">এডিট করুন</a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Photos Gallery --}}
        @if($service->photos && count($service->photos) > 0)
            <div class="w-full h-64 md:h-80 bg-gray-100 relative">
                <img src="{{ Storage::url($service->photos[0]) }}" alt="{{ $service->title }}" class="w-full h-full object-cover">
                @if(count($service->photos) > 1)
                    <div class="absolute bottom-4 right-4 bg-black/60 text-white px-3 py-1 rounded-lg text-sm">
                        +{{ count($service->photos) - 1 }} আরও ছবি
                    </div>
                @endif
            </div>
        @endif

        <div class="p-6 md:p-8">
            <div class="flex items-center gap-3 mb-4">
                <span class="text-xs font-bold text-primary-700 bg-primary-50 px-2.5 py-1 rounded-md border border-primary-100">
                    {{ $service->subcategory->category->name }} > {{ $service->subcategory->name }}
                </span>
                <span class="text-xs font-semibold {{ $service->is_active ? 'text-green-600 bg-green-50' : 'text-gray-500 bg-gray-100' }} px-2.5 py-1 rounded-md border {{ $service->is_active ? 'border-green-200' : 'border-gray-200' }}">
                    {{ $service->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                </span>
            </div>

            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">{{ $service->title }}</h1>
            
            <div class="text-2xl font-black text-primary-700 mb-6">
                ৳{{ number_format($service->price) }} 
                <span class="text-sm font-normal text-gray-500">
                    {{ $service->price_type === 'hourly' ? '/ঘন্টা' : ($service->price_type === 'starting_from' ? 'থেকে শুরু' : '(ফিক্সড)') }}
                </span>
            </div>

            <div class="prose max-w-none text-gray-600 mb-8">
                {!! nl2br(e($service->description)) !!}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-gray-50 rounded-2xl border border-gray-100">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-1 text-sm">আনুমানিক সময়</h4>
                    <p class="text-gray-600">{{ $service->estimated_duration ?: 'উল্লেখ নেই' }}</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-1 text-sm">যোগ করার তারিখ</h4>
                    <p class="text-gray-600">{{ $service->created_at->format('d M, Y h:i A') }}</p>
                </div>
                <div class="md:col-span-2">
                    <h4 class="font-semibold text-gray-900 mb-2 text-sm">সার্ভিস এরিয়া</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach(\App\Models\District::whereIn('id', $service->service_areas)->get() as $d)
                            <span class="px-2.5 py-1 bg-white border border-gray-200 text-gray-600 text-xs rounded-md">{{ $d->bn_name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bookings for this service --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
        <h3 class="font-bold text-gray-900 text-lg mb-4">এই সেবার বুকিংস</h3>
        
        @if($service->bookings->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">কাস্টমার</th>
                            <th class="px-4 py-3">তারিখ</th>
                            <th class="px-4 py-3">স্ট্যাটাস</th>
                            <th class="px-4 py-3">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($service->bookings as $booking)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 font-medium text-gray-900">#{{ $booking->id }}</td>
                                <td class="px-4 py-3">{{ $booking->seeker->name }}</td>
                                <td class="px-4 py-3">{{ $booking->created_at->format('d M, Y') }}</td>
                                <td class="px-4 py-3">
                                    <x-status-badge :status="$booking->status" />
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('provider.bookings.show', $booking->id) }}" class="text-primary-600 font-medium hover:underline">দেখুন</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-4 bg-gray-50 rounded-xl">এই সেবার জন্য এখনও কোনো বুকিং আসেনি।</p>
        @endif
    </div>

</div>
@endsection
