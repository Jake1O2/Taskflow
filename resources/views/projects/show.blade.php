@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm animate-fade" role="alert">
                    <p class="font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Succès
                    </p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6 border border-gray-100 animate-slide-down">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row justify-between md:items-start gap-6">
                        <div class="flex-1">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="shrink-0 h-16 w-16 flex items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-bold text-3xl shadow-md">
                                    {{ substr($project->title, 0, 1) }}
                                </div>
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900">{{ $project->title }}</h1>
                                    @php
                                        $statusStyles = [
                                            'preparation' => 'bg-gray-100 text-gray-800 border-gray-200',
                                            'in_progress' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'completed' => 'bg-green-100 text-green-800 border-green-200',
                                        ];
                                        $statusLabels = [
                                            'preparation' => 'En préparation',
                                            'in_progress' => 'En cours',
                                            'completed' => 'Terminé',
                                        ];
                                    @endphp
                                    <span class="mt-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full border {{ $statusStyles[$project->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$project->status] ?? ucfirst($project->status) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="prose prose-indigo max-w-none text-gray-600 bg-gray-50 p-4 rounded-lg border border-gray-100">
                                {{ $project->description ?: 'Aucune description disponible.' }}
                            </div>
                            
                            <div class="flex gap-8 mt-6 text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="font-semibold">Début :</span> 
                                    {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') : 'Non définie' }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="font-semibold">Fin :</span> 
                                    {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') : 'Non définie' }}
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 w-full md:w-auto">
                            <a href="{{ route('projects.edit', $project->id) }}" class="inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                Éditer
                            </a>
                            <a href="{{ route('projects.kanban', $project->id) }}" class="inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>
                                Vue Kanban
                            </a>
                            <a href="{{ route('projects.calendar', $project->id) }}" class="inline-flex justify-center items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 transition ease-in-out duration-150 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Vue Calendrier
                            </a>
                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition ease-in-out duration-150 shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 animate-slide-down" style="animation-delay: 0.1s">
                <div class="p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            Tâches du projet
                        </h3>
                        <a href="{{ route('tasks.create', ['projectId' => $project->id]) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-indigo-700 hover:to-purple-700 shadow-md transform hover:-translate-y-0.5 transition-all duration-200">
                            + Ajouter une tâche
                        </a>
                    </div>

                    @if($project->tasks->count() > 0)
                        <div class="space-y-4">
                            @foreach($project->tasks as $task)
                                <div class="group flex flex-col md:flex-row justify-between items-center p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md hover:border-indigo-300 transition-all duration-200">
                                    <div class="flex-1 w-full">
                                        <div class="flex items-center justify-between md:justify-start gap-3 mb-2 md:mb-0">
                                            <a href="{{ route('tasks.show', $task->id) }}" class="text-lg font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors">
                                                {{ $task->title }}
                                            </a>
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                {{ $task->status === 'done' ? 'bg-green-100 text-green-800 border border-green-200' : 
                                                   ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 
                                                   'bg-gray-100 text-gray-800 border border-gray-200') }}">
                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1 line-clamp-1">{{ $task->description }}</p>
                                    </div>
                                    
                                    <div class="flex items-center gap-4 mt-4 md:mt-0 w-full md:w-auto justify-end">
                                        @if($task->due_date)
                                            <div class="text-xs text-gray-500 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                            </div>
                                        @endif
                                        <div class="flex items-center gap-2 opacity-50 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('tasks.edit', $task->id) }}" class="p-1 text-gray-400 hover:text-indigo-600 transition-colors" title="Éditer">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Supprimer cette tâche ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 text-gray-400 hover:text-red-600 transition-colors" title="Supprimer">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <p class="text-gray-500 italic">Aucune tâche pour ce projet pour le moment.</p>
                            <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold mt-2 inline-block">Commencer par en ajouter une &rarr;</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection