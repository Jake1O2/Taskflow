@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-slide-up">
        <!-- Team Header -->
        <header class="card-premium bg-gradient-to-br from-gray-900 to-indigo-950 text-white border-none p-8 flex flex-col md:flex-row justify-between items-center gap-6 shadow-2xl shadow-indigo-500/10">
            <div class="flex items-center gap-6 text-center md:text-left">
                <div class="w-20 h-20 rounded-3xl bg-white/10 flex items-center justify-center text-4xl font-bold shadow-soft">
                    {{ substr($team->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold">{{ $team->name }}</h1>
                    <div class="flex items-center gap-3 mt-2 text-indigo-200">
                        <span class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest bg-white/10 px-3 py-1 rounded-full">
                            {{ count($members) }} Membres
                        </span>
                        <span class="text-xs opacity-60">Créé par {{ $team->owner->name }}</span>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                @if(Auth::id() === $team->user_id)
                    <a href="{{ route('teams.invitations', $team->id) }}" class="px-5 py-2.5 bg-white/10 hover:bg-white text-white hover:text-gray-900 rounded-2xl font-bold text-sm transition-all">
                        Invitations
                    </a>
                @endif
                <a href="{{ route('teams.edit', $team->id) }}" class="px-5 py-2.5 bg-white/10 hover:bg-white text-white hover:text-gray-900 rounded-2xl font-bold text-sm transition-all">
                    Paramètres
                </a>
                <a href="{{ route('teams.index') }}" class="px-5 py-2.5 bg-white/5 hover:bg-white/10 text-white rounded-2xl font-bold text-sm transition-all border border-white/10">
                    Retour
                </a>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Members List (Spans 2) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="card-premium">
                    <h2 class="text-xl font-bold mb-6">Membres de l'équipe</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Utilisateur</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Rôle</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($members as $member)
                                    <tr class="group hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center font-bold text-gray-500 shadow-sm">
                                                    {{ substr($member->user->name, 0, 1) }}
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-gray-900">{{ $member->user->name }}</span>
                                                    <span class="text-xs text-gray-400">{{ $member->user->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($member->user_id === $team->user_id)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest bg-indigo-50 text-indigo-600 border border-indigo-100">
                                                    Propriétaire
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest bg-gray-100 text-gray-500 border border-gray-200">
                                                    Membre
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if(Auth::id() === $team->user_id && $member->user_id !== $team->user_id)
                                                <form action="{{ route('teams.removeMember', [$team->id, $member->user_id]) }}" method="POST" onsubmit="return confirm('Retirer ce membre ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2.5 text-gray-400 hover:text-danger hover:bg-danger/5 rounded-xl transition-all">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-300 font-medium">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center text-gray-400 italic">Aucun membre</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar / Add Member (Spans 1) -->
            <div class="space-y-6">
                @if(Auth::id() === $team->user_id)
                    <div class="card-premium">
                        <h2 class="text-xl font-bold mb-6">Ajouter un membre</h2>
                        <form action="{{ route('teams.addMember', $team->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="space-y-2">
                                <label for="email" class="text-sm font-bold text-gray-700 ml-1">Email de l'utilisateur</label>
                                <x-text-input type="email" name="email" id="email" placeholder="email@exemple.com" required />
                                @error('email')
                                    <p class="text-danger text-xs mt-2 px-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                            <x-primary-button class="w-full">
                                Ajouter le membre
                            </x-primary-button>
                        </form>
                    </div>
                @endif

                <div class="card-premium bg-gray-50 border-gray-100">
                    <h3 class="font-bold text-gray-900 mb-2">Conseils d'équipe</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Collaborez plus efficacement en partageant vos projets avec les membres de votre équipe. 
                        Seul le propriétaire peut gérer les membres.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
