@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Tâches</h2>
                <a href="{{ route('projects.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Voir les projets</a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(isset($tasks) && $tasks->count() > 0)
                        <ul class="divide-y divide-gray-200">
                            @foreach($tasks as $task)
                                <li class="py-4 flex justify-between items-center">
                                    <div>
                                        <a href="{{ route('tasks.show', $task->id) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600">{{ $task->title }}</a>
                                        <p class="text-sm text-gray-500">{{ $task->project->title ?? '' }}</p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $task->status === 'done' ? 'bg-green-100 text-green-800' : ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                        <a href="{{ route('tasks.edit', $task->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Éditer</a>
                                        <a href="{{ route('projects.show', $task->project_id) }}" class="text-gray-600 hover:text-gray-900 text-sm">Projet</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 text-center py-8">Aucune tâche trouvée.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
