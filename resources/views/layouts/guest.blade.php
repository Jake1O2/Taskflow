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
    <body class="antialiased font-sans bg-gray-50 text-gray-900 overflow-hidden">
        <div class="min-h-screen relative flex items-center justify-center p-4">
            <!-- Decorative Background Elements -->
            <div class="fixed inset-0 pointer-events-none -z-10">
                <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-blue-100/50 rounded-full blur-[120px]"></div>
                <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-purple-100/40 rounded-full blur-[120px]"></div>
            </div>

            <div class="w-full max-w-md animate-slide-up">
                <div class="flex flex-col items-center mb-10">
                    <div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center shadow-2xl shadow-primary/20 mb-4 group hover:scale-110 transition-transform">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">TaskFlow</h1>
                    <p class="text-gray-500 font-medium text-sm mt-1">Gérez vos projets avec élégance.</p>
                </div>

                <div class="card-premium !p-8 backdrop-blur-sm bg-white/80">
                    @yield('content')
                </div>

                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-400 font-medium tracking-widest uppercase">&copy; {{ date('Y') }} TaskFlow. Premium Experience.</p>
                </div>
            </div>
        </div>
    </body>
</html>