@extends('layouts.admin')

@section('title', 'টাকা উত্তোলন (Withdrawals)')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">টাকা উত্তোলন রিকোয়েস্ট</h1>
            <p class="text-sm text-gray-500">প্রোভাইডারদের উইথড্র রিকোয়েস্ট এবং তার স্ট্যাটাস।</p>
        </div>
        
        <div class="bg-yellow-50 border border-yellow-200 px-4 py-2 rounded-xl">
            <span class="text-sm text-yellow-700 font-semibold block">মোট পেন্ডিং উইথড্র</span>
            <span class="text-xl font-bold text-yellow-900">৳{{ number_format($pendingTotal) }}</span>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
        <form action="{{ route('admin.withdrawals.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="w-full sm:w-48">
                <label class="block text-xs font-semibold text-gray-700 mb-1">স্ট্যাটাস</label>
                <select name="status" class="input" onchange="this.form.submit()">
                    <option value="">সকল স্ট্যাটাস</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending (বিচারাধীন)</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved (অপ্রুভড)</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Rejected (বাতিল)</option>
                </select>
            </div>
            
            @if(request('status'))
                <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-outline border-gray-200 hover:bg-gray-50 px-4" title="রিসেট">
                    <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </form>
    </div>

    {{-- Withdrawals List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">উইথড্র আইডি</th>
                        <th class="px-6 py-4">প্রোভাইডার</th>
                        <th class="px-6 py-4 text-center">মেথড</th>
                        <th class="px-6 py-4 text-right">অ্যামাউন্ট</th>
                        <th class="px-6 py-4 text-center">স্ট্যাটাস</th>
                        <th class="px-6 py-4 text-right">তারিখ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($withdrawals as $wd)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.withdrawals.show', $wd->id) }}" class="font-bold text-gray-900 hover:text-blue-600">
                                    #WD-{{ str_pad($wd->id, 5, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.users.show', $wd->provider->id) }}" class="font-medium text-gray-900 hover:text-blue-600 block">
                                    {{ $wd->provider->name }}
                                </a>
                                <div class="text-xs text-gray-500">{{ $wd->provider->phone }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-block bg-gray-100 px-2 py-1 rounded text-xs font-semibold text-gray-700 uppercase">
                                    {{ $wd->method }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="font-bold text-gray-900">৳{{ number_format($wd->amount) }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($wd->status === 'pending')
                                    <span class="inline-flex px-2.5 py-1 rounded-md text-[11px] font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">Pending</span>
                                @elseif($wd->status === 'approved')
                                    <span class="inline-flex px-2.5 py-1 rounded-md text-[11px] font-semibold bg-green-50 text-green-700 border border-green-200">Approved</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-md text-[11px] font-semibold bg-red-50 text-red-700 border border-red-200">Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="text-gray-900">{{ $wd->created_at->format('d M, y') }}</div>
                                <a href="{{ route('admin.withdrawals.show', $wd->id) }}" class="text-xs font-semibold text-blue-600 hover:underline mt-1 inline-block">বিস্তারিত &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <p class="text-gray-500">কোনো উইথড্র রিকোয়েস্ট নেই।</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($withdrawals->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
