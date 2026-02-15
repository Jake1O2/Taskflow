@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg shadow-sm animate-fade-in flex items-center mb-6" role="alert">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- En-tête de l'équipe -->
            <div class="bg-white overflow-hidden shadow-sm shadow-gray-200 rounded-xl mb-6 border border-gray-200 animate-slide-down">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row justify-between md:items-start gap-4">
                        <div>
                            <div class="flex items-center gap-4 mb-2">
                                <div class="shrink-0 h-16 w-16 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-bold text-3xl shadow-sm">
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
                                <p class="text-gray-600 mt-4 leading-relaxed max-w-2xl bg-gray-50 p-4 rounded-lg border border-gray-100 italic">
                                    "{{ $team->description }}"
                                </p>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('teams.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">
                                Retour
                            </a>
                            @if(Auth::id() === $team->user_id)
                                <a href="{{ route('teams.edit', $team->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    Éditer
                                </a>
                                <form action="{{ route('teams.destroy', $team->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Supprimer cette équipe ? Cette action est irréversible.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 border border-red-200 rounded-lg font-medium text-sm text-red-600 shadow-sm hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Supprimer
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Membres -->
            <div class="bg-white overflow-hidden shadow-sm shadow-gray-200 rounded-xl border border-gray-200 animate-slide-down" style="animation-delay: 100ms;">
                <div class="p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="bg-indigo-100 p-1.5 rounded-lg text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </span>
                        Membres de l'équipe
                    </h2>

                    <!-- Table des membres -->
                    <div class="overflow-hidden rounded-xl border border-gray-200 mb-8">
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
                                <tr class="bg-amber-50/50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-9 w-9 rounded-full bg-amber-200 flex items-center justify-center text-xs font-bold text-amber-800 mr-3 border-2 border-white shadow-sm">
                                                {{ substr($team->owner->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $team->owner->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $team->owner->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800 border border-amber-200">
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
                                                <div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-700 mr-3 border-2 border-white shadow-sm">
                                                    {{ substr($member->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $member->user->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $member->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase tracking-wide">
                                                {{ $member->role ?? 'member' }}
                                            </span>
                                        </td>
                                        @if(Auth::id() === $team->user_id)
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form action="{{ route('teams.removeMember', [$team->id, $member->user_id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Retirer ce membre de l\'équipe ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1.5 hover:bg-red-50 rounded-lg transition-colors" title="Retirer">
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
                            <div class="p-12 text-center bg-gray-50 border-t border-gray-100">
                                <div class="bg-white p-3 rounded-full shadow-sm inline-block mb-3">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <p class="text-gray-500 font-medium">Aucun membre ajouté pour le moment.</p>
                                <p class="text-gray-400 text-sm mt-1">Invitez des collègues pour collaborer.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Formulaire d'ajout de membre (owner seulement) -->
                    @if(Auth::id() === $team->user_id)
                        <div class="border-t border-gray-100 pt-8 mt-4 bg-gray-50/50 p-6 rounded-xl border border-gray-100">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <div class="bg-indigo-100 p-1.5 rounded-lg">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                </div>
                                Ajouter un nouveau membre
                            </h3>
                            <form action="{{ route('teams.addMember', $team->id) }}" method="POST" class="flex flex-col md:flex-row gap-4 items-start">
                                @csrf
                                <div class="flex-1 w-full space-y-1">
                                    <label for="email" class="sr-only">Email</label>
                                    <input type="email" name="email" id="email" 
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all duration-200" 
                                           placeholder="Adresse email de l'utilisateur" value="{{ old('email') }}" required />
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-shake">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="w-full md:w-48 space-y-1">
                                    <label for="role" class="sr-only">Rôle</label>
                                    <select name="role" id="role" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all duration-200 cursor-pointer">
                                        <option value="member">Membre</option>
                                        <option value="admin">Administrateur</option>
                                    </select>
                                </div>
                                <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">
                                    Inviter
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection