@extends('layouts.public')

@section('title', 'আমাদের সম্পর্কে — LocalEmployments')
@section('meta_description', 'LocalEmployments সম্পর্কে বিস্তারিত জানুন। বাংলাদেশের সবচেয়ে বিশ্বস্ত লোকাল সার্ভিস মার্কেটপ্লেস।')

@section('content')
<div class="bg-gray-50/60 py-12 md:py-20">
    <div class="container mx-auto px-4 max-w-6xl">
        
        {{-- Hero Header with Brand Teal Background --}}
        <div class="relative overflow-hidden rounded-3xl p-8 md:p-14 mb-12 shadow-2xl text-white" style="background: linear-gradient(135deg, #0f766e 0%, #0d9488 50%, #115e59 100%) !important;">
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full blur-3xl pointer-events-none" style="background: rgba(255, 255, 255, 0.12);"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full blur-3xl pointer-events-none" style="background: rgba(52, 211, 153, 0.15);"></div>

            <div class="grid lg:grid-cols-2 gap-10 items-center relative z-10">
                <div>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold mb-6" style="background: rgba(255, 255, 255, 0.18); color: #ffffff !important; border: 1px solid rgba(255, 255, 255, 0.3);">
                        <span class="w-2 h-2 rounded-full animate-pulse" style="background: #34d399;"></span>
                        আমাদের কথা ও স্বপ্ন
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black leading-tight tracking-tight mb-6" style="color: #ffffff !important;">
                        স্থানীয় দক্ষতার সাথে <br>
                        <span style="color: #a7f3d0 !important;">স্মার্ট সেতুবন্ধন</span>
                    </h1>
                    <p class="text-base md:text-lg leading-relaxed mb-8 font-semibold" style="color: #ffffff !important;">
                        LocalEmployments হলো বাংলাদেশের স্থানীয় কাজের সহজ ও বিশ্বস্ত প্ল্যাটফর্ম। দৈনন্দিন গৃহস্থালি কাজ থেকে শুরু করে টেকনিক্যাল সাপোর্ট—সবকিছুর জন্য সঠিক সময়ে নির্ভরযোগ্য দক্ষ কর্মী খুঁজে পাওয়ার ডিজিটাল সমাধান।
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('register') }}" class="btn px-7 py-3.5 font-bold text-sm shadow-lg" style="background: #ffffff !important; color: #0f766e !important;">আজই যুক্ত হোন</a>
                        <a href="{{ route('jobs.index') }}" class="btn px-7 py-3.5 font-bold text-sm" style="background: rgba(255, 255, 255, 0.18) !important; color: #ffffff !important; border: 1px solid rgba(255, 255, 255, 0.3);">কাজগুলো দেখুন</a>
                    </div>
                </div>

                {{-- Hero Visual Card / Infographic Illustration (Pure White High-Contrast Box) --}}
                <div class="relative">
                    <div class="rounded-3xl p-6 md:p-8 shadow-2xl border border-gray-100" style="background: #ffffff !important; color: #111827 !important;">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-black text-lg">✓</div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-base" style="color: #111827 !important;">১০০% ভেরিফাইড কর্মী</h4>
                                    <p class="text-xs text-gray-600 font-bold" style="color: #4b5563 !important;">জাতীয় পরিচয়পত্র (NID) যাচাইকৃত</p>
                                </div>
                            </div>
                            <span class="text-xs font-black text-emerald-800 bg-emerald-100 border border-emerald-300 px-3 py-1 rounded-lg">সুরক্ষিত</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-center py-4">
                            <div class="bg-teal-50 rounded-2xl p-4 border border-teal-200">
                                <div class="text-3xl font-black text-teal-800" style="color: #0f766e !important;">৬৪+</div>
                                <div class="text-xs text-gray-800 font-bold mt-1" style="color: #1f2937 !important;">জেলায় সেবা</div>
                            </div>
                            <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-200">
                                <div class="text-3xl font-black text-emerald-800" style="color: #065f46 !important;">২৪/৭</div>
                                <div class="text-xs text-gray-800 font-bold mt-1" style="color: #1f2937 !important;">গ্রাহক সাপোর্ট</div>
                            </div>
                        </div>

                        <div class="p-3.5 bg-gray-100 rounded-2xl border border-gray-300 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl text-white flex items-center justify-center font-bold text-sm shrink-0" style="background: #0f766e !important; color: #ffffff !important;">৳</div>
                            <div class="text-xs text-gray-800 font-semibold leading-relaxed" style="color: #1f2937 !important;">
                                <strong class="font-black" style="color: #111827 !important;">স্বচ্ছ পারিশ্রমিক:</strong> মধ্যস্বত্বভোগী ছাড়াই সরাসরি কর্মীর সাথে চুক্তি।
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Infographic Features Grid --}}
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-primary-800 font-black uppercase text-xs tracking-widest bg-teal-50 px-3.5 py-1.5 rounded-lg border border-teal-200" style="color: #0f766e !important;">কেন আমরা সেরা?</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mt-4">আমাদের ইনফোগ্রাফিক ও মূল বৈশিষ্ট্যসমূহ</h2>
            <p class="text-gray-600 text-sm md:text-base mt-2 font-medium">আমরা নিশ্চিত করি সেবাগ্রহীতা ও সেবা প্রদানকারী উভয়ের নিরাপদ ভবিষ্যৎ</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 mb-16">
            {{-- Feature 1 --}}
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-md transition-all group">
                <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-800 flex items-center justify-center mb-6 group-hover:bg-teal-700 group-hover:text-white transition-all">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">NID ভেরিফিকেশন সিস্টেম</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    নিরাপত্তা আমাদের সর্বোচ্চ অগ্রাধিকার। প্রতিটি প্রোভাইডারের এনআইডি কার্ড ও তথ্যাদি এডমিন প্যানেল থেকে ম্যানুয়ালি যাচাই করা হয়।
                </p>
            </div>

            {{-- Feature 2 --}}
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-md transition-all group">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-800 flex items-center justify-center mb-6 group-hover:bg-emerald-700 group-hover:text-white transition-all">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">ন্যায্য মূল্য ও কোনো হিডেন ফি নেই</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    সরাসরি বিডিংয়ের মাধ্যমে কাজের সঠিক মূল্য নির্ধারিত হয়। অতিরিক্ত কোনো হিডেন চার্জ বা অন্যায্য কমিশন দিতে হয় না।
                </p>
            </div>

            {{-- Feature 3 --}}
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-md transition-all group">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-6 group-hover:bg-amber-500 group-hover:text-white transition-all">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">স্বচ্ছ রেটিং ও কাস্টমার রিভিউ</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    কাজ সম্পন্ন করার পর আসল গ্রাহকদের দেওয়া রেটিং ও রিভিউ দেখে কর্মীদের অভিজ্ঞতা সহজেই যাচাই করা যায়।
                </p>
            </div>
        </div>

        {{-- Mission & Vision Visual Cards --}}
        <div class="bg-white rounded-3xl p-8 md:p-12 border border-gray-100 shadow-sm mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">আমাদের মূল লক্ষ্য ও দৃষ্টিভঙ্গি</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="p-6 bg-teal-50/80 rounded-2xl border border-teal-200">
                    <div class="w-12 h-12 bg-teal-700 text-white rounded-xl flex items-center justify-center font-bold text-xl mb-4">🎯</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">সেবাগ্রহীতাদের জন্য</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        হাতে থাকা সময়ে যেকোনো পারিবারিক বা ব্যবসায়িক প্রয়োজনে বিশ্বস্ত কর্মী দ্রুত খুঁজে পাওয়া এবং নিশ্চিন্তে কাজ সম্পন্ন করা।
                    </p>
                </div>
                <div class="p-6 bg-emerald-50/80 rounded-2xl border border-emerald-200">
                    <div class="w-12 h-12 bg-emerald-700 text-white rounded-xl flex items-center justify-center font-bold text-xl mb-4">💼</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">পেশাদার কর্মীদের জন্য</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        নিজের এলাকাতেই নিয়মিত কাজের সুযোগ তৈরি করা, নিজস্ব পারিশ্রমিক নির্ধারণ এবং আর্থিক স্বাধীনতা অর্জন করা।
                    </p>
                </div>
            </div>
        </div>

        {{-- CTA Banner --}}
        <div class="text-center rounded-3xl p-8 md:p-12 shadow-lg text-white" style="background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%) !important;">
            <h2 class="text-2xl md:text-3xl font-extrabold mb-4" style="color: #ffffff !important;">আজই যুক্ত হোন আমাদের সাথে</h2>
            <p class="text-sm md:text-base max-w-xl mx-auto mb-8 font-medium" style="color: #e6fffa !important;">
                আপনার প্রয়োজন যাই হোক না কেন, সমাধান আমাদের কাছেই আছে।
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="btn px-8 py-3.5 font-bold text-sm shadow-md" style="background: #ffffff !important; color: #0f766e !important;">কাজ পোস্ট করুন</a>
                <a href="{{ route('register') }}?role=provider" class="btn px-8 py-3.5 font-bold text-sm shadow-md" style="background: #042f2e !important; color: #ffffff !important; border: 1px solid rgba(255, 255, 255, 0.3);">কর্মী হিসেবে যুক্ত হোন</a>
            </div>
        </div>

    </div>
</div>
@endsection
