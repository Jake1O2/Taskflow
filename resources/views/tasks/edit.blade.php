@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 animate-slide-down">
                <div class="p-8">
                    <div class="flex justify-between items-center mb-6 border-b pb-4 border-gray-100">
                        <h1 class="text-3xl font-bold text-gray-900">Modifier la tâche</h1>
                        <span class="text-sm text-gray-400">ID: {{ $task->id }}</span>
                    </div>

                    <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre de la tâche</label>
                            <x-text-input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" class="w-full" required />
                            @error('title')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-200 focus:scale-[1.01]">{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-1">Projet associé</label>
                            <select name="project_id" id="project_id" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-200">
                                <option value="">Aucun projet</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ (old('project_id') ?? $task->project_id) == $project->id ? 'selected' : '' }}>{{ $project->title }}</option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                                <select name="status" id="status" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-200">
                                    <option value="todo" {{ $task->status == 'todo' ? 'selected' : '' }}>À faire</option>
                                    <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                    <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Terminé</option>
                                </select>
                            </div>
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Date d'échéance</label>
                                <x-text-input type="date" name="due_date" id="due_date" value="{{ old('due_date', $task->due_date) }}" class="w-full" />
                            </div>
                        </div>
                        
                         <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                            <select name="priority" id="priority" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-200">
                                <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Basse</option>
                                <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>Haute</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                            <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-gray-900 font-medium text-sm transition-colors">
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