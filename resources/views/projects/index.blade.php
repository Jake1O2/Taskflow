@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-up">
        <header class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Projets</h1>
                <p class="text-gray-500 font-medium">Suivez l'avancement de vos travaux.</p>
            </div>
            <a href="{{ route('projects.create') }}" class="px-5 py-2.5 bg-primary text-white rounded-2xl font-semibold shadow-lg shadow-primary/20 hover:scale-105 transition-all text-sm">
                + Nouveau Projet
            </a>
        </header>

        @if(session('success'))
            <div class="glass border-success/30 bg-success/5 text-success p-4 rounded-2xl animate-fade-in flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($projects as $project)
                <div class="card-premium group relative">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold text-xl shadow-lg group-hover:rotate-6 transition-transform">
                            {{ substr($project->title, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold text-gray-900 truncate">{{ $project->title }}</h3>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">
                                {{ $project->team ? $project->team->name : 'Personnel' }}
                            </p>
                        </div>
                    </div>
                    
                    <p class="text-sm text-gray-500 line-clamp-2 mb-6 min-h-[40px]">
                        {{ $project->description }}
                    </p>

                    <div class="flex items-center justify-between mb-6">
                        @php
                            $statusColors = [
                                'preparation' => 'bg-amber-50 text-amber-700 border-amber-100',
                                'en_cours' => 'bg-blue-50 text-blue-700 border-blue-100',
                                'termine' => 'bg-green-50 text-green-700 border-green-100',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border {{ $statusColors[$project->status] ?? 'bg-gray-50 text-gray-600' }}">
                            {{ str_replace('_', ' ', $project->status) }}
                        </span>
                        
                        <div class="flex items-center gap-1.5 text-xs font-bold text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            {{ count($project->tasks ?? []) }}
                        </div>
                    </div>

                    <div class="flex items-center gap-2 border-t border-gray-50 pt-6">
                        <a href="{{ route('projects.show', $project->id) }}" class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl bg-gray-50 text-gray-700 font-bold text-sm hover:bg-primary/5 hover:text-primary transition-all">
                            Ouvrir
                        </a>
                        <div class="flex gap-1">
                            <a href="{{ route('projects.kanban', $project->id) }}" class="p-2.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title="Kanban">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 002-2h-2a2 2 0 00-2 2"></path></svg>
                            </a>
                            <a href="{{ route('projects.calendar', $project->id) }}" class="p-2.5 text-gray-400 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-all" title="Calendrier">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 card-premium flex flex-col items-center justify-center border-dashed">
                    <p class="text-gray-500 font-bold">Aucun projet trouvé</p>
                    <a href="{{ route('projects.create') }}" class="text-primary font-semibold text-sm mt-1 hover:underline">Créer votre premier projet</a>
                </div>
            @endforelse
        </div>
    </div>
@endsection