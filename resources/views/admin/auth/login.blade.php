<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>অ্যাডমিন লগইন — LocalEmployments</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md p-4">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-200">
                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900">অ্যাডমিন লগইন</h1>
            <p class="text-gray-500 mt-1 text-sm">শুধুমাত্র অনুমোদিত অ্যাডমিনদের জন্য</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 p-8 border border-gray-100">
            
            <form action="{{ route('admin.login.attempt') }}" method="POST">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">ফোন নম্বর</label>
                        <input type="text" name="phone" class="input bg-gray-50 border-gray-200 focus:bg-white" placeholder="01XXX-XXXXXX" value="{{ old('phone') }}" required>
                        @error('phone') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">পাসওয়ার্ড</label>
                        <input type="password" name="password" class="input bg-gray-50 border-gray-200 focus:bg-white" placeholder="••••••••" required>
                        @error('password') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-lg shadow-blue-200 focus:ring-4 focus:ring-blue-100">
                            লগইন করুন
                        </button>
                    </div>
                </div>

            </form>

            <div class="mt-8 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} LocalEmployments. All rights reserved.
            </div>
        </div>

    </div>

</body>
</html>
