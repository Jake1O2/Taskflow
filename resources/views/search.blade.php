@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-up">
        <header>
            <h1 class="text-3xl font-bold text-gray-900">Résultats pour <span class="text-primary">"{{ request('q') }}"</span></h1>
            <p class="text-gray-500 font-medium">Nous avons trouvé des correspondances dans vos projets et vos tâches.</p>
        </header>

        <!-- Search Tabs Navigation -->
        <div class="glass p-1.5 rounded-2xl flex items-center gap-1 w-fit">
            <button id="tab-projects" class="px-6 py-2 rounded-xl text-sm font-bold bg-white shadow-sm text-gray-900 transition-all">Projets</button>
            <button id="tab-tasks" class="px-6 py-2 rounded-xl text-sm font-bold text-gray-500 hover:bg-white/50 transition-all">Tâches</button>
        </div>

        <!-- Projects Results Section -->
        <div id="results-projects" class="space-y-6">
            <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 px-1">Projets ({{ count($projects) }})</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($projects as $project)
                    <div class="card-premium group">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold shadow-soft">
                                {{ substr($project->title, 0, 1) }}
                            </div>
                            <h3 class="font-bold text-gray-900 truncate">{{ $project->title }}</h3>
                        </div>
                        <p class="text-xs text-gray-500 line-clamp-2 mb-4">{{ $project->description }}</p>
                        <a href="{{ route('projects.show', $project->id) }}" class="w-full py-2 flex items-center justify-center bg-gray-50 text-gray-700 rounded-xl font-bold text-xs hover:bg-primary hover:text-white transition-all">
                            Voir le projet
                        </a>
                    </div>
                @empty
                    <div class="col-span-full py-12 card-premium text-center border-dashed">
                        <p class="text-gray-400 font-medium">Aucun projet trouvé.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Tasks Results Section -->
        <div id="results-tasks" class="hidden space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 px-1">Tâches ({{ count($tasks) }})</h2>
            <div class="space-y-3">
                @forelse($tasks as $task)
                    <div class="card-premium !p-4 flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <div class="w-2 h-2 rounded-full {{ $task->status === 'done' ? 'bg-success' : 'bg-gray-300' }}"></div>
                            <div>
                                <h4 class="font-bold text-gray-900 group-hover:text-primary transition-colors">{{ $task->title }}</h4>
                                <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $task->project ? $task->project->title : 'Personnel' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('tasks.show', $task->id) }}" class="p-2 text-gray-400 hover:text-primary transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                @empty
                    <div class="py-12 card-premium text-center border-dashed">
                        <p class="text-gray-400 font-medium">Aucune tâche trouvée.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Simple Tab Script -->
    <script>
        const btnProj = document.getElementById('tab-projects');
        const btnTask = document.getElementById('tab-tasks');
        const resProj = document.getElementById('results-projects');
        const resTask = document.getElementById('results-tasks');

        function setActive(activeBtn, inactiveBtn, showEl, hideEl) {
            activeBtn.classList.add('bg-white', 'shadow-sm', 'text-gray-900');
            activeBtn.classList.remove('text-gray-500', 'hover:bg-white/50');
            inactiveBtn.classList.remove('bg-white', 'shadow-sm', 'text-gray-900');
            inactiveBtn.classList.add('text-gray-500', 'hover:bg-white/50');
            showEl.classList.remove('hidden');
            hideEl.classList.add('hidden');
        }

        btnProj.addEventListener('click', () => setActive(btnProj, btnTask, resProj, resTask));
        btnTask.addEventListener('click', () => setActive(btnTask, btnProj, resTask, resProj));
    </script>
@endsection