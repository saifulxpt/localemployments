<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — প্রোভাইডার প্যানেল</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anek+Bangla:wght@300;400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&family=Noto+Sans+Bengali:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/kalpurush-font@1.0.0/kalpurush.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased" x-data="{ mobileMenuOpen: false }">

    {{-- Mobile Top Header (App Bar) --}}
    <div class="lg:hidden bg-gray-900 text-white px-4 py-3 flex items-center justify-between sticky top-0 z-40 shadow-md">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <div class="w-7 h-7 bg-primary-600 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="font-bold text-lg tracking-tight">LocalEmployments</span>
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('provider.messages.index') }}" class="relative p-2 text-gray-300 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                @php $unreadCount = auth()->user()->receivedMessages()->where('is_read', false)->count(); @endphp
                @if($unreadCount > 0)
                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-gray-900"></span>
                @endif
            </a>
            <a href="{{ route('provider.profile.edit') }}">
                <img src="{{ auth()->user()->avatar_url }}" alt="" class="w-8 h-8 rounded-full object-cover ring-2 ring-gray-700 shadow-sm">
            </a>
        </div>
    </div>

    <div class="flex h-screen overflow-hidden">
        
        {{-- Desktop Sidebar --}}
        <aside class="hidden lg:flex flex-col w-64 bg-gray-900 text-white shadow-xl z-30 shrink-0 border-r border-gray-800">
            {{-- Logo --}}
            <div class="h-16 flex items-center px-6 border-b border-gray-800 shrink-0">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-xl hover:opacity-80 transition-opacity">
                    <div class="w-8 h-8 bg-primary-600 rounded-xl shadow-md shadow-primary-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span>LocalEmployments</span>
                </a>
            </div>

            @php
                $activeNav = 'bg-primary-600/90 text-white shadow-md shadow-primary-900/20';
                $inactiveNav = 'text-gray-400 hover:bg-gray-800 hover:text-white';
            @endphp

            {{-- Nav Links --}}
            <div class="flex-1 overflow-y-auto px-4 py-4 space-y-1 custom-scrollbar">
                <a href="{{ route('provider.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all font-medium {{ active_class('provider.dashboard', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('provider.dashboard', 'text-white', 'text-gray-500') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    ড্যাশবোর্ড
                </a>

                <div class="pt-3 pb-1">
                    <a href="{{ route('seeker.dashboard') }}" class="flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-gray-800 hover:bg-gray-700 text-teal-400 font-bold border border-gray-700 transition-all text-xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        <span>কাজ পোস্ট করুন (Seeker Mode)</span>
                    </a>
                </div>
                
                <div class="pt-5 pb-2">
                    <p class="px-3 text-xs font-bold text-gray-500 uppercase tracking-wider">কাজের ক্ষেত্র</p>
                </div>
                <a href="{{ route('provider.jobs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all font-medium {{ active_class('provider.jobs.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('provider.jobs.*', 'text-white', 'text-gray-500') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    কাজ খুঁজুন
                </a>
                <a href="{{ route('provider.bids.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all font-medium {{ active_class('provider.bids.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('provider.bids.*', 'text-white', 'text-gray-500') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    আমার বিডসমূহ
                </a>
                <a href="{{ route('provider.bookings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all font-medium {{ active_class('provider.bookings.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('provider.bookings.*', 'text-white', 'text-gray-500') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    বুকিং ও কাজ
                </a>
                <a href="{{ route('provider.messages.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all font-medium {{ active_class('provider.messages.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('provider.messages.*', 'text-white', 'text-gray-500') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    মেসেজ
                </a>
                <a href="{{ route('provider.services.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all font-medium {{ active_class('provider.services.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('provider.services.*', 'text-white', 'text-gray-500') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    আমার সার্ভিস
                </a>

                <div class="pt-5 pb-2">
                    <p class="px-3 text-xs font-bold text-gray-500 uppercase tracking-wider">প্রোফাইল ও আয়</p>
                </div>
                <a href="{{ route('provider.earnings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all font-medium {{ active_class('provider.earnings.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('provider.earnings.*', 'text-white', 'text-gray-500') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    আমার আয়
                </a>
                <a href="{{ route('provider.withdrawals.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all font-medium {{ active_class('provider.withdrawals.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('provider.withdrawals.*', 'text-white', 'text-gray-500') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    টাকা উত্তোলন
                </a>
                <a href="{{ route('provider.skills.manage') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all font-medium {{ active_class('provider.skills.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('provider.skills.*', 'text-white', 'text-gray-500') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    দক্ষতাসমূহ
                </a>
                <a href="{{ route('provider.profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all font-medium {{ active_class('provider.profile.edit', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('provider.profile.edit', 'text-white', 'text-gray-500') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    প্রোফাইল সেটিংস
                </a>
                @if(!auth()->user()->providerProfile?->is_verified)
                <a href="{{ route('provider.verification.show') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all font-medium {{ active_class('provider.verification.*', 'bg-yellow-600 text-white', 'text-yellow-500 hover:bg-gray-800') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    ভেরিফিকেশন পেন্ডিং
                </a>
                @endif
            </div>

            {{-- Logout --}}
            <div class="p-4 border-t border-gray-800 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800 transition-colors w-full font-medium">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        লগআউট
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 flex flex-col min-w-0 bg-gray-50 overflow-y-auto pb-20 lg:pb-0 relative">
            
            {{-- Topbar Desktop --}}
            <header class="hidden lg:flex h-16 bg-white border-b border-gray-200 items-center justify-between px-8 sticky top-0 z-30">
                <div class="font-bold text-gray-900 text-lg">@yield('title')</div>
                
                <div class="flex items-center gap-5">
                    {{-- Notification Bell Component --}}
                    <div x-data="notificationBell()" class="relative">
                        <button @click="toggle()" class="relative p-2 text-gray-400 hover:text-primary-600 hover:bg-gray-50 rounded-full transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span x-show="count > 0" x-text="count" class="absolute top-1 right-1 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center"></span>
                        </button>
                        
                        {{-- Dropdown --}}
                        <div x-show="open" x-cloak @click.outside="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50">
                            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
                                <h3 class="font-bold text-gray-900 text-sm">বিজ্ঞপ্তি</h3>
                                <button @click="markAllRead()" class="text-xs font-semibold text-primary-600 hover:text-primary-800">সব পড়া হয়েছে</button>
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                <template x-for="n in notifications" :key="n.id">
                                    <a :href="n.url || '#'" class="flex items-start gap-4 px-5 py-4 hover:bg-gray-50 border-b border-gray-50 transition-colors" :class="{ 'bg-primary-50/50': !n.is_read }">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate" x-text="n.title"></p>
                                            <p class="text-xs text-gray-500 mt-1 line-clamp-2 leading-relaxed" x-text="n.message"></p>
                                        </div>
                                    </a>
                                </template>
                                <div x-show="notifications.length === 0" class="px-5 py-10 text-center text-gray-400 text-sm font-medium">কোনো বিজ্ঞপ্তি নেই</div>
                            </div>
                        </div>
                    </div>

                    <div class="w-px h-6 bg-gray-200"></div>
                    
                    <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-primary-600 font-semibold transition-colors">ওয়েবসাইট দেখুন</a>
                    
                    <a href="{{ route('provider.profile.edit') }}" class="flex items-center">
                        <img src="{{ auth()->user()->avatar_url }}" alt="" class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100 shadow-sm transition-transform hover:scale-105">
                    </a>
                </div>
            </header>

            {{-- Flash Messages --}}
            @if(session('success') || session('error'))
                <div class="px-4 lg:px-8 pt-6 pb-0" x-data="flashMessage()">
                    <div x-show="show" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                        @if(session('success'))
                            <div class="bg-green-50 text-green-700 p-4 rounded-xl shadow-sm border border-green-100 font-medium text-sm">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="bg-red-50 text-red-700 p-4 rounded-xl shadow-sm border border-red-100 font-medium text-sm">{{ session('error') }}</div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Content Area --}}
            <div class="p-4 lg:p-8 flex-1">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- Mobile Bottom Navigation Bar (4 items for better spacing) --}}
    <nav class="lg:hidden fixed bottom-0 w-full bg-white/90 backdrop-blur-lg border-t border-gray-100 flex items-center justify-around pb-safe pt-2 z-40 shadow-[0_-4px_20px_-10px_rgba(0,0,0,0.1)]">
        
        {{-- Home --}}
        <a href="{{ route('provider.dashboard') }}" class="flex flex-col items-center gap-1 p-2 flex-1 {{ request()->routeIs('provider.dashboard') ? 'text-primary-600' : 'text-gray-400' }}">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('provider.dashboard') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('provider.dashboard') ? '0' : '2' }}"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span class="text-[11px] font-medium tracking-wide">হোম</span>
        </a>

        {{-- Find Jobs --}}
        <a href="{{ route('provider.jobs.index') }}" class="flex flex-col items-center gap-1 p-2 flex-1 {{ request()->routeIs('provider.jobs.*') ? 'text-primary-600' : 'text-gray-400' }}">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('provider.jobs.*') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('provider.jobs.*') ? '0' : '2' }}"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span class="text-[11px] font-medium tracking-wide">কাজ খুঁজুন</span>
        </a>

        {{-- Bookings --}}
        <a href="{{ route('provider.bookings.index') }}" class="flex flex-col items-center gap-1 p-2 flex-1 {{ request()->routeIs('provider.bookings.*') ? 'text-primary-600' : 'text-gray-400' }}">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('provider.bookings.*') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('provider.bookings.*') ? '0' : '2' }}"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            <span class="text-[11px] font-medium tracking-wide">বুকিং</span>
        </a>

        {{-- Menu Toggle --}}
        <button @click="mobileMenuOpen = true" class="flex flex-col items-center gap-1 p-2 flex-1 text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <span class="text-[11px] font-medium tracking-wide">মেনু</span>
        </button>
    </nav>

    {{-- Mobile Full Screen Menu Overlay --}}
    <div x-show="mobileMenuOpen" x-cloak class="lg:hidden fixed inset-0 z-50 flex flex-col justify-end bg-gray-900/60 backdrop-blur-sm">
        
        {{-- Close backdrop area --}}
        <div class="flex-1" @click="mobileMenuOpen = false"></div>

        {{-- Menu Sheet --}}
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="bg-white rounded-t-3xl shadow-2xl overflow-hidden max-h-[85vh] flex flex-col w-full">
            
            {{-- Handle/Drag indicator --}}
            <div class="w-full flex justify-center py-3 bg-white shrink-0 border-b border-gray-100" @click="mobileMenuOpen = false">
                <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
            </div>

            {{-- Profile summary in menu --}}
            <div class="px-6 py-4 bg-primary-50/50 flex items-center gap-4 shrink-0">
                <img src="{{ auth()->user()->avatar_url }}" alt="" class="w-12 h-12 rounded-full object-cover ring-2 ring-white shadow-sm">
                <div>
                    <h3 class="font-bold text-gray-900">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-gray-500">{{ auth()->user()->phone }}</p>
                </div>
            </div>

            <div class="overflow-y-auto px-4 py-4 space-y-1 pb-safe bg-white flex-1 custom-scrollbar">
                
                {{-- App Bar Navigation Duplicates for ease --}}
                <a href="{{ route('provider.bids.index') }}" class="flex items-center gap-4 p-3 rounded-xl text-gray-700 hover:bg-gray-50 font-medium">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    আমার বিডসমূহ
                </a>
                
                <a href="{{ route('provider.services.index') }}" class="flex items-center gap-4 p-3 rounded-xl text-gray-700 hover:bg-gray-50 font-medium">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    আমার সার্ভিস
                </a>

                <div class="my-2 border-t border-gray-100"></div>

                <a href="{{ route('provider.earnings.index') }}" class="flex items-center gap-4 p-3 rounded-xl text-gray-700 hover:bg-gray-50 font-medium">
                    <div class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    আমার আয়
                </a>

                <a href="{{ route('provider.withdrawals.index') }}" class="flex items-center gap-4 p-3 rounded-xl text-gray-700 hover:bg-gray-50 font-medium">
                    <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    টাকা উত্তোলন
                </a>

                <a href="{{ route('provider.skills.manage') }}" class="flex items-center gap-4 p-3 rounded-xl text-gray-700 hover:bg-gray-50 font-medium">
                    <div class="w-10 h-10 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    </div>
                    দক্ষতাসমূহ
                </a>

                <a href="{{ route('provider.profile.edit') }}" class="flex items-center gap-4 p-3 rounded-xl text-gray-700 hover:bg-gray-50 font-medium">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    প্রোফাইল সেটিংস
                </a>
                
                @if(!auth()->user()->providerProfile?->is_verified)
                <a href="{{ route('provider.verification.show') }}" class="flex items-center gap-4 p-3 rounded-xl text-yellow-700 bg-yellow-50 font-medium">
                    <div class="w-10 h-10 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    ভেরিফিকেশন পেন্ডিং
                </a>
                @endif

                <div class="my-4 border-t border-gray-100"></div>

                <form method="POST" action="{{ route('logout') }}" class="mb-4">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-4 p-3 rounded-xl text-red-600 hover:bg-red-50 font-medium">
                        <div class="w-10 h-10 rounded-lg bg-red-50 text-red-500 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </div>
                        লগআউট করুন
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('notificationBell', () => ({
                open: false,
                count: 0,
                notifications: [],
                toggle() {
                    this.open = !this.open;
                    if (this.open && this.count > 0) {
                        this.markAllRead();
                    }
                },
                markAllRead() {
                    this.count = 0;
                    this.notifications.forEach(n => n.is_read = true);
                }
            }));
            
            Alpine.data('flashMessage', () => ({
                show: true,
                init() {
                    setTimeout(() => { this.show = false; }, 5000);
                }
            }));
        });
    </script>
</body>
</html>
