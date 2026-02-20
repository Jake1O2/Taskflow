@extends('layouts.guest')

@section('content')
    <div
        class="glass-premium rounded-[40px] p-12 sm:p-16 animate-fade-in relative overflow-hidden border border-white/10 text-center">
        {{-- Grain Texture --}}
        <div class="absolute inset-0 grain-texture pointer-events-none"></div>

        {{-- Floating orb accents --}}
        <div class="absolute -top-8 -left-8 w-32 h-32 rounded-full blur-3xl opacity-30"
            style="background: radial-gradient(circle, #2a78b6, transparent);"></div>
        <div class="absolute -bottom-8 -right-8 w-24 h-24 rounded-full blur-2xl opacity-20"
            style="background: radial-gradient(circle, #0d9488, transparent);"></div>

        <div class="relative z-10">
            {{-- Logo --}}
            <div class="flex flex-col items-center mb-10"
                style="animation: slideUp 0.55s cubic-bezier(0.22, 1, 0.36, 1) both; animation-delay: 100ms;">
                <div class="relative w-24 h-24 mb-8">
                    <div class="absolute inset-0 bg-blue-500/25 rounded-[32px] blur-2xl animate-pulse"></div>
                    <div class="relative w-24 h-24 rounded-[28px] flex items-center justify-center shadow-2xl shadow-blue-500/30 border border-white/20 hover:scale-105 transition-transform duration-500"
                        style="background: linear-gradient(135deg, #1a68a8, #0d9488);">
                        <svg width="56" height="56" style="color: white; width: 56px; height: 56px;"
                            class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>

                <h1 class="text-5xl sm:text-6xl font-black tracking-tighter text-white leading-tight mb-4">
                    Maîtrisez votre<br>
                    <span class="text-gradient-electric">flux.</span>
                </h1>
                <p class="text-gray-400 font-medium text-base leading-relaxed max-w-sm mx-auto">
                    Gérez vos projets et collaborez avec une fluidité sans précédent.
                </p>
            </div>

            {{-- Feature Pills --}}
            <div class="flex flex-wrap justify-center gap-2 mb-10"
                style="animation: slideUp 0.55s cubic-bezier(0.22, 1, 0.36, 1) both; animation-delay: 250ms;">
                <span
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/6 border border-white/10 text-gray-300 text-sm font-semibold">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Projets & Tâches
                </span>
                <span
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/6 border border-white/10 text-gray-300 text-sm font-semibold">
                    <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Travail en équipe
                </span>
                <span
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/6 border border-white/10 text-gray-300 text-sm font-semibold">
                    <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Kanban & Calendrier
                </span>
            </div>

            {{-- CTA Buttons --}}
            <div class="grid grid-cols-1 gap-4 mb-12"
                style="animation: slideUp 0.55s cubic-bezier(0.22, 1, 0.36, 1) both; animation-delay: 400ms;">
                <a href="{{ route('login') }}"
                    class="relative group overflow-hidden rounded-[22px] py-5 px-8 transition-all duration-500 active:scale-[0.97] shadow-2xl shadow-blue-500/30"
                    style="background: linear-gradient(135deg, #0f4c81, #1a68a8);">
                    {{-- Shimmer --}}
                    <div
                        class="absolute inset-x-0 top-0 h-full w-[200%] bg-gradient-to-r from-transparent via-white/15 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700 ease-in-out">
                    </div>
                    <span
                        class="relative z-10 flex items-center justify-center gap-3 text-white text-[17px] font-black tracking-tight uppercase italic">
                        Connexion
                        <svg class="w-5 h-5 group-hover:translate-x-1.5 transition-transform duration-300" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M11 16l4-4m0 0l-4-4m4 4H9m6 4a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                </a>

                <a href="{{ route('register') }}"
                    class="relative group overflow-hidden bg-white/6 border border-white/12 hover:bg-white/10 rounded-[22px] py-5 px-8 transition-all duration-500 active:scale-[0.97]">
                    <span
                        class="relative z-10 flex items-center justify-center gap-3 text-gray-300 group-hover:text-white text-[17px] font-black tracking-tight uppercase italic transition-colors">
                        Rejoindre l'aventure
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                            </path>
                        </svg>
                    </span>
                </a>
            </div>

            {{-- Footer note --}}
            <div class="pt-6 border-t border-white/5 opacity-40"
                style="animation: slideUp 0.55s cubic-bezier(0.22, 1, 0.36, 1) both; animation-delay: 600ms;">
                <p class="text-[12px] font-bold text-gray-500 tracking-widest uppercase">
                    Propulsé par <span class="text-blue-400/80">TaskFlow Engine v4.0</span>
                </p>
            </div>
        </div>
    </div>
@endsection