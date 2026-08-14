@extends('layouts.admin')

@section('title', 'SMS লগস')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">SMS লগস</h1>
            <p class="text-sm text-gray-500">সিস্টেম থেকে পাঠানো সকল SMS এর হিস্ট্রি ও BulkSMSBD রেসপন্স।</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.settings.api') }}" class="btn btn-outline border-gray-200 text-sm">SMS সেটিংস</a>
            <a href="{{ route('admin.sms.show') }}" class="btn btn-primary bg-blue-600 hover:bg-blue-700">নতুন SMS পাঠান</a>
        </div>
    </div>

    {{-- Stats --}}
    @php
        $sent  = \App\Models\SmsLog::where('status', 'sent')->count();
        $failed= \App\Models\SmsLog::where('status', 'failed')->count();
    @endphp
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-200 p-4 text-center">
            <div class="text-2xl font-black text-gray-900">{{ $logs->total() }}</div>
            <div class="text-xs text-gray-500 mt-1">মোট SMS</div>
        </div>
        <div class="bg-green-50 rounded-2xl border border-green-200 p-4 text-center">
            <div class="text-2xl font-black text-green-700">{{ $sent }}</div>
            <div class="text-xs text-green-600 mt-1">✅ সফল</div>
        </div>
        <div class="bg-red-50 rounded-2xl border border-red-200 p-4 text-center">
            <div class="text-2xl font-black text-red-700">{{ $failed }}</div>
            <div class="text-xs text-red-600 mt-1">❌ ব্যর্থ</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-4">ফোন নাম্বার</th>
                        <th class="px-4 py-4">মেসেজ</th>
                        <th class="px-4 py-4 text-center">ধরন</th>
                        <th class="px-4 py-4 text-center">স্ট্যাটাস</th>
                        <th class="px-4 py-4">BulkSMSBD রেসপন্স (Error Code)</th>
                        <th class="px-4 py-4 text-right">সময়</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        @php
                            $resp = is_array($log->gateway_response)
                                ? $log->gateway_response
                                : json_decode($log->gateway_response ?? '{}', true);
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors {{ $log->status === 'failed' ? 'bg-red-50/40' : '' }}">
                            <td class="px-4 py-4 font-mono font-bold text-gray-900 whitespace-nowrap">{{ $log->phone }}</td>
                            <td class="px-4 py-4 max-w-xs">
                                <div class="text-xs text-gray-600 bg-gray-50 p-2 rounded border border-gray-100">{{ Str::limit($log->message, 80) }}</div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $log->type === 'otp' ? 'bg-purple-100 text-purple-700' :
                                       ($log->type === 'test' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ strtoupper($log->type) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($log->status === 'sent')
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">✅ সফল</span>
                                @else
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">❌ ব্যর্থ</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if(!empty($resp))
                                    <div class="text-xs font-mono bg-gray-900 text-green-400 p-2 rounded max-w-xs overflow-auto">
                                        @if(isset($resp['response_code']))
                                            <span class="{{ $resp['response_code'] == 202 ? 'text-green-400' : 'text-red-400' }} font-bold">
                                                Code: {{ $resp['response_code'] }}
                                            </span>
                                            @if($resp['response_code'] != 202)
                                                <br><span class="text-yellow-400">⚠ Error {{ $resp['response_code'] }}</span>
                                            @endif
                                        @elseif(isset($resp['dev_mode']))
                                            <span class="text-yellow-400">Dev Mode (No API Key)</span>
                                        @elseif(isset($resp['error']))
                                            <span class="text-red-400">{{ $resp['error'] }}</span>
                                        @else
                                            {{ json_encode($resp) }}
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right whitespace-nowrap">
                                <div class="text-gray-900 text-xs font-semibold">{{ $log->created_at->format('d M Y') }}</div>
                                <div class="text-[10px] text-gray-400">{{ $log->created_at->format('h:i A') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                কোনো SMS লগ পাওয়া যায়নি। প্রথমে একটি টেস্ট SMS পাঠান।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-gray-100">{{ $logs->links() }}</div>
        @endif
    </div>

</div>
@endsection
