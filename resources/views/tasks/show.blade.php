@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-up">
        <!-- Task Header Card -->
        <header class="card-premium border-none p-10 flex flex-col md:flex-row justify-between items-start gap-8 relative overflow-hidden">
            <!-- Background Accent -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-full -mr-8 -mt-8"></div>
            
            <div class="flex-1 space-y-4">
                <div class="flex items-center gap-3">
                    @php
                        $statusMeta = [
                            'todo' => ['label' => 'À faire', 'color' => 'bg-gray-100 text-gray-500 border-gray-200'],
                            'in_progress' => ['label' => 'En cours', 'color' => 'bg-blue-50 text-blue-600 border-blue-100'],
                            'done' => ['label' => 'Terminé', 'color' => 'bg-success/10 text-success border-success/20'],
                        ];
                        $meta = $statusMeta[$task->status] ?? $statusMeta['todo'];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border {{ $meta['color'] }}">
                        {{ $meta['label'] }}
                    </span>
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">
                        Tâche #{{ $task->id }}
                    </span>
                </div>
                
                <h1 class="text-4xl font-extrabold text-gray-900 leading-tight">
                    {{ $task->title }}
                </h1>

                @if($task->project)
                    <a href="{{ route('projects.show', $task->project_id) }}" class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:underline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                        Projet : {{ $task->project->title }}
                    </a>
                @endif
            </div>

            <div class="flex gap-2 shrink-0">
                <a href="{{ route('tasks.edit', $task->id) }}" class="px-6 py-3 bg-gray-900 text-white rounded-2xl font-bold text-sm shadow-xl hover:scale-105 active:scale-95 transition-all">
                    Modifier
                </a>
                <a href="{{ url()->previous() }}" class="px-6 py-3 bg-gray-50 text-gray-700 rounded-2xl font-bold text-sm border border-gray-100 hover:bg-gray-100 transition-all">
                    Fermer
                </a>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Task Content -->
            <div class="lg:col-span-2 card-premium">
                <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 px-1">Description</h2>
                <div class="prose prose-blue max-w-none">
                    <p class="text-gray-700 leading-relaxed text-lg italic whitespace-pre-wrap">
                        {{ $task->description ?: 'Aucun détail supplémentaire pour cette tâche.' }}
                    </p>
                </div>
            </div>

            <!-- Task Sidebar -->
            <div class="space-y-6">
                <div class="card-premium">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-6 px-1">Informations</h2>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase">Échéance</span>
                                <p class="text-sm font-bold text-gray-900">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d F Y') : 'Non définie' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase">Créée le</span>
                                <p class="text-sm font-bold text-gray-900">{{ $task->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Card -->
                <div class="card-premium bg-primary text-white border-none shadow-xl shadow-primary/20 p-8 text-center group">
                    <h3 class="font-extrabold text-xl mb-2">Terminée ?</h3>
                    <p class="text-xs font-bold opacity-80 uppercase tracking-widest mb-6">Mettez à jour le statut</p>
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="{{ $task->status === 'done' ? 'todo' : 'done' }}">
                        <input type="hidden" name="title" value="{{ $task->title }}">
                        <button type="submit" class="w-full py-3 bg-white text-primary rounded-2xl font-extrabold text-sm shadow-lg hover:scale-105 transition-all">
                            {{ $task->status === 'done' ? 'Remettre à faire' : 'Marquer comme terminée' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection