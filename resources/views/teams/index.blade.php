@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 animate-slide-down">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <span class="text-indigo-600 bg-indigo-50 p-2 rounded-lg border border-indigo-100">👥</span> 
                        Équipes
                    </h1>
                     <p class="text-sm text-gray-500 mt-1 ml-12">Gérez vos équipes et vos collaborateurs</p>
                </div>
                <a href="{{ route('teams.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white shadow-sm hover:bg-indigo-700 hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nouvelle Équipe
                </a>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg shadow-sm animate-fade-in flex items-center mb-6" role="alert">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in" style="animation-delay: 100ms;">
                 @forelse($teams as $team)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-indigo-200 transition-all duration-200 group flex flex-col h-full">
                        <div class="p-6 flex-1">
                            <div class="flex items-start justify-between mb-4">
                                <div class="shrink-0 h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-bold text-xl shadow-sm">
                                    {{ substr($team->name, 0, 1) }}
                                </div>
                                @if(Auth::id() === $team->user_id)
                                    <div class="relative flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('teams.edit', $team->id) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors" title="Éditer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <form action="{{ route('teams.destroy', $team->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette équipe ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-gray-50 rounded-lg transition-colors" title="Supprimer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                            
                            <a href="{{ route('teams.show', $team->id) }}" class="block">
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors mb-1">{{ $team->name }}</h3>
                                <p class="text-sm text-gray-500 line-clamp-2 min-h-[2.5rem]">{{ $team->description ?: 'Aucune description' }}</p>
                            </a>
                            
                            <div class="mt-4 flex items-center justify-between">
                                <div class="flex -space-x-2 overflow-hidden">
                                    <div class="h-8 w-8 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center text-xs font-medium text-gray-600" title="Propriétaire: {{ $team->owner->name }}">
                                        {{ substr($team->owner->name, 0, 1) }}
                                    </div>
                                    <!-- Placeholder for other members avatars if available in eager load, otherwise just count -->
                                </div>
                                <span class="text-xs font-medium text-gray-500 bg-gray-50 px-2 py-1 rounded-full border border-gray-100">
                                    {{ $team->members_count }} membre(s)
                                </span>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 rounded-b-xl flex justify-between items-center">
                            <span class="text-xs text-gray-400">Créée le {{ $team->created_at->format('d/m/Y') }}</span>
                            <a href="{{ route('teams.show', $team->id) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors flex items-center">
                                Voir détails <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                 @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
                        <div class="bg-gray-50 p-4 rounded-full mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Aucune équipe trouvée</h3>
                        <p class="text-gray-500 mt-1 mb-6">Commencez par créer votre première équipe.</p>
                        <a href="{{ route('teams.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white shadow-sm hover:bg-indigo-700 transition-all duration-200">
                            + Nouvelle Équipe
                        </a>
                    </div>
                 @endforelse
            </div>
        </div>
    </div>
@endsection
