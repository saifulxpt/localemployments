@extends('layouts.provider')

@section('title', 'অ্যাকাউন্ট যাচাইকরণ')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    @php
        $status = $user->providerProfile?->verification_status ?? 'unverified';
    @endphp

    @if($status === 'approved')
        <div class="bg-white rounded-2xl p-10 border border-green-200 text-center">
            <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">অভিনন্দন! আপনার অ্যাকাউন্ট যাচাইকৃত।</h2>
            <p class="text-gray-600">আপনি এখন নিশ্চিন্তে কাজ করতে পারেন এবং বিড করতে পারেন।</p>
        </div>
    @elseif($status === 'pending')
        <div class="bg-white rounded-2xl p-10 border border-yellow-200 text-center">
            <div class="w-20 h-20 bg-yellow-100 text-yellow-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">আপনার নথি পর্যালোচনা করা হচ্ছে</h2>
            <p class="text-gray-600">আপনার যাচাইকরণ নথিগুলো আমাদের কাছে জমা হয়েছে। অ্যাডমিন প্যানেল থেকে অনুমোদনের জন্য অপেক্ষা করুন। (সাধারণত ১-৩ কার্যদিবস সময় লাগে)</p>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 md:p-8 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-2">অ্যাকাউন্ট যাচাইকরণ (Verification)</h2>
                <p class="text-gray-500 text-sm">কাজ পাওয়ার সম্ভাবনা বাড়াতে এবং প্ল্যাটফর্মে নিজেকে বিশ্বস্ত প্রমাণ করতে আপনার NID কার্ড দিয়ে অ্যাকাউন্ট যাচাই করুন।</p>
                
                @if($status === 'rejected')
                    <div class="mt-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                        <strong class="font-bold">দুঃখিত!</strong> আপনার আগের আবেদনটি বাতিল করা হয়েছে। অনুগ্রহ করে পরিষ্কার এবং সঠিক ছবি দিয়ে পুনরায় চেষ্টা করুন।
                    </div>
                @endif
            </div>

            <form action="{{ route('provider.verification.submit') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-8">
                @csrf
                
                {{-- NID Front --}}
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/3">
                        <h3 class="font-bold text-gray-800 text-sm mb-1">NID এর সামনের অংশ</h3>
                        <p class="text-xs text-gray-500">জাতীয় পরিচয়পত্রের সামনের দিকের স্পষ্ট ছবি।</p>
                    </div>
                    <div class="w-full md:w-2/3">
                        <input type="file" name="nid_front" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" accept="image/*">
                        @error('nid_front') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- NID Back --}}
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/3">
                        <h3 class="font-bold text-gray-800 text-sm mb-1">NID এর পেছনের অংশ</h3>
                        <p class="text-xs text-gray-500">জাতীয় পরিচয়পত্রের পেছনের দিকের স্পষ্ট ছবি।</p>
                    </div>
                    <div class="w-full md:w-2/3">
                        <input type="file" name="nid_back" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" accept="image/*">
                        @error('nid_back') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Selfie with NID --}}
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/3">
                        <h3 class="font-bold text-gray-800 text-sm mb-1">NID সহ নিজস্ব ছবি (সেলফি)</h3>
                        <p class="text-xs text-gray-500">NID কার্ডটি আপনার চেহারার পাশে ধরে একটি পরিষ্কার ছবি তুলুন।</p>
                    </div>
                    <div class="w-full md:w-2/3">
                        <input type="file" name="selfie_with_nid" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" accept="image/*">
                        @error('selfie_with_nid') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6 flex justify-end">
                    <button type="submit" class="btn btn-primary px-8">যাচাইকরণের জন্য জমা দিন</button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection
