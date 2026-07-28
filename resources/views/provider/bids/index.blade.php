@extends('layouts.provider')

@section('title', 'আমার বিডসমূহ')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">আমার বিডসমূহ</h1>
        <p class="text-sm text-gray-500">আপনার সাবমিট করা সকল বিডের বর্তমান অবস্থা দেখুন।</p>
    </div>

    @if($bids->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">কাজের বিবরণ</th>
                            <th class="px-6 py-4">আপনার বিড</th>
                            <th class="px-6 py-4">তারিখ</th>
                            <th class="px-6 py-4 text-center">স্ট্যাটাস</th>
                            <th class="px-6 py-4 text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($bids as $bid)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1 max-w-xs">
                                        <a href="{{ route('provider.jobs.show', $bid->jobRequest->id) }}" class="font-bold text-gray-900 hover:text-primary-600 line-clamp-1">
                                            {{ $bid->jobRequest->title }}
                                        </a>
                                        <div class="text-xs text-gray-500">
                                            {{ $bid->jobRequest->subcategory->name }} | {{ $bid->jobRequest->district->bn_name }}
                                        </div>
                                        <div class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            {{ $bid->jobRequest->seeker->name }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-extrabold text-primary-700 text-lg mb-1">৳{{ number_format($bid->bid_amount) }}</div>
                                    @if($bid->estimated_hours)
                                        <div class="text-xs text-gray-500">{{ $bid->estimated_hours }} ঘন্টা সময় লাগবে</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ $bid->created_at->format('d M, Y') }}
                                    <div class="text-xs text-gray-400">{{ $bid->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($bid->status === 'pending')
                                        <span class="bg-yellow-50 text-yellow-700 border border-yellow-200 px-2.5 py-1 rounded-md text-xs font-semibold whitespace-nowrap">বিবেচনাধীন</span>
                                    @elseif($bid->status === 'accepted')
                                        <span class="bg-green-50 text-green-700 border border-green-200 px-2.5 py-1 rounded-md text-xs font-semibold whitespace-nowrap">গৃহীত</span>
                                    @elseif($bid->status === 'rejected')
                                        <span class="bg-red-50 text-red-700 border border-red-200 px-2.5 py-1 rounded-md text-xs font-semibold whitespace-nowrap">বাতিলকৃত</span>
                                    @elseif($bid->status === 'withdrawn')
                                        <span class="bg-gray-100 text-gray-700 border border-gray-200 px-2.5 py-1 rounded-md text-xs font-semibold whitespace-nowrap">আপনি তুলে নিয়েছেন</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($bid->status === 'pending')
                                        <div x-data="{ showEdit: false }" class="inline-block relative">
                                            
                                            <div class="flex items-center justify-end gap-2">
                                                <button @click="showEdit = true" class="text-sm font-semibold text-primary-600 hover:underline">এডিট</button>
                                                
                                                <form action="{{ route('provider.bids.destroy', $bid->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই বিডটি তুলে নিতে চান?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm font-semibold text-red-500 hover:underline">বাতিল</button>
                                                </form>
                                            </div>

                                            {{-- Edit Modal --}}
                                            <div x-show="showEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 text-left">
                                                <div @click.outside="showEdit = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
                                                    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50">
                                                        <h3 class="font-bold text-gray-900">বিড আপডেট করুন</h3>
                                                        <button type="button" @click="showEdit = false" class="text-gray-400 hover:text-gray-600">
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        </button>
                                                    </div>
                                                    
                                                    <form action="{{ route('provider.bids.update', $bid->id) }}" method="POST" class="p-6">
                                                        @csrf
                                                        @method('PUT')
                                                        
                                                        <div class="form-group">
                                                            <label class="form-label">অফার করা মূল্য (৳)</label>
                                                            <input type="number" name="bid_amount" class="input font-bold text-primary-700" value="{{ $bid->bid_amount }}" min="50" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">আনুমানিক ঘন্টা (ঐচ্ছিক)</label>
                                                            <input type="number" name="estimated_hours" class="input" value="{{ $bid->estimated_hours }}" min="1">
                                                        </div>
                                                        <div class="form-group mb-6">
                                                            <label class="form-label">মেসেজ</label>
                                                            <textarea name="message" rows="3" class="input" required minlength="20">{{ $bid->message }}</textarea>
                                                        </div>
                                                        
                                                        <div class="flex justify-end gap-3">
                                                            <button type="button" @click="showEdit = false" class="btn btn-outline btn-sm">বাতিল</button>
                                                            <button type="submit" class="btn btn-primary btn-sm">আপডেট করুন</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($bid->status === 'accepted')
                                        <a href="{{ route('provider.bookings.index') }}" class="text-sm font-semibold text-primary-600 hover:underline">বুকিং দেখুন</a>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-6">
            {{ $bids->links() }}
        </div>
    @else
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">আপনি এখনও কোনো বিড করেননি</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">কাস্টমারদের পোস্ট করা কাজে বিড করে সহজেই কাজ পেয়ে যান। নতুন কাজগুলো ব্রাউজ করুন।</p>
            <a href="{{ route('provider.jobs.index') }}" class="btn btn-primary">কাজ খুঁজুন</a>
        </div>
    @endif

</div>
@endsection
