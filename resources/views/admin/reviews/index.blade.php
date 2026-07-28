@extends('layouts.admin')

@section('title', 'রিভিউ ম্যানেজমেন্ট')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">রিভিউ ও রেটিং</h1>
            <p class="text-sm text-gray-500">ইউজারদের দেওয়া সকল রিভিউ ও রেটিং।</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">বুকিং</th>
                        <th class="px-6 py-4">রিভিউয়ার (দিয়েছেন)</th>
                        <th class="px-6 py-4">রিভিউয়ী (পেয়েছেন)</th>
                        <th class="px-6 py-4 text-center">রেটিং</th>
                        <th class="px-6 py-4 w-1/3">মন্তব্য</th>
                        <th class="px-6 py-4 text-center">স্ট্যাটাস</th>
                        <th class="px-6 py-4 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.bookings.show', $review->booking_id) }}" class="font-bold text-blue-600 hover:underline">
                                    #{{ $review->booking_id }}
                                </a>
                                <div class="text-[10px] text-gray-400 mt-1">{{ $review->created_at->format('d M, y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.users.show', $review->reviewer_id) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                    {{ $review->reviewer->name ?? 'Unknown' }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.users.show', $review->reviewee_id) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                    {{ $review->reviewee->name ?? 'Unknown' }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center text-yellow-500 text-lg">
                                @for($i = 1; $i <= 5; $i++)
                                    {!! $i <= $review->rating ? '★' : '<span class="text-gray-300">★</span>' !!}
                                @endfor
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-600 {{ !$review->is_visible ? 'line-through text-gray-400' : '' }}">
                                    {{ Str::limit($review->comment, 80) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($review->is_visible)
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold bg-green-50 text-green-700 border border-green-200">Visible</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold bg-red-50 text-red-700 border border-red-200">Hidden</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($review->is_visible)
                                    <form action="{{ route('admin.reviews.hide', $review->id) }}" method="POST" onsubmit="return confirm('রিভিউটি লুকিয়ে রাখতে চান?')">
                                        @csrf
                                        <button class="btn btn-outline border-red-200 text-red-600 hover:bg-red-50 btn-sm">Hide (লুকান)</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.reviews.show', $review->id) }}" method="POST" onsubmit="return confirm('রিভিউটি দৃশ্যমান করতে চান?')">
                                        @csrf
                                        <button class="btn btn-outline border-green-200 text-green-600 hover:bg-green-50 btn-sm">Show (দেখান)</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                কোনো রিভিউ পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($reviews->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
