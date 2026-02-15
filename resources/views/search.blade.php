@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 flex items-center gap-2 animate-slide-down">
                Résultats de recherche
                <span class="text-lg font-normal text-gray-500 ml-2">pour "<span class="font-semibold text-gray-800">{{ $query }}</span>"</span>
            </h1>

            <div class="mb-6 animate-fade" style="animation-delay: 0.1s">
                <div class="flex space-x-1 bg-white p-1 rounded-lg border border-gray-200 inline-flex">
                    <a href="{{ route('search', ['q' => $query, 'type' => 'all']) }}" 
                       class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $type === 'all' ? 'bg-blue-100 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Tout
                    </a>
                    <a href="{{ route('search', ['q' => $query, 'type' => 'projects']) }}" 
                       class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $type === 'projects' ? 'bg-indigo-100 text-indigo-700 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Projets
                    </a>
                    <a href="{{ route('search', ['q' => $query, 'type' => 'tasks']) }}" 
                       class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $type === 'tasks' ? 'bg-green-100 text-green-700 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Tâches
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-slide-down" style="animation-delay: 0.2s">
                <!-- Projets trouvés -->
                @if($type === 'all' || $type === 'projects')
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 h-full">
                        <div class="p-6 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                Projets ({{ $projects->count() }})
                            </h2>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse($projects as $project)
                                <a href="{{ route('projects.show', $project->id) }}" class="block p-4 hover:bg-gray-50 transition-colors duration-200 group">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $project->title }}</h3>
                                            <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $project->description }}</p>
                                        </div>
                                        <span class="px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-600 whitespace-nowrap">
                                            {{ ucfirst($project->status) }}
                                        </span>
                                    </div>
                                </a>
                            @empty
                                <div class="p-8 text-center text-gray-500 italic text-sm">
                                    Aucun projet trouvé.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif

                <!-- Tâches trouvées -->
                @if($type === 'all' || $type === 'tasks')
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 h-full">
                        <div class="p-6 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                Tâches ({{ $tasks->count() }})
                            </h2>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse($tasks as $task)
                                <a href="{{ route('tasks.show', $task->id) }}" class="block p-4 hover:bg-gray-50 transition-colors duration-200 group">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-sm font-bold text-gray-900 group-hover:text-green-600 transition-colors">{{ $task->title }}</h3>
                                            <div class="flex gap-2 mt-1">
                                                @if($task->project)
                                                    <span class="text-xs text-blue-500 bg-blue-50 px-1.5 py-0.5 rounded">{{ $task->project->title }}</span>
                                                @endif
                                                <p class="text-xs text-gray-500 line-clamp-1 flex-1">{{ $task->description }}</p>
                                            </div>
                                        </div>
                                        <span class="px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-600 whitespace-nowrap">
                                            {{ ucfirst($task->status) }}
                                        </span>
                                    </div>
                                </a>
                            @empty
                                <div class="p-8 text-center text-gray-500 italic text-sm">
                                    Aucune tâche trouvée.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>

            @if($projects->isEmpty() && $tasks->isEmpty())
                <div class="mt-8 text-center">
                    <p class="text-gray-500">Essayez avec d'autres mots-clés.</p>
                </div>
            @endif
        </div>
    </div>
@endsection