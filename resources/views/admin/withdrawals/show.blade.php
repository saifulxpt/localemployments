@extends('layouts.admin')

@section('title', 'উইথড্র রিকোয়েস্ট #WD-' . str_pad($withdrawal->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('admin.withdrawals.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            উইথড্র লিস্ট
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">উইথড্র #WD-{{ str_pad($withdrawal->id, 5, '0', STR_PAD_LEFT) }}</h1>
                        <p class="text-sm text-gray-500 mt-1">রিকোয়েস্ট করা হয়েছে: {{ $withdrawal->created_at->format('d M, Y h:i A') }}</p>
                    </div>
                    @if($withdrawal->status === 'pending')
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">Pending</span>
                    @elseif($withdrawal->status === 'approved')
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">Approved</span>
                    @else
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">Rejected</span>
                    @endif
                </div>
                
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 mb-6">
                    <div class="text-center mb-6">
                        <span class="text-sm text-gray-500 uppercase font-bold tracking-wider block mb-1">অ্যামাউন্ট</span>
                        <span class="text-4xl font-black text-gray-900">৳{{ number_format($withdrawal->amount) }}</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm bg-white p-4 rounded-lg border border-gray-200">
                        <div>
                            <span class="block text-gray-500 mb-1">পেমেন্ট মেথড</span>
                            <span class="font-bold text-gray-900 uppercase">{{ $withdrawal->method }}</span>
                        </div>
                        <div>
                            <span class="block text-gray-500 mb-1">অ্যাকাউন্ট নম্বর / ডিটেইলস</span>
                            <span class="font-bold text-gray-900">{{ $withdrawal->account_details }}</span>
                        </div>
                    </div>
                </div>

                @if($withdrawal->status === 'pending')
                    <div class="border-t border-gray-100 pt-6 mt-6">
                        <h3 class="font-bold text-gray-900 mb-4">পেমেন্ট সম্পন্ন করুন</h3>
                        <p class="text-sm text-gray-600 mb-4 bg-yellow-50 p-3 rounded-lg border border-yellow-100">দয়া করে প্রোভাইডারের উল্লেখিত অ্যাকাউন্টে ({{ $withdrawal->account_details }}) টাকা সেন্ড মানি বা ক্যাশ ইন করার পর নিচে 'অপ্রুভড' বাটনে ক্লিক করুন।</p>
                        
                        <div class="flex gap-4">
                            <form action="{{ route('admin.withdrawals.approve', $withdrawal->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="btn btn-primary bg-green-600 hover:bg-green-700 border-green-600 w-full" onclick="return confirm('আপনি কি নিশ্চিত যে প্রোভাইডারকে টাকা পাঠানো হয়েছে?')">
                                    Mark as Approved (টাকা পাঠানো হয়েছে)
                                </button>
                            </form>
                            
                            <div x-data="{ showReject: false }" class="flex-1">
                                <button type="button" @click="showReject = !showReject" class="btn btn-outline text-red-600 border-red-200 hover:bg-red-50 w-full">
                                    Reject (বাতিল)
                                </button>
                                
                                <div x-show="showReject" x-cloak class="mt-4 p-4 border border-red-200 bg-red-50 rounded-xl">
                                    <form action="{{ route('admin.withdrawals.reject', $withdrawal->id) }}" method="POST">
                                        @csrf
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">বাতিলের কারণ</label>
                                        <textarea name="admin_note" rows="2" class="input text-sm mb-3" placeholder="কেন বাতিল করা হলো..." required></textarea>
                                        <button type="submit" class="btn bg-red-600 text-white hover:bg-red-700 btn-sm w-full" onclick="return confirm('আপনি কি নিশ্চিত?')">বাতিল নিশ্চিত করুন</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                        <h3 class="font-bold text-gray-900 text-sm uppercase mb-3">প্রসেসিং তথ্য</h3>
                        <div class="text-sm space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-500">প্রসেস করেছেন:</span>
                                <span class="font-medium text-gray-900">{{ $withdrawal->processedBy->name ?? 'Admin' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">সময়:</span>
                                <span class="font-medium text-gray-900">{{ $withdrawal->processed_at ? \Carbon\Carbon::parse($withdrawal->processed_at)->format('d M, Y h:i A') : 'N/A' }}</span>
                            </div>
                            @if($withdrawal->admin_note)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <span class="block text-gray-500 mb-1 text-xs">নোট:</span>
                                    <p class="font-medium text-gray-900 bg-white p-2 border border-gray-100 rounded">{{ $withdrawal->admin_note }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

        </div>

        {{-- Sidebar Provider Info --}}
        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">প্রোভাইডার তথ্য</h3>
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ $withdrawal->provider->avatar_url }}" alt="" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <a href="{{ route('admin.users.show', $withdrawal->provider->id) }}" class="font-bold text-gray-900 hover:text-blue-600">{{ $withdrawal->provider->name }}</a>
                        <div class="text-xs text-gray-500">{{ $withdrawal->provider->phone }}</div>
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl mt-4">
                    <div class="text-xs text-blue-800 mb-1">বর্তমান উপলব্ধ ব্যালেন্স</div>
                    <div class="text-2xl font-black text-blue-900">৳{{ number_format($withdrawal->provider->balance) }}</div>
                </div>
                
                <a href="{{ route('admin.bookings.index', ['provider_id' => $withdrawal->provider->id]) }}" class="btn btn-outline border-gray-200 w-full mt-4 btn-sm">প্রোভাইডারের সকল বুকিং</a>
            </div>

        </div>
    </div>

</div>
@endsection
