@extends('layouts.public')

@section('title', 'গোপনীয়তা নীতি')

@section('content')
<section class="py-16 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 md:p-12 prose prose-lg max-w-none text-gray-700">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8 border-b pb-6">গোপনীয়তা নীতি (Privacy Policy)</h1>
            
            <p>আমাদের ওয়েবসাইট <strong>LocalEmployments</strong> ব্যবহার করার জন্য আপনাকে ধন্যবাদ। আপনার তথ্যের গোপনীয়তা রক্ষা করা আমাদের অন্যতম প্রধান দায়িত্ব।</p>
            
            <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">১. আমরা কী ধরনের তথ্য সংগ্রহ করি?</h3>
            <p>একাউন্ট তৈরি করার সময় আমরা আপনার নাম, ফোন নম্বর, এবং লোকেশন সংগ্রহ করি। প্রোভাইডারদের ক্ষেত্রে সেবার বিবরণ ও অন্যান্য পেশাগত তথ্য নেওয়া হয়।</p>
            
            <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">২. তথ্য কীভাবে ব্যবহৃত হয়?</h3>
            <ul>
                <li>গ্রাহক এবং সেবাদানকারীর মধ্যে সঠিক যোগাযোগ স্থাপন করা।</li>
                <li>আমাদের সেবার মান উন্নত করা এবং নিরাপত্তা নিশ্চিত করা।</li>
                <li>প্রয়োজনীয় আপডেট এবং নোটিফিকেশন পাঠানো।</li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">৩. তৃতীয় পক্ষের সাথে তথ্য শেয়ার</h3>
            <p>আমরা আপনার ব্যক্তিগত তথ্য কোনো থার্ড-পার্টি বা তৃতীয় পক্ষের কাছে বিক্রি করি না বা শেয়ার করি না। শুধুমাত্র সার্ভিস প্রদানের স্বার্থে গ্রাহক ও প্রোভাইডারের মধ্যে প্রয়োজনীয় তথ্য শেয়ার করা হয়।</p>

            <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">৪. আপনার অধিকার</h3>
            <p>আপনি যেকোনো সময় আপনার প্রোফাইলের তথ্য আপডেট বা ডিলিট করতে পারবেন। প্রয়োজনে আমাদের সাপোর্ট টিমের সাথে যোগাযোগ করতে পারবেন।</p>

            <div class="mt-12 p-6 bg-primary-50 rounded-2xl text-primary-900">
                <p class="m-0 font-medium">কোনো প্রশ্ন থাকলে আমাদের সাথে <a href="{{ route('contact') }}" class="text-primary-700 font-bold underline">যোগাযোগ করুন</a>।</p>
            </div>
        </div>
    </div>
</section>
@endsection
