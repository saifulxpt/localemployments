@extends('layouts.public')

@section('title', 'শর্তাবলী')

@section('content')
<section class="py-16 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 md:p-12 prose prose-lg max-w-none text-gray-700">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8 border-b pb-6">শর্তাবলী (Terms & Conditions)</h1>
            
            <p>আমাদের ওয়েবসাইট <strong>LocalEmployments</strong> ব্যবহারের ক্ষেত্রে নিচের শর্তাবলী প্রযোজ্য হবে। একাউন্ট খোলার মাধ্যমে আপনি এই শর্তগুলোতে সম্মতি জ্ঞাপন করছেন।</p>
            
            <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">১. সাধারণ শর্তাবলী</h3>
            <p>এই প্লাটফর্মটি একটি মার্কেটপ্লেস হিসেবে কাজ করে, যা কর্মী এবং গ্রাহকের মাঝে সংযোগ স্থাপন করে। সেবার গুণগত মান বা সরাসরি কোনো ক্ষতির জন্য প্লাটফর্ম কর্তৃপক্ষ দায়ী থাকবে না।</p>
            
            <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">২. প্রোভাইডারদের জন্য</h3>
            <ul>
                <li>কাজের প্রতিশ্রুতি দিয়ে তা অমান্য করা হলে বা গ্রাহকের সাথে অসদাচরণ করলে একাউন্ট সাসপেন্ড করা হতে পারে।</li>
                <li>কাজের জন্য ন্যায্য মূল্য দাবি করতে হবে।</li>
                <li>প্লাটফর্মের নিয়ম অনুযায়ী নির্দিষ্ট কমিশন প্রদান করতে হবে।</li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">৩. গ্রাহকদের জন্য</h3>
            <ul>
                <li>কাজ শেষে অবশ্যই প্রোভাইডারকে তার প্রাপ্য পারিশ্রমিক পরিশোধ করতে হবে।</li>
                <li>অযথা কোনো প্রোভাইডারকে হয়রানি করা বা ফেক বুকিং দেওয়া থেকে বিরত থাকতে হবে।</li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">৪. পেমেন্ট ও লেনদেন</h3>
            <p>আমাদের প্লাটফর্মের মাধ্যমে লেনদেন করার সময় সতর্ক থাকুন। ক্যাশ অন ডেলিভারি বা সরাসরি কাজ শেষে পেমেন্ট দেওয়ার ক্ষেত্রে দুই পক্ষকেই সাবধানে লেনদেন করতে হবে।</p>

            <div class="mt-12 p-6 bg-primary-50 rounded-2xl text-primary-900">
                <p class="m-0 font-medium">কোনো প্রশ্ন থাকলে আমাদের সাথে <a href="{{ route('contact') }}" class="text-primary-700 font-bold underline">যোগাযোগ করুন</a>।</p>
            </div>
        </div>
    </div>
</section>
@endsection
