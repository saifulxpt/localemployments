<nav x-data="{ mobileOpen: false, userMenuOpen: false }" class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-xl text-primary-700">
                @if(setting('site_logo'))
                    <img src="{{ asset(ltrim(setting('site_logo'), '/')) }}" alt="{{ setting('site_name', 'Logo') }}" class="h-10 max-w-[220px] object-contain">
                @else
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span class="hidden sm:block">{{ setting('site_name', 'LocalEmployments') }}</span>
                @endif
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="{{ route('home') }}" class="transition-colors {{ active_class('home', 'text-primary-700 font-bold', 'text-gray-600 hover:text-primary-600') }}">Home</a>
                <a href="{{ route('jobs.index') }}" class="transition-colors {{ active_class('jobs.*', 'text-primary-700 font-bold', 'text-gray-600 hover:text-primary-600') }}">Browse Jobs</a>
                <a href="{{ route('services.index') }}" class="transition-colors {{ active_class('services.*', 'text-primary-700 font-bold', 'text-gray-600 hover:text-primary-600') }}">Services</a>
                <a href="{{ route('search') }}" class="transition-colors {{ active_class('search', 'text-primary-700 font-bold', 'text-gray-600 hover:text-primary-600') }}">Search Workers</a>
                <a href="{{ route('about') }}" class="transition-colors {{ active_class('about', 'text-primary-700 font-bold', 'text-gray-600 hover:text-primary-600') }}">About Us</a>
            </div>

            {{-- Right Side --}}
            <div class="flex items-center gap-3">
                @auth
                    {{-- Notification Bell --}}
                    <div x-data="notificationBell()" class="relative">
                        <button @click="toggle()" class="relative p-2 text-gray-600 hover:text-primary-600 hover:bg-gray-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span x-show="count > 0" x-text="count > 9 ? '9+' : count"
                                  class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center font-bold leading-none"></span>
                        </button>

                        {{-- Dropdown --}}
                        <div x-show="open" x-cloak @click.outside="open = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                                <h3 class="font-semibold text-gray-900 text-sm">বিজ্ঞপ্তি</h3>
                                <button @click="markAllRead()" class="text-xs text-primary-600 hover:underline">সব পড়া হয়েছে</button>
                            </div>
                            <div class="max-h-72 overflow-y-auto">
                                <template x-for="n in notifications" :key="n.id">
                                    <a :href="n.url || '#'" class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 border-b border-gray-50 transition-colors" :class="{ 'bg-primary-50': !n.is_read }">
                                        <span class="text-xl flex-shrink-0" x-text="n.icon"></span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate" x-text="n.title"></p>
                                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2" x-text="n.message"></p>
                                            <p class="text-xs text-gray-400 mt-1" x-text="n.time_ago"></p>
                                        </div>
                                    </a>
                                </template>
                                <div x-show="notifications.length === 0" class="px-4 py-8 text-center text-gray-400 text-sm">
                                    কোনো বিজ্ঞপ্তি নেই
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- User Menu --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-50 transition-colors">
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                                 class="w-8 h-8 rounded-full object-cover ring-2 ring-primary-100">
                            <span class="hidden md:block text-sm font-medium text-gray-700 max-w-28 truncate">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" x-cloak @click.outside="open = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50">
                            @if(auth()->user()->isSeeker())
                                <a href="{{ route('seeker.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    Dashboard
                                </a>
                                <a href="{{ route('seeker.profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profile
                                </a>
                            @elseif(auth()->user()->isProvider())
                                <a href="{{ route('provider.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    Dashboard
                                </a>
                                <a href="{{ route('provider.profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profile
                                </a>
                            @elseif(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    Admin Panel
                                </a>
                            @endif
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-primary-600 transition-colors hidden sm:block">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary text-sm">Register</a>
                @endauth

                {{-- Mobile Menu Button --}}
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                    <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileOpen" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileOpen" x-cloak x-transition class="md:hidden bg-white border-t border-gray-100 px-4 py-3 space-y-1">
        <a href="{{ route('home') }}" class="block py-2 text-sm {{ active_class('home', 'text-primary-700 font-bold bg-primary-50 px-3 rounded-lg', 'text-gray-700 hover:text-primary-600 px-3') }}">Home</a>
        <a href="{{ route('jobs.index') }}" class="block py-2 text-sm {{ active_class('jobs.*', 'text-primary-700 font-bold bg-primary-50 px-3 rounded-lg', 'text-gray-700 hover:text-primary-600 px-3') }}">Browse Jobs</a>
        <a href="{{ route('services.index') }}" class="block py-2 text-sm {{ active_class('services.*', 'text-primary-700 font-bold bg-primary-50 px-3 rounded-lg', 'text-gray-700 hover:text-primary-600 px-3') }}">Services</a>
        <a href="{{ route('search') }}" class="block py-2 text-sm {{ active_class('search', 'text-primary-700 font-bold bg-primary-50 px-3 rounded-lg', 'text-gray-700 hover:text-primary-600 px-3') }}">Search Workers</a>
        <a href="{{ route('about') }}" class="block py-2 text-sm {{ active_class('about', 'text-primary-700 font-bold bg-primary-50 px-3 rounded-lg', 'text-gray-700 hover:text-primary-600 px-3') }}">About Us</a>
        @auth
            <div class="pt-2 mt-2 border-t border-gray-100 space-y-1">
                <div class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    {{ auth()->user()->name }}
                </div>
                @if(auth()->user()->isSeeker())
                    <a href="{{ route('seeker.dashboard') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">Dashboard</a>
                    <a href="{{ route('seeker.profile.edit') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">Profile</a>
                @elseif(auth()->user()->isProvider())
                    <a href="{{ route('provider.dashboard') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">Dashboard</a>
                    <a href="{{ route('provider.profile.edit') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">Profile</a>
                @elseif(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">Admin Panel</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg">Logout</button>
                </form>
            </div>
        @else
            <div class="pt-2 flex gap-2">
                <a href="{{ route('login') }}" class="btn btn-outline flex-1 text-center text-sm">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary flex-1 text-center text-sm">Register</a>
            </div>
        @endauth
    </div>
</nav>
