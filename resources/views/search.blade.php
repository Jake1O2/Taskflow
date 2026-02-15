@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-slide-down">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                        <span class="text-indigo-600 bg-indigo-50 p-2 rounded-lg border border-indigo-100">🔍</span> 
                        Résultats de recherche
                    </h1>
                    <p class="text-gray-500 mt-2">
                        Résultats pour "<span class="font-semibold text-gray-900">{{ $query }}</span>"
                    </p>
                </div>

                <div class="flex bg-gray-100 p-1 rounded-xl">
                    <a href="{{ route('search', ['q' => $query, 'type' => 'all']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $type === 'all' ? 'bg-white text-indigo-600 shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-200/50' }}">
                        Tout
                    </a>
                    <a href="{{ route('search', ['q' => $query, 'type' => 'projects']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $type === 'projects' ? 'bg-white text-indigo-600 shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-200/50' }}">
                        Projets ({{ $projects->count() }})
                    </a>
                    <a href="{{ route('search', ['q' => $query, 'type' => 'tasks']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $type === 'tasks' ? 'bg-white text-indigo-600 shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-200/50' }}">
                        Tâches ({{ $tasks->count() }})
                    </a>
                </div>
            </div>

            <div class="space-y-8 animate-fade-in" style="animation-delay: 200ms;">
                <!-- Projets trouvés -->
                @if(($type === 'all' || $type === 'projects') && $projects->isNotEmpty())
                    <section>
                        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="bg-blue-100 text-blue-600 p-1.5 rounded-lg">💼</span> 
                            Projets trouvés
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($projects as $project)
                                <a href="{{ route('projects.show', $project->id) }}" class="group bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-indigo-300 transition-all duration-200 p-6 flex flex-col h-full">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="h-10 w-10 text-white rounded-lg flex items-center justify-center font-bold bg-gradient-to-br from-blue-500 to-indigo-600 shadow-sm">
                                            {{ substr($project->title, 0, 1) }}
                                        </div>
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200 group-hover:bg-indigo-50 group-hover:text-indigo-700 group-hover:border-indigo-100 transition-colors">
                                            {{ ucfirst($project->status) }}
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors mb-2">{{ $project->title }}</h3>
                                    <p class="text-sm text-gray-500 line-clamp-2 flex-1">{{ $project->description }}</p>
                                    <div class="mt-4 pt-4 border-t border-gray-50 flex items-center text-xs text-gray-400">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') : 'Aucune date' }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- Tâches trouvées -->
                @if(($type === 'all' || $type === 'tasks') && $tasks->isNotEmpty())
                    <section>
                        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="bg-green-100 text-green-600 p-1.5 rounded-lg">✅</span> 
                            Tâches trouvées
                        </h2>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="divide-y divide-gray-100">
                                @foreach($tasks as $task)
                                    <a href="{{ route('tasks.show', $task->id) }}" class="block p-4 hover:bg-gray-50 transition-colors duration-200 group">
                                        <div class="flex items-start gap-4">
                                            <div class="mt-1">
                                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 group-hover:border-indigo-500 transition-colors"></div>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex justify-between items-start">
                                                    <h3 class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $task->title }}</h3>
                                                    <span class="px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-600 group-hover:bg-white group-hover:shadow-sm transition-all whitespace-nowrap ml-2">
                                                        {{ ucfirst($task->status) }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $task->description }}</p>
                                                <div class="flex items-center gap-3 mt-2">
                                                    @if($task->project)
                                                        <span class="inline-flex items-center text-xs text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                                            {{ $task->project->title }}
                                                        </span>
                                                    @endif
                                                    <span class="flex items-center text-xs text-gray-400">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Due le {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') : 'N/A' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif
                
                @if($projects->isEmpty() && $tasks->isEmpty())
                    <div class="py-16 text-center">
                        <div class="bg-gray-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Aucun résultat trouvé</h3>
                        <p class="text-gray-500 mt-1">Nous n'avons rien trouvé correspondant à "{{ $query }}".</p>
                        <p class="text-gray-400 text-sm mt-4">Essayez de vérifier l'orthographe ou d'utiliser d'autres mots-clés.</p>
                        
                        <div class="mt-8 flex justify-center gap-4">
                            <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">Retour au tableau de bord</a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('projects.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">Voir tous les projets</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection