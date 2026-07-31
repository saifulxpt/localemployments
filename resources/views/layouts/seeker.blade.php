<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — কাস্টমার প্যানেল</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased" x-data="{ sidebarOpen: false }">

    {{-- Mobile Header --}}
    <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between sticky top-0 z-40">
        <div class="flex items-center gap-2">
            <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <span class="font-bold text-primary-700 text-lg">LocalEmployments</span>
        </div>
        <img src="{{ auth()->user()->avatar_url }}" alt="" class="w-8 h-8 rounded-full object-cover">
    </div>

    <div class="flex h-screen overflow-hidden">
        
        {{-- Sidebar Backdrop --}}
        <div x-show="sidebarOpen" x-transition.opacity 
             class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden" 
             @click="sidebarOpen = false"></div>

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transition-transform duration-300 lg:static lg:translate-x-0 flex flex-col">
            
            {{-- Logo --}}
            <div class="h-16 flex items-center px-6 bg-white border-b border-gray-200 shrink-0">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-xl text-primary-700">
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span>LocalEmployments</span>
                </a>
            </div>

            {{-- Post Job CTA --}}
            <div class="p-4 shrink-0">
                <a href="{{ route('seeker.job-requests.create') }}" class="btn btn-primary w-full shadow-md flex items-center justify-center gap-2 py-2.5">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    নতুন কাজ পোস্ট করুন
                </a>
            </div>

            {{-- Nav Links --}}
            <div class="flex-1 overflow-y-auto px-3 pb-4 space-y-1">
                <a href="{{ route('seeker.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ active_class('seeker.dashboard', 'bg-primary-50 text-primary-700 font-semibold', 'text-gray-600 hover:bg-gray-50') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    ড্যাশবোর্ড
                </a>
                
                <a href="{{ route('seeker.job-requests.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ active_class('seeker.job-requests.*', 'bg-primary-50 text-primary-700 font-semibold', 'text-gray-600 hover:bg-gray-50') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    আমার কাজগুলো
                </a>

                <a href="{{ route('seeker.bookings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ active_class('seeker.bookings.*', 'bg-primary-50 text-primary-700 font-semibold', 'text-gray-600 hover:bg-gray-50') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    বুকিং সমূহ
                </a>
                
                <a href="{{ route('seeker.messages.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ active_class('seeker.messages.*', 'bg-primary-50 text-primary-700 font-semibold', 'text-gray-600 hover:bg-gray-50') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    মেসেজ
                </a>

                <div class="pt-4 pb-1 border-t border-gray-100 mt-2">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">প্রোফাইল</p>
                </div>
                
                <a href="{{ route('seeker.profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ active_class('seeker.profile.edit', 'bg-primary-50 text-primary-700 font-semibold', 'text-gray-600 hover:bg-gray-50') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    প্রোফাইল সেটিংস
                </a>
            </div>

            {{-- Logout --}}
            <div class="p-4 border-t border-gray-100 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-500 hover:bg-red-50 transition-colors w-full">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        লগআউট
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            {{-- Topbar Desktop --}}
            <header class="hidden lg:flex h-16 bg-white border-b border-gray-200 items-center justify-between px-8 shrink-0">
                <div class="font-semibold text-gray-800 text-lg">@yield('title')</div>
                
                <div class="flex items-center gap-4">
                    {{-- Notification Bell Component (Alpine) --}}
                    <div x-data="notificationBell()" class="relative">
                        <button @click="toggle()" class="relative p-2 text-gray-400 hover:text-primary-600 hover:bg-gray-50 rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span x-show="count > 0" x-text="count" class="absolute top-1 right-1 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center"></span>
                        </button>
                        
                        {{-- Dropdown --}}
                        <div x-show="open" x-cloak @click.outside="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                                <h3 class="font-semibold text-gray-900 text-sm">বিজ্ঞপ্তি</h3>
                                <button @click="markAllRead()" class="text-xs text-primary-600 hover:underline">সব পড়া হয়েছে</button>
                            </div>
                            <div class="max-h-72 overflow-y-auto">
                                <template x-for="n in notifications" :key="n.id">
                                    <a :href="n.url || '#'" class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 border-b border-gray-50 transition-colors" :class="{ 'bg-primary-50': !n.is_read }">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate" x-text="n.title"></p>
                                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2" x-text="n.message"></p>
                                        </div>
                                    </a>
                                </template>
                                <div x-show="notifications.length === 0" class="px-4 py-8 text-center text-gray-400 text-sm">কোনো বিজ্ঞপ্তি নেই</div>
                            </div>
                        </div>
                    </div>

                    <div class="w-px h-6 bg-gray-200"></div>
                    
                    <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-primary-600 font-medium">ওয়েবসাইট দেখুন</a>
                    
                    <a href="{{ route('seeker.profile.edit') }}" class="flex items-center gap-2">
                        <img src="{{ auth()->user()->avatar_url }}" alt="" class="w-9 h-9 rounded-full object-cover ring-2 ring-gray-100">
                    </a>
                </div>
            </header>

            {{-- Flash Messages --}}
            @if(session('success') || session('error'))
                <div class="px-4 lg:px-8 pt-6 pb-0" x-data="flashMessage()">
                    <div x-show="show" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Page Content --}}
            <div class="flex-1 overflow-y-auto p-4 lg:p-8">
                @yield('content')
            </div>

        </main>
    </div>
</body>
</html>
