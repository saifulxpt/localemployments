<!DOCTYPE html>
<html lang="bn" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', setting('site_name', 'LocalEmployments')) — {{ setting('site_tagline', 'আপনার এলাকায়, আপনার মানুষ') }}</title>
    <meta name="description" content="@yield('meta_description', 'LocalEmployments — বাংলাদেশের সেরা লোকাল সার্ভিস মার্কেটপ্লেস। উন্মুক্ত কাজ খুঁজুন বা কাজ পোস্ট করুন।')">

    <!-- Google & Bangla Fonts: Kalpurush, Noto Sans Bengali, Hind Siliguri, Anek Bangla -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anek+Bangla:wght@300;400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&family=Noto+Sans+Bengali:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/kalpurush-font@1.0.0/kalpurush.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col pb-16 md:pb-0">

    <!-- ───────────────────────── NAVBAR ───────────────────────── -->
    @include('components.public.navbar')

    <!-- ───────────────────────── FLASH ALERTS ───────────────────── -->
    @if(session('success') || session('error') || session('info') || session('warning'))
        <div class="container mx-auto px-4 pt-4" x-data="flashMessage()">
            <div x-show="show" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                @if(session('success'))
                    <div class="alert alert-success flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                @if(session('info'))
                    <div class="alert alert-info flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- ───────────────────────── MAIN CONTENT ───────────────────── -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- ───────────────────────── FOOTER (Desktop Only for pure App-like Mobile Feel) ───────────────────────── -->
    <div class="hidden md:block">
        @include('components.public.footer')
    </div>

    <!-- ───────────────────────── MOBILE BOTTOM NAV (App-like 5-Tab Bar) ───────────────────────── -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-[0_-4px_25px_rgba(0,0,0,0.08)] z-50 flex justify-around items-center h-16 px-1 pb-[env(safe-area-inset-bottom)]">
        
        {{-- 1. Home --}}
        <a href="{{ route('home') }}" class="flex flex-col items-center justify-center flex-1 h-full py-1 {{ request()->routeIs('home') ? 'text-primary-600 font-bold' : 'text-gray-500 hover:text-primary-600' }} transition-colors">
            <svg class="w-5 h-5 mb-1" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('home') ? '0' : '2' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-[10px] tracking-tight">হোম</span>
        </a>

        {{-- 2. Browse Jobs --}}
        <a href="{{ route('jobs.index') }}" class="flex flex-col items-center justify-center flex-1 h-full py-1 {{ request()->routeIs('jobs.*') && !request()->routeIs('jobs.post') ? 'text-primary-600 font-bold' : 'text-gray-500 hover:text-primary-600' }} transition-colors">
            <svg class="w-5 h-5 mb-1" fill="{{ request()->routeIs('jobs.*') && !request()->routeIs('jobs.post') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('jobs.*') && !request()->routeIs('jobs.post') ? '0' : '2' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span class="text-[10px] tracking-tight">কাজের তালিকা</span>
        </a>

        {{-- 3. Center Elevated Post Job (+) Button --}}
        <div class="relative -top-4 flex flex-col items-center justify-center flex-1">
            <a href="{{ route('jobs.post') }}" class="w-12 h-12 bg-primary-700 hover:bg-primary-800 text-white rounded-full flex items-center justify-center shadow-lg shadow-primary-900/30 border-2 border-white ring-2 ring-primary-100 transform active:scale-95 hover:scale-105 transition-all">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </a>
            <span class="text-[10px] font-bold text-primary-800 mt-0.5">পোস্ট</span>
        </div>

        {{-- 4. My Jobs (Smart Tab for Seeker / Provider / Guest) --}}
        <a href="{{ route('my-jobs') }}" class="flex flex-col items-center justify-center flex-1 h-full py-1 {{ request()->routeIs('my-jobs') || request()->routeIs('seeker.job-requests.*') || request()->routeIs('provider.bids.*') ? 'text-primary-600 font-bold' : 'text-gray-500 hover:text-primary-600' }} transition-colors">
            <svg class="w-5 h-5 mb-1" fill="{{ request()->routeIs('my-jobs') || request()->routeIs('seeker.job-requests.*') || request()->routeIs('provider.bids.*') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('my-jobs') || request()->routeIs('seeker.job-requests.*') || request()->routeIs('provider.bids.*') ? '0' : '2' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            <span class="text-[10px] tracking-tight">আমার কাজ</span>
        </a>

        {{-- 5. Account / Dashboard / Login --}}
        @auth
            @php
                $dashRoute = auth()->user()->isSeeker() ? route('seeker.dashboard') : (auth()->user()->isProvider() ? route('provider.dashboard') : route('admin.dashboard'));
                $isDashActive = request()->routeIs('seeker.dashboard') || request()->routeIs('provider.dashboard') || request()->routeIs('admin.dashboard');
            @endphp
            <a href="{{ $dashRoute }}" class="flex flex-col items-center justify-center flex-1 h-full py-1 {{ $isDashActive ? 'text-primary-600 font-bold' : 'text-gray-500 hover:text-primary-600' }} transition-colors">
                @if(auth()->user()->avatar_url)
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-5 h-5 rounded-full object-cover mb-1 ring-1 ring-primary-500">
                @else
                    <svg class="w-5 h-5 mb-1" fill="{{ $isDashActive ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ $isDashActive ? '0' : '2' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                @endif
                <span class="text-[10px] tracking-tight">অ্যাকাউন্ট</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center justify-center flex-1 h-full py-1 {{ request()->routeIs('login') ? 'text-primary-600 font-bold' : 'text-gray-500 hover:text-primary-600' }} transition-colors">
                <svg class="w-5 h-5 mb-1" fill="{{ request()->routeIs('login') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('login') ? '0' : '2' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                <span class="text-[10px] tracking-tight">লগইন</span>
            </a>
        @endauth
    </nav>

    @stack('scripts')
</body>
</html>
