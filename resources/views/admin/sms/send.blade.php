@extends('layouts.admin')

@section('title', 'বাল্ক SMS সেন্ড')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">SMS ব্রডকাস্ট</h1>
            <p class="text-sm text-gray-500">ইউজারদের কাছে বাল্ক বা নির্দিষ্ট নাম্বারে SMS পাঠান।</p>
        </div>
        <a href="{{ route('admin.sms.logs') }}" class="btn btn-outline border-gray-200">SMS লগস দেখুন</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
        <form action="{{ route('admin.sms.send') }}" method="POST" x-data="{ target: 'specific' }">
            @csrf

            <div class="space-y-6">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">কাকে পাঠাবেন? <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <label class="border border-gray-200 rounded-lg p-4 flex items-center gap-3 cursor-pointer hover:bg-gray-50 transition-colors" :class="target == 'all' ? 'border-blue-500 bg-blue-50/30' : ''">
                            <input type="radio" name="target" value="all" x-model="target" class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-900">সকল ইউজার</span>
                        </label>
                        <label class="border border-gray-200 rounded-lg p-4 flex items-center gap-3 cursor-pointer hover:bg-gray-50 transition-colors" :class="target == 'seekers' ? 'border-blue-500 bg-blue-50/30' : ''">
                            <input type="radio" name="target" value="seekers" x-model="target" class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-900">শুধুমাত্র সিকার</span>
                        </label>
                        <label class="border border-gray-200 rounded-lg p-4 flex items-center gap-3 cursor-pointer hover:bg-gray-50 transition-colors" :class="target == 'providers' ? 'border-blue-500 bg-blue-50/30' : ''">
                            <input type="radio" name="target" value="providers" x-model="target" class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-900">শুধুমাত্র প্রোভাইডার</span>
                        </label>
                        <label class="border border-gray-200 rounded-lg p-4 flex items-center gap-3 cursor-pointer hover:bg-gray-50 transition-colors" :class="target == 'specific' ? 'border-blue-500 bg-blue-50/30' : ''">
                            <input type="radio" name="target" value="specific" x-model="target" class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-900">নির্দিষ্ট নাম্বার</span>
                        </label>
                    </div>
                    @error('target') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div x-show="target === 'specific'" x-cloak>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">ফোন নাম্বার <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" class="input w-full md:w-1/2" placeholder="01XXXXXXXXX" :required="target === 'specific'">
                    <p class="text-xs text-gray-500 mt-1">১১ ডিজিটের নাম্বার দিন।</p>
                    @error('phone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div x-data="{ count: 0 }">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">SMS টেক্সট <span class="text-red-500">*</span></label>
                    <textarea name="message" rows="4" class="input w-full" placeholder="আপনার মেসেজ লিখুন (সর্বোচ্চ ১৬০ ক্যারেক্টার)..." required maxlength="160" x-on:keyup="count = $event.target.value.length"></textarea>
                    <div class="flex justify-between items-center mt-1">
                        @error('message') <span class="text-xs text-red-500 block">{{ $message }}</span> @enderror
                        <span class="text-xs text-gray-500 ml-auto" :class="count > 150 ? 'text-red-500 font-bold' : ''"><span x-text="count">0</span>/160</span>
                    </div>
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-gray-100">
                <button type="submit" class="btn btn-primary bg-blue-600 hover:bg-blue-700 px-8 w-full md:w-auto" onclick="return confirm('আপনি কি নিশ্চিত যে SMS সেন্ড করতে চান?')">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    SMS সেন্ড করুন
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
