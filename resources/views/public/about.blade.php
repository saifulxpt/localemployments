@extends('layouts.public')

@section('title', 'আমাদের সম্পর্কে')
@section('meta_description', 'LocalEmployments সম্পর্কে বিস্তারিত জানুন।')

@section('content')
<div class="bg-gray-50 py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">আমাদের সম্পর্কে</h1>
            <p class="text-xl text-gray-600 leading-relaxed">
                LocalEmployments হলো বাংলাদেশের স্থানীয় সেবার জন্য একটি বিশ্বস্ত প্ল্যাটফর্ম। 
                আমরা বিশ্বাস করি, সঠিক সময়ে সঠিক মানুষের সহায়তা পেলে জীবন অনেক সহজ হয়ে যায়।
            </p>
        </div>

        <div class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-gray-100 mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">আমাদের লক্ষ্য</h2>
            <p class="text-gray-600 mb-8 leading-relaxed">
                আমাদের প্রধান লক্ষ্য হলো দৈনন্দিন জীবনের বিভিন্ন প্রয়োজনীয় কাজের জন্য বিশ্বস্ত এবং দক্ষ কর্মীদের সাথে সাধারণ মানুষের একটি সেতুবন্ধন তৈরি করা। একই সাথে, আমরা চাই দক্ষ কর্মীরা যেন তাদের ন্যায্য পারিশ্রমিক পান এবং বেকারত্ব দূরীকরণে এটি সহায়ক ভূমিকা পালন করে।
            </p>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="p-6 bg-primary-50 rounded-2xl border border-primary-100">
                    <div class="w-12 h-12 bg-primary-600 text-white rounded-xl flex items-center justify-center text-2xl mb-4">🎯</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">সেবাগ্রহীতাদের জন্য</h3>
                    <p class="text-gray-600 text-sm">কোনো ঝামেলা ছাড়াই দ্রুত যাচাইকৃত কর্মী খুঁজে পাওয়া এবং নিরাপদ লেনদেনের মাধ্যমে সেবা নিশ্চিত করা।</p>
                </div>
                <div class="p-6 bg-accent-50 rounded-2xl border border-accent-100">
                    <div class="w-12 h-12 bg-accent-500 text-white rounded-xl flex items-center justify-center text-2xl mb-4">💼</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">কর্মীদের জন্য</h3>
                    <p class="text-gray-600 text-sm">নিজের দক্ষতার মাধ্যমে স্বাধীনভাবে কাজ খুঁজে নেওয়া এবং মধ্যস্বত্বভোগীদের এড়িয়ে ন্যায্য আয় নিশ্চিত করা।</p>
                </div>
            </div>
        </div>

        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">আমাদের সাথে যুক্ত হোন</h2>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="btn btn-primary px-8">কাজ পোস্ট করুন</a>
                <a href="{{ route('register') }}?role=provider" class="btn btn-outline px-8">কর্মী হিসেবে যুক্ত হোন</a>
            </div>
        </div>

    </div>
</div>
@endsection
