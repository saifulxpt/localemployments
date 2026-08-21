<footer class="bg-gray-900 text-gray-300 mt-auto">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

            {{-- Brand --}}
            <div class="md:col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    @if(setting('site_logo'))
                        <img src="{{ asset(ltrim(setting('site_logo'), '/')) }}" alt="{{ setting('site_name', 'Logo') }}" class="h-10 max-w-[220px] object-contain">
                    @else
                        <div class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-white text-lg">{{ setting('site_name', 'LocalEmployments') }}</span>
                    @endif
                </div>
                <p class="text-sm text-gray-400 leading-relaxed">
                    বাংলাদেশের সেরা লোকাল সার্ভিস মার্কেটপ্লেস। আপনার এলাকায় উন্মুক্ত কাজ খুঁজুন বা নতুন কাজ পোস্ট করুন।
                </p>
                <div class="flex gap-3 mt-5">
                    <a href="{{ setting('social_facebook', '#') }}" target="_blank" class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-colors" aria-label="Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                    </a>
                    <a href="{{ setting('social_youtube', '#') }}" target="_blank" class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-colors" aria-label="YouTube">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 00-1.95 1.96A29 29 0 001 12a29 29 0 00.46 5.58A2.78 2.78 0 003.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.4a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Links Wrapper for side-by-side on mobile --}}
            <div class="grid grid-cols-2 gap-8 md:col-span-2">
                {{-- Services --}}
                <div>
                    <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">কাজ ও সেবা</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('jobs.index') }}" class="text-gray-400 hover:text-primary-400 transition-colors">উন্মুক্ত কাজসমূহ</a></li>
                        <li><a href="{{ route('services.index') }}" class="text-gray-400 hover:text-primary-400 transition-colors">সকল সেবাসমূহ</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-primary-400 transition-colors">কাজ পোস্ট করুন</a></li>
                        <li><a href="{{ route('register') }}?role=provider" class="text-gray-400 hover:text-primary-400 transition-colors">প্রোভাইডার হিসেবে যুক্ত হোন</a></li>
                    </ul>
                </div>

                {{-- Company --}}
                <div>
                    <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">কোম্পানি</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-primary-400 transition-colors">আমাদের সম্পর্কে</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-primary-400 transition-colors">যোগাযোগ করুন</a></li>
                        <li><a href="{{ route('privacy') }}" class="text-gray-400 hover:text-primary-400 transition-colors">প্রাইভেসি পলিসি</a></li>
                        <li><a href="{{ route('terms') }}" class="text-gray-400 hover:text-primary-400 transition-colors">শর্তাবলী</a></li>
                    </ul>
                </div>

                {{-- Helpful Links --}}
                <div>
                    <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">গুরুত্বপূর্ণ লিঙ্ক</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('faq') }}" class="text-gray-400 hover:text-primary-400 transition-colors">সাধারণ প্রশ্ন (FAQ)</a></li>
                        <li><a href="{{ route('jobs.index') }}" class="text-gray-400 hover:text-primary-400 transition-colors">কাজের তালিকা</a></li>
                        <li><a href="{{ route('services.index') }}" class="text-gray-400 hover:text-primary-400 transition-colors">সকল ক্যাটাগরি</a></li>
                    </ul>
                </div>
            </div>

            <div>
                <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">Contact</h4>
                <ul class="space-y-3 text-sm text-gray-400">
                    @if(setting('contact_phone'))
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <a href="tel:{{ setting('contact_phone') }}" class="hover:text-primary-400 transition-colors">{{ setting('contact_phone') }}</a>
                        </li>
                    @else
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <a href="{{ route('contact') }}" class="hover:text-primary-400 transition-colors">যোগাযোগ করুন</a>
                        </li>
                    @endif
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-primary-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ setting('contact_address', 'ঢাকা, বাংলাদেশ') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="border-t border-gray-800">
        <div class="container mx-auto px-4 py-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-gray-500">
            <p>© {{ date('Y') }} LocalEmployments. সর্বস্বত্ব সংরক্ষিত।</p>
            <p>বাংলাদেশে তৈরি 🇧🇩</p>
        </div>
    </div>
</footer>
