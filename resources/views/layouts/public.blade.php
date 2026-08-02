<!DOCTYPE html>
<html lang="bn" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', setting('site_name', 'LocalEmployments')) — {{ setting('site_tagline', 'আপনার এলাকায়, আপনার মানুষ') }}</title>
    <meta name="description" content="@yield('meta_description', 'LocalEmployments — বাংলাদেশের সেরা লোকাল সার্ভিস মার্কেটপ্লেস। দক্ষ কর্মী খুঁজুন বা কাজ পান।')">

    <!-- Google Fonts: Hind Siliguri & Kalpurush -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">

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

    <!-- ───────────────────────── FOOTER ───────────────────────── -->
    <div class="hidden md:block">
        @include('components.public.footer')
    </div>

    <!-- ───────────────────────── MOBILE BOTTOM NAV ───────────────────────── -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-50 flex justify-around items-center h-16 px-2">
        <a href="{{ route('home') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('home') ? 'text-primary-600' : 'text-gray-500' }}">
            <svg class="w-6 h-6 mb-1" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span class="text-[10px] font-medium">Home</span>
        </a>
        <a href="{{ route('search') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('search') ? 'text-primary-600' : 'text-gray-500' }}">
            <svg class="w-6 h-6 mb-1" fill="{{ request()->routeIs('search') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <span class="text-[10px] font-medium">Search</span>
        </a>
        <a href="{{ route('login') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('login') ? 'text-primary-600' : 'text-gray-500' }}">
            <svg class="w-6 h-6 mb-1" fill="{{ request()->routeIs('login') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            <span class="text-[10px] font-medium">Orders</span>
        </a>
        <a href="{{ route('login') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('login') ? 'text-primary-600' : 'text-gray-500' }}">
            <svg class="w-6 h-6 mb-1" fill="{{ request()->routeIs('login') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span class="text-[10px] font-medium">Profile</span>
        </a>
    </div>

    @stack('scripts')
</body>
</html>
