@extends('layouts.admin')

@section('title', 'লোকেশন ম্যানেজমেন্ট')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">লোকেশন (জেলা ও এলাকা)</h1>
            <p class="text-sm text-gray-500">প্লাটফর্মের সেবাদানযোগ্য জেলাসমূহ এবং এর অধীনস্থ এলাকাসমূহের তালিকা।</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
            <h3 class="font-bold text-gray-900">জেলাসমূহ</h3>
            <span class="text-sm text-gray-500">মোট {{ $districts->total() }} টি জেলা</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">জেলার নাম (English)</th>
                        <th class="px-6 py-4">জেলার নাম (বাংলা)</th>
                        <th class="px-6 py-4 text-center">উপজেলা/এলাকার সংখ্যা</th>
                        <th class="px-6 py-4 text-center">স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($districts as $district)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-900">
                                {{ $district->name }}
                            </td>
                            <td class="px-6 py-4 font-medium">
                                {{ $district->bn_name }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700">
                                    {{ $district->areas_count }} টি
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($district->is_active)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                কোনো জেলা পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($districts->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $districts->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
