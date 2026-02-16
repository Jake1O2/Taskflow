@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 animate-slide-up">
        <header class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h1 class="text-2xl font-extrabold text-gray-900 flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center flex-shrink-0 shadow-lg shadow-purple-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 002-2h-2a2 2 0 00-2 2"></path></svg>
                </span>
                Kanban : <span class="text-gray-500 font-medium">{{ $project->title }}</span>
            </h1>
            <div class="flex gap-2">
                <a href="{{ route('tasks.create', ['projectId' => $project->id]) }}" class="px-5 py-2.5 bg-primary text-white rounded-2xl font-bold text-sm shadow-lg shadow-primary/20 hover:scale-105 transition-all">
                    + Nouvelle Tâche
                </a>
                <a href="{{ route('projects.show', $project->id) }}" class="px-5 py-2.5 bg-white border border-gray-100 text-gray-700 rounded-2xl font-bold text-sm shadow-sm hover:bg-gray-50 transition-all">
                    Détails
                </a>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 min-h-[70vh]">
            @foreach(['todo' => 'À faire', 'in_progress' => 'En cours', 'done' => 'Terminé'] as $status => $label)
                <div class="flex flex-col gap-4 bg-gray-100/50 p-4 rounded-3xl border border-gray-200/50">
                    <div class="flex items-center justify-between px-2">
                        <h3 class="text-xs font-extrabold uppercase tracking-widest text-gray-500">{{ $label }}</h3>
                        <span class="w-6 h-6 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-900 shadow-sm">
                            {{ count($tasksByStatus[$status]) }}
                        </span>
                    </div>
                    
                    <div class="flex-1 space-y-3 p-1">
                        @forelse($tasksByStatus[$status] as $task)
                            <div class="card-premium p-4 !rounded-2xl !p-4 hover:border-primary/30 group cursor-pointer">
                                <h4 class="font-bold text-gray-900 group-hover:text-primary transition-colors">{{ $task->title }}</h4>
                                @if($task->description)
                                    <p class="text-[11px] text-gray-500 mt-2 line-clamp-2 leading-relaxed italic opacity-80">
                                        {{ $task->description }}
                                    </p>
                                @endif
                                <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                                    <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->translatedFormat('j M') : 'Aucune' }}
                                    </div>
                                    <div class="flex -space-x-1.5">
                                        <div class="w-6 h-6 rounded-lg bg-gray-100 border border-white flex items-center justify-center text-[10px] font-bold text-gray-400">?</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="h-24 rounded-2xl border-2 border-dashed border-gray-200 flex items-center justify-center">
                                <span class="text-[10px] font-bold text-gray-300 uppercase">Vide</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection