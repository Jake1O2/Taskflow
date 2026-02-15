@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Résultats de recherche</h1>

            @if($q)
                <p class="text-gray-600 mb-6">Résultats pour : <strong>{{ $q }}</strong></p>
            @else
                <p class="text-gray-600 mb-6">Entrez un terme de recherche</p>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Projets -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Projets ({{ $projects->count() }})</h2>
                        @if($projects->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach($projects as $project)
                                    <li class="py-3">
                                        <a href="{{ route('projects.show', $project->id) }}" class="text-blue-600 hover:text-blue-900">
                                            {{ $project->title }}
                                        </a>
                                        <p class="text-sm text-gray-500">{{ \Illuminate\Support\Str::limit($project->description, 50) }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">Aucun projet trouvé</p>
                        @endif
                    </div>
                </div>

                <!-- Tâches -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Tâches ({{ $tasks->count() }})</h2>
                        @if($tasks->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach($tasks as $task)
                                    <li class="py-3">
                                        <a href="{{ route('tasks.show', $task->id) }}" class="text-blue-600 hover:text-blue-900">
                                            {{ $task->title }}
                                        </a>
                                        <p class="text-sm text-gray-500">Projet: {{ $task->project->title }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">Aucune tâche trouvée</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('projects.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Retour
                </a>
            </div>
        </div>
    </div>
@endsection