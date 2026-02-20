<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TaskFlow') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    $pageTheme = [
        'grad1' => 'rgba(15, 76, 129, 0.08)',
        'grad2' => 'rgba(13, 148, 136, 0.08)',
        'base' => '#f8fafc',
        'orb1' => 'rgba(15, 76, 129, 0.15)',
        'orb2' => 'rgba(13, 148, 136, 0.2)',
        'orb3' => 'rgba(181, 71, 8, 0.1)',
    ];

    if (request()->routeIs('dashboard')) {
        $pageTheme = [
            'grad1' => 'rgba(30, 64, 175, 0.1)',
            'grad2' => 'rgba(13, 148, 136, 0.1)',
            'base' => '#f7fbff',
            'orb1' => 'rgba(30, 64, 175, 0.18)',
            'orb2' => 'rgba(13, 148, 136, 0.18)',
            'orb3' => 'rgba(245, 158, 11, 0.12)',
        ];
    } elseif (request()->routeIs('projects.*')) {
        $pageTheme = [
            'grad1' => 'rgba(3, 105, 161, 0.1)',
            'grad2' => 'rgba(2, 132, 199, 0.08)',
            'base' => '#f8fcff',
            'orb1' => 'rgba(2, 132, 199, 0.18)',
            'orb2' => 'rgba(20, 184, 166, 0.15)',
            'orb3' => 'rgba(234, 88, 12, 0.12)',
        ];
    } elseif (request()->routeIs('tasks.*')) {
        $pageTheme = [
            'grad1' => 'rgba(13, 148, 136, 0.12)',
            'grad2' => 'rgba(14, 116, 144, 0.09)',
            'base' => '#f7fcfb',
            'orb1' => 'rgba(13, 148, 136, 0.2)',
            'orb2' => 'rgba(14, 116, 144, 0.18)',
            'orb3' => 'rgba(245, 158, 11, 0.1)',
        ];
    } elseif (request()->routeIs('teams.*')) {
        $pageTheme = [
            'grad1' => 'rgba(5, 150, 105, 0.1)',
            'grad2' => 'rgba(14, 165, 233, 0.09)',
            'base' => '#f8fffc',
            'orb1' => 'rgba(5, 150, 105, 0.2)',
            'orb2' => 'rgba(14, 165, 233, 0.17)',
            'orb3' => 'rgba(161, 98, 7, 0.1)',
        ];
    } elseif (request()->routeIs('notifications.*')) {
        $pageTheme = [
            'grad1' => 'rgba(2, 132, 199, 0.09)',
            'grad2' => 'rgba(220, 38, 38, 0.06)',
            'base' => '#f9fbff',
            'orb1' => 'rgba(2, 132, 199, 0.16)',
            'orb2' => 'rgba(220, 38, 38, 0.12)',
            'orb3' => 'rgba(234, 179, 8, 0.12)',
        ];
    } elseif (request()->routeIs('billing.*') || request()->routeIs('pricing.*')) {
        $pageTheme = [
            'grad1' => 'rgba(180, 83, 9, 0.11)',
            'grad2' => 'rgba(8, 145, 178, 0.08)',
            'base' => '#fffaf5',
            'orb1' => 'rgba(180, 83, 9, 0.18)',
            'orb2' => 'rgba(8, 145, 178, 0.16)',
            'orb3' => 'rgba(146, 64, 14, 0.12)',
        ];
    } elseif (request()->routeIs('webhooks.*') || request()->routeIs('slack.*') || request()->routeIs('api.docs') || request()->routeIs('api.tokens.*')) {
        $pageTheme = [
            'grad1' => 'rgba(30, 64, 175, 0.1)',
            'grad2' => 'rgba(3, 105, 161, 0.08)',
            'base' => '#f6f9ff',
            'orb1' => 'rgba(30, 64, 175, 0.17)',
            'orb2' => 'rgba(3, 105, 161, 0.16)',
            'orb3' => 'rgba(8, 145, 178, 0.12)',
        ];
    } elseif (request()->routeIs('search') || request()->routeIs('profile')) {
        $pageTheme = [
            'grad1' => 'rgba(15, 118, 110, 0.09)',
            'grad2' => 'rgba(37, 99, 235, 0.07)',
            'base' => '#f8fcff',
            'orb1' => 'rgba(15, 118, 110, 0.17)',
            'orb2' => 'rgba(37, 99, 235, 0.15)',
            'orb3' => 'rgba(217, 119, 6, 0.1)',
        ];
    }

    // Dynamic avatar color based on user name initial
    $avatarColors = [
        'A' => '#0f4c81',
        'B' => '#7c3aed',
        'C' => '#059669',
        'D' => '#d97706',
        'E' => '#dc2626',
        'F' => '#0284c7',
        'G' => '#16a34a',
        'H' => '#9333ea',
        'I' => '#0f766e',
        'J' => '#b45309',
        'K' => '#4f46e5',
        'L' => '#0369a1',
        'M' => '#be185d',
        'N' => '#0f4c81',
        'O' => '#7c3aed',
        'P' => '#059669',
        'Q' => '#d97706',
        'R' => '#dc2626',
        'S' => '#0284c7',
        'T' => '#16a34a',
        'U' => '#9333ea',
        'V' => '#0f766e',
        'W' => '#b45309',
        'X' => '#4f46e5',
        'Y' => '#0369a1',
        'Z' => '#be185d',
    ];
    $userInitial = strtoupper(substr(Auth::user()->name, 0, 1));
    $avatarColor = $avatarColors[$userInitial] ?? '#0f4c81';
@endphp

<body class="antialiased font-sans text-gray-900" style="
              --page-grad-1: {{ $pageTheme['grad1'] }};
              --page-grad-2: {{ $pageTheme['grad2'] }};
              --page-grad-base: {{ $pageTheme['base'] }};
              --page-orb-1: {{ $pageTheme['orb1'] }};
              --page-orb-2: {{ $pageTheme['orb2'] }};
              --page-orb-3: {{ $pageTheme['orb3'] }};
          ">
    <div class="min-h-screen relative">

        {{-- Ambient Orbs --}}
        <div class="fixed inset-0 -z-10 pointer-events-none overflow-hidden">
            <div class="absolute -top-32 -left-32 h-96 w-96 rounded-full blur-[120px] opacity-80 transition-all duration-1000"
                style="background: var(--page-orb-1);"></div>
            <div class="absolute top-20 -right-24 h-80 w-80 rounded-full blur-[120px] opacity-70"
                style="background: var(--page-orb-2);"></div>
            <div class="absolute bottom-0 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full blur-[100px] opacity-60"
                style="background: var(--page-orb-3);"></div>
        </div>

        {{-- Navigation --}}
        <nav class="sticky top-3 z-50 mx-auto w-full max-w-7xl px-3 sm:px-6 lg:px-8">
            <div class="glass rounded-2xl border border-white/60 shadow-lg shadow-gray-900/8">
                <div class="flex h-16 items-center justify-between px-3 sm:px-5">

                    {{-- Logo + Nav Links --}}
                    <div class="flex items-center gap-3 sm:gap-8">
                        <a href="{{ route('dashboard') }}" class="group flex items-center gap-2.5 shrink-0">
                            <div
                                class="relative flex h-9 w-9 items-center justify-center rounded-xl bg-primary text-white shadow-md shadow-primary/30 transition-all duration-300 group-hover:scale-105 group-hover:shadow-lg group-hover:shadow-primary/40 group-hover:rotate-3">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <span class="font-display text-lg font-bold tracking-tight text-gray-900">TaskFlow</span>
                        </a>

                        <div class="hidden items-center gap-0.5 lg:flex">
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Tableau de
                                bord</x-nav-link>
                            <x-nav-link :href="route('projects.index')"
                                :active="request()->routeIs('projects.*')">Projets</x-nav-link>
                            <x-nav-link :href="route('teams.index')"
                                :active="request()->routeIs('teams.*')">Équipes</x-nav-link>
                            <x-nav-link :href="route('notifications.index')"
                                :active="request()->routeIs('notifications.*')">Notifications</x-nav-link>
                        </div>
                    </div>

                    {{-- Right Controls --}}
                    <div class="flex items-center gap-2">
                        {{-- Search --}}
                        <div class="relative hidden w-52 md:block">
                            <form action="{{ route('search') }}" method="GET">
                                <span
                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </span>
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher..."
                                    class="w-full rounded-xl border border-gray-200/80 bg-white/70 py-2 pl-9 pr-3 text-sm placeholder-gray-400 focus:bg-white focus:border-primary/30 focus:ring-2 focus:ring-primary/15 focus:outline-none">
                            </form>
                        </div>

                        {{-- Notification Bell --}}
                        <div class="relative">
                            @php $unreadCount = Auth::user()->unreadNotifications->count(); @endphp
                            <a href="{{ route('notifications.index') }}"
                                class="relative inline-flex h-10 w-10 items-center justify-center rounded-xl text-gray-500 transition hover:bg-primary/8 hover:text-primary">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                                @if($unreadCount > 0)
                                    <span class="absolute -top-0.5 -right-0.5 flex h-4 w-4 items-center justify-center">
                                        <span
                                            class="absolute inline-flex h-full w-full rounded-full bg-danger opacity-60 animate-ping"></span>
                                        <span
                                            class="relative inline-flex h-3.5 w-3.5 items-center justify-center rounded-full bg-danger text-[8px] font-bold text-white border border-white">
                                            {{ $unreadCount > 9 ? '9' : $unreadCount }}
                                        </span>
                                    </span>
                                @endif
                            </a>
                        </div>

                        {{-- Profile Dropdown --}}
                        <div class="relative hidden sm:block" id="profile-dropdown">
                            <button type="button" id="profile-button"
                                class="flex items-center gap-2.5 rounded-xl border border-transparent px-2 py-1.5 transition hover:border-gray-200 hover:bg-gray-50/80 focus:outline-none">
                                <span
                                    class="hidden text-sm font-semibold text-gray-700 lg:block leading-none">{{ Auth::user()->name }}</span>
                                <span
                                    class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold text-white shadow-sm shrink-0"
                                    style="background: {{ $avatarColor }}; box-shadow: 0 2px 8px {{ $avatarColor }}40;">
                                    {{ $userInitial }}
                                </span>
                                <svg class="h-3.5 w-3.5 text-gray-400 transition-transform duration-200"
                                    id="dropdown-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div id="dropdown-menu"
                                class="absolute right-0 mt-2 hidden w-60 rounded-2xl border border-gray-200/80 bg-white/95 backdrop-blur-xl p-2 shadow-xl shadow-gray-900/10 origin-top-right">
                                {{-- User Info --}}
                                <div class="mb-1 px-3 py-2.5 border-b border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-sm font-bold text-white"
                                            style="background: {{ $avatarColor }};">{{ $userInitial }}</span>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}
                                            </p>
                                            <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Menu Items --}}
                                <div class="py-1 space-y-0.5">
                                    <a href="{{ route('profile') }}"
                                        class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary transition-colors">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Mon compte
                                    </a>
                                    <a href="{{ route('billing.index') }}"
                                        class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary transition-colors">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        Facturation
                                    </a>
                                    <a href="{{ route('api.tokens.index') }}"
                                        class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary transition-colors">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                        Tokens API
                                    </a>
                                    <a href="{{ route('slack.index') }}"
                                        class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary transition-colors">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        Slack
                                    </a>
                                    <a href="{{ route('webhooks.index') }}"
                                        class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary transition-colors">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                        Webhooks
                                    </a>
                                    <a href="{{ route('api.docs') }}"
                                        class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary transition-colors">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Documentation
                                    </a>
                                </div>

                                <div class="my-1.5 h-px bg-gray-100 mx-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold text-danger hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Mobile Menu Button --}}
                        <button type="button" id="mobile-menu-button"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-gray-600 transition hover:bg-primary/8 hover:text-primary lg:hidden"
                            aria-expanded="false" aria-controls="mobile-menu">
                            <svg class="h-5 w-5" id="mobile-menu-icon" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Mobile Menu --}}
                <div id="mobile-menu" class="hidden border-t border-gray-100/80 px-3 pb-3 pt-2 lg:hidden">
                    <div class="mb-3">
                        <form action="{{ route('search') }}" method="GET">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher..."
                                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm focus:border-primary/30 focus:ring-2 focus:ring-primary/15 focus:outline-none">
                        </form>
                    </div>
                    <div class="grid grid-cols-1 gap-0.5">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Tableau de
                            bord</x-nav-link>
                        <x-nav-link :href="route('projects.index')"
                            :active="request()->routeIs('projects.*')">Projets</x-nav-link>
                        <x-nav-link :href="route('teams.index')"
                            :active="request()->routeIs('teams.*')">Équipes</x-nav-link>
                        <x-nav-link :href="route('notifications.index')"
                            :active="request()->routeIs('notifications.*')">Notifications</x-nav-link>
                        <x-nav-link :href="route('profile')" :active="request()->routeIs('profile')">Mon
                            compte</x-nav-link>
                        <x-nav-link :href="route('slack.index')"
                            :active="request()->routeIs('slack.*')">Slack</x-nav-link>
                        <form method="POST" action="{{ route('logout') }}" class="pt-1">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-xl px-3.5 py-2 text-left text-sm font-semibold text-danger hover:bg-red-50 transition-colors">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-8 animate-fade-in">
            @yield('content')
        </main>

        <footer class="mt-16 border-t border-gray-200/60 bg-white/60 backdrop-blur-sm py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col items-center gap-4 text-center">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary text-white">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span class="font-display text-base font-bold text-gray-800">TaskFlow</span>
                    </div>
                    <p class="text-sm text-gray-500">Simplifiez votre gestion de projet. Collaborez. Livrez.</p>
                    <div class="flex items-center gap-6 text-xs text-gray-400">
                        <a href="{{ route('projects.index') }}" class="hover:text-primary transition-colors">Projets</a>
                        <a href="{{ route('teams.index') }}" class="hover:text-primary transition-colors">Équipes</a>
                        <a href="{{ route('api.docs') }}" class="hover:text-primary transition-colors">API Docs</a>
                        <a href="{{ route('billing.index') }}"
                            class="hover:text-primary transition-colors">Facturation</a>
                    </div>
                    <p class="text-xs text-gray-400">&copy; {{ date('Y') }} TaskFlow. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const profileButton = document.getElementById('profile-button');
            const dropdownMenu = document.getElementById('dropdown-menu');
            const dropdownChevron = document.getElementById('dropdown-chevron');
            const mobileButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            if (profileButton && dropdownMenu) {
                profileButton.addEventListener('click', (event) => {
                    event.stopPropagation();
                    const isHidden = dropdownMenu.classList.contains('hidden');
                    dropdownMenu.classList.toggle('hidden');
                    if (dropdownChevron) {
                        dropdownChevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
                    }
                });
            }

            if (mobileButton && mobileMenu) {
                mobileButton.addEventListener('click', () => {
                    const isOpen = !mobileMenu.classList.contains('hidden');
                    mobileMenu.classList.toggle('hidden');
                    mobileButton.setAttribute('aria-expanded', String(!isOpen));
                });
            }

            document.addEventListener('click', (event) => {
                if (dropdownMenu && profileButton && !dropdownMenu.contains(event.target) && !profileButton.contains(event.target)) {
                    dropdownMenu.classList.add('hidden');
                    if (dropdownChevron) dropdownChevron.style.transform = 'rotate(0deg)';
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    dropdownMenu?.classList.add('hidden');
                    if (dropdownChevron) dropdownChevron.style.transform = 'rotate(0deg)';
                    mobileMenu?.classList.add('hidden');
                    mobileButton?.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>
</body>

</html>