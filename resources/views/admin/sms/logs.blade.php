@extends('layouts.admin')

@section('title', 'SMS লগস')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">SMS লগস</h1>
            <p class="text-sm text-gray-500">সিস্টেম থেকে পাঠানো সকল SMS এর হিস্ট্রি।</p>
        </div>
        <a href="{{ route('admin.sms.show') }}" class="btn btn-primary bg-blue-600 hover:bg-blue-700">নতুন SMS পাঠান</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">ফোন নাম্বার</th>
                        <th class="px-6 py-4 w-1/2">মেসেজ</th>
                        <th class="px-6 py-4 text-center">প্রকার (Type)</th>
                        <th class="px-6 py-4 text-right">তারিখ ও সময়</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-gray-900">
                                {{ $log->phone }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-600 bg-gray-50 p-2 rounded border border-gray-100">
                                    {{ $log->message }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($log->type === 'otp')
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold bg-purple-100 text-purple-700 uppercase">OTP</span>
                                @elseif($log->type === 'bulk')
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-700 uppercase">Bulk</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold bg-gray-100 text-gray-700 uppercase">{{ $log->type }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="text-gray-900">{{ $log->created_at->format('d M, y') }}</div>
                                <div class="text-[10px] text-gray-400">{{ $log->created_at->format('h:i A') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                কোনো SMS লগ পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
