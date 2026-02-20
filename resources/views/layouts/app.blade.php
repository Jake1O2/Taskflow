<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TaskFlow') }}</title>

    <script>
        (function () {
            const theme = localStorage.getItem('theme') || '{{ auth()->check() ? auth()->user()->preference->theme : 'auto' }}';
            if (theme === 'dark' || (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    @if(auth()->check())
        <style>
            :root {
                --primary:
                    {{ auth()->user()->preference->primary_color ?? '#2563eb' }}
                ;
                --accent:
                    {{ auth()->user()->preference->accent_color ?? '#059669' }}
                ;
            }
        </style>
    @endif

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
        <nav x-data="{ mobileMenuOpen: false }"
            class="glass sticky top-0 z-[100] border-b border-gray-100/50 px-4 transition-all duration-300">
            <div class="max-w-7xl mx-auto flex h-20 items-center justify-between">
                <div class="flex items-center gap-10">
                    <a href="{{ route('dashboard') }}" class="group relative flex items-center gap-3">
                        <div
                            class="relative flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-primary to-cyan-vibrant text-white shadow-lg shadow-primary/20 group-hover:scale-105 group-hover:rotate-3 transition-all duration-300">
                            <svg width="24" height="24" style="color: white; width: 24px; height: 24px;" class="h-6 w-6"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-xl font-black italic tracking-tighter text-gray-900 uppercase">TASKFLOW</span>
                    </a>

                    <div class="hidden items-center gap-6 lg:gap-8 md:flex">
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
                        </a>
                        <a href="{{ route('teams.index') }}"
                            class="relative text-sm font-bold {{ request()->routeIs('teams.*') ? 'text-primary' : 'text-gray-400 hover:text-gray-900' }} transition-colors">
                            Équipes
                        </a>
                        <a href="{{ route('webhooks.index') }}"
                            class="relative text-sm font-bold {{ request()->routeIs('webhooks.*') ? 'text-primary' : 'text-gray-400 hover:text-gray-900' }} transition-colors">
                            Webhooks
                        </a>
                        <a href="{{ route('slack.index') }}"
                            class="relative text-sm font-bold {{ request()->routeIs('slack.*') ? 'text-primary' : 'text-gray-400 hover:text-gray-900' }} transition-colors">
                            Slack
                        </a>
                        <a href="{{ route('api.tokens.index') }}"
                            class="relative text-sm font-bold {{ (request()->routeIs('api.tokens.*') || request()->routeIs('api.docs')) ? 'text-primary' : 'text-gray-400 hover:text-gray-900' }} transition-colors">
                            API
                        </a>
                        <a href="{{ route('billing.index') }}"
                            class="relative text-sm font-bold {{ request()->routeIs('billing.*') ? 'text-primary' : 'text-gray-400 hover:text-gray-900' }} transition-colors">
                            Facturation
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-3 sm:gap-4">
                    {{-- Theme Toggle --}}
                    <button id="theme-toggle"
                        class="p-2.5 rounded-2xl bg-gray-50 text-gray-400 hover:bg-primary/5 hover:text-primary border border-transparent hover:border-primary/10 transition-all group"
                        title="Changer le thème">
                        <svg id="theme-toggle-dark-icon" class="hidden h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden h-5 w-5" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    {{-- Notifications --}}
                    <a href="{{ route('notifications.index') }}"
                        class="relative p-2.5 rounded-2xl bg-gray-50 text-gray-400 hover:bg-primary/5 hover:text-primary border border-transparent hover:border-primary/10 transition-all group">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        @php $unreadCount = Auth::user() ? Auth::user()->unreadNotifications->count() : 0; @endphp
                        @if($unreadCount > 0)
                            <span class="absolute top-2 right-2 flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                            </span>
                        @endif
                    </a>

                    <div
                        class="hidden sm:flex items-center gap-3 px-3 py-1.5 rounded-2xl bg-gray-50/50 border border-gray-100">
                        <div
                            class="h-7 w-7 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-black text-[10px]">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                        <span
                            class="text-[11px] font-bold text-gray-700 hidden lg:block">{{ Auth::user()->name ?? 'User' }}</span>
                    </div>

                    <a href="{{ route('profile') }}"
                        class="p-2.5 rounded-2xl bg-gray-50 text-gray-400 hover:bg-primary/5 hover:text-primary border border-transparent hover:border-primary/10 transition-all group"
                        title="Mon Profil">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </a>

                    <a href="{{ route('preferences') }}"
                        class="p-2.5 rounded-2xl bg-gray-50 text-gray-400 hover:bg-primary/5 hover:text-primary border border-transparent hover:border-primary/10 transition-all group"
                        title="Paramètres">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
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

                    {{-- Mobile menu button --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="md:hidden p-2.5 rounded-2xl bg-gray-50 text-gray-400 hover:bg-gray-100 transition-all ring-1 ring-gray-100">
                        <svg class="h-6 w-6" x-show="!mobileMenuOpen" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                        <svg class="h-6 w-6" x-show="mobileMenuOpen" x-cloak fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Menu --}}
            <div x-show="mobileMenuOpen" x-cloak @click.away="mobileMenuOpen = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4"
                class="absolute top-full left-0 w-full bg-white border-b border-gray-100 shadow-xl py-6 space-y-2 md:hidden z-[110]">
                <a href="{{ route('dashboard') }}"
                    class="block px-6 py-3 text-sm font-bold {{ request()->routeIs('dashboard') ? 'text-primary bg-primary/5' : 'text-gray-500 hover:bg-gray-50' }} mx-4 rounded-xl transition-all">Tableau
                    de bord</a>
                <a href="{{ route('projects.index') }}"
                    class="block px-6 py-3 text-sm font-bold {{ request()->routeIs('projects.*') ? 'text-primary bg-primary/5' : 'text-gray-500 hover:bg-gray-50' }} mx-4 rounded-xl transition-all">Projets</a>
                <a href="{{ route('teams.index') }}"
                    class="block px-6 py-3 text-sm font-bold {{ request()->routeIs('teams.*') ? 'text-primary bg-primary/5' : 'text-gray-500 hover:bg-gray-50' }} mx-4 rounded-xl transition-all">Équipes</a>
                <a href="{{ route('webhooks.index') }}"
                    class="block px-6 py-3 text-sm font-bold {{ request()->routeIs('webhooks.*') ? 'text-primary bg-primary/5' : 'text-gray-500 hover:bg-gray-50' }} mx-4 rounded-xl transition-all">Webhooks</a>
                <a href="{{ route('slack.index') }}"
                    class="block px-6 py-3 text-sm font-bold {{ request()->routeIs('slack.*') ? 'text-primary bg-primary/5' : 'text-gray-500 hover:bg-gray-50' }} mx-4 rounded-xl transition-all">Slack</a>
                <a href="{{ route('api.tokens.index') }}"
                    class="block px-6 py-3 text-sm font-bold {{ request()->routeIs('api.*') ? 'text-primary bg-primary/5' : 'text-gray-500 hover:bg-gray-50' }} mx-4 rounded-xl transition-all">API</a>

                <div class="pt-4 mt-2 border-t border-gray-50">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-10 py-3 text-sm font-black text-danger hover:bg-red-50 transition-all uppercase tracking-wider">Déconnexion</button>
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
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-3 grayscale opacity-30">
                    <span class="text-sm font-black italic tracking-tighter uppercase">TASKFLOW</span>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">&copy; {{ date('Y') }} — Expérience
                    Premium</p>
            </div>
        </footer>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            // Change the icons inside the button based on previous settings
            if (document.documentElement.classList.contains('dark')) {
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
            }

            var themeToggleBtn = document.getElementById('theme-toggle');

            themeToggleBtn.addEventListener('click', function () {
                // toggle icons inside button
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');

                // if set via local storage previously
                if (localStorage.getItem('theme')) {
                    if (localStorage.getItem('theme') === 'light') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    }

                    // if NOT set via local storage previously
                } else {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    }
                }

                // Sync with backend if logged in
                @auth
                    fetch('{{ route('api.user.theme') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                        })
                    });
                @endauth
            });
        });
    </script>
</body>

</html>