<!DOCTYPE html>
<html lang="bn" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', setting('site_name', 'LocalEmployments')) — {{ setting('site_tagline', 'আপনার এলাকায়, আপনার মানুষ') }}</title>
    <meta name="description" content="@yield('meta_description', 'LocalEmployments — বাংলাদেশের সেরা লোকাল সার্ভিস মার্কেটপ্লেস। দক্ষ কর্মী খুঁজুন বা কাজ পান।')">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

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
    @include('components.public.footer')

    @stack('scripts')
</body>
</html>
