@extends('layouts.admin')

@section('title', 'প্রোভাইডার ভেরিফিকেশন')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">প্রোভাইডার ভেরিফিকেশন</h1>
            <p class="text-sm text-gray-500">নতুন প্রোভাইডারদের এনআইডি ও তথ্য যাচাইয়ের তালিকা (পেন্ডিং)।</p>
        </div>
    </div>

    {{-- Pending Verifications List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        
        <div class="border-b border-gray-100 p-4 bg-gray-50/50 flex justify-between items-center">
            <h2 class="font-bold text-gray-900">পেন্ডিং ভেরিফিকেশন রিকোয়েস্ট ({{ $pending->total() }})</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">প্রোভাইডার</th>
                        <th class="px-6 py-4">এনআইডি নম্বর</th>
                        <th class="px-6 py-4">সাবমিট করেছে</th>
                        <th class="px-6 py-4 text-center">স্ট্যাটাস</th>
                        <th class="px-6 py-4 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pending as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $user->avatar_url }}" alt="" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                    <div>
                                        <a href="{{ route('admin.verifications.show', $user->id) }}" class="font-bold text-gray-900 hover:text-blue-600">{{ $user->name }}</a>
                                        <div class="text-xs text-gray-500">{{ $user->phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $user->verificationDoc->nid_number ?? 'দেওয়া হয়নি' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-900">{{ $user->verificationDoc?->created_at->format('d M, Y') ?? 'N/A' }}</div>
                                @if($user->verificationDoc)
                                    <div class="text-xs text-gray-400">{{ $user->verificationDoc->created_at->diffForHumans() }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                    পেন্ডিং
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.verifications.show', $user->id) }}" class="btn btn-primary bg-blue-600 hover:bg-blue-700 btn-sm">রিভিউ করুন</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <p class="text-gray-500 text-lg font-medium">কোনো পেন্ডিং ভেরিফিকেশন নেই।</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($pending->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $pending->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
