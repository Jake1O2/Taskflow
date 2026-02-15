@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm shadow-gray-200 rounded-xl border border-gray-200 animate-slide-down">
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                        <div class="bg-indigo-50 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Modifier la tâche</h1>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-mono text-gray-400 bg-gray-50 px-2 py-0.5 rounded border border-gray-100">ID: #{{ $task->id }}</span>
                                <span class="text-gray-500 text-sm">Mise à jour des informations</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="space-y-1">
                            <label for="title" class="block text-sm font-medium text-gray-700">Titre de la tâche <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all duration-200" required />
                            @error('title')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all duration-200"
                                      >{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="project_id" class="block text-sm font-medium text-gray-700">Projet associé</label>
                            <select name="project_id" id="project_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all duration-200 cursor-pointer">
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
                            <div class="space-y-1">
                                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                                <div class="relative">
                                    <select name="status" id="status" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all duration-200 cursor-pointer appearance-none">
                                        <option value="todo" {{ $task->status == 'todo' ? 'selected' : '' }}>À faire</option>
                                        <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                        <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Terminé</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label for="due_date" class="block text-sm font-medium text-gray-700">Date d'échéance</label>
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $task->due_date) }}" 
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all duration-200" />
                            </div>
                        </div>
                        
                         <div class="space-y-1">
                            <label for="priority" class="block text-sm font-medium text-gray-700">Priorité</label>
                            <div class="relative">
                                <select name="priority" id="priority" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all duration-200 cursor-pointer appearance-none">
                                    <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Basse</option>
                                    <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                    <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>Haute</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100 mt-8">
                            <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-gray-900 font-medium text-sm transition-colors px-4 py-2 rounded-lg hover:bg-gray-100">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex justify-center items-center px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-semibold text-sm shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection