@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Détails de l'équipe --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $team->name }}</h1>

                    @if($team->description)
                        <p class="text-gray-700 mb-4">{{ $team->description }}</p>
                    @endif

                    <p class="text-sm text-gray-500 mb-4">
                        Propriétaire : <span class="font-semibold text-gray-700">{{ $team->owner->name }}</span>
                    </p>

                    <div class="flex flex-wrap gap-2">
                        @if(Auth::id() === $team->user_id)
                            <a href="{{ route('teams.edit', $team->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Éditer
                            </a>
                            <form action="{{ route('teams.destroy', $team->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Supprimer cette équipe ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Supprimer
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('teams.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                            Retour
                        </a>
                    </div>
                </div>
            </div>

            {{-- Section Membres --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Membres</h2>

                    {{-- Table des membres --}}
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                                    @if(Auth::id() === $team->user_id)
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- Le propriétaire --}}
                                <tr class="bg-yellow-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $team->owner->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $team->owner->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Propriétaire
                                        </span>
                                    </td>
                                    @if(Auth::id() === $team->user_id)
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-400">—</td>
                                    @endif
                                </tr>
                                {{-- Les membres --}}
                                @foreach($members as $member)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $member->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $member->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ ucfirst($member->role ?? 'member') }}
                                            </span>
                                        </td>
                                        @if(Auth::id() === $team->user_id)
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form action="{{ route('teams.removeMember', [$team->id, $member->user_id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Retirer ce membre ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Retirer</button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if(empty($members) || count($members) === 0)
                            <p class="text-gray-500 italic text-center py-4">Aucun membre ajouté.</p>
                        @endif
                    </div>

            {{-- Formulaire d'ajout de membre (owner seulement) --}}
                    @if(Auth::id() === $team->user_id)
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Ajouter un membre</h3>
                            <form action="{{ route('teams.addMember', $team->id) }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                                @csrf
                                <div class="flex-1">
                                    <input type="email" name="email" placeholder="Email de l'utilisateur"
                                           class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2"
                                           value="{{ old('email') }}" required>
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <select name="role" class="rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2">
                                        <option value="member">Membre</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Ajouter
                                </button>
                            </form>
                        </div>
                    @endif                </div>
            </div>
        </div>
    </div>
@endsection