@extends('layouts.admin')

@section('title', 'সিস্টেম রিপোর্ট')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">অ্যাডমিন রিপোর্ট</h1>
            <p class="text-sm text-gray-500">প্লাটফর্মের আয়, বুকিং ও ইউজার সম্পর্কিত ডেটা।</p>
        </div>
        
        <div class="flex items-center gap-4">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="flex items-center gap-2">
                <select name="period" class="input py-2" onchange="this.form.submit()">
                    <option value="week" @selected($period === 'week')>This Week</option>
                    <option value="month" @selected($period === 'month')>This Month</option>
                    <option value="year" @selected($period === 'year')>This Year</option>
                </select>
            </form>
            
            <a href="{{ route('admin.reports.export') }}" class="btn btn-outline border-gray-200">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <div class="text-gray-500 text-sm font-semibold mb-1">Total Revenue (Gross)</div>
            <div class="text-3xl font-black text-gray-900 mb-2">৳{{ number_format($stats['total_revenue']) }}</div>
            <div class="text-xs text-gray-400">মোট লেনদেন (বুকিং + ফিচার্ড)</div>
        </div>

        <div class="bg-blue-600 p-6 rounded-2xl shadow-sm text-white">
            <div class="text-blue-100 text-sm font-semibold mb-1">Platform Income (Net)</div>
            <div class="text-3xl font-black mb-2">৳{{ number_format($stats['platform_income']) }}</div>
            <div class="text-xs text-blue-200">কমিশন ও ফি থেকে আয়</div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <div class="text-gray-500 text-sm font-semibold mb-1">Total Paid Out</div>
            <div class="text-3xl font-black text-gray-900 mb-2">৳{{ number_format($stats['total_paid_out']) }}</div>
            <div class="text-xs text-gray-400">প্রোভাইডারদের উইথড্র দেওয়া হয়েছে</div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <div class="text-gray-500 text-sm font-semibold mb-1">New Users</div>
            <div class="text-3xl font-black text-gray-900 mb-2">{{ number_format($stats['new_users']) }}</div>
            <div class="text-xs text-gray-400">নতুন সাইনআপ</div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <div class="text-gray-500 text-sm font-semibold mb-1">New Bookings</div>
            <div class="text-3xl font-black text-gray-900 mb-2">{{ number_format($stats['new_bookings']) }}</div>
            <div class="text-xs text-gray-400">নতুন বুকিং তৈরি হয়েছে</div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <div class="text-gray-500 text-sm font-semibold mb-1">Completed Bookings</div>
            <div class="text-3xl font-black text-green-600 mb-2">{{ number_format($stats['completed']) }}</div>
            <div class="text-xs text-gray-400">সফলভাবে সম্পন্ন হওয়া বুকিং</div>
        </div>
    </div>

    {{-- Chart Placeholder/Data Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mt-6">
        <h2 class="font-bold text-gray-900 mb-4 text-lg">ডেইলি ইনকাম (Daily Income)</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">তারিখ</th>
                        <th class="px-6 py-4 text-right">সার্ভিস অ্যামাউন্ট</th>
                        <th class="px-6 py-4 text-right text-blue-600">প্লাটফর্ম কমিশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($dailyRevenue as $data)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-900">{{ \Carbon\Carbon::parse($data->date)->format('d M, Y') }}</td>
                            <td class="px-6 py-4 text-right">৳{{ number_format($data->total) }}</td>
                            <td class="px-6 py-4 text-right font-bold text-blue-700">৳{{ number_format($data->commission) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                এই সময়ে কোনো ইনকাম ডেটা নেই।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
