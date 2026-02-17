@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 animate-slide-up">
    <!-- Header -->
    <div class="mb-12">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Webhooks</h1>
        <p class="text-gray-500 mt-2">Recevez des notifications en temps réel pour vos événements TaskFlow.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Webhooks List -->
        <div class="lg:col-span-2 space-y-8">
            <section class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Webhooks actifs</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">URL du Endpoint</th>
                                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Événements</th>
                                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Statut</th>
                                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($webhooks as $webhook)
                                <tr>
                                    <td class="px-8 py-4">
                                        <div class="text-sm font-bold text-gray-900 truncate max-w-xs">{{ $webhook->url }}</div>
                                        <div class="text-[10px] text-gray-400 font-bold mt-1 uppercase tracking-widest">Ajouté le {{ $webhook->created_at->format('j M Y') }}</div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($webhook->events as $event)
                                                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-lg">{{ $event }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <span class="px-2 py-0.5 {{ $webhook->status === 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-gray-50 text-gray-500 border-gray-100' }} text-[10px] font-bold rounded-full border uppercase tracking-widest">
                                            {{ $webhook->status }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="Tester le webhook">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                            </button>
                                            <form action="{{ route('webhooks.destroy', $webhook->id) }}" method="POST" onsubmit="return confirm('Supprimer ce webhook ?');">
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

            <!-- JSON Format Card -->
            <section class="bg-gray-900 rounded-2xl p-8 overflow-hidden relative shadow-2xl">
                <div class="absolute top-0 right-0 p-4 opacity-5">
                    <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                </div>
                <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Structure JSON d'exemple</h4>
<pre class="text-emerald-400 font-mono text-xs leading-relaxed overflow-x-auto">
{
  "event": "task.created",
  "timestamp": "2026-02-17T01:10:00Z",
  "data": {
    "task_id": 123,
    "project_id": 45,
    "title": "Nouvelle tâche",
    "created_by": "John Doe"
  }
}
</pre>
            </section>
        </div>

        <!-- Create Webhook Form -->
        <div class="space-y-8">
            <section class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Nouveau webhook</h3>
                <form action="{{ route('webhooks.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">URL du Endpoint</label>
                        <input type="url" name="url" placeholder="https://api.yourdomain.com/hooks" required 
                               class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                        <p class="text-[10px] text-gray-400 mt-2 font-medium italic">Doit commencer par https://</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">Événements à écouter</label>
                        <div class="grid grid-cols-1 gap-2">
                            @foreach(['project.created', 'task.created', 'comment.added', 'team.member.added', 'payment.received'] as $ev)
                                <label class="flex items-center gap-3 p-3 border border-gray-50 rounded-xl bg-gray-50/30 cursor-pointer hover:bg-white hover:border-blue-100 transition-all group">
                                    <input type="checkbox" name="events[]" value="{{ $ev }}" class="rounded text-blue-600 focus:ring-blue-500/20">
                                    <span class="text-xs text-gray-600 font-bold group-hover:text-blue-600 transition-colors capitalize">{{ str_replace('.', ' ', $ev) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-blue-50/30 rounded-xl border border-blue-50">
                        <span class="text-sm font-bold text-blue-900">Activer le webhook</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20">
                        Créer le webhook
                    </button>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection
