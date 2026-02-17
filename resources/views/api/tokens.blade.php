@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 animate-slide-up">
    <!-- Header -->
    <div class="mb-12">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Tokens API</h1>
        <p class="text-gray-500 mt-2">Gérez vos tokens pour l'accès aux ressources de l'API TaskFlow.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Tokens List -->
        <div class="lg:col-span-2 space-y-8">
            <section class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-8 border-b border-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Tokens actifs</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Nom</th>
                                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Créé</th>
                                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Dernier usage</th>
                                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($tokens as $token)
                                <tr>
                                    <td class="px-8 py-4">
                                        <div class="font-bold text-gray-900">{{ $token->name }}</div>
                                        <div class="text-[10px] font-mono text-gray-400 mt-1">{{ $token->token }}</div>
                                    </td>
                                    <td class="px-8 py-4 text-sm text-gray-600">{{ $token->created_at->format('j F Y') }}</td>
                                    <td class="px-8 py-4 text-sm text-gray-500 italic">{{ $token->last_used_at->diffForHumans() }}</td>
                                    <td class="px-8 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button onclick="alert('Token: {{ $token->token }}')" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </button>
                                            <form action="{{ route('api.tokens.destroy', $token->id) }}" method="POST" onsubmit="return confirm('Révoquer ce token ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <!-- Create Token Form -->
        <div class="space-y-8">
            <section class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Nouveau token</h3>
                <form action="{{ route('api.tokens.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nom du token</label>
                        <input type="text" name="name" placeholder="Ex: Mobile App" required 
                               class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">Permissions</label>
                        <div class="space-y-3">
                            @foreach(['projects', 'tasks', 'teams'] as $res)
                                <div class="flex flex-col gap-2 p-3 border border-gray-50 rounded-xl bg-gray-50/30">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $res }}</span>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="checkbox" name="abilities[]" value="read:{{ $res }}" class="rounded text-blue-600 focus:ring-blue-500/20">
                                            <span class="text-sm text-gray-600 group-hover:text-gray-900">Lecture</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="checkbox" name="abilities[]" value="write:{{ $res }}" class="rounded text-blue-600 focus:ring-blue-500/20">
                                            <span class="text-sm text-gray-600 group-hover:text-gray-900">Écriture</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20">
                        Créer le token
                    </button>
                    
                    <div class="p-4 bg-amber-50 rounded-xl border border-amber-100">
                        <p class="text-xs text-amber-700 leading-relaxed font-medium">
                            <span class="font-bold">Attention :</span> Pour votre sécurité, ce token ne sera affiché qu'une seule fois. Gardez-le précieusement.
                        </p>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection
