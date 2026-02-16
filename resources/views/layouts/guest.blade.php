<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'TaskFlow') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-mesh-ultimate min-h-screen relative overflow-x-hidden selection:bg-blue-500/30 selection:text-white">
        <!-- Interactive Geometric Scene -->
        <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
            {{-- Floating SVG Rings for 3D depth --}}
            <svg class="absolute top-[5%] left-[10%] w-[600px] h-[600px] text-blue-500/10 animate-float opacity-30" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="40" stroke="currentColor" stroke-width="0.5" fill="none" />
                <circle cx="50" cy="50" r="30" stroke="currentColor" stroke-width="0.3" fill="none" />
            </svg>
            <svg class="absolute bottom-[0%] right-[-5%] w-[800px] h-[800px] text-purple-600/10 animate-float-slow opacity-20" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="45" stroke="currentColor" stroke-width="0.2" fill="none" stroke-dasharray="2 2" />
                <circle cx="50" cy="50" r="35" stroke="currentColor" stroke-width="0.1" fill="none" />
            </svg>
            
            {{-- Dynamic Glows --}}
            <div class="absolute top-[-20%] right-[-10%] w-[50%] h-[50%] bg-blue-600/10 rounded-full blur-[150px]"></div>
            <div class="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-600/10 rounded-full blur-[150px]"></div>
        </div>

        <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 relative z-10">
            <div class="w-full max-w-md">
                @yield('content')
            </div>
        </div>
    </body>
</html>