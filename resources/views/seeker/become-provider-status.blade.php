@extends('layouts.seeker')

@section('title', 'প্রোভাইডার আবেদনের অবস্থা')

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 text-center">
    
    @php
        $status = $user->providerProfile?->verification_status ?? 'pending';
    @endphp

    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-8 sm:p-12 relative overflow-hidden">
        
        @if($status === 'pending')
            <div class="w-20 h-20 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <svg class="w-10 h-10 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            
            <h2 class="text-3xl font-extrabold text-gray-900 mb-3">আবেদন পর্যালোচনাাধীন (Pending)</h2>
            <p class="text-gray-600 text-base mb-6 leading-relaxed max-w-md mx-auto">
                আপনার সার্ভিস প্রোভাইডার আবেদন ও NID ভেরিফিকেশন ফাইলসমূহ আমাদের টিম পরীক্ষা করছে। সাধারণত ২৪ ঘণ্টার মধ্যে অ্যাডমিন রিভিউ সম্পন্ন হয়।
            </p>

            <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl p-4 text-xs font-medium max-w-md mx-auto mb-8">
                💡 টিপস: অনুমোদন সম্পন্ন হওয়ার সাথে সাথেই আপনার ফোন নম্বরে SMS যাবে এবং আপনি প্রোভাইডার ড্যাশবোর্ডে প্রবেশের অনুমতি পাবেন।
            </div>

        @elseif($status === 'rejected')
            <div class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            
            <h2 class="text-3xl font-extrabold text-gray-900 mb-3">আবেদন বাতিল করা হয়েছে</h2>
            <p class="text-gray-600 text-base mb-6 leading-relaxed max-w-md mx-auto">
                দুঃখিত, আপনার প্রদত্ত NID বা তথ্যে কিছু অসামঞ্জস্য পাওয়া গেছে। অনুগ্রহ করে সঠিক তথ্য দিয়ে পুনরায় আবেদন করুন।
            </p>
            
            <a href="{{ route('seeker.become-provider') }}" class="inline-block px-8 py-3.5 bg-primary-600 text-white font-bold rounded-xl shadow-lg hover:bg-primary-700 transition-colors">
                পুনরায় আবেদন করুন
            </a>

        @else
            <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            
            <h2 class="text-3xl font-extrabold text-gray-900 mb-3">আপনি একজন ভেরিফাইড প্রোভাইডার!</h2>
            <p class="text-gray-600 text-base mb-8 max-w-md mx-auto">
                আপনার একাউন্টটি সফলভাবে ভেরিফাইড করা হয়েছে। এখন আপনি প্রোভাইডার হিসেবে সরাসরি কাজ বেছে নিতে পারেন।
            </p>

            <a href="{{ route('provider.dashboard') }}" class="inline-block px-8 py-3.5 bg-emerald-600 text-white font-bold rounded-xl shadow-lg hover:bg-emerald-700 transition-colors">
                প্রোভাইডার ড্যাশবোর্ডে যান
            </a>
        @endif

        <div class="mt-8 pt-6 border-t border-gray-100">
            <a href="{{ route('seeker.dashboard') }}" class="text-gray-500 hover:text-primary-600 font-bold text-sm">
                ← ইউজার ড্যাশবোর্ডে ফিরে যান
            </a>
        </div>
    </div>
</div>
@endsection
