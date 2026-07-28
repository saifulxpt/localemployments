@extends('layouts.admin')

@section('title', 'বুকিং ম্যানেজমেন্ট')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">বুকিং ম্যানেজমেন্ট</h1>
            <p class="text-sm text-gray-500">প্লাটফর্মের সকল বুকিং ও কাজের লিস্ট।</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="w-full sm:w-48">
                <label class="block text-xs font-semibold text-gray-700 mb-1">স্ট্যাটাস</label>
                <select name="status" class="input" onchange="this.form.submit()">
                    <option value="">সকল স্ট্যাটাস</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmed</option>
                    <option value="in_progress" @selected(request('status') === 'in_progress')>In Progress</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                </select>
            </div>
            
            @if(request('status') || request('seeker_id') || request('provider_id'))
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline border-gray-200 hover:bg-gray-50 px-4" title="রিসেট">
                    <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </form>
    </div>

    {{-- Bookings List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">বুকিং আইডি</th>
                        <th class="px-6 py-4">সিকার (ক্লায়েন্ট)</th>
                        <th class="px-6 py-4">প্রোভাইডার</th>
                        <th class="px-6 py-4">অ্যামাউন্ট</th>
                        <th class="px-6 py-4 text-center">স্ট্যাটাস</th>
                        <th class="px-6 py-4">তারিখ</th>
                        <th class="px-6 py-4 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="font-bold text-gray-900 hover:text-blue-600">
                                    #{{ $booking->id }}
                                </a>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $booking->jobRequest ? 'জব রিকোয়েস্ট' : ($booking->directService ? 'ডাইরেক্ট সার্ভিস' : 'অজানা') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.users.show', $booking->seeker->id) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                    {{ $booking->seeker->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.users.show', $booking->provider->id) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                    {{ $booking->provider->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">৳{{ number_format($booking->service_amount) }}</div>
                                @if($booking->platform_fee > 0)
                                    <div class="text-xs text-primary-600">ফি: ৳{{ number_format($booking->platform_fee) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                        'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'in_progress' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'completed' => 'bg-green-50 text-green-700 border-green-200',
                                        'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                    ];
                                    $color = $statusColors[$booking->status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                @endphp
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $color }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-900">{{ $booking->created_at->format('d M, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-outline border-gray-200 btn-sm hover:border-blue-300 hover:text-blue-600">বিস্তারিত</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                </div>
                                <p class="text-gray-500">কোনো বুকিং পাওয়া যায়নি।</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($bookings->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
