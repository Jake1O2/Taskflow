@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 animate-slide-down">
                <div class="p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2 border-b pb-4 border-gray-100">Créer une tâche</h1>
                    <p class="text-gray-500 mb-6 text-sm">Ajoutez une nouvelle tâche à réaliser.</p>

                    <form action="{{ route('tasks.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Hidden Project ID if passed via query param -->
                        @if(request('project_id'))
                            <input type="hidden" name="project_id" value="{{ request('project_id') }}">
                        @endif

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre de la tâche</label>
                            <x-text-input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full" placeholder="Ex: Rédiger le rapport mensuel" required autofocus />
                            @error('title')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-200 focus:scale-[1.01]"
                                      placeholder="Détails de la tâche...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Project Selection (only if not pre-selected) -->
                        @if(!request('project_id'))
                            <div>
                                <label for="project_id" class="block text-sm font-medium text-gray-700 mb-1">Projet associé (optionnel)</label>
                                <select name="project_id" id="project_id" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-200">
                                    <option value="">Aucun projet</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->title }}</option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                                <select name="status" id="status" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-200">
                                    <option value="todo">À faire</option>
                                    <option value="in_progress">En cours</option>
                                    <option value="done">Terminé</option>
                                </select>
                            </div>
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Date d'échéance</label>
                                <x-text-input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="w-full" />
                            </div>
                        </div>
                        
                         <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                            <select name="priority" id="priority" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-200">
                                <option value="low">Basse</option>
                                <option value="medium" selected>Moyenne</option>
                                <option value="high">Haute</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                            <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-gray-900 font-medium text-sm transition-colors">
                                Annuler
                            </a>
                            <x-primary-button>
                                Créer la tâche
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection