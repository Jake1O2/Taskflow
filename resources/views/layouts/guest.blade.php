<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'TaskFlow') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-mesh min-h-screen relative overflow-x-hidden">
        <!-- Floating Decorative Blobs -->
        <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
            <div class="absolute top-[10%] left-[5%] w-[300px] h-[300px] bg-blue-500/20 rounded-full blur-[80px] animate-float" style="animation-duration: 4s;"></div>
            <div class="absolute bottom-[20%] right-[10%] w-[250px] h-[250px] bg-purple-500/20 rounded-full blur-[80px] animate-float" style="animation-duration: 6s;"></div>
        </div>

        <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
            <div class="w-full max-w-md">
                @yield('content')
            </div>
        </div>
    </body>
</html>