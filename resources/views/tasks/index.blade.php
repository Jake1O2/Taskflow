@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-in-up">
        {{-- Header --}}
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Tâches</h1>
                <p class="text-gray-400 font-medium mt-1">Suivez vos priorités sur tous vos projets.</p>
            </div>
        </header>

        {{-- Filter Tabs --}}
        <div class="flex items-center gap-1 p-1 bg-gray-100/80 rounded-2xl overflow-x-auto no-scrollbar w-fit">
            <a href="{{ route('tasks.filter', ['status' => 'todo', 'priority' => '']) }}"
               class="px-5 py-2 rounded-xl text-sm font-bold transition-all whitespace-nowrap {{ (request('status') === 'todo' || !request('status')) ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                À faire
            </a>
            <a href="{{ route('tasks.filter', ['status' => 'in_progress', 'priority' => '']) }}"
               class="px-5 py-2 rounded-xl text-sm font-bold transition-all whitespace-nowrap {{ request('status') === 'in_progress' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                En cours
            </a>
            <a href="{{ route('tasks.filter', ['status' => 'done', 'priority' => '']) }}"
               class="px-5 py-2 rounded-xl text-sm font-bold transition-all whitespace-nowrap {{ request('status') === 'done' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                Terminées
            </a>
        </div>

        {{-- Tasks List --}}
        <div class="space-y-3">
            @forelse($tasks as $task)
                @php
                    $isOverdue = $task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status !== 'done';
                    $isDone = $task->status === 'done';
                    $isProgress = $task->status === 'in_progress';
                @endphp
                <div class="card-internal group flex flex-col sm:flex-row items-start sm:items-center gap-4 p-5">
                    {{-- Status Icon --}}
                    <div class="shrink-0">
                        @if($isDone)
                            <div class="w-11 h-11 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-inner">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        @elseif($isProgress)
                            <div class="w-11 h-11 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </div>
                        @else
                            <div class="w-11 h-11 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            @if($task->project)
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-100 px-2 py-0.5 rounded-lg">
                                    {{ $task->project->title }}
                                </span>
                            @endif
                            @if($isOverdue)
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-lg bg-red-100 text-danger">
                                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    En retard
                                </span>
                            @endif
                            @if($task->due_date)
                                <span class="text-[10px] font-semibold {{ $isOverdue ? 'text-danger' : ($isDone ? 'text-gray-400' : 'text-amber-600') }}">
                                    {{ \Carbon\Carbon::parse($task->due_date)->translatedFormat('j M') }}
                                </span>
                            @endif
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary transition-colors {{ $isDone ? 'line-through opacity-50' : '' }}">
                            {{ $task->title }}
                        </h3>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('tasks.show', $task->id) }}"
                           class="px-4 py-2 rounded-xl bg-gray-50 text-gray-700 font-bold text-sm hover:bg-blue-50 hover:text-primary transition-all">
                            Détails
                        </a>
                        <a href="{{ route('tasks.edit', $task->id) }}"
                           class="p-2.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="w-16 h-16 bg-emerald-50 rounded-3xl flex items-center justify-center mb-4 text-emerald-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-gray-600 font-bold mb-1">
                        @if(request('status') === 'done')
                            Aucune tâche terminée
                        @elseif(request('status') === 'in_progress')
                            Aucune tâche en cours
                        @else
                            Aucune tâche à faire 🎉
                        @endif
                    </p>
                    <p class="text-gray-400 text-sm mt-1">Créez une tâche depuis un projet pour commencer.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection