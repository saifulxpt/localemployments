@extends('layouts.admin')

@section('title', 'পেমেন্ট হিস্ট্রি')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">পেমেন্ট হিস্ট্রি</h1>
            <p class="text-sm text-gray-500">সকল বুকিংয়ের সফল লেনদেনের তালিকা।</p>
        </div>
        
        <div class="bg-blue-50 border border-blue-200 px-4 py-2 rounded-xl">
            <span class="text-sm text-blue-700 font-semibold block">মোট লেনদেন (সফল)</span>
            <span class="text-xl font-bold text-blue-900">৳{{ number_format($totalRevenue) }}</span>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
        <form action="{{ route('admin.payments.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="w-full sm:w-48">
                <label class="block text-xs font-semibold text-gray-700 mb-1">স্ট্যাটাস</label>
                <select name="status" class="input" onchange="this.form.submit()">
                    <option value="">সকল স্ট্যাটাস</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    <option value="failed" @selected(request('status') === 'failed')>Failed</option>
                    <option value="refunded" @selected(request('status') === 'refunded')>Refunded</option>
                </select>
            </div>
            
            @if(request('status'))
                <a href="{{ route('admin.payments.index') }}" class="btn btn-outline border-gray-200 hover:bg-gray-50 px-4" title="রিসেট">
                    <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </form>
    </div>

    {{-- Payments List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">ট্রানজেকশন আইডি</th>
                        <th class="px-6 py-4">বুকিং রেফারেন্স</th>
                        <th class="px-6 py-4">সিকার (পেমেন্টকারী)</th>
                        <th class="px-6 py-4 text-center">স্ট্যাটাস</th>
                        <th class="px-6 py-4 text-right">অ্যামাউন্ট</th>
                        <th class="px-6 py-4 text-right">তারিখ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-mono text-xs bg-gray-100 px-2 py-1 rounded inline-block text-gray-700">
                                    {{ $payment->transaction_id ?: 'N/A' }}
                                </div>
                                <div class="text-[10px] text-gray-400 mt-1 uppercase">{{ $payment->payment_method }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($payment->booking)
                                    <a href="{{ route('admin.bookings.show', $payment->booking->id) }}" class="font-bold text-gray-900 hover:text-blue-600 block">
                                        বুকিং #{{ $payment->booking->id }}
                                    </a>
                                @else
                                    <span class="text-gray-400 italic">বুকিং মুছে ফেলা হয়েছে</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($payment->booking && $payment->booking->seeker)
                                    <a href="{{ route('admin.users.show', $payment->booking->seeker->id) }}" class="font-medium text-gray-900 hover:text-blue-600 block">
                                        {{ $payment->booking->seeker->name }}
                                    </a>
                                    <div class="text-xs text-gray-500">{{ $payment->booking->seeker->phone }}</div>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                        'completed' => 'bg-green-50 text-green-700 border-green-200',
                                        'failed' => 'bg-red-50 text-red-700 border-red-200',
                                        'refunded' => 'bg-gray-100 text-gray-700 border-gray-300',
                                    ];
                                    $color = $statusColors[$payment->status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                @endphp
                                <span class="text-[11px] font-semibold px-2 py-0.5 rounded border {{ $color }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="font-bold text-gray-900">৳{{ number_format($payment->amount) }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="text-gray-900">{{ $payment->created_at->format('d M, y') }}</div>
                                <div class="text-[10px] text-gray-400">{{ $payment->created_at->format('h:i A') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                </div>
                                <p class="text-gray-500">কোনো পেমেন্ট রেকর্ড পাওয়া যায়নি।</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($payments->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
