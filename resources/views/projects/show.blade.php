@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg shadow-sm animate-fade-in flex items-center" role="alert">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Project Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 animate-slide-down">
                <div class="flex flex-col md:flex-row justify-between items-start gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-4">
                            <h1 class="text-[32px] font-bold text-gray-900 leading-tight">{{ $project->title }}</h1>
                        </div>
                        
                        <div class="flex items-center gap-4 mb-6">
                            @php
                                $statusStyles = [
                                    'preparation' => 'bg-gray-100 text-gray-700',
                                    'in_progress' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-emerald-100 text-emerald-700',
                                ];
                                $statusLabels = [
                                    'preparation' => 'En préparation',
                                    'in_progress' => 'En cours',
                                    'completed' => 'Terminé',
                                ];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full {{ $statusStyles[$project->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$project->status] ?? ucfirst($project->status) }}
                            </span>
                        </div>

                        <div class="text-gray-700 text-lg leading-relaxed mb-8 max-w-3xl">
                            {{ $project->description ?: 'Aucune description disponible pour ce projet.' }}
                        </div>

                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-100 inline-flex flex-col sm:flex-row gap-8">
                            <div>
                                <div class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-1">Date de début</div>
                                <div class="text-gray-900 font-medium text-lg">{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') : 'Non définie' }}</div>
                            </div>
                            <div class="w-px bg-gray-200 hidden sm:block"></div>
                            <div>
                                <div class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-1">Date de fin</div>
                                <div class="text-gray-900 font-medium text-lg">{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') : 'Non définie' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 w-full md:w-auto md:flex-col lg:flex-row">
                         <a href="{{ route('projects.kanban', $project->id) }}" class="inline-flex justify-center items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 hover:border-blue-200 transition-all duration-200 shadow-sm flex-1 md:flex-none">
                            <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>
                            Kanban
                        </a>
                        <a href="{{ route('projects.calendar', $project->id) }}" class="inline-flex justify-center items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 hover:bg-gray-50 hover:text-teal-600 hover:border-teal-200 transition-all duration-200 shadow-sm flex-1 md:flex-none">
                            <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Calendrier
                        </a>
                        <a href="{{ route('projects.edit', $project->id) }}" class="inline-flex justify-center items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 hover:border-indigo-200 transition-all duration-200 shadow-sm flex-1 md:flex-none">
                            <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            Éditer
                        </a>
                        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="flex-1 md:flex-none" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-red-600 hover:bg-red-50 hover:border-red-200 transition-all duration-200 shadow-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tasks Section -->
            <div class="mt-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Tâches du projet</h2>
                    <a href="{{ route('tasks.create', ['projectId' => $project->id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-md transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Ajouter une tâche
                    </a>
                </div>

                @if($project->tasks->count() > 0)
                    <div class="space-y-4">
                        @foreach($project->tasks as $task)
                            <div class="group bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md hover:border-blue-300 transition-all duration-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-1">
                                        <a href="{{ route('tasks.show', $task->id) }}" class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                            {{ $task->title }}
                                        </a>
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            {{ $task->status === 'done' ? 'bg-emerald-100 text-emerald-800' : 
                                                ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                                'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 line-clamp-1">{{ $task->description }}</p>
                                </div>
                                
                                <div class="flex items-center gap-6 w-full sm:w-auto justify-between sm:justify-end">
                                    @if($task->due_date)
                                        <div class="flex items-center text-sm text-gray-500">
                                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('tasks.edit', $task->id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Éditer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Supprimer cette tâche ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 rounded-xl border border-dashed border-gray-300 p-10 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <p class="text-gray-500 italic text-lg mb-2">Aucune tâche pour le moment</p>
                        <p class="text-sm text-gray-400">Ajoutez des tâches pour commencer à suivre l'avancement du projet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection