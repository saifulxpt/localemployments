@extends('layouts.public')

@section('title', 'সাধারণ জিজ্ঞাসা (FAQ)')

@section('content')
<section class="py-16 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">সাধারণ জিজ্ঞাসা (FAQ)</h1>
            <p class="text-gray-500">আপনাদের করা সবচেয়ে কমন কিছু প্রশ্নের উত্তর নিচে দেওয়া হলো</p>
        </div>

        <div class="space-y-6" x-data="{ active: null }">
            {{-- FAQ 1 --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button @click="active = active === 1 ? null : 1" class="w-full text-left p-6 flex justify-between items-center focus:outline-none">
                    <h3 class="text-lg font-semibold text-gray-900">LocalEmployments কীভাবে কাজ করে?</h3>
                    <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="active === 1 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="active === 1" x-collapse x-cloak>
                    <div class="p-6 pt-0 text-gray-600">
                        LocalEmployments হলো একটি লোকাল সার্ভিস মার্কেটপ্লেস। এখানে আপনি আপনার এলাকার বিভিন্ন প্রফেশনাল যেমন ইলেকট্রিশিয়ান, প্লাম্বার, মেকানিক ইত্যাদিকে খুঁজে পেতে পারেন এবং তাদের সাথে সরাসরি যোগাযোগ করে কাজ করাতে পারেন।
                    </div>
                </div>
            </div>

            {{-- FAQ 2 --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button @click="active = active === 2 ? null : 2" class="w-full text-left p-6 flex justify-between items-center focus:outline-none">
                    <h3 class="text-lg font-semibold text-gray-900">আমি কি ফ্রিতে একাউন্ট খুলতে পারবো?</h3>
                    <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="active === 2 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="active === 2" x-collapse x-cloak>
                    <div class="p-6 pt-0 text-gray-600">
                        হ্যাঁ, সাধারণ গ্রাহক এবং প্রোভাইডার উভয়েই সম্পূর্ণ ফ্রিতে একাউন্ট তৈরি করতে পারবেন।
                    </div>
                </div>
            </div>

            {{-- FAQ 3 --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button @click="active = active === 3 ? null : 3" class="w-full text-left p-6 flex justify-between items-center focus:outline-none">
                    <h3 class="text-lg font-semibold text-gray-900">প্রোভাইডারদের সাথে কীভাবে যোগাযোগ করবো?</h3>
                    <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="active === 3 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="active === 3" x-collapse x-cloak>
                    <div class="p-6 pt-0 text-gray-600">
                        যেকোনো সার্ভিস বুক করার পর আপনি প্রোভাইডারের ফোন নম্বর পেয়ে যাবেন। এছাড়াও আমাদের ইন-অ্যাপ মেসেজিং সিস্টেমের মাধ্যমে আপনি সরাসরি চ্যাট করতে পারবেন।
                    </div>
                </div>
            </div>

            {{-- FAQ 4 --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <button @click="active = active === 4 ? null : 4" class="w-full text-left p-6 flex justify-between items-center focus:outline-none">
                    <h3 class="text-lg font-semibold text-gray-900">কাজের পেমেন্ট কীভাবে দিতে হবে?</h3>
                    <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="active === 4 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="active === 4" x-collapse x-cloak>
                    <div class="p-6 pt-0 text-gray-600">
                        কাজ সম্পন্ন হওয়ার পর আপনি ক্যাশ অন ডেলিভারি অথবা আমাদের অনলাইন পেমেন্ট গেটওয়ে ব্যবহার করে পেমেন্ট করতে পারবেন।
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-12 text-center">
            <p class="text-gray-600">আপনার প্রশ্নের উত্তর এখানে না পেলে <a href="{{ route('contact') }}" class="text-primary-600 font-semibold hover:underline">আমাদের সাথে যোগাযোগ করুন</a>।</p>
        </div>
    </div>
</section>
@endsection
