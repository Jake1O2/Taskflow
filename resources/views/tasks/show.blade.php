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
                                <div class="shrink-0 h-16 w-16 flex items-center justify-center rounded-full bg-gradient-to-br from-green-500 to-teal-600 text-white font-bold text-3xl shadow-md">
                                    {{ substr($task->title, 0, 1) }}
                                </div>
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900">{{ $task->title }}</h1>
                                    @php
                                        $statusStyles = [
                                            'todo' => 'bg-gray-100 text-gray-800 border-gray-200',
                                            'in_progress' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'done' => 'bg-green-100 text-green-800 border-green-200',
                                        ];
                                        $statusLabels = [
                                            'todo' => 'À faire',
                                            'in_progress' => 'En cours',
                                            'done' => 'Terminé',
                                        ];
                                    @endphp
                                    <div class="mt-2 flex gap-2">
                                         <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full border {{ $statusStyles[$task->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusLabels[$task->status] ?? ucfirst($task->status) }}
                                        </span>
                                        @if($task->priority)
                                            @php
                                                $priorityColors = [
                                                    'low' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                    'medium' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                                    'high' => 'bg-red-50 text-red-700 border-red-100',
                                                ];
                                                $priorityLabels = [
                                                    'low' => 'Priorité Basse',
                                                    'medium' => 'Priorité Moyenne',
                                                    'high' => 'Priorité Haute',
                                                ];
                                            @endphp
                                             <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full border {{ $priorityColors[$task->priority] ?? 'bg-gray-100' }}">
                                                {{ $priorityLabels[$task->priority] ?? ucfirst($task->priority) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="prose prose-indigo max-w-none text-gray-600 bg-gray-50 p-4 rounded-lg border border-gray-100">
                                {{ $task->description ?: 'Aucune description disponible.' }}
                            </div>
                            
                            <div class="flex gap-8 mt-6 text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="font-semibold">Échéance :</span> 
                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') : 'Non définie' }}
                                </div>
                                 <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                    <span class="font-semibold">Projet :</span> 
                                    @if($task->project)
                                        <a href="{{ route('projects.show', $task->project->id) }}" class="text-blue-600 hover:underline">{{ $task->project->title }}</a>
                                    @else
                                        <span class="text-gray-400 italic">Aucun</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 w-full md:w-auto">
                            <a href="{{ route('tasks.edit', $task->id) }}" class="inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                Éditer
                            </a>
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition ease-in-out duration-150 shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Supprimer
                                </button>
                            </form>
                             <a href="{{ route('tasks.index') }}" class="inline-flex justify-center items-center px-4 py-2 bg-gray-100 border border-transparent rounded-md font-semibold text-xs text-gray-600 uppercase tracking-widest hover:bg-gray-200 transition ease-in-out duration-150">
                                Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection