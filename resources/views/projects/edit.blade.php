@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 animate-slide-down">
                <div class="p-8">
                    <div class="flex justify-between items-center mb-6 border-b pb-4 border-gray-100">
                        <h1 class="text-3xl font-bold text-gray-900">Modifier le projet</h1>
                        <span class="text-sm text-gray-400">ID: {{ $project->id }}</span>
                    </div>

                    <form action="{{ route('projects.update', $project->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre du projet</label>
                            <x-text-input type="text" name="title" id="title" value="{{ old('title', $project->title) }}" class="w-full" required />
                            @error('title')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-200 focus:scale-[1.01]">{{ old('description', $project->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                                <x-text-input type="date" name="start_date" id="start_date" value="{{ old('start_date', $project->start_date) }}" class="w-full" />
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                                <x-text-input type="date" name="end_date" id="end_date" value="{{ old('end_date', $project->end_date) }}" class="w-full" />
                            </div>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                            <select name="status" id="status" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-200">
                                <option value="preparation" {{ $project->status == 'preparation' ? 'selected' : '' }}>En préparation</option>
                                <option value="in_progress" {{ $project->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>Terminé</option>
                            </select>
                        </div>
                        
                         <!-- Team Selection -->
                        @if($teams->count() > 0)
                            <div>
                                <label for="team_id" class="block text-sm font-medium text-gray-700 mb-1">Équipe associée</label>
                                <select name="team_id" id="team_id" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-200">
                                    <option value="">Aucune équipe</option>
                                    @foreach($teams as $team)
                                            <option value="{{ $team->id }}" {{ $project->team_id == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('projects.index') }}" class="text-gray-600 hover:text-gray-900 font-medium text-sm transition-colors">
                                Annuler
                            </a>
                            <x-primary-button>
                                Mettre à jour
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection