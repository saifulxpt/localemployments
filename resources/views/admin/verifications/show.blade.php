@extends('layouts.admin')

@section('title', 'ভেরিফিকেশন রিভিউ: ' . $user->name)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('admin.verifications.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            পেন্ডিং লিস্ট
        </a>
    </div>

    @if(!$user->verificationDoc)
        <div class="bg-red-50 border border-red-200 text-red-800 p-6 rounded-2xl text-center">
            <h2 class="font-bold text-xl mb-2">কোনো ডকুমেন্ট নেই!</h2>
            <p>এই প্রোভাইডার কোনো ভেরিফিকেশন ডকুমেন্ট সাবমিট করেনি।</p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Provider Info Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 text-center">
                    <img src="{{ $user->avatar_url }}" alt="" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md mx-auto mb-4">
                    
                    <h2 class="text-xl font-bold text-gray-900 mb-1">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="hover:text-blue-600">{{ $user->name }}</a>
                    </h2>
                    <div class="text-sm text-gray-500 mb-4">{{ $user->phone }}</div>
                    
                    <div class="border-t border-gray-100 pt-4 text-left space-y-3 text-sm">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500 mb-0.5">বর্তমান স্ট্যাটাস</span>
                            <span class="font-bold {{ $user->providerProfile->verification_status === 'approved' ? 'text-green-600' : ($user->providerProfile->verification_status === 'rejected' ? 'text-red-600' : 'text-yellow-600') }}">
                                {{ ucfirst($user->providerProfile->verification_status ?? 'Not Submitted') }}
                            </span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500 mb-0.5">লোকেশন</span>
                            <span class="font-medium text-gray-900">{{ $user->area ? $user->area->bn_name : '' }}, {{ $user->district ? $user->district->bn_name : 'সেট করা নেই' }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500 mb-0.5">জয়েন করেছে</span>
                            <span class="font-medium text-gray-900">{{ $user->created_at->format('d M, Y') }}</span>
                        </div>
                    </div>
                </div>

                @if($user->providerProfile->verification_status === 'pending')
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="font-bold text-gray-900 mb-4">অ্যাকশন (Action)</h3>
                        
                        <form action="{{ route('admin.verifications.approve', $user->id) }}" method="POST" class="mb-4">
                            @csrf
                            <button type="submit" class="btn btn-primary bg-green-600 hover:bg-green-700 border-green-600 w-full" onclick="return confirm('আপনি কি নিশ্চিত যে এনআইডি ঠিক আছে এবং প্রোফাইল ভেরিফাই করতে চান?')">
                                Approve (ভেরিফাই করুন)
                            </button>
                        </form>

                        <hr class="border-gray-100 my-4">

                        <form action="{{ route('admin.verifications.reject', $user->id) }}" method="POST" x-data="{ showReason: false }">
                            @csrf
                            <button type="button" @click="showReason = !showReason" class="btn btn-outline text-red-500 border-red-200 hover:bg-red-50 w-full mb-3">
                                Reject (বাতিল করুন)
                            </button>

                            <div x-show="showReason" x-cloak class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700">বাতিলের কারণ (প্রোভাইডার দেখতে পারবে)</label>
                                <textarea name="admin_note" rows="3" class="input text-sm" placeholder="যেমন: এনআইডি ছবি অস্পষ্ট..." required></textarea>
                                <button type="submit" class="btn bg-red-600 text-white hover:bg-red-700 w-full btn-sm" onclick="return confirm('আপনি কি নিশ্চিত যে ভেরিফিকেশন বাতিল করতে চান?')">নিশ্চিত করুন</button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Documents Preview --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
                    <h3 class="font-bold text-xl text-gray-900 mb-6 border-b border-gray-100 pb-4">যাচাইয়ের জন্য জমাকৃত ডকুমেন্টস</h3>
                    
                    <div class="mb-8">
                        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">এনআইডি নম্বর</h4>
                        <div class="text-2xl font-black text-gray-900 bg-gray-50 inline-block px-4 py-2 rounded-xl border border-gray-200 tracking-widest mb-4">
                            {{ $user->verificationDoc->nid_number }}
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm bg-gray-50 p-6 rounded-xl border border-gray-100">
                            <div>
                                <span class="block text-gray-500 mb-1">পুরো নাম</span>
                                <span class="font-bold text-gray-900">{{ $user->verificationDoc->full_name }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500 mb-1">জন্ম তারিখ</span>
                                <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($user->verificationDoc->dob)->format('d M, Y') }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500 mb-1">পিতার নাম</span>
                                <span class="font-bold text-gray-900">{{ $user->verificationDoc->father_name ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500 mb-1">মাতার নাম</span>
                                <span class="font-bold text-gray-900">{{ $user->verificationDoc->mother_name ?? '-' }}</span>
                            </div>
                            <div class="md:col-span-2 mt-2 pt-2 border-t border-gray-200"></div>
                            <div class="md:col-span-2">
                                <span class="block text-gray-500 mb-1">বর্তমান ঠিকানা</span>
                                <span class="font-bold text-gray-900">{{ $user->verificationDoc->current_address }}</span>
                            </div>
                            <div class="md:col-span-2">
                                <span class="block text-gray-500 mb-1">স্থায়ী ঠিকানা</span>
                                <span class="font-bold text-gray-900">{{ $user->verificationDoc->permanent_address }}</span>
                            </div>
                            <div class="md:col-span-2 mt-2 pt-2 border-t border-gray-200"></div>
                            <div class="md:col-span-2">
                                <span class="block text-red-500 font-bold mb-2">জরুরী যোগাযোগ (Emergency Contact)</span>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                    <div>
                                        <span class="block text-gray-500 text-xs">নাম</span>
                                        <span class="font-bold text-gray-900">{{ $user->verificationDoc->emergency_contact_name }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-gray-500 text-xs">সম্পর্ক</span>
                                        <span class="font-bold text-gray-900">{{ $user->verificationDoc->emergency_contact_relation }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-gray-500 text-xs">ফোন</span>
                                        <span class="font-bold text-gray-900">{{ $user->verificationDoc->emergency_contact_phone }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8" x-data="{ imgOpen: false, currentImg: '' }">
                        
                        <div>
                            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">এনআইডি (সামনের অংশ)</h4>
                            <div class="bg-gray-100 rounded-xl overflow-hidden border border-gray-200 cursor-pointer hover:opacity-90 transition-opacity" @click="imgOpen = true; currentImg = '{{ Storage::url($user->verificationDoc->nid_front) }}'">
                                <img src="{{ Storage::url($user->verificationDoc->nid_front) }}" alt="NID Front" class="w-full h-auto max-h-[400px] object-contain bg-black/5">
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">এনআইডি (পেছনের অংশ)</h4>
                            <div class="bg-gray-100 rounded-xl overflow-hidden border border-gray-200 cursor-pointer hover:opacity-90 transition-opacity" @click="imgOpen = true; currentImg = '{{ Storage::url($user->verificationDoc->nid_back) }}'">
                                <img src="{{ Storage::url($user->verificationDoc->nid_back) }}" alt="NID Back" class="w-full h-auto max-h-[400px] object-contain bg-black/5">
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">সেলফি (NID সহ)</h4>
                            <div class="bg-gray-100 rounded-xl overflow-hidden border border-gray-200 cursor-pointer hover:opacity-90 transition-opacity" @click="imgOpen = true; currentImg = '{{ Storage::url($user->verificationDoc->selfie_with_nid) }}'">
                                <img src="{{ Storage::url($user->verificationDoc->selfie_with_nid) }}" alt="Selfie with NID" class="w-full h-auto max-h-[400px] object-contain bg-black/5">
                            </div>
                        </div>

                        {{-- Additional Certificates --}}
                        @if($user->verificationDoc->other_docs && count($user->verificationDoc->other_docs) > 0)
                            <div class="border-t border-gray-100 pt-8 mt-8">
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">অতিরিক্ত সনদ / স্কিল সার্টিফিকেট</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($user->verificationDoc->other_docs as $index => $docPath)
                                        @php
                                            $isPdf = Str::endsWith(strtolower($docPath), '.pdf');
                                        @endphp
                                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                            @if($isPdf)
                                                <div class="h-32 bg-gray-50 flex flex-col items-center justify-center border-b border-gray-100">
                                                    <svg class="w-10 h-10 text-red-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                    <span class="text-xs font-semibold text-gray-500">PDF Document</span>
                                                </div>
                                                <div class="p-3 bg-white text-center">
                                                    <a href="{{ Storage::url($docPath) }}" target="_blank" class="text-sm font-medium text-blue-600 hover:underline flex items-center justify-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        পিডিএফ দেখুন
                                                    </a>
                                                </div>
                                            @else
                                                <div class="h-40 bg-gray-100 overflow-hidden cursor-pointer" @click="imgOpen = true; currentImg = '{{ Storage::url($docPath) }}'">
                                                    <img src="{{ Storage::url($docPath) }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                                </div>
                                                <div class="p-2 bg-white text-center border-t border-gray-100">
                                                    <span class="text-xs font-medium text-gray-500">সার্টিফিকেট {{ $index + 1 }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Lightbox --}}
                        <div x-show="imgOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 p-4">
                            <button @click="imgOpen = false" class="absolute top-4 right-4 text-white hover:text-gray-300 p-2">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <img :src="currentImg" class="max-w-full max-h-[90vh] rounded-lg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
