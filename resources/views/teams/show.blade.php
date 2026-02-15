@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm animate-fade" role="alert">
                    <p class="font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Succès
                    </p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- En-tête de l'équipe -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6 border border-gray-100 animate-slide-down">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row justify-between md:items-start gap-4">
                        <div>
                            <div class="flex items-center gap-4 mb-2">
                                <div class="shrink-0 h-16 w-16 flex items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-bold text-3xl shadow-md">
                                    {{ substr($team->name, 0, 1) }}
                                </div>
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900">{{ $team->name }}</h1>
                                    <p class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Propriétaire : <span class="font-semibold text-gray-700">{{ $team->owner->name }}</span>
                                    </p>
                                </div>
                            </div>
                            @if($team->description)
                                <p class="text-gray-700 mt-4 leading-relaxed max-w-2xl bg-gray-50 p-4 rounded-lg border border-gray-100 italic">
                                    "{{ $team->description }}"
                                </p>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-3">
                            @if(Auth::id() === $team->user_id)
                                <a href="{{ route('teams.edit', $team->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    Éditer
                                </a>
                                <form action="{{ route('teams.destroy', $team->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Supprimer cette équipe ? Cette action est irréversible.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Supprimer
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('teams.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-md font-semibold text-xs text-gray-600 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition ease-in-out duration-150">
                                Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Membres -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 animate-slide-down" style="animation-delay: 0.1s">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <span class="text-blue-500">👥</span> Membres de l'équipe
                    </h2>

                    <!-- Table des membres -->
                    <div class="overflow-hidden rounded-lg border border-gray-200 mb-8">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Utilisateur</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rôle</th>
                                    @if(Auth::id() === $team->user_id)
                                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Propriétaire -->
                                <tr class="bg-yellow-50/50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-yellow-200 flex items-center justify-center text-xs font-bold text-yellow-800 mr-3">
                                                {{ substr($team->owner->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $team->owner->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $team->owner->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            👑 Propriétaire
                                        </span>
                                    </td>
                                    @if(Auth::id() === $team->user_id)
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-400">—</td>
                                    @endif
                                </tr>
                                <!-- Membres -->
                                @foreach($members as $member)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-700 mr-3">
                                                    {{ substr($member->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $member->user->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $member->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-200 uppercase tracking-wide">
                                                {{ $member->role ?? 'member' }}
                                            </span>
                                        </td>
                                        @if(Auth::id() === $team->user_id)
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form action="{{ route('teams.removeMember', [$team->id, $member->user_id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Retirer ce membre de l\'équipe ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1.5 hover:bg-red-50 rounded-full transition-colors" title="Retirer">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($members->isEmpty())
                            <div class="p-8 text-center bg-gray-50 border-t border-gray-100">
                                <p class="text-gray-500 italic flex flex-col items-center">
                                    <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Aucun membre ajouté pour le moment.
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Formulaire d'ajout de membre (owner seulement) -->
                    @if(Auth::id() === $team->user_id)
                        <div class="border-t border-gray-100 pt-8 mt-4 bg-gray-50/50 p-6 rounded-xl">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                Ajouter un nouveau membre
                            </h3>
                            <form action="{{ route('teams.addMember', $team->id) }}" method="POST" class="flex flex-col md:flex-row gap-4 items-start">
                                @csrf
                                <div class="flex-1 w-full">
                                    <x-text-input type="email" name="email" placeholder="Adresse email de l'utilisateur" class="w-full" value="{{ old('email') }}" required />
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="w-full md:w-48">
                                    <select name="role" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-200">
                                        <option value="member">Membre</option>
                                        <option value="admin">Administrateur</option>
                                    </select>
                                </div>
                                <x-primary-button class="w-full md:w-auto justify-center bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800">
                                    Inviter
                                </x-primary-button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection