<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TaskFlow') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Space+Grotesk:wght@300..700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-mesh-ultimate min-h-screen font-sans text-gray-900 antialiased selection:bg-primary/10 overflow-x-hidden">
    <div class="relative z-10 flex flex-col min-h-screen">
        {{-- Navigation Premium --}}
        <nav class="glass sticky top-0 z-[100] border-b border-gray-100/50 px-4 transition-all duration-300">
            <div class="max-w-7xl mx-auto flex h-20 items-center justify-between">
                <div class="flex items-center gap-10">
                    <a href="{{ route('dashboard') }}" class="group relative flex items-center gap-3">
                        <div
                            class="relative flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-primary to-cyan-vibrant text-white shadow-lg shadow-primary/20 group-hover:scale-105 group-hover:rotate-3 transition-all duration-300">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-xl font-black italic tracking-tighter text-gray-900">TASKFLOW</span>
                    </a>

                    <div class="hidden items-center gap-8 md:flex">
                        <a href="{{ route('dashboard') }}"
                            class="relative text-sm font-bold {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-gray-400 hover:text-gray-900' }} transition-colors group">
                            Tableau de bord
                            @if(request()->routeIs('dashboard'))
                                <span
                                    class="absolute -bottom-1 left-0 h-1 w-full rounded-full bg-primary animate-fade-in"></span>
                            @endif
                        </a>
                        <a href="{{ route('projects.index') }}"
                            class="relative text-sm font-bold {{ request()->routeIs('projects.*') ? 'text-primary' : 'text-gray-400 hover:text-gray-900' }} transition-colors">
                            Projets
                            @if(request()->routeIs('projects.*'))
                                <span
                                    class="absolute -bottom-1 left-0 h-1 w-full rounded-full bg-primary animate-fade-in"></span>
                            @endif
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-3 px-4 py-2 rounded-2xl bg-gray-50/50 border border-gray-100">
                        <div
                            class="h-8 w-8 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-black text-xs">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="text-xs font-bold text-gray-700 hidden sm:block">{{ Auth::user()->name }}</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="p-2.5 rounded-2xl bg-gray-50 text-gray-400 hover:bg-danger/5 hover:text-danger border border-transparent hover:border-danger/10 transition-all group"
                            title="Déconnexion">
                            <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="relative flex-1 py-10">
            <div class="animate-slide-up">
                @yield('content')
            </div>
        </main>

        {{-- Footer --}}
        <footer class="border-t border-gray-100/50 py-10 px-4">
            <div class="max-w-7xl mx-auto flex flex-col md:row items-center justify-between gap-6">
                <div class="flex items-center gap-3 grayscale opacity-30">
                    <span class="text-sm font-black italic tracking-tighter">TASKFLOW</span>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">&copy; {{ date('Y') }} — Expérience
                    Premium</p>
            </div>
        </footer>
    </div>
</body>

</html>