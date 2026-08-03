@extends('layouts.admin')

@section('title', 'যোগাযোগের ম্যাসেজসমূহ')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold text-gray-900">যোগাযোগের ম্যাসেজসমূহ</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        @if($messages->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-100">
                            <th class="p-4 font-semibold">তারিখ</th>
                            <th class="p-4 font-semibold">নাম</th>
                            <th class="p-4 font-semibold">ফোন নম্বর</th>
                            <th class="p-4 font-semibold">ম্যাসেজ</th>
                            <th class="p-4 font-semibold text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($messages as $msg)
                            <tr class="hover:bg-gray-50 {{ !$msg->is_read ? 'bg-blue-50/50' : '' }}">
                                <td class="p-4 whitespace-nowrap text-gray-500">
                                    {{ $msg->created_at->format('d M Y, h:i A') }}
                                </td>
                                <td class="p-4 font-medium text-gray-900 flex items-center gap-2">
                                    @if(!$msg->is_read)
                                        <span class="w-2 h-2 rounded-full bg-blue-500" title="Unread"></span>
                                    @endif
                                    {{ $msg->name }}
                                </td>
                                <td class="p-4 text-gray-600">{{ $msg->phone }}</td>
                                <td class="p-4 text-gray-500 max-w-xs truncate">{{ $msg->message }}</td>
                                <td class="p-4 text-right">
                                    <a href="{{ route('admin.contact-messages.show', $msg->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">বিস্তারিত</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-gray-100">
                {{ $messages->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">কোনো ম্যাসেজ নেই</h3>
                <p class="text-gray-500">এখন পর্যন্ত কেউ কোনো যোগাযোগ করেনি।</p>
            </div>
        @endif
    </div>

</div>
@endsection
