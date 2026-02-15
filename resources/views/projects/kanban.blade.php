@extends('layouts.app')

@section('content')
    <div class="py-12 h-screen flex flex-col">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w-full flex-1 flex flex-col">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 animate-slide-down">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <span class="text-indigo-600 bg-indigo-50 p-1.5 rounded-lg border border-indigo-100">📊</span> 
                        Kanban: <span class="text-gray-600 ml-1">{{ $project->title }}</span>
                    </h2>
                    <p class="text-sm text-gray-500 mt-1 ml-11">Gérez les tâches par statut</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('projects.show', $project->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 shadow-sm hover:bg-gray-50 hover:text-gray-900 transition-all duration-200">
                        &larr; Retour au projet
                    </a>
                    <a href="{{ route('tasks.create', ['projectId' => $project->id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white shadow-sm hover:bg-blue-700 hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Ajouter une tâche
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in flex-1 min-h-0" style="animation-delay: 100ms;">
                <!-- Colonne À faire -->
                <div class="bg-gray-50 rounded-xl border border-gray-200 flex flex-col h-full max-h-[calc(100vh-200px)]">
                    <div class="p-4 border-b border-gray-200 bg-white rounded-t-xl sticky top-0 z-10 flex justify-between items-center shadow-sm">
                        <h3 class="font-bold text-gray-700 flex items-center gap-2 text-sm tracking-wide">
                            <span class="w-3 h-3 rounded-full bg-gray-400"></span> À FAIRE
                        </h3>
                        <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2.5 py-0.5 rounded-full border border-gray-200">{{ count($tasksByStatus['todo']) }}</span>
                    </div>
                    <div class="p-3 space-y-3 flex-1 overflow-y-auto custom-scrollbar">
                        @forelse($tasksByStatus['todo'] as $task)
                            <a href="{{ route('tasks.edit', $task->id) }}" class="block bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:shadow-md hover:border-blue-300 transition-all duration-200 group transform hover:-translate-y-1 relative overflow-hidden">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-gray-300 group-hover:bg-blue-500 transition-colors"></div>
                                <div class="pl-2">
                                    <h4 class="font-semibold text-gray-800 text-sm group-hover:text-blue-600 transition-colors mb-1">{{ $task->title }}</h4>
                                    @if($task->description)
                                        <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">{{ $task->description }}</p>
                                    @endif
                                    @if($task->due_date)
                                        <div class="mt-3 flex items-center text-xs text-gray-400 gap-1 bg-gray-50 inline-flex px-2 py-1 rounded border border-gray-100">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ \Carbon\Carbon::parse($task->due_date)->format('d M') }}
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="flex flex-col items-center justify-center py-10 text-gray-400 opacity-60">
                                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                <p class="text-sm italic">Vide</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Colonne En cours -->
                <div class="bg-blue-50/30 rounded-xl border border-blue-100 flex flex-col h-full max-h-[calc(100vh-200px)]">
                    <div class="p-4 border-b border-blue-100 bg-blue-50/50 rounded-t-xl sticky top-0 z-10 flex justify-between items-center shadow-sm backdrop-blur-sm">
                        <h3 class="font-bold text-blue-800 flex items-center gap-2 text-sm tracking-wide">
                            <span class="w-3 h-3 rounded-full bg-blue-500 animate-pulse"></span> EN COURS
                        </h3>
                        <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-0.5 rounded-full border border-blue-200">{{ count($tasksByStatus['in_progress']) }}</span>
                    </div>
                    <div class="p-3 space-y-3 flex-1 overflow-y-auto custom-scrollbar">
                        @forelse($tasksByStatus['in_progress'] as $task)
                             <a href="{{ route('tasks.edit', $task->id) }}" class="block bg-white p-4 rounded-lg shadow-sm border border-blue-100 hover:shadow-md hover:border-blue-400 transition-all duration-200 group transform hover:-translate-y-1 relative overflow-hidden">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500"></div>
                                <div class="pl-2">
                                    <h4 class="font-semibold text-gray-800 text-sm group-hover:text-blue-600 transition-colors mb-1">{{ $task->title }}</h4>
                                    @if($task->description)
                                        <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">{{ $task->description }}</p>
                                    @endif
                                    @if($task->due_date)
                                      <div class="mt-3 flex items-center text-xs {{ \Carbon\Carbon::parse($task->due_date)->isPast() ? 'text-red-500 bg-red-50 border-red-100' : 'text-blue-500 bg-blue-50 border-blue-100' }} gap-1 inline-flex px-2 py-1 rounded border">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ \Carbon\Carbon::parse($task->due_date)->format('d M') }}
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @empty
                             <div class="flex flex-col items-center justify-center py-10 text-blue-300 opacity-60">
                                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                <p class="text-sm italic">Rien en cours</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Colonne Terminée -->
                <div class="bg-emerald-50/30 rounded-xl border border-emerald-100 flex flex-col h-full max-h-[calc(100vh-200px)]">
                    <div class="p-4 border-b border-emerald-100 bg-emerald-50/50 rounded-t-xl sticky top-0 z-10 flex justify-between items-center shadow-sm backdrop-blur-sm">
                        <h3 class="font-bold text-emerald-800 flex items-center gap-2 text-sm tracking-wide">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span> TERMINÉ
                        </h3>
                        <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2.5 py-0.5 rounded-full border border-emerald-200">{{ count($tasksByStatus['done']) }}</span>
                    </div>
                    <div class="p-3 space-y-3 flex-1 overflow-y-auto custom-scrollbar">
                        @forelse($tasksByStatus['done'] as $task)
                            <a href="{{ route('tasks.edit', $task->id) }}" class="block bg-white p-4 rounded-lg shadow-sm border border-emerald-100 hover:shadow-md hover:border-emerald-300 transition-all duration-200 group transform hover:-translate-y-1 relative overflow-hidden opacity-90 hover:opacity-100">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500"></div>
                                <div class="pl-2">
                                    <h4 class="font-semibold text-gray-500 line-through text-sm group-hover:text-emerald-700 transition-colors mb-1">{{ $task->title }}</h4>
                                    @if($task->description)
                                        <p class="text-xs text-gray-400 line-clamp-2 leading-relaxed">{{ $task->description }}</p>
                                    @endif
                                    <div class="mt-3 flex items-center justify-end">
                                        <span class="text-xs text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Fait
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @empty
                             <div class="flex flex-col items-center justify-center py-10 text-emerald-300 opacity-60">
                                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-sm italic">Aucune tâche terminée</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection