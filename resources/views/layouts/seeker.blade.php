<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — কাস্টমার প্যানেল</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anek+Bangla:wght@300;400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&family=Noto+Sans+Bengali:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/kalpurush-font@1.0.0/kalpurush.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased" x-data="{ sidebarOpen: false }">

    {{-- Mobile Top Header (App Bar) --}}
    <div class="lg:hidden bg-white/80 backdrop-blur-md border-b border-gray-100 px-4 py-3 flex items-center justify-between sticky top-0 z-40 shadow-sm">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <div class="w-7 h-7 bg-primary-600 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="font-bold text-gray-900 text-lg tracking-tight">LocalEmployments</span>
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('seeker.profile.edit') }}">
                <img src="{{ auth()->user()->avatar_url }}" alt="" class="w-8 h-8 rounded-full object-cover ring-2 ring-gray-100 shadow-sm">
            </a>
        </div>
    </div>

    <div class="flex h-screen overflow-hidden">
        
        {{-- Desktop Sidebar --}}
        <aside class="hidden lg:flex flex-col w-64 bg-white border-r border-gray-100 shadow-sm z-30 shrink-0">
            {{-- Logo --}}
            <div class="h-16 flex items-center px-6 border-b border-gray-50 shrink-0">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-xl text-primary-700 hover:opacity-80 transition-opacity">
                    <div class="w-8 h-8 bg-primary-600 rounded-xl shadow-md shadow-primary-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span>LocalEmployments</span>
                </a>
            </div>

            {{-- Post Job CTA --}}
            <div class="p-4 shrink-0">
                <a href="{{ route('seeker.job-requests.create') }}" class="btn btn-primary w-full shadow-lg shadow-primary-500/20 flex items-center justify-center gap-2 py-2.5 rounded-xl transition-transform hover:scale-[1.02]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    নতুন কাজ পোস্ট করুন
                </a>
            </div>

            @php
                $activeNav = 'bg-primary-50 text-primary-700 font-bold';
                $inactiveNav = 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium';
            @endphp

            {{-- Nav Links --}}
            <div class="flex-1 overflow-y-auto px-4 pb-4 space-y-1.5 custom-scrollbar mt-2">
                <a href="{{ route('seeker.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ active_class('seeker.dashboard', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('seeker.dashboard', 'text-primary-600', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    ড্যাশবোর্ড
                </a>
                
                <a href="{{ route('seeker.job-requests.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ active_class('seeker.job-requests.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('seeker.job-requests.*', 'text-primary-600', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    আমার কাজগুলো
                </a>

                <a href="{{ route('seeker.bookings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ active_class('seeker.bookings.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('seeker.bookings.*', 'text-primary-600', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    বুকিং সমূহ
                </a>
                
                <a href="{{ route('seeker.messages.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ active_class('seeker.messages.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('seeker.messages.*', 'text-primary-600', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    মেসেজ
                </a>

                <div class="pt-4 pb-1">
                    <a href="{{ route('seeker.become-provider') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 text-white font-bold shadow-md hover:opacity-95 transition-all text-xs">
                        <svg class="w-5 h-5 text-teal-100" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>সার্ভিস প্রোভাইডার হোন 🛠️</span>
                    </a>
                </div>

                <div class="pt-5 pb-2">
                    <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider">সেটিংস</p>
                </div>
                
                <a href="{{ route('seeker.profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ active_class('seeker.profile.edit', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('seeker.profile.edit', 'text-primary-600', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    প্রোফাইল সেটিংস
                </a>
            </div>

            {{-- Logout --}}
            <div class="p-4 border-t border-gray-50 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-red-500 hover:bg-red-50 hover:text-red-600 font-medium transition-colors w-full">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        লগআউট
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-gray-50/50 pb-20 lg:pb-0 relative">
            
            {{-- Topbar Desktop --}}
            <header class="hidden lg:flex h-16 bg-white/80 backdrop-blur-md border-b border-gray-100 items-center justify-between px-8 sticky top-0 z-30">
                <div class="font-bold text-gray-900 text-lg">@yield('title')</div>
                
                <div class="flex items-center gap-5">
                    {{-- Notification Bell --}}
                    <div x-data="notificationBell()" class="relative">
                        <button @click="toggle()" class="relative p-2.5 text-gray-500 hover:text-primary-600 hover:bg-primary-50 rounded-full transition-all">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span x-show="count > 0" x-text="count" class="absolute top-1 right-1 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center shadow-sm"></span>
                        </button>
                        
                        {{-- Notification Dropdown --}}
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
                    
                    <a href="{{ route('seeker.profile.edit') }}" class="flex items-center">
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

    {{-- Mobile Bottom Navigation Bar --}}
    <nav class="lg:hidden fixed bottom-0 w-full bg-white/90 backdrop-blur-lg border-t border-gray-100 flex items-center justify-around pb-safe pt-2 z-50 shadow-[0_-4px_20px_-10px_rgba(0,0,0,0.1)]">
        
        {{-- Home --}}
        <a href="{{ route('seeker.dashboard') }}" class="flex flex-col items-center gap-1 p-2 w-16 {{ request()->routeIs('seeker.dashboard') ? 'text-primary-600' : 'text-gray-400' }}">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('seeker.dashboard') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('seeker.dashboard') ? '0' : '2' }}"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span class="text-[10px] font-medium tracking-wide">হোম</span>
        </a>

        {{-- My Jobs --}}
        <a href="{{ route('seeker.job-requests.index') }}" class="flex flex-col items-center gap-1 p-2 w-16 {{ request()->routeIs('seeker.job-requests.*') ? 'text-primary-600' : 'text-gray-400' }}">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('seeker.job-requests.*') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('seeker.job-requests.*') ? '0' : '2' }}"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span class="text-[10px] font-medium tracking-wide">কাজগুলো</span>
        </a>

        {{-- Floating Action Button (FAB) for Post Job --}}
        <div class="relative -top-6">
            <a href="{{ route('seeker.job-requests.create') }}" class="w-14 h-14 bg-gradient-to-tr from-primary-600 to-primary-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-primary-500/40 border-4 border-gray-50 transform hover:scale-105 transition-transform">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            </a>
        </div>

        {{-- Bookings --}}
        <a href="{{ route('seeker.bookings.index') }}" class="flex flex-col items-center gap-1 p-2 w-16 {{ request()->routeIs('seeker.bookings.*') ? 'text-primary-600' : 'text-gray-400' }}">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('seeker.bookings.*') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('seeker.bookings.*') ? '0' : '2' }}"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span class="text-[10px] font-medium tracking-wide">বুকিং</span>
        </a>

        {{-- Messages --}}
        <a href="{{ route('seeker.messages.index') }}" class="flex flex-col items-center gap-1 p-2 w-16 {{ request()->routeIs('seeker.messages.*') ? 'text-primary-600' : 'text-gray-400' }} relative">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('seeker.messages.*') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('seeker.messages.*') ? '0' : '2' }}"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            <span class="text-[10px] font-medium tracking-wide">মেসেজ</span>
        </a>
    </nav>

    {{-- Safe area spacer for bottom nav on newer iPhones --}}
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
