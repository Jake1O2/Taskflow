@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-up">
        <header class="card-premium border-none p-10 flex flex-col md:flex-row justify-between items-start gap-8 relative overflow-hidden">
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

        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="#" class="border-primary text-primary whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm">
                    Détails
                </a>
                <a href="{{ route('tasks.activity', $task->id) }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Activité
                </a>
            </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 card-premium">
                <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 px-1">Description</h2>
                <div class="prose prose-blue max-w-none">
                    <p class="text-gray-700 leading-relaxed text-lg italic whitespace-pre-wrap">
                        {{ $task->description ?: 'Aucun détail supplémentaire pour cette tâche.' }}
                    </p>
                </div>

                <div class="mt-12 border-t border-gray-100 pt-8">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-6 px-1">Commentaires</h2>
                    
                    <div class="mb-8 relative" x-data="commentMentions()">
                        <form action="{{ route('comments.store', ['id' => $task->id]) }}" method="POST">
                            @csrf
                            <div class="relative">
                                <textarea 
                                    name="content" 
                                    rows="3" 
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 p-4 focus:bg-white focus:border-primary focus:ring-primary transition-all"
                                    placeholder="Tapez @ pour mentionner quelqu'un..."
                                    @input="handleInput"
                                    x-model="content"
                                ></textarea>
                                
                                <div x-show="showMentions" class="absolute bottom-full left-0 mb-2 w-72 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-10" style="display: none;">
                                    <template x-for="user in filteredUsers" :key="user.id">
                                        <button type="button" @click="selectUser(user)" class="w-full flex items-center gap-3 p-3 hover:bg-gray-50 transition-colors text-left border-b border-gray-50 last:border-0">
                                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold" x-text="user.initials"></div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900" x-text="user.name"></div>
                                                <div class="text-xs text-gray-500" x-text="user.email"></div>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            <div class="flex justify-end mt-2">
                                <button type="submit" class="px-6 py-2 bg-gray-900 text-white rounded-lg font-bold text-sm hover:scale-105 transition-all">
                                    Commenter
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="space-y-8">
                        @forelse($comments as $comment)
                            <div class="flex gap-4 group">
                                <div class="w-10 h-10 rounded-xl bg-gray-100 flex-shrink-0 flex items-center justify-center font-bold text-gray-500">
                                    {{ substr($comment->user->name, 0, 2) }}
                                </div>
                                <div class="flex-1 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-gray-900">{{ $comment->user->name }}</span>
                                        <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="text-gray-600 text-sm leading-relaxed">
                                        {!! \App\Helpers\MentionParser::formatText($comment->content) !!}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-400 italic text-center py-4">Aucun commentaire pour le moment.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card-premium" x-data="{ assignModalOpen: false }">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 px-1">Assigné à</h2>
                    </div>
                    
                    @if($task->assignee)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs">
                                    {{ substr($task->assignee->name, 0, 2) }}
                                </div>
                                <span class="font-medium text-sm text-gray-900">{{ $task->assignee->name }}</span>
                            </div>
                            <form action="{{ route('tasks.unassign', $task->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-6 h-6 flex items-center justify-center rounded-full text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-center py-2">
                            <p class="text-sm text-gray-500 italic mb-3">Non assigné</p>
                            <button @click="assignModalOpen = true" class="w-full py-2 bg-gray-100 text-gray-700 rounded-lg font-bold text-xs uppercase tracking-wide hover:bg-gray-200 transition-colors">
                                Assigner
                            </button>
                        </div>
                    @endif

                    <div x-show="assignModalOpen" class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display: none;">
                        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="assignModalOpen = false"></div>
                        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 overflow-hidden animate-slide-up">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-gray-900">Assigner une tâche</h3>
                                <button @click="assignModalOpen = false" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <input type="text" placeholder="Rechercher un membre..." class="w-full rounded-xl border-gray-200 bg-gray-50 mb-4 focus:border-primary focus:ring-primary">
                            <div class="max-h-60 overflow-y-auto space-y-1">
                                <form action="{{ route('tasks.assign', ['id' => $task->id, 'userId' => 1]) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors text-left group">
                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-500">JD</div>
                                        <span class="font-medium text-sm group-hover:text-primary transition-colors">John Doe</span>
                                    </button>
                                </form>
                                <form action="{{ route('tasks.assign', ['id' => $task->id, 'userId' => 2]) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors text-left group">
                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-500">AS</div>
                                        <span class="font-medium text-sm group-hover:text-primary transition-colors">Alice Smith</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-premium">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-6 px-1">Informations</h2>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase">Échéance</span>
                                <p class="text-sm font-bold text-gray-900">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->translatedFormat('j F Y') : 'Non définie' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase">Créée le</span>
                                <p class="text-sm font-bold text-gray-900">{{ $task->created_at->translatedFormat('j/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

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

    <script>
    function commentMentions() {
        return {
            showMentions: false,
            content: '',
            filteredUsers: [],
            async handleInput(e) {
                const val = e.target.value;
                const cursorPosition = e.target.selectionStart;
                const textBeforeCursor = val.substring(0, cursorPosition);
                const lastWord = textBeforeCursor.split(/\s+/).pop();
                
                if (lastWord.startsWith('@') && lastWord.length > 1) {
                    const query = lastWord.substring(1).toLowerCase();
                    await this.searchUsers(query);
                    this.showMentions = this.filteredUsers.length > 0;
                } else {
                    this.showMentions = false;
                }
            },
            async searchUsers(query) {
                try {
                    const response = await fetch(`{{ route('api.users.search') }}?q=${encodeURIComponent(query)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    const payload = await response.json();
                    const users = Array.isArray(payload.data) ? payload.data : [];
                    this.filteredUsers = users.map((user) => ({
                        ...user,
                        initials: (user.name || 'U').split(' ').map((part) => part.charAt(0)).join('').slice(0, 2).toUpperCase()
                    }));
                } catch (e) {
                    this.filteredUsers = [];
                }
            },
            selectUser(user) {
                this.content = this.content.replace(/@\w*$/, (user.mention || '@' + (user.name || '').replace(/\s+/g, '')) + ' ');
                this.showMentions = false;
            }
        }
    }
    </script>
@endsection
