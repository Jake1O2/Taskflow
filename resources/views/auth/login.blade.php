@extends('layouts.guest')

@section('content')
    <div class="w-full max-w-md mx-auto bg-white/95 rounded-2xl shadow-xl p-8 space-y-8">
        <header class="space-y-2 text-center">
            <h1 class="text-2xl font-bold text-gray-900">Connexion</h1>
            <p class="text-sm text-gray-500">
                Connectez-vous pour accéder à votre tableau de bord TaskFlow.
            </p>
        </header>

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="font-medium">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div class="space-y-1.5">
                <label for="email" class="block text-sm font-medium text-gray-700">
                    Adresse email
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-primary focus:ring-primary"
                    placeholder="vous@exemple.com"
                >
            </div>

            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Mot de passe
                    </label>
                </div>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-primary focus:ring-primary"
                    placeholder="Votre mot de passe"
                >
            </div>

            <div class="flex items-center justify-between">
                <label class="inline-flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="remember"
                        class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                    >
                    <span class="text-sm text-gray-600">Rester connecté</span>
                </label>
            </div>

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
            >
                Se connecter
            </button>
        </form>

        <p class="text-center text-sm text-gray-500">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="font-semibold text-primary hover:text-primary-dark">
                Créer un compte
            </a>
        </p>
    </div>
@endsection

