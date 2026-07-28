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

{{-- Providers Section --}}
<div class="bg-gray-50 py-16">
    <div class="container mx-auto px-4">
        
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">এই ক্যাটাগরির কর্মী</h2>
                <p class="text-gray-500 mt-2">যাঁরা এই কাজগুলো করতে দক্ষ</p>
            </div>
            <a href="{{ route('search', ['category' => $category->id]) }}" class="text-primary-600 font-semibold hover:underline hidden sm:block">
                সবাইকে দেখুন →
            </a>
        </div>

        @if($providers->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($providers as $provider)
                    @include('components.public.provider-card', ['provider' => $provider])
                @endforeach
            </div>
            <div class="mt-8">
                {{ $providers->links() }}
            </div>
        @else
            <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center max-w-3xl mx-auto">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">এই ক্যাটাগরিতে কোনো কর্মী নেই</h3>
                <p class="text-gray-500 mb-6">দুঃখিত, বর্তমানে এই সেবার জন্য কোনো কর্মী নিবন্ধিত নেই। আপনি চাইলে প্রথম কর্মী হিসেবে যুক্ত হতে পারেন!</p>
                <a href="{{ route('register') }}?role=provider" class="btn btn-primary">প্রোভাইডার হিসেবে যোগ দিন</a>
            </div>
        @endif
        
    </div>
</div>

@endsection
