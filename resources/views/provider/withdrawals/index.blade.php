@extends('layouts.provider')

@section('title', 'টাকা উত্তোলনের হিস্ট্রি')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">টাকা উত্তোলনের হিস্ট্রি</h1>
            <p class="text-sm text-gray-500">আপনার সমস্ত উইথড্র রিকোয়েস্টের তালিকা।</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right">
                <div class="text-xs text-gray-500">উত্তোলনযোগ্য ব্যালেন্স</div>
                <div class="text-lg font-bold text-green-600">৳{{ number_format($available) }}</div>
            </div>
            @if($available >= $minWithdraw)
                <a href="{{ route('provider.withdrawals.create') }}" class="btn btn-primary whitespace-nowrap">
                    উত্তোলন করুন (Withdraw)
                </a>
            @else
                <button disabled class="btn bg-gray-100 text-gray-400 cursor-not-allowed border-none" title="ন্যুনতম ৳{{ $minWithdraw }} প্রয়োজন">
                    উত্তোলন করুন
                </button>
            @endif
        </div>
    </div>

    @if($withdrawals->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">রিকোয়েস্ট আইডি</th>
                            <th class="px-6 py-4">পেমেন্ট মেথড</th>
                            <th class="px-6 py-4">পরিমাণ</th>
                            <th class="px-6 py-4">তারিখ</th>
                            <th class="px-6 py-4 text-center">স্ট্যাটাস</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($withdrawals as $withdrawal)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">#{{ $withdrawal->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($withdrawal->method === 'bkash')
                                            <span class="w-8 h-8 rounded bg-pink-100 text-pink-600 flex items-center justify-center font-bold text-xs">bKash</span>
                                        @elseif($withdrawal->method === 'nagad')
                                            <span class="w-8 h-8 rounded bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-xs">Nagad</span>
                                        @else
                                            <span class="w-8 h-8 rounded bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">Bank</span>
                                        @endif
                                        <div>
                                            <div class="font-semibold text-gray-900 capitalize">{{ $withdrawal->method }}</div>
                                            <div class="text-xs text-gray-500">{{ $withdrawal->account_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-gray-900">৳{{ number_format($withdrawal->amount) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $withdrawal->created_at->format('d M, Y') }}
                                    <div class="text-xs text-gray-400">{{ $withdrawal->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($withdrawal->status === 'pending')
                                        <span class="bg-yellow-50 text-yellow-700 border border-yellow-200 px-2.5 py-1 rounded-md text-xs font-semibold whitespace-nowrap">পেন্ডিং</span>
                                    @elseif($withdrawal->status === 'processing')
                                        <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-1 rounded-md text-xs font-semibold whitespace-nowrap">প্রসেসিং</span>
                                    @elseif($withdrawal->status === 'approved')
                                        <span class="bg-green-50 text-green-700 border border-green-200 px-2.5 py-1 rounded-md text-xs font-semibold whitespace-nowrap">সফল</span>
                                    @elseif($withdrawal->status === 'rejected')
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="bg-red-50 text-red-700 border border-red-200 px-2.5 py-1 rounded-md text-xs font-semibold whitespace-nowrap">বাতিলকৃত</span>
                                            @if($withdrawal->admin_note)
                                                <span class="text-[10px] text-red-500" title="{{ $withdrawal->admin_note }}">নোট দেখুন</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-6 border-t border-gray-100">
                {{ $withdrawals->links() }}
            </div>
        </div>
    @else
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">কোনো উত্তোলনের রেকর্ড নেই</h3>
            <p class="text-gray-500">আপনি এখনও কোনো টাকা উত্তোলনের রিকোয়েস্ট করেননি।</p>
        </div>
    @endif

</div>
@endsection
