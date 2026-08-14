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
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-4 sm:p-6 font-sans antialiased text-slate-800">

    <div class="w-full max-w-[440px] my-auto">
        {{-- Logo --}}
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 group">
                <div class="w-10 h-10 bg-primary-600 group-hover:bg-primary-700 transition-colors rounded-xl flex items-center justify-center shadow-md shadow-primary-600/20">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="font-bold text-2xl tracking-tight text-slate-900">Local<span class="text-primary-600">Employments</span></span>
            </a>
            <p class="text-slate-500 text-xs mt-1">আপনার এলাকায়, আপনার মানুষ</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-100 p-6 sm:p-8">
            @yield('content')
        </div>

        @yield('footer-links')
    </div>
</body>
</html>
