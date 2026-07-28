@extends('layouts.provider')

@section('title', 'নতুন কাজ খুঁজুন')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">আপনার এলাকার নতুন কাজ</h1>
            <p class="text-sm text-gray-500">আপনার স্কিল এবং লোকেশন অনুযায়ী বর্তমানে যেসব কাজ খোলা আছে।</p>
        </div>
    </div>

    @if($jobs->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($jobs as $job)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col hover:shadow-md hover:border-primary-200 transition-all">
                    
                    <div class="flex justify-between items-start gap-3 mb-4">
                        <span class="text-xs font-bold text-primary-700 bg-primary-50 px-2.5 py-1 rounded-md border border-primary-100 line-clamp-1">
                            {{ $job->subcategory->name }}
                        </span>
                        <span class="text-xs text-gray-500 shrink-0">{{ $job->created_at->diffForHumans() }}</span>
                    </div>

                    <h3 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2">
                        <a href="{{ route('provider.jobs.show', $job->id) }}" class="hover:text-primary-600 transition-colors">{{ $job->title }}</a>
                    </h3>
                    
                    <p class="text-sm text-gray-600 line-clamp-2 mb-4">{{ $job->description }}</p>

                    <div class="space-y-2 mb-4 text-sm text-gray-600">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-gray-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="line-clamp-1">{{ $job->district->bn_name }}, {{ $job->area->bn_name }}</span>
                        </div>
                        @if($job->budget_range)
                            <div class="flex items-start gap-2 text-green-700 font-medium">
                                <svg class="w-4 h-4 text-green-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                বাজেট: {{ $job->budget_range }}
                            </div>
                        @endif
                    </div>

                    <div class="mt-auto border-t border-gray-100 pt-4 flex items-center justify-between">
                        <div class="text-sm font-medium {{ $job->bids_count >= setting('max_bid_per_job', 10) ? 'text-red-500' : 'text-gray-500' }}">
                            {{ $job->bids_count }} জন বিড করেছে
                        </div>
                        <a href="{{ route('provider.jobs.show', $job->id) }}" class="btn btn-primary btn-sm px-4">বিড করুন</a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-8">
            {{ $jobs->links() }}
        </div>
    @else
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">কোনো নতুন কাজ পাওয়া যায়নি</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">বর্তমানে আপনার স্কিল এবং এলাকার সাথে মিল রেখে কোনো নতুন কাজ নেই। আপনার প্রোফাইলে আরও স্কিল যুক্ত করতে পারেন অথবা পরে আবার চেক করুন।</p>
            <a href="{{ route('provider.skills.manage') }}" class="btn btn-outline">স্কিল ম্যানেজ করুন</a>
        </div>
    @endif

</div>
@endsection
