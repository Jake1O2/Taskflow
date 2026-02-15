@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <a href="{{ url()->previous() }}" class="inline-flex items-center text-sm text-gray-500 hover:text-blue-600 transition-colors mb-4 group">
                <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Retour
            </a>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg shadow-sm animate-fade-in flex items-center" role="alert">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 animate-slide-down overflow-hidden">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row justify-between md:items-start gap-6 mb-8 border-b border-gray-100 pb-8">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-4">
                                @php
                                    $statusStyles = [
                                        'todo' => 'bg-gray-100 text-gray-700 border-gray-200',
                                        'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'done' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    ];
                                    $statusLabels = [
                                        'todo' => 'À faire',
                                        'in_progress' => 'En cours',
                                        'done' => 'Terminé',
                                    ];
                                @endphp
                                <span class="px-3 py-1 text-sm font-semibold rounded-full border {{ $statusStyles[$task->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabels[$task->status] ?? ucfirst($task->status) }}
                                </span>
                                
                                @if($task->priority)
                                    @php
                                        $priorityColors = [
                                            'low' => 'text-blue-600 bg-blue-50 border-blue-100',
                                            'medium' => 'text-amber-600 bg-amber-50 border-amber-100',
                                            'high' => 'text-red-600 bg-red-50 border-red-100',
                                        ];
                                        $priorityLabels = [
                                            'low' => 'Priorité Basse',
                                            'medium' => 'Priorité Moyenne',
                                            'high' => 'Priorité Haute',
                                        ];
                                    @endphp
                                     <span class="px-3 py-1 text-sm font-semibold rounded-full border {{ $priorityColors[$task->priority] ?? 'bg-gray-100' }}">
                                        {{ $priorityLabels[$task->priority] ?? ucfirst($task->priority) }}
                                    </span>
                                @endif
                            </div>

                            <h1 class="text-3xl font-bold text-gray-900 mb-4 leading-tight">{{ $task->title }}</h1>
                            
                            <div class="prose prose-slate max-w-none text-gray-600">
                                <p>{{ $task->description ?: 'Aucune description fournie.' }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 min-w-[180px]">
                            <a href="{{ route('tasks.edit', $task->id) }}" class="flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 hover:border-blue-200 transition-all duration-200 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                Modifier
                            </a>
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-sm text-red-600 hover:bg-red-50 hover:border-red-200 transition-all duration-200 shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Détails temporels</h3>
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium">Date d'échéance</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d F Y') : 'Non définie' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium">Créée le</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $task->created_at->format('d/m/Y à H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Contexte</h3>
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-indigo-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium">Projet associé</p>
                                        @if($task->project)
                                            <a href="{{ route('projects.show', $task->project->id) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                                {{ $task->project->title }}
                                            </a>
                                        @else
                                            <p class="text-sm font-semibold text-gray-400 italic">Aucun projet</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection