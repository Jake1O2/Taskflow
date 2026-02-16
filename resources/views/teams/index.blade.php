@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-up">
        <header class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Équipes</h1>
                <p class="text-gray-500 font-medium">Gérez vos membres et collaborations.</p>
            </div>
            <a href="{{ route('teams.create') }}" class="px-5 py-2.5 bg-primary text-white rounded-2xl font-semibold shadow-lg shadow-primary/20 hover:scale-105 transition-all text-sm">
                + Nouvelle Équipe
            </a>
        </header>

        @if(session('success'))
            <div class="glass border-success/30 bg-success/5 text-success p-4 rounded-2xl animate-fade-in flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($teams as $team)
                <div class="card-premium group relative">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-bold text-xl shadow-lg group-hover:rotate-6 transition-transform">
                            {{ substr($team->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold text-gray-900 truncate">{{ $team->name }}</h3>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">
                                {{ $team->members_count ?? 0 }} Membres
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 mb-6">
                        @if($team->owner)
                            <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                                <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                                Owner: {{ $team->owner->name }}
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 border-t border-gray-50 pt-6">
                        <a href="{{ route('teams.show', $team->id) }}" class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl bg-gray-50 text-gray-700 font-bold text-sm hover:bg-primary/5 hover:text-primary transition-all">
                            Voir détails
                        </a>
                        <div class="flex gap-1">
                            <a href="{{ route('teams.edit', $team->id) }}" class="p-2.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('teams.destroy', $team->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 text-gray-400 hover:text-danger hover:bg-danger/5 rounded-xl transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 card-premium flex flex-col items-center justify-center border-dashed">
                    <div class="w-16 h-16 rounded-3xl bg-gray-50 flex items-center justify-center mb-4 text-gray-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <p class="text-gray-500 font-bold">Aucune équipe disponible</p>
                    <p class="text-gray-400 text-sm mt-1">Commencez par en créer une pour collaborer.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
