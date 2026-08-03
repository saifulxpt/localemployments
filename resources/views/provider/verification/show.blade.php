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
                
                {{-- Personal Info Section --}}
                <div class="border-b border-gray-100 pb-6 mb-6">
                    <h3 class="font-bold text-lg text-gray-900 mb-4">ব্যক্তিগত তথ্য</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-1">পুরো নাম (NID অনুযায়ী) <span class="text-red-500">*</span></label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" required class="form-input w-full rounded-xl" placeholder="আপনার পুরো নাম">
                            @error('full_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-1">জাতীয় পরিচয়পত্র (NID) নম্বর <span class="text-red-500">*</span></label>
                            <input type="text" name="nid_number" value="{{ old('nid_number') }}" required class="form-input w-full rounded-xl" placeholder="১০ বা ১৭ ডিজিটের NID নম্বর">
                            @error('nid_number') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-1">জন্ম তারিখ <span class="text-red-500">*</span></label>
                            <input type="date" name="dob" value="{{ old('dob') }}" required class="form-input w-full rounded-xl">
                            @error('dob') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-1">পিতার নাম</label>
                            <input type="text" name="father_name" value="{{ old('father_name') }}" class="form-input w-full rounded-xl" placeholder="পিতার নাম">
                        </div>
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-1">মাতার নাম</label>
                            <input type="text" name="mother_name" value="{{ old('mother_name') }}" class="form-input w-full rounded-xl" placeholder="মাতার নাম">
                        </div>
                    </div>
                </div>

                {{-- Address Section --}}
                <div class="border-b border-gray-100 pb-6 mb-6">
                    <h3 class="font-bold text-lg text-gray-900 mb-4">ঠিকানা</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-1">বর্তমান ঠিকানা <span class="text-red-500">*</span></label>
                            <textarea name="current_address" required rows="3" class="form-input w-full rounded-xl" placeholder="বাড়ি নং, রাস্তা, এলাকা, থানা, জেলা">{{ old('current_address') }}</textarea>
                            @error('current_address') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-1">স্থায়ী ঠিকানা <span class="text-red-500">*</span></label>
                            <textarea name="permanent_address" required rows="3" class="form-input w-full rounded-xl" placeholder="বাড়ি নং, রাস্তা, এলাকা, থানা, জেলা">{{ old('permanent_address') }}</textarea>
                            @error('permanent_address') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- Emergency Contact --}}
                <div class="border-b border-gray-100 pb-6 mb-6">
                    <h3 class="font-bold text-lg text-gray-900 mb-4">জরুরী যোগাযোগ (Emergency Contact)</h3>
                    <p class="text-sm text-gray-500 mb-4">আপনার অবর্তমানে বা জরুরী প্রয়োজনে যোগাযোগ করার জন্য পরিবারের একজন সদস্যের (বাবা/মা/ভাই/স্বামী/স্ত্রী) তথ্য দিন।</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-1">নাম <span class="text-red-500">*</span></label>
                            <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" required class="form-input w-full rounded-xl" placeholder="সম্পূর্ণ নাম">
                            @error('emergency_contact_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-1">সম্পর্ক <span class="text-red-500">*</span></label>
                            <input type="text" name="emergency_contact_relation" value="{{ old('emergency_contact_relation') }}" required class="form-input w-full rounded-xl" placeholder="যেমন: বাবা / ভাই">
                            @error('emergency_contact_relation') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-1">ফোন নম্বর <span class="text-red-500">*</span></label>
                            <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" required class="form-input w-full rounded-xl" placeholder="01XXX-XXXXXX">
                            @error('emergency_contact_phone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- Documents --}}
                <div>
                    <h3 class="font-bold text-lg text-gray-900 mb-4">নথিপত্র (Documents)</h3>
                    <div class="space-y-6">
                        {{-- NID Front --}}
                        <div class="flex flex-col md:flex-row gap-6 items-center">
                            <div class="w-full md:w-1/3">
                                <h3 class="font-bold text-gray-800 text-sm mb-1">NID এর সামনের অংশ <span class="text-red-500">*</span></h3>
                                <p class="text-xs text-gray-500">জাতীয় পরিচয়পত্রের সামনের দিকের স্পষ্ট ছবি।</p>
                            </div>
                            <div class="w-full md:w-2/3">
                                <input type="file" name="nid_front" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" accept="image/*">
                                @error('nid_front') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- NID Back --}}
                        <div class="flex flex-col md:flex-row gap-6 items-center">
                            <div class="w-full md:w-1/3">
                                <h3 class="font-bold text-gray-800 text-sm mb-1">NID এর পেছনের অংশ <span class="text-red-500">*</span></h3>
                                <p class="text-xs text-gray-500">জাতীয় পরিচয়পত্রের পেছনের দিকের স্পষ্ট ছবি।</p>
                            </div>
                            <div class="w-full md:w-2/3">
                                <input type="file" name="nid_back" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" accept="image/*">
                                @error('nid_back') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Selfie with NID --}}
                        <div class="flex flex-col md:flex-row gap-6 items-center">
                            <div class="w-full md:w-1/3">
                                <h3 class="font-bold text-gray-800 text-sm mb-1">NID সহ নিজস্ব ছবি (সেলফি) <span class="text-red-500">*</span></h3>
                                <p class="text-xs text-gray-500">NID কার্ডটি আপনার চেহারার পাশে ধরে একটি পরিষ্কার ছবি তুলুন।</p>
                            </div>
                            <div class="w-full md:w-2/3">
                                <input type="file" name="selfie_with_nid" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" accept="image/*">
                                @error('selfie_with_nid') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Additional Certificates Section --}}
                <div class="border-t border-gray-100 pt-6">
                    <h3 class="font-bold text-lg text-gray-900 mb-4">পেশাগত সনদ / স্কিল সার্টিফিকেট (ঐচ্ছিক)</h3>
                    <div class="flex flex-col md:flex-row gap-6 items-start">
                        <div class="w-full md:w-1/3">
                            <h3 class="font-bold text-gray-800 text-sm mb-1">সার্টিফিকেট / ট্রেড লাইসেন্স</h3>
                            <p class="text-xs text-gray-500">আপনার পেশার উপর কোনো প্রশিক্ষণ বা সনদ থাকলে তা আপলোড করতে পারেন। এটি আপনার প্রোফাইলের বিশ্বাসযোগ্যতা বাড়াবে। (একাধিক ফাইল আপলোড করতে পারবেন)</p>
                        </div>
                        <div class="w-full md:w-2/3">
                            <input type="file" name="certificates[]" multiple class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" accept="image/*,.pdf">
                            <p class="text-xs text-gray-400 mt-2">ফাইল ফরম্যাট: JPG, PNG, PDF. সর্বোচ্চ ৫MB প্রতি ফাইল।</p>
                            @error('certificates.*') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
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
