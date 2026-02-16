@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-up">
        <header>
            <h1 class="text-3xl font-bold text-gray-900">Modifier le projet</h1>
            <p class="text-gray-500 font-medium mt-1">Ajustez les détails de votre projet pour qu'il reste sur la bonne voie.</p>
        </header>

        <div class="card-premium overflow-hidden">
            <form action="{{ route('projects.update', $project->id) }}" method="POST" class="space-y-8 p-1">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Title & Team -->
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label for="title" class="text-sm font-bold text-gray-700 ml-1">Titre du projet</label>
                            <x-text-input type="text" name="title" id="title" value="{{ old('title', $project->title) }}" required />
                            @error('title')
                                <p class="text-danger text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="team_id" class="text-sm font-bold text-gray-700 ml-1">Équipe (optionnel)</label>
                            <select name="team_id" id="team_id" class="w-full border-none bg-gray-100/50 focus:bg-white focus:ring-4 focus:ring-primary/10 rounded-2xl py-3 px-4 text-gray-900 text-sm transition-all shadow-inner appearance-none">
                                <option value="">Projet Personnel</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}" {{ old('team_id', $project->team_id) == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Status & Date -->
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label for="status" class="text-sm font-bold text-gray-700 ml-1">Statut actuel</label>
                            <select name="status" id="status" class="w-full border-none bg-gray-100/50 focus:bg-white focus:ring-4 focus:ring-primary/10 rounded-2xl py-3 px-4 text-gray-900 text-sm transition-all shadow-inner appearance-none">
                                <option value="preparation" {{ $project->status == 'preparation' ? 'selected' : '' }}>En préparation</option>
                                <option value="en_cours" {{ $project->status == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                <option value="termine" {{ $project->status == 'termine' ? 'selected' : '' }}>Terminé</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="due_date" class="text-sm font-bold text-gray-700 ml-1">Date d'échéance</label>
                            <x-text-input type="date" name="due_date" id="due_date" value="{{ old('due_date', $project->due_date ? \Carbon\Carbon::parse($project->due_date)->format('Y-m-d') : '') }}" />
                        </div>
                    </div>

                    <!-- Description (Full Width) -->
                    <div class="md:col-span-2 space-y-2">
                        <label for="description" class="text-sm font-bold text-gray-700 ml-1">Description du projet</label>
                        <textarea name="description" id="description" rows="5" class="w-full border-none bg-gray-100/50 focus:bg-white focus:ring-4 focus:ring-primary/10 rounded-2xl py-3 px-4 text-gray-900 placeholder-gray-400 text-sm transition-all shadow-inner">{{ old('description', $project->description) }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-8 border-t border-gray-50">
                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Attention ! Toutes les tâches associées seront supprimées. Confirmer ?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-2.5 text-danger font-bold text-sm hover:bg-danger/5 rounded-2xl transition-colors">
                            Supprimer le projet
                        </button>
                    </form>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('projects.show', $project->id) }}" class="px-5 py-2.5 text-gray-500 hover:text-gray-900 font-bold text-sm transition-colors">
                            Annuler
                        </a>
                        <x-primary-button>
                            Sauvegarder
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection