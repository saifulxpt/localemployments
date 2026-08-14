<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — LocalEmployments</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anek+Bangla:wght@300;400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&family=Noto+Sans+Bengali:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/kalpurush-font@1.0.0/kalpurush.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-primary-50 via-white to-emerald-50 flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="font-bold text-2xl text-primary-700">LocalEmployments</span>
            </a>
            <p class="text-gray-500 text-sm mt-2">আপনার এলাকায়, আপনার মানুষ</p>
        </div>

        {{-- Flash Messages (Only shown here, not duplicated inside) --}}
        @if(session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mb-4">{{ session('error') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info mb-4">{{ session('info') }}</div>
        @endif

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
            @yield('content')
        </div>

        @yield('footer-links')
    </div>
</body>
</html>
