@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 animate-slide-down">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    Kanban: <span class="text-gray-600">{{ $project->title }}</span>
                </h2>
                <div class="flex gap-3">
                    <a href="{{ route('projects.show', $project->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition ease-in-out duration-150">
                        &larr; Retour
                    </a>
                    <a href="{{ route('tasks.create', ['projectId' => $project->id]) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-blue-700 hover:to-blue-800 shadow-md transform hover:-translate-y-0.5 transition-all duration-200">
                        + Ajouter une tâche
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade" style="animation-delay: 0.1s">
                <!-- Colonne À faire -->
                <div class="bg-gray-100 rounded-xl shadow-inner border border-gray-200 flex flex-col h-full min-h-[600px]">
                    <div class="p-4 border-b border-gray-200 bg-gray-50 rounded-t-xl sticky top-0 z-10">
                        <h3 class="font-bold text-gray-700 flex justify-between items-center uppercase text-sm tracking-wide">
                            À faire
                            <span class="bg-gray-200 text-gray-700 text-xs font-bold px-2.5 py-1 rounded-full border border-gray-300">{{ count($tasksByStatus['todo']) }}</span>
                        </h3>
                    </div>
                    <div class="p-4 space-y-3 flex-1 overflow-y-auto custom-scrollbar">
                        @forelse($tasksByStatus['todo'] as $task)
                            <a href="{{ route('tasks.edit', $task->id) }}" class="block bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 border-l-4 border-gray-400 group transform hover:-translate-y-1">
                                <h4 class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">{{ $task->title }}</h4>
                                @if($task->description)
                                    <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ $task->description }}</p>
                                @endif
                                <div class="mt-3 flex items-center justify-between">
                                    @if($task->due_date)
                                        <div class="flex items-center text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <p class="text-center text-gray-400 text-sm italic py-4">Aucune tâche à faire</p>
                        @endforelse
                    </div>
                </div>

                <!-- Colonne En cours -->
                <div class="bg-blue-50/50 rounded-xl shadow-inner border border-blue-100 flex flex-col h-full min-h-[600px]">
                    <div class="p-4 border-b border-blue-100 bg-blue-50 rounded-t-xl sticky top-0 z-10">
                        <h3 class="font-bold text-blue-700 flex justify-between items-center uppercase text-sm tracking-wide">
                            En cours
                            <span class="bg-blue-200 text-blue-800 text-xs font-bold px-2.5 py-1 rounded-full border border-blue-300">{{ count($tasksByStatus['in_progress']) }}</span>
                        </h3>
                    </div>
                    <div class="p-4 space-y-3 flex-1 overflow-y-auto custom-scrollbar">
                        @forelse($tasksByStatus['in_progress'] as $task)
                            <a href="{{ route('tasks.edit', $task->id) }}" class="block bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 border-l-4 border-blue-500 group transform hover:-translate-y-1">
                                <h4 class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">{{ $task->title }}</h4>
                                @if($task->description)
                                    <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ $task->description }}</p>
                                @endif
                                <div class="mt-3 flex items-center justify-between">
                                    @if($task->due_date)
                                        <div class="flex items-center text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <p class="text-center text-blue-300 text-sm italic py-4">Aucune tâche en cours</p>
                        @endforelse
                    </div>
                </div>

                <!-- Colonne Terminée -->
                <div class="bg-green-50/50 rounded-xl shadow-inner border border-green-100 flex flex-col h-full min-h-[600px]">
                    <div class="p-4 border-b border-green-100 bg-green-50 rounded-t-xl sticky top-0 z-10">
                        <h3 class="font-bold text-green-700 flex justify-between items-center uppercase text-sm tracking-wide">
                            Terminée
                            <span class="bg-green-200 text-green-800 text-xs font-bold px-2.5 py-1 rounded-full border border-green-300">{{ count($tasksByStatus['done']) }}</span>
                        </h3>
                    </div>
                    <div class="p-4 space-y-3 flex-1 overflow-y-auto custom-scrollbar">
                        @forelse($tasksByStatus['done'] as $task)
                            <a href="{{ route('tasks.edit', $task->id) }}" class="block bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 border-l-4 border-green-500 opacity-80 hover:opacity-100 group transform hover:-translate-y-1">
                                <h4 class="font-semibold text-gray-800 line-through group-hover:text-green-700 transition-colors">{{ $task->title }}</h4>
                                @if($task->description)
                                    <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ $task->description }}</p>
                                @endif
                                <div class="mt-3 flex items-center justify-between">
                                    @if($task->due_date)
                                        <div class="flex items-center text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <p class="text-center text-green-300 text-sm italic py-4">Aucune tâche terminée</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection