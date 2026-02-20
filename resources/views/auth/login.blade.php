@extends('layouts.guest')

@section('content')
    <div
        class="glass-premium rounded-[40px] p-10 sm:p-12 animate-fade-in relative overflow-hidden border border-white/10 shadow-2xl">
        {{-- Grain Texture for depth --}}
        <div class="absolute inset-0 grain-texture pointer-events-none opacity-20"></div>

        <div class="relative z-10">
            {{-- Header/Logo --}}
            <div class="flex flex-col items-center mb-10"
                style="animation: slideUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;">
                <div class="relative w-16 h-16 mb-6">
                    <div class="absolute inset-0 bg-primary/20 rounded-2xl blur-xl animate-pulse"></div>
                    <div
                        class="relative w-16 h-16 rounded-2xl flex items-center justify-center bg-gradient-to-br from-primary to-cyan-vibrant shadow-lg border border-white/10">
                        <svg width="32" height="32" style="color: white; width: 32px; height: 32px;" class="text-white"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-black text-white tracking-tight mb-2">Bienvenue</h1>
                <p class="text-gray-400 text-sm font-medium">Connectez-vous pour continuer</p>
            </div>

            {{-- Error Display --}}
            @if ($errors->any())
                <div class="mb-8 p-4 rounded-2xl bg-danger/10 border border-danger/20 text-danger animate-shake"
                    style="animation: slideUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) both; animation-delay: 100ms;">
                    <ul class="text-xs font-bold list-none space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-center gap-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div class="space-y-2 group"
                    style="animation: slideUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) both; animation-delay: 200ms;">
                    <label for="email"
                        class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1 transition-colors group-focus-within:text-primary">
                        Adresse Email
                    </label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 group-focus-within:text-primary transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="block w-full rounded-2xl bg-white/5 border border-white/10 pl-11 pr-4 py-3.5 text-white text-sm placeholder-gray-500 focus:bg-white/10 focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                            placeholder="votre@email.com">
                    </div>
                </div>

                <div class="space-y-2 group"
                    style="animation: slideUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) both; animation-delay: 300ms;">
                    <div class="flex items-center justify-between ml-1">
                        <label for="password"
                            class="block text-xs font-black text-gray-400 uppercase tracking-widest transition-colors group-focus-within:text-primary">
                            Mot de passe
                        </label>
                    </div>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 group-focus-within:text-primary transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required
                            class="block w-full rounded-2xl bg-white/5 border border-white/10 pl-11 pr-4 py-3.5 text-white text-sm placeholder-gray-500 focus:bg-white/10 focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between"
                    style="animation: slideUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) both; animation-delay: 400ms;">
                    <label class="inline-flex items-center gap-3 cursor-pointer group">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="remember" class="peer hidden">
                            <div
                                class="w-5 h-5 rounded-lg border-2 border-white/20 peer-checked:bg-primary peer-checked:border-primary transition-all flex items-center justify-center">
                                <svg class="w-3 h-3 text-white scale-0 peer-checked:scale-100 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span
                                class="text-xs font-bold text-gray-400 group-hover:text-gray-300 ml-3 transition-colors">Rester
                                connecté</span>
                        </div>
                    </label>
                </div>

                <div class="pt-2"
                    style="animation: slideUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) both; animation-delay: 500ms;">
                    <button type="submit"
                        class="relative group w-full overflow-hidden rounded-2xl py-4 transition-all duration-300 active:scale-95 shadow-xl shadow-primary/20"
                        style="background: linear-gradient(135deg, #0f4c81, #1a68a8);">
                        <div
                            class="absolute inset-x-0 top-0 h-full w-[200%] bg-gradient-to-r from-transparent via-white/15 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000 ease-in-out">
                        </div>
                        <span
                            class="relative z-10 flex items-center justify-center gap-2 text-white text-sm font-black uppercase italic tracking-widest">
                            Se Connecter
                            <svg class="w-4 h-4 group-hover:translate-x-1.5 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </span>
                    </button>
                </div>
            </form>

            {{-- Footer --}}
            <div class="mt-10 pt-8 border-t border-white/5 text-center"
                style="animation: slideUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) both; animation-delay: 600ms;">
                <p class="text-sm text-gray-500 font-medium">
                    Pas encore membre ?
                    <a href="{{ route('register') }}"
                        class="text-primary font-black hover:text-primary-light transition-colors ml-1 uppercase italic tracking-tighter text-xs">
                        Créer un profil premium
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection