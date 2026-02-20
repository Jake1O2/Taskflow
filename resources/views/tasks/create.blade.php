@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-up">
        <header>
            <h1 class="text-3xl font-bold text-gray-900">Nouvelle Tâche</h1>
            <p class="text-gray-500 font-medium mt-1">Définissez un objectif clair pour avancer sur votre projet.</p>
        </header>

        <div class="card-premium overflow-hidden">
            <form action="{{ route('tasks.store') }}" method="POST" class="space-y-8 p-1">
                @csrf
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label for="title" class="text-sm font-bold text-gray-700 ml-1">Titre de la tâche</label>
                        <x-text-input type="text" name="title" id="title" placeholder="Ex: Développer l'API d'authentification..." value="{{ old('title') }}" required autofocus />
                        @error('title')
                            <p class="text-danger text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label for="project_id" class="text-sm font-bold text-gray-700 ml-1">Projet</label>
                            <select name="project_id" id="project_id" class="w-full border-none bg-gray-100/50 focus:bg-white focus:ring-4 focus:ring-primary/10 rounded-2xl py-3 px-4 text-gray-900 text-sm transition-all shadow-inner appearance-none">
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ (isset($projectId) && $projectId == $project->id) || old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="due_date" class="text-sm font-bold text-gray-700 ml-1">Date d'échéance</label>
                            <x-text-input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" />
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <label for="description" class="text-sm font-bold text-gray-700 ml-1">Détails (optionnel)</label>
                        <textarea name="description" id="description" rows="4" class="w-full border-none bg-gray-100/50 focus:bg-white focus:ring-4 focus:ring-primary/10 rounded-2xl py-3 px-4 text-gray-900 placeholder-gray-400 text-sm transition-all shadow-inner" placeholder="Précisez les étapes ou les prérequis...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Status -->
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Statut</label>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach(['todo' => 'À faire', 'in_progress' => 'En cours', 'done' => 'Terminé'] as $val => $lab)
                                <label class="cursor-pointer group">
                                    <input type="radio" name="status" value="{{ $val }}" class="hidden peer" {{ $val === 'todo' ? 'checked' : '' }}>
                                    <div class="text-center py-3 rounded-2xl bg-gray-50 border border-gray-100 text-sm font-bold text-gray-500 peer-checked:bg-primary peer-checked:text-white peer-checked:border-primary peer-checked:shadow-lg peer-checked:shadow-primary/20 transition-all">
                                        {{ $lab }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-8 border-t border-gray-50">
                    <a href="{{ url()->previous() }}" class="px-5 py-2.5 text-gray-500 hover:text-gray-900 font-bold text-sm transition-colors">
                        Annuler
                    </a>
                    <x-primary-button>
                        Créer la tâche
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
@endsection