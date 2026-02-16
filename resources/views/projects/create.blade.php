@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-up">
        <header>
            <h1 class="text-3xl font-bold text-gray-900">Nouveau Projet</h1>
            <p class="text-gray-500 font-medium mt-1">Planifiez votre prochaine grande réussite.</p>
        </header>

        <div class="card-premium overflow-hidden">
            <form action="{{ route('projects.store') }}" method="POST" class="space-y-8 p-1">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Title & Team -->
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label for="title" class="text-sm font-bold text-gray-700 ml-1">Titre du projet</label>
                            <x-text-input type="text" name="title" id="title" placeholder="Ex: Refonte du Site Web" value="{{ old('title') }}" required autofocus />
                            @error('title')
                                <p class="text-danger text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="team_id" class="text-sm font-bold text-gray-700 ml-1">Équipe (optionnel)</label>
                            <select name="team_id" id="team_id" class="w-full border-none bg-gray-100/50 focus:bg-white focus:ring-4 focus:ring-primary/10 rounded-2xl py-3 px-4 text-gray-900 text-sm transition-all shadow-inner appearance-none">
                                <option value="">Projet Personnel</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Status & Date -->
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label for="status" class="text-sm font-bold text-gray-700 ml-1">Statut initial</label>
                            <select name="status" id="status" class="w-full border-none bg-gray-100/50 focus:bg-white focus:ring-4 focus:ring-primary/10 rounded-2xl py-3 px-4 text-gray-900 text-sm transition-all shadow-inner appearance-none">
                                <option value="preparation">En préparation</option>
                                <option value="en_cours">En cours</option>
                                <option value="termine">Terminé</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="due_date" class="text-sm font-bold text-gray-700 ml-1">Date d'échéance</label>
                            <x-text-input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" />
                        </div>
                    </div>

                    <!-- Description (Full Width) -->
                    <div class="md:col-span-2 space-y-2">
                        <label for="description" class="text-sm font-bold text-gray-700 ml-1">Description du projet</label>
                        <textarea name="description" id="description" rows="5" class="w-full border-none bg-gray-100/50 focus:bg-white focus:ring-4 focus:ring-primary/10 rounded-2xl py-3 px-4 text-gray-900 placeholder-gray-400 text-sm transition-all shadow-inner" placeholder="Décrivez les objectifs, les livrables et le contexte du projet...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-8 border-t border-gray-50">
                    <a href="{{ route('projects.index') }}" class="px-5 py-2.5 text-gray-500 hover:text-gray-900 font-bold text-sm transition-colors">
                        Annuler
                    </a>
                    <x-primary-button>
                        Lancer le projet
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
@endsection