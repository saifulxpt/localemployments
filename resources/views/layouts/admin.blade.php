<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — Admin Panel</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased" x-data="{ sidebarOpen: false }">

    {{-- Mobile Header --}}
    <div class="lg:hidden bg-gray-900 text-white px-4 py-3 flex items-center justify-between sticky top-0 z-40">
        <div class="flex items-center gap-2">
            <button @click="sidebarOpen = true" class="text-gray-300 hover:text-white">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <span class="font-bold text-lg">Admin Panel</span>
        </div>
    </div>

    <div class="flex h-screen overflow-hidden">
        
        {{-- Sidebar Backdrop --}}
        <div x-show="sidebarOpen" x-transition.opacity 
             class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden" 
             @click="sidebarOpen = false"></div>

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-100 transition-transform duration-300 lg:static lg:translate-x-0 flex flex-col">
            
            {{-- Logo --}}
            <div class="h-20 flex items-center px-6 shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 font-extrabold text-2xl text-gray-900 tracking-tight">
                    @if(setting('site_logo'))
                        <img src="{{ asset(ltrim(setting('site_logo'), '/')) }}" alt="Logo" class="h-10 max-w-[180px] object-contain">
                    @else
                        <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-600/30">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <span>Local<span class="text-primary-600">Admin</span></span>
                    @endif
                </a>
            </div>

            @php
                $activeNav = 'bg-primary-600 text-white shadow-md shadow-primary-600/20';
                $inactiveNav = 'text-gray-500 hover:text-gray-900 hover:bg-gray-50';
            @endphp

            {{-- Nav Links --}}
            <div class="flex-1 overflow-y-auto px-4 py-4 space-y-1.5 custom-scrollbar">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ active_class('admin.dashboard', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('admin.dashboard', 'text-white', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ active_class('admin.reports.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('admin.reports.*', 'text-white', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Reports
                </a>
                
                <div class="pt-5 pb-2">
                    <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Management</p>
                </div>
                
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ active_class('admin.users.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('admin.users.*', 'text-white', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Users
                </a>

                <a href="{{ route('admin.contact-messages.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ active_class('admin.contact-messages.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('admin.contact-messages.*', 'text-white', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Contact Messages
                    @php
                        $unreadMsgs = \App\Models\ContactMessage::where('is_read', false)->count();
                    @endphp
                    @if($unreadMsgs > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $unreadMsgs }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.featured.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ active_class('admin.featured.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('admin.featured.*', 'text-white', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    Featured
                </a>
                
                <a href="{{ route('admin.verifications.index') }}" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all font-medium {{ active_class('admin.verifications.*', $activeNav, $inactiveNav) }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 {{ active_class('admin.verifications.*', 'text-white', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Verifications
                    </div>
                </a>
                
                <a href="{{ route('admin.job-requests.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ active_class('admin.job-requests.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('admin.job-requests.*', 'text-white', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Job Requests
                </a>
                
                <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ active_class('admin.bookings.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('admin.bookings.*', 'text-white', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    Bookings
                </a>

                <div class="pt-5 pb-2">
                    <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Finance & Disputes</p>
                </div>

                <a href="{{ route('admin.disputes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ active_class('admin.disputes.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('admin.disputes.*', 'text-white', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Disputes
                </a>

                <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ active_class('admin.reviews.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('admin.reviews.*', 'text-white', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    Reviews
                </a>
                
                <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ active_class('admin.payments.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('admin.payments.*', 'text-white', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Payments
                </a>
                
                <a href="{{ route('admin.withdrawals.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ active_class('admin.withdrawals.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('admin.withdrawals.*', 'text-white', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Withdrawals
                </a>

                <div class="pt-5 pb-2">
                    <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">System</p>
                </div>
                
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ active_class('admin.categories.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('admin.categories.*', 'text-white', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Categories
                </a>
                
                <a href="{{ route('admin.locations.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ active_class('admin.locations.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('admin.locations.*', 'text-white', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Locations
                </a>

                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ active_class('admin.settings.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('admin.settings.*', 'text-white', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                    Settings
                </a>

                <a href="{{ route('admin.sms.show') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ active_class('admin.sms.*', $activeNav, $inactiveNav) }}">
                    <svg class="w-5 h-5 {{ active_class('admin.sms.*', 'text-white', 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    SMS Broadcast
                </a>
            </div>

            {{-- Admin Profile / Logout (Crextio style bottom profile) --}}
            <div class="p-4 border-t border-gray-100 shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">Administrator</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}" class="ml-2">
                        @csrf
                        <button type="submit" title="Logout" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden bg-gray-100">
            
            {{-- Topbar Desktop --}}
            <header class="hidden lg:flex h-16 bg-white border-b border-gray-200 items-center justify-between px-8 shrink-0 shadow-sm z-10">
                <div class="font-semibold text-gray-800 text-lg">@yield('title')</div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('system.deploy') }}" class="text-sm bg-blue-600 text-white px-3 py-1.5 rounded hover:bg-blue-700 font-medium flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        Deploy / Fix DB
                    </a>
                    <a href="{{ route('home') }}" target="_blank" class="text-sm text-gray-500 hover:text-blue-600 font-medium flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Visit Site
                    </a>
                </div>
            </header>

            {{-- Flash Messages --}}
            @if(session('success') || session('error'))
                <div class="px-4 lg:px-8 pt-6 pb-0" x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)">
                    <div x-show="show" x-transition class="mb-4">
                        @if(session('success'))
                            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                                </div>
                            </div>
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
