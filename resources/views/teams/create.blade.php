@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-up">
        <header>
            <h1 class="text-3xl font-bold text-gray-900">Créer une équipe</h1>
            <p class="text-gray-500 font-medium mt-1">Donnez un nom à votre nouvel espace de collaboration.</p>
        </header>

        <div class="card-premium">
            <form action="{{ route('teams.store') }}" method="POST" class="space-y-8">
                @csrf

                <div class="space-y-3">
                    <label for="name" class="text-sm font-bold text-gray-700 ml-1">Nom de l'équipe</label>
                    <x-text-input type="text" name="name" id="name" placeholder="Ex: Équipe Marketing, Studio créatif..." value="{{ old('name') }}" required autofocus />
                    @error('name')
                        <p class="text-danger text-xs mt-2 px-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-50">
                    <a href="{{ route('teams.index') }}" class="px-5 py-2.5 text-gray-500 hover:text-gray-900 font-bold text-sm transition-colors">
                        Annuler
                    </a>
                    <x-primary-button>
                        Créer l'équipe
                    </x-primary-button>
                </div>
            </form>
        </div>

        <!-- Help Info -->
        <div class="glass border-primary/10 bg-primary/5 p-6 rounded-2xl flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="text-sm text-primary/80 leading-relaxed font-medium">
                Vous pourrez ajouter des membres à cette équipe une fois qu'elle sera créée en consultant les détails de l'équipe.
            </div>
        </div>
    </div>
@endsection
