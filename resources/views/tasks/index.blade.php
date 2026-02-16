@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-up">
        <header class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tâches</h1>
                <p class="text-gray-500 font-medium">Suivez vos priorités sur tous vos projets.</p>
            </div>
            <a href="{{ route('tasks.create') }}" class="px-5 py-2.5 bg-primary text-white rounded-2xl font-semibold shadow-lg shadow-primary/20 hover:scale-105 transition-all text-sm">
                + Nouvelle Tâche
            </a>
        </header>

        <!-- Tasks Filter Bar -->
        <div class="glass p-2 rounded-2xl flex items-center gap-2 overflow-x-auto no-scrollbar">
            <a href="{{ route('tasks.index') }}" class="px-5 py-2 rounded-xl text-sm font-bold bg-white shadow-sm text-primary transition-all">Toutes</a>
            <span class="text-gray-300 mx-2">|</span>
            <button class="px-5 py-2 rounded-xl text-sm font-bold text-gray-500 hover:bg-white/50 transition-all">À faire</button>
            <button class="px-5 py-2 rounded-xl text-sm font-bold text-gray-500 hover:bg-white/50 transition-all">En cours</button>
            <button class="px-5 py-2 rounded-xl text-sm font-bold text-gray-500 hover:bg-white/50 transition-all">Terminées</button>
        </div>

        <!-- Task List -->
        <div class="space-y-4">
            @forelse($tasks as $task)
                <div class="card-premium flex flex-col md:flex-row items-center gap-6 group">
                    <!-- Status Icon Area -->
                    <div class="shrink-0">
                        @if($task->status === 'done')
                            <div class="w-12 h-12 rounded-2xl bg-success/10 text-success flex items-center justify-center shadow-inner">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        @elseif($task->status === 'in_progress')
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center animate-pulse">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        @else
                            <div class="w-12 h-12 rounded-2xl bg-gray-50 text-gray-300 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                        @endif
                    </div>

                    <!-- Meta & Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                {{ $task->project ? $task->project->title : 'Personnel' }}
                            </span>
                            @if($task->due_date)
                                <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                <span class="text-[10px] font-bold {{ \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status !== 'done' ? 'text-danger' : 'text-gray-400' }} uppercase tracking-widest">
                                    {{ \Carbon\Carbon::parse($task->due_date)->format('d M') }}
                                </span>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-primary transition-colors truncate">
                            {{ $task->title }}
                        </h3>
                    </div>

                    <!-- Actions Area -->
                    <div class="flex items-center gap-2">
                        <a href="{{ route('tasks.show', $task->id) }}" class="px-5 py-2.5 rounded-xl bg-gray-50 text-gray-700 font-bold text-sm hover:bg-primary/5 hover:text-primary transition-all">
                            Détails
                        </a>
                        <a href="{{ route('tasks.edit', $task->id) }}" class="p-2.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="py-20 card-premium flex flex-col items-center justify-center border-dashed">
                    <div class="w-16 h-16 bg-gray-50 rounded-3xl flex items-center justify-center mb-4 text-gray-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <p class="text-gray-500 font-bold uppercase tracking-widest text-xs">Aucune tâche en vue</p>
                    <a href="{{ route('tasks.create') }}" class="text-primary font-bold text-sm mt-2 hover:underline">Créer une tâche maintenant</a>
                </div>
            @endforelse
        </div>
    </div>
@endsection
