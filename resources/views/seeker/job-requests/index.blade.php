@extends('layouts.seeker')

@section('title', 'আমার জব রিকোয়েস্টসমূহ')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">আমার জব রিকোয়েস্টসমূহ</h1>
            <p class="text-sm text-gray-500">আপনার পোস্ট করা সকল কাজের তালিকা এবং বর্তমান অবস্থা।</p>
        </div>
        <a href="{{ route('seeker.job-requests.create') }}" class="btn btn-primary whitespace-nowrap">
            + নতুন কাজ পোস্ট করুন
        </a>
    </div>

    @if($requests->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($requests as $job)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col hover:shadow-md transition-all">
                    
                    <div class="flex justify-between items-start gap-3 mb-4">
                        <span class="text-xs font-bold text-primary-700 bg-primary-50 px-2.5 py-1 rounded-md border border-primary-100 line-clamp-1">
                            {{ $job->subcategory->name }}
                        </span>
                        <span class="text-xs font-semibold px-2 py-1 rounded border {{ $job->status === 'open' ? 'bg-green-50 text-green-700 border-green-200' : ($job->status === 'assigned' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-gray-50 text-gray-700 border-gray-200') }}">
                            {{ ucfirst($job->status) }}
                        </span>
                    </div>

                    <h3 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2">
                        <a href="{{ route('seeker.job-requests.show', $job->id) }}" class="hover:text-primary-600 transition-colors">{{ $job->title }}</a>
                    </h3>
                    
                    <p class="text-sm text-gray-600 line-clamp-2 mb-4">{{ $job->description }}</p>

                    <div class="space-y-2 mb-4 text-sm text-gray-600">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="line-clamp-1">{{ $job->area->bn_name }}, {{ $job->district->bn_name }}</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="line-clamp-1 text-gray-500 text-xs">{{ $job->created_at->format('d M, Y h:i A') }}</span>
                        </div>
                    </div>

                    <div class="mt-auto border-t border-gray-100 pt-4 flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-sm font-semibold {{ $job->bids_count > 0 ? 'text-primary-600' : 'text-gray-400' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            {{ $job->bids_count }} টি বিড
                        </div>
                        <a href="{{ route('seeker.job-requests.show', $job->id) }}" class="btn btn-outline btn-sm">বিস্তারিত</a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-8">
            {{ $requests->links() }}
        </div>
    @else
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">কোনো জব রিকোয়েস্ট নেই</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">আপনি এখনও কোনো কাজের জন্য রিকোয়েস্ট পোস্ট করেননি। আপনার কী কাজ প্রয়োজন তা বিস্তারিত লিখে পোস্ট করুন, প্রোভাইডাররা বিড করবে।</p>
            <a href="{{ route('seeker.job-requests.create') }}" class="btn btn-primary">প্রথম কাজ পোস্ট করুন</a>
        </div>
    @endif

</div>
@endsection
