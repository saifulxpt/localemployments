@extends('layouts.public')

@section('title', 'সকল কাজসমূহ (Browse Jobs)')

@section('content')
<div class="bg-gray-50 py-10 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">সকল উন্মুক্ত কাজসমূহ</h1>
            <p class="text-lg text-gray-600">আপনার এলাকার আশেপাশে মানুষ কী কী কাজের জন্য সার্ভিস প্রোভাইডার খুঁজছেন তা দেখুন। কাজগুলো করতে চাইলে প্রোভাইডার হিসেবে জয়েন করুন।</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Filters --}}
            <div class="w-full lg:w-1/4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        ফিল্টার করুন
                    </h3>
                    
                    <form action="{{ route('jobs.index') }}" method="GET" class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">ক্যাটাগরি</label>
                            <select name="category" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200">
                                <option value="">সব ক্যাটাগরি</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->bn_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">জেলা</label>
                            <select name="district" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200">
                                <option value="">সব জেলা</option>
                                @foreach($districts as $dist)
                                    <option value="{{ $dist->id }}" {{ request('district') == $dist->id ? 'selected' : '' }}>{{ $dist->bn_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-xl transition-colors">
                            খুঁজুন
                        </button>

                        @if(request()->anyFilled(['category', 'district']))
                            <a href="{{ route('jobs.index') }}" class="block text-center text-sm text-red-600 hover:underline mt-2">ফিল্টার মুছুন</a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Jobs List --}}
            <div class="w-full lg:w-3/4">
                @if($jobs->count() > 0)
                    <div class="space-y-4">
                        @foreach($jobs as $job)
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                                <div class="flex flex-col md:flex-row justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="bg-primary-50 text-primary-700 text-xs font-bold px-2.5 py-0.5 rounded-full border border-primary-100">
                                                {{ $job->subcategory->bn_name }}
                                            </span>
                                            <span class="text-xs text-gray-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ $job->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        
                                        <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $job->title }}</h2>
                                        
                                        <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $job->description }}</p>
                                        
                                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                {{ $job->area ? $job->area->bn_name : '' }}, {{ $job->district->bn_name }}
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                বাজেট: ৳{{ $job->budget_min }} - ৳{{ $job->budget_max }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col justify-center shrink-0">
                                        <a href="{{ route('provider.jobs.index') }}" onclick="alert('কাজে বিড করার জন্য প্রোভাইডার হিসেবে লগইন করুন!');" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-900 text-white rounded-xl font-bold hover:bg-gray-800 transition-colors">
                                            বিড করুন
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </a>
                                        <div class="text-center mt-2">
                                            <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded-lg">মোট বিড: {{ $job->total_bids }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $jobs->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
                        <div class="w-20 h-20 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">কোনো কাজ পাওয়া যায়নি</h3>
                        <p class="text-gray-500">বর্তমানে আপনার সিলেক্ট করা এলাকায় কোনো কাজ খোলা নেই। অন্য ক্যাটাগরি বা জেলা সিলেক্ট করে দেখুন।</p>
                    </div>
                @endif
            </div>
        </div>
        
    </div>
</div>
@endsection
