<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name', 'Worth of Worship Ministry International') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .bg-metal-gold { background-color: #D4AF37; }
        .text-metal-gold { color: #D4AF37; }
        .hover\:bg-metal-gold:hover { background-color: #C4A030; }
        .bg-teal { background-color: #008080; }
        .text-teal { color: #008080; }
        .hover\:bg-teal:hover { background-color: #006666; }
        .bg-crimson { background-color: #DC143C; }
        .text-crimson { color: #DC143C; }
        .hover\:bg-crimson:hover { background-color: #C01234; }
        .border-metal-gold { border-color: #D4AF37; }
        .border-teal { border-color: #008080; }
        .border-crimson { border-color: #DC143C; }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen flex">
        <!-- Left side - Image and overlay -->
        <div class="hidden lg:flex lg:w-1/2 relative">
            <div class="absolute inset-0 bg-gradient-to-r from-teal to-metal-gold opacity-90"></div>
            <div class="absolute inset-0 flex flex-col justify-center px-12 text-white z-10">
                <h2 class="text-4xl font-bold mb-6">Worth of Worship Ministry International</h2>
                <p class="text-xl">Empowering churches through modern management solutions.</p>
            </div>
            <img src="{{ asset('storage/images/church-background.jpg') }}" alt="Church Background" class="object-cover w-full h-full">
        </div>

        <!-- Right side - Auth form -->
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <div class="flex justify-center mb-6">
                    <span class="text-4xl font-bold text-metal-gold">WoW</span>
                </div>
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
