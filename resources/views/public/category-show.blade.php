@extends('layouts.public')

@section('title', $category->name . ' সার্ভিস')
@section('meta_description', $category->name . ' এর জন্য আমাদের দক্ষ কর্মীদের থেকে সেরা সার্ভিস নিন।')

@section('content')

{{-- Hero Section --}}
<div class="bg-primary-900 text-white pt-16 pb-24 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                <circle cx="1" cy="1" r="1" fill="white"/>
            </pattern>
            <rect width="100%" height="100%" fill="url(#grid)"/>
        </svg>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="flex items-center gap-2 text-sm text-primary-200 mb-6">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">হোম</a>
            <span>›</span>
            <a href="{{ route('services.index') }}" class="hover:text-white transition-colors">সেবাসমূহ</a>
            <span>›</span>
            <span class="text-white">{{ $category->name }}</span>
        </div>

        <div class="flex flex-col md:flex-row items-center gap-8">
            <div class="w-20 h-20 bg-primary-800 rounded-3xl flex items-center justify-center border-4 border-primary-700 shadow-xl flex-shrink-0 text-4xl">
                {{ $category->icon ? '⚡' : '🛠️' }}
            </div>
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold mb-4">{{ $category->name }}</h1>
                <p class="text-xl text-primary-100 max-w-2xl">{{ $category->description ?? 'এই ক্যাটাগরিতে আমাদের দক্ষ কর্মীরা আপনাকে সেরা সেবা দিতে প্রস্তুত। কাজ পোস্ট করুন অথবা সরাসরি বুক করুন।' }}</p>
                <div class="mt-8 flex gap-4">
                    <a href="{{ route('register') }}" class="btn bg-white text-primary-900 hover:bg-gray-100 shadow-lg px-8 py-3">
                        কাজ পোস্ট করুন
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 -mt-10 relative z-20">
    <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
        
        <h2 class="text-2xl font-bold text-gray-900 mb-6">এই ক্যাটাগরির সেবাসমূহ</h2>
        
        @if($subcategories->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($subcategories as $sub)
                    <div class="p-4 rounded-xl border border-gray-100 bg-gray-50 hover:border-primary-200 hover:bg-primary-50 transition-colors group cursor-default">
                        <h3 class="font-bold text-gray-800 group-hover:text-primary-700 mb-1">{{ $sub->name }}</h3>
                        @if($sub->description)
                            <p class="text-xs text-gray-500 line-clamp-2">{{ $sub->description }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">কোনো সাবক্যাটাগরি নেই।</p>
        @endif

    </div>
</div>

{{-- Open Jobs Section --}}
<div class="bg-gray-50 py-16">
    <div class="container mx-auto px-4">
        
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">এই ক্যাটাগরির উন্মুক্ত কাজসমূহ</h2>
                <p class="text-gray-500 mt-2">আপনার দক্ষতা অনুযায়ী কাজ খুঁজে নিয়ে বিড করুন</p>
            </div>
            <a href="{{ route('jobs.index', ['category' => $category->id]) }}" class="text-primary-600 font-semibold hover:underline hidden sm:block">
                সব উন্মুক্ত কাজ দেখুন →
            </a>
        </div>

        @if($jobs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($jobs as $job)
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-primary-50 text-primary-700">
                                    {{ $job->subcategory?->name }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    {{ $job->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <h3 class="font-bold text-lg text-gray-900 group-hover:text-primary-600 transition-colors mb-2 line-clamp-1">
                                {{ $job->title }}
                            </h3>

                            <p class="text-sm text-gray-600 line-clamp-2 mb-4">
                                {{ $job->description }}
                            </p>
                        </div>

                        <div>
                            <div class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100 mb-4">
                                <span class="flex items-center gap-1 font-medium">
                                    <svg class="w-3.5 h-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $job->district?->bn_name }}
                                </span>
                                <span class="font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                                    ৳{{ number_format($job->budget_min) }} - ৳{{ number_format($job->budget_max) }}
                                </span>
                            </div>

                            <a href="{{ route('jobs.index', ['category' => $category->id]) }}" class="block text-center py-2 bg-gray-900 group-hover:bg-primary-600 text-white text-xs font-bold rounded-xl transition-colors">
                                বিস্তারিত ও বিড দেখুন
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $jobs->links() }}
            </div>
        @else
            <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center max-w-3xl mx-auto">
                <div class="text-6xl mb-4">📋</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">এই ক্যাটাগরিতে বর্তমানে কোনো উন্মুক্ত কাজ নেই</h3>
                <p class="text-gray-500 mb-6">আপনি কি এই সেবার জন্য কাউকে খুঁজছেন? সহজেই আপনার কাজের চাহিদা জানিয়ে একটি রিকোয়েস্ট পোস্ট করুন!</p>
                <a href="{{ route('register') }}" class="btn btn-primary">কাজ পোস্ট করুন</a>
            </div>
        @endif
        
    </div>
</div>

@endsection
