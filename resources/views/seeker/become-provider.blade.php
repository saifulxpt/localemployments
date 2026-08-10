@extends('layouts.seeker')

@section('title', 'সার্ভিস প্রোভাইডার হিসেবে আবেদন করুন')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6">
    
    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-primary-800 via-primary-700 to-teal-800 text-white rounded-3xl p-8 mb-8 shadow-xl relative overflow-hidden">
        <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-8 translate-y-8">
            <svg class="w-72 h-72 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/></svg>
        </div>

        <div class="relative z-10 max-w-xl">
            <span class="inline-block bg-teal-500/20 text-teal-200 border border-teal-400/30 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-3">
                Professional Onboarding
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold mb-3 leading-tight">আপনার দক্ষতাকে আয়ে রূপান্তর করুন</h1>
            <p class="text-primary-100 text-base leading-relaxed">
                LocalEmployments-এ সার্ভিস প্রোভাইডার হিসেবে আবেদন করে আপনার এলাকার হাজারো ক্লায়েন্টের সাথে যুক্ত হোন। কোনো মধ্যস্বত্বভোগী নেই, নিজস্ব রেটে কাজ করুন।
            </p>
        </div>
    </div>

    {{-- Application Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-10">
        
        @if($errors->any())
            <div class="bg-red-50 text-red-700 p-4 rounded-xl text-sm font-medium flex items-start gap-3 border border-red-200 mb-8">
                <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="font-bold">আবেদনে কিছু ভুল রয়েছে:</p>
                    <p class="mt-1 text-sm">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('seeker.become-provider.store') }}" enctype="multipart/form-data" 
              x-data="{ step: 1, loading: false }" @submit="loading = true">
            @csrf

            {{-- Step Indicator --}}
            <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-base transition-colors"
                         :class="step === 1 ? 'bg-primary-600 text-white' : 'bg-emerald-100 text-emerald-700'">
                        <template x-if="step === 1"><span>১</span></template>
                        <template x-if="step === 2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </template>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">১. স্কিল ও সার্ভিস প্রোফাইল</h3>
                        <p class="text-xs text-gray-500">ক্যাটাগরি, কাজের অভিজ্ঞতা ও রেট</p>
                    </div>
                </div>

                <div class="h-0.5 w-12 sm:w-24 bg-gray-200"></div>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-base transition-colors"
                         :class="step === 2 ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-400'">
                        ২
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">২. জাতীয় পরিচয়পত্র ভেরিফিকেশন</h3>
                        <p class="text-xs text-gray-500">NID তথ্য ও সিকিউরিটি ডকুমেন্টস</p>
                    </div>
                </div>
            </div>

            {{-- ─────────────────────────────────────────
                 STEP 1: Skill & Service Info
                 ───────────────────────────────────────── --}}
            <div x-show="step === 1" x-transition>
                <div class="space-y-6">
                    
                    {{-- Service Subcategories --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">
                            আপনি যে যে বিষয়ে দক্ষ (কমপক্ষে ১টি সিলেক্ট করুন) <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-4 max-h-72 overflow-y-auto pr-2 border border-gray-200 rounded-xl p-4 bg-gray-50/50">
                            @foreach($categories as $cat)
                                <div>
                                    <h4 class="font-bold text-primary-800 text-xs uppercase tracking-wider mb-2 flex items-center gap-2">
                                        <span>{{ $cat->icon ?? '🛠️' }}</span>
                                        <span>{{ $cat->name }}</span>
                                    </h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pl-4">
                                        @foreach($cat->activeSubcategories as $sub)
                                            <label class="flex items-center gap-2.5 p-2 bg-white border border-gray-200 rounded-lg hover:border-primary-400 cursor-pointer transition-colors text-sm">
                                                <input type="checkbox" name="subcategories[]" value="{{ $sub->id }}" 
                                                       {{ is_array(old('subcategories')) && in_array($sub->id, old('subcategories')) ? 'checked' : '' }}
                                                       class="w-4 h-4 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
                                                <span class="font-medium text-gray-800">{{ $sub->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Bio --}}
                    <div>
                        <label for="bio" class="block text-sm font-bold text-gray-900 mb-2">
                            আপনার কাজের সংক্ষিপ্ত পরিচয় (Bio) <span class="text-red-500">*</span>
                        </label>
                        <textarea id="bio" name="bio" rows="3" required
                                  class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 text-sm font-medium"
                                  placeholder="আপনার অভিজ্ঞতা, কাজের ধরন ও সার্ভিস দেওয়ার মূল প্রতিশ্রুতি উল্লেখ করুন...">{{ old('bio') }}</textarea>
                    </div>

                    {{-- Experience & Rates --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="experience_years" class="block text-sm font-bold text-gray-900 mb-2">অভিজ্ঞতা (বছর)</label>
                            <input id="experience_years" type="number" name="experience_years" min="0" max="50" value="{{ old('experience_years', 1) }}" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 text-sm font-medium">
                        </div>

                        <div>
                            <label for="hourly_rate_min" class="block text-sm font-bold text-gray-900 mb-2">সর্বনিম্ন ঘণ্টা/কাজের রেট (৳)</label>
                            <input id="hourly_rate_min" type="number" name="hourly_rate_min" min="0" step="50" value="{{ old('hourly_rate_min', 300) }}" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 text-sm font-medium">
                        </div>

                        <div>
                            <label for="hourly_rate_max" class="block text-sm font-bold text-gray-900 mb-2">সর্বোচ্চ রেট (৳, অপশনাল)</label>
                            <input id="hourly_rate_max" type="number" name="hourly_rate_max" min="0" step="50" value="{{ old('hourly_rate_max') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 text-sm font-medium">
                        </div>
                    </div>

                </div>

                {{-- Next Step Button --}}
                <div class="mt-8 flex justify-end">
                    <button type="button" @click="step = 2"
                            class="px-8 py-3.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-lg shadow-primary-600/30 flex items-center gap-2 transition-all">
                        <span>পরবর্তী ধাপ (ভেরিফিকেশন)</span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>

            {{-- ─────────────────────────────────────────
                 STEP 2: NID & Verification Info
                 ───────────────────────────────────────── --}}
            <div x-show="step === 2" x-transition style="display: none;">
                <div class="space-y-6">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="full_name" class="block text-sm font-bold text-gray-900 mb-2">NID অনুযায়ী পূর্ণ নাম <span class="text-red-500">*</span></label>
                            <input id="full_name" type="text" name="full_name" value="{{ old('full_name', $user->name) }}" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 text-sm font-medium">
                        </div>

                        <div>
                            <label for="nid_number" class="block text-sm font-bold text-gray-900 mb-2">NID / জাতীয় পরিচয়পত্র নম্বর <span class="text-red-500">*</span></label>
                            <input id="nid_number" type="text" name="nid_number" value="{{ old('nid_number') }}" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 text-sm font-medium"
                                   placeholder="উদাঃ 1995123456789">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="dob" class="block text-sm font-bold text-gray-900 mb-2">জন্ম তারিখ <span class="text-red-500">*</span></label>
                            <input id="dob" type="date" name="dob" value="{{ old('dob') }}" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 text-sm font-medium">
                        </div>

                        <div>
                            <label for="father_name" class="block text-sm font-bold text-gray-900 mb-2">পিতার নাম</label>
                            <input id="father_name" type="text" name="father_name" value="{{ old('father_name') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 text-sm font-medium">
                        </div>

                        <div>
                            <label for="mother_name" class="block text-sm font-bold text-gray-900 mb-2">মাতার নাম</label>
                            <input id="mother_name" type="text" name="mother_name" value="{{ old('mother_name') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 text-sm font-medium">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="current_address" class="block text-sm font-bold text-gray-900 mb-2">বর্তমান ঠিকানা <span class="text-red-500">*</span></label>
                            <textarea id="current_address" name="current_address" rows="2" required
                                      class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 text-sm font-medium">{{ old('current_address', $user->address) }}</textarea>
                        </div>

                        <div>
                            <label for="permanent_address" class="block text-sm font-bold text-gray-900 mb-2">স্থায়ী ঠিকানা <span class="text-red-500">*</span></label>
                            <textarea id="permanent_address" name="permanent_address" rows="2" required
                                      class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 text-sm font-medium">{{ old('permanent_address') }}</textarea>
                        </div>
                    </div>

                    {{-- Emergency Contact --}}
                    <div class="bg-gray-50 border border-gray-200 p-4 rounded-xl">
                        <h4 class="font-bold text-gray-900 text-sm mb-3">জরুরি পরিচিতির তথ্য</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <input type="text" name="emergency_contact_name" placeholder="যোগাযোগের ব্যক্তির নাম" value="{{ old('emergency_contact_name') }}" required
                                   class="px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm">
                            <input type="text" name="emergency_contact_relation" placeholder="সম্পর্ক (উদাঃ ভাই/বাবা)" value="{{ old('emergency_contact_relation') }}" required
                                   class="px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm">
                            <input type="text" name="emergency_contact_phone" placeholder="ফোন নম্বর" value="{{ old('emergency_contact_phone') }}" required
                                   class="px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm">
                        </div>
                    </div>

                    {{-- Documents Upload --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">NID সামনের দিকের ছবি <span class="text-red-500">*</span></label>
                            <input type="file" name="nid_front" accept="image/*" required
                                   class="w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">NID পিছনের দিকের ছবি <span class="text-red-500">*</span></label>
                            <input type="file" name="nid_back" accept="image/*" required
                                   class="w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">NID হাতে ধরা সেলফি <span class="text-red-500">*</span></label>
                            <input type="file" name="selfie_with_nid" accept="image/*" required
                                   class="w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        </div>
                    </div>

                </div>

                {{-- Action Buttons --}}
                <div class="mt-8 flex items-center justify-between border-t border-gray-100 pt-6">
                    <button type="button" @click="step = 1"
                            class="px-6 py-3 border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-colors text-sm">
                        আগের ধাপে ফিরুন
                    </button>

                    <button type="submit" :disabled="loading"
                            class="px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 flex items-center gap-2 transition-all text-sm">
                        <template x-if="!loading"><span>আবেদন সাবমিট করুন</span></template>
                        <template x-if="loading"><span>প্রসেসিং হচ্ছে...</span></template>
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection
