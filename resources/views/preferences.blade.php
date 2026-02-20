@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="animate-slide-up">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight dark:text-white">Préférences</h1>
            <p class="text-gray-500 mt-1 font-medium dark:text-gray-400">Personnalisez votre expérience TaskFlow</p>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl animate-fade-in flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('preferences.update') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Theme Selector --}}
            <div class="card-internal dark:bg-gray-800 dark:border-gray-700">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Thème</h2>
                    <p class="text-sm text-gray-500 font-medium">Choisissez votre mode préféré</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach(['light' => 'Clair', 'dark' => 'Sombre', 'auto' => 'Auto'] as $val => $label)
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="theme" value="{{ $val }}" class="peer sr-only" {{ $preference->theme == $val ? 'checked' : '' }}>
                            <div
                                class="p-4 rounded-2xl border-2 border-gray-100 bg-gray-50/50 peer-checked:border-primary peer-checked:bg-primary/5 transition-all group-hover:border-gray-200 dark:bg-gray-700/50 dark:border-gray-600 dark:peer-checked:border-primary">
                                <div class="flex flex-col items-center text-center gap-3">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-gray-400 group-hover:scale-110 transition-transform dark:bg-gray-800">
                                        @if($val == 'light')
                                            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z" />
                                            </svg>
                                        @elseif($val == 'dark')
                                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-sm text-gray-900 dark:text-white">{{ $label }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">
                                            @if($val == 'light') Interface claire @elseif($val == 'dark') Interface sombre @else
                                            Système @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Color Customization --}}
            <div class="card-internal dark:bg-gray-800 dark:border-gray-700">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Couleurs</h2>
                    <p class="text-sm text-gray-500 font-medium">Personnalisez les couleurs de l'application</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <label class="block">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Couleur Primaire</span>
                            <div class="mt-2 flex items-center gap-4">
                                <input type="color" name="primary_color" value="{{ $preference->primary_color }}"
                                    class="h-12 w-12 rounded-xl cursor-pointer border-none p-0 bg-transparent overflow-hidden shadow-sm">
                                <div
                                    class="flex-1 p-3 rounded-xl bg-gray-50 border border-gray-100 text-xs font-bold text-gray-500 dark:bg-gray-700 dark:border-gray-600">
                                    Utilisée pour les boutons et les liens principaux
                                </div>
                            </div>
                        </label>

                        <label class="block">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Couleur d'Accent</span>
                            <div class="mt-2 flex items-center gap-4">
                                <input type="color" name="accent_color" value="{{ $preference->accent_color }}"
                                    class="h-12 w-12 rounded-xl cursor-pointer border-none p-0 bg-transparent overflow-hidden shadow-sm">
                                <div
                                    class="flex-1 p-3 rounded-xl bg-gray-50 border border-gray-100 text-xs font-bold text-gray-500 dark:bg-gray-700 dark:border-gray-600">
                                    Utilisée pour les points d'attention et badges
                                </div>
                            </div>
                        </label>
                    </div>

                    <div
                        class="p-6 rounded-2xl bg-gray-50/50 border border-dashed border-gray-200 dark:bg-gray-900/50 dark:border-gray-700">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Aperçu en direct</p>
                        <div class="space-y-4">
                            <button type="button"
                                class="w-full py-3 px-4 rounded-xl text-white font-bold text-sm shadow-lg transition-all"
                                style="background-color: var(--primary)">Bouton Primaire</button>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold text-white uppercase"
                                    style="background-color: var(--accent)">Badge Accent</span>
                                <span class="text-xs font-bold" style="color: var(--primary)">Lien interactif</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex flex-wrap gap-2">
                    <p class="w-full text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Préréglages</p>
                    <button type="button" @click="$dispatch('set-colors', {p: '#2563eb', a: '#059669'})"
                        class="w-8 h-8 rounded-full bg-blue-600 border-2 border-white shadow-sm hover:scale-110 transition-transform"></button>
                    <button type="button" @click="$dispatch('set-colors', {p: '#7c3aed', a: '#db2777'})"
                        class="w-8 h-8 rounded-full bg-violet-600 border-2 border-white shadow-sm hover:scale-110 transition-transform"></button>
                    <button type="button" @click="$dispatch('set-colors', {p: '#0f766e', a: '#b54708'})"
                        class="w-8 h-8 rounded-full bg-teal-700 border-2 border-white shadow-sm hover:scale-110 transition-transform"></button>
                    <button type="button" @click="$dispatch('set-colors', {p: '#dc2626', a: '#92400e'})"
                        class="w-8 h-8 rounded-full bg-red-600 border-2 border-white shadow-sm hover:scale-110 transition-transform"></button>
                </div>
            </div>

            {{-- Locale Settings --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="card-internal dark:bg-gray-800 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Langue</h2>
                    <select name="language"
                        class="w-full rounded-xl border-gray-100 bg-gray-50 font-bold text-sm focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="fr" {{ $preference->language == 'fr' ? 'selected' : '' }}>Français</option>
                        <option value="en" {{ $preference->language == 'en' ? 'selected' : '' }}>English</option>
                        <option value="es" {{ $preference->language == 'es' ? 'selected' : '' }}>Español</option>
                    </select>
                </div>

                <div class="card-internal dark:bg-gray-800 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Fuseau horaire</h2>
                    <select name="timezone"
                        class="w-full rounded-xl border-gray-100 bg-gray-50 font-bold text-sm focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="Europe/Paris" {{ $preference->timezone == 'Europe/Paris' ? 'selected' : '' }}>(UTC+1)
                            Europe/Paris</option>
                        <option value="UTC" {{ $preference->timezone == 'UTC' ? 'selected' : '' }}>(UTC+0) UTC</option>
                        <option value="America/New_York" {{ $preference->timezone == 'America/New_York' ? 'selected' : '' }}>
                            (UTC-5) New York</option>
                    </select>
                </div>
            </div>

            {{-- Additional Toggles --}}
            <div class="card-internal dark:bg-gray-800 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Notifications & Sécurité</h2>
                <div class="space-y-4">
                    <label
                        class="flex items-center justify-between p-4 rounded-2xl bg-gray-50/50 border border-gray-100 hover:bg-white hover:shadow-sm transition-all cursor-pointer dark:bg-gray-700/50 dark:border-gray-600">
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Notifications Email</p>
                            <p class="text-[11px] text-gray-400 font-medium">Recevoir les alertes d'activité par email</p>
                        </div>
                        <input type="checkbox" name="notifications_email" value="1" {{ $preference->notifications_email ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary">
                    </label>

                    <label
                        class="flex items-center justify-between p-4 rounded-2xl bg-gray-50/50 border border-gray-100 hover:bg-white hover:shadow-sm transition-all cursor-pointer dark:bg-gray-700/50 dark:border-gray-600">
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Résumé hebdomadaire</p>
                            <p class="text-[11px] text-gray-400 font-medium">Un récapitulatif de vos progrès chaque lundi
                            </p>
                        </div>
                        <input type="checkbox" name="notifications_weekly_summary" value="1" {{ $preference->notifications_weekly_summary ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary">
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4">
                <button type="button" class="btn-ghost text-danger border-danger/20 hover:bg-danger/5">Supprimer mon
                    compte</button>
                <button type="submit" class="btn-primary px-10 shadow-xl shadow-primary/20">Enregistrer les
                    changements</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            // Alpine initialization if needed
        });

        // Listen for preset colors
        window.addEventListener('set-colors', (e) => {
            document.querySelector('input[name="primary_color"]').value = e.detail.p;
            document.querySelector('input[name="accent_color"]').value = e.detail.a;
            // Update CSS variables for live preview
            document.documentElement.style.setProperty('--primary', e.detail.p);
            document.documentElement.style.setProperty('--accent', e.detail.a);
        });

        // Individual color picker changes
        document.querySelector('input[name="primary_color"]').addEventListener('input', (e) => {
            document.documentElement.style.setProperty('--primary', e.target.value);
        });
        document.querySelector('input[name="accent_color"]').addEventListener('input', (e) => {
            document.documentElement.style.setProperty('--accent', e.target.value);
        });
    </script>
@endsection