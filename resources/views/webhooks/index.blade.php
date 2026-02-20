@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 animate-slide-up">

    <!-- Header -->
    <div class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Webhooks</h1>
            <p class="text-gray-500 mt-1">Recevez des notifications en temps réel pour vos événements TaskFlow.</p>
        </div>
        <a href="{{ route('api.docs') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Documentation
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-emerald-700 shadow-sm" id="flash-success">
            <div class="flex-shrink-0 w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
            <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3 text-red-700 shadow-sm" id="flash-error">
            <div class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-sm font-semibold">{{ session('error') }}</span>
            <button onclick="document.getElementById('flash-error').remove()" class="ml-auto text-red-400 hover:text-red-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Webhooks List -->
        <div class="lg:col-span-2 space-y-6">
            <section class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Webhooks configurés</h3>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $webhooks->count() }} webhook{{ $webhooks->count() > 1 ? 's' : '' }} enregistré{{ $webhooks->count() > 1 ? 's' : '' }}</p>
                    </div>
                </div>

                @if($webhooks->isEmpty())
                    <div class="py-16 px-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-50 border border-gray-100 mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h4 class="text-gray-900 font-bold mb-1">Aucun webhook configuré</h4>
                        <p class="text-gray-500 text-sm">Créez votre premier webhook pour recevoir des notifications.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($webhooks as $webhook)
                            <div class="p-6 hover:bg-gray-50/50 transition-colors duration-150" id="webhook-row-{{ $webhook->id }}">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-3 mb-2">
                                            <!-- Toggle Switch -->
                                            <label class="relative inline-flex items-center cursor-pointer" title="{{ $webhook->status === 'active' ? 'Désactiver' : 'Activer' }}">
                                                <input type="checkbox"
                                                    class="sr-only peer webhook-toggle"
                                                    data-id="{{ $webhook->id }}"
                                                    {{ $webhook->status === 'active' ? 'checked' : '' }}>
                                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                                            </label>
                                            <span class="text-xs font-bold uppercase tracking-widest {{ $webhook->status === 'active' ? 'text-emerald-600' : 'text-gray-400' }}" id="status-label-{{ $webhook->id }}">
                                                {{ $webhook->status === 'active' ? 'Actif' : 'Inactif' }}
                                            </span>
                                        </div>

                                        <!-- URL with copy button -->
                                        <div class="flex items-center gap-2 mb-3">
                                            <code class="text-sm font-mono text-gray-700 truncate max-w-xs" id="url-{{ $webhook->id }}">{{ $webhook->url }}</code>
                                            <button onclick="copyToClipboard('url-{{ $webhook->id }}', this)" class="flex-shrink-0 p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200" title="Copier l'URL">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                            </button>
                                        </div>

                                        <!-- Events -->
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($webhook->events as $event)
                                                <span class="px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-100 text-[10px] font-bold rounded-lg uppercase tracking-wide">{{ $event }}</span>
                                            @endforeach
                                        </div>

                                        <div class="mt-2 text-xs text-gray-400">Ajouté le {{ $webhook->created_at->format('j M Y') }}</div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center gap-1.5 flex-shrink-0">
                                        <a href="{{ route('webhooks.logs', $webhook) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200" title="Voir les logs">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </a>
                                        <a href="{{ route('webhooks.edit', $webhook) }}" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-all duration-200" title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('webhooks.test', $webhook) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200" title="Tester le webhook">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                            </button>
                                        </form>
                                        <button type="button" onclick="openDeleteModal(this)" data-delete-url="{{ route('webhooks.destroy', $webhook->id) }}" data-webhook-url="{{ e($webhook->url) }}" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200" title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- JSON Payload Example -->
            <section class="bg-gray-900 rounded-2xl overflow-hidden shadow-xl">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700/50">
                    <div class="flex items-center gap-3">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-red-500/70"></div>
                            <div class="w-3 h-3 rounded-full bg-amber-500/70"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-500/70"></div>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Payload JSON exemple</span>
                    </div>
                    <button onclick="copyToClipboard('json-payload', this)" class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white rounded-lg text-xs font-semibold transition-all duration-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        Copier
                    </button>
                </div>
                <pre class="text-emerald-400 font-mono text-xs leading-relaxed overflow-x-auto p-6" id="json-payload">{
  "event": "task.created",
  "timestamp": "2026-02-17T01:10:00Z",
  "data": {
    "task_id": 123,
    "project_id": 45,
    "title": "Nouvelle tâche",
    "status": "todo",
    "created_by": {
      "id": 1,
      "name": "John Doe"
    }
  }
}</pre>
            </section>
        </div>

        <!-- Create Webhook Form -->
        <div class="space-y-6">
            <section class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Nouveau webhook</h3>
                </div>

                <form action="{{ route('webhooks.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">URL du endpoint <span class="text-red-500">*</span></label>
                        <input type="url" name="url" value="{{ old('url') }}" placeholder="https://api.yourdomain.com/hooks" required
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none @error('url') border-red-400 bg-red-50 @enderror">
                        @error('url')
                            <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-gray-400 mt-1.5 font-medium">Doit commencer par https://</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">&#201;v&#233;nements &#224; &#233;couter</label>
                        <div class="space-y-2">
                            @php
                                $events = [
                                    ['value' => 'project.created', 'label' => 'Projet cr&#233;&#233;'],
                                    ['value' => 'task.created', 'label' => 'T&#226;che cr&#233;&#233;e'],
                                    ['value' => 'task.updated', 'label' => 'T&#226;che mise &#224; jour'],
                                    ['value' => 'task.assigned', 'label' => 'T&#226;che assign&#233;e'],
                                    ['value' => 'comment.added', 'label' => 'Commentaire ajout&#233;'],
                                    ['value' => 'team.member.added', 'label' => 'Membre ajout&#233;'],
                                ];
                            @endphp
                            @foreach($events as $ev)
                                <label class="flex items-center gap-3 p-3 border border-gray-100 rounded-xl bg-gray-50/50 cursor-pointer hover:bg-white hover:border-blue-200 hover:shadow-sm transition-all duration-200 group">
                                    <input type="checkbox" name="events[]" value="{{ $ev['value'] }}"
                                           {{ in_array($ev['value'], old('events', [])) ? 'checked' : '' }}
                                           class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500/20">
                                    <span class="text-xs text-gray-600 font-semibold group-hover:text-blue-700 transition-colors">{!! $ev['label'] !!}</span>
                                    <span class="ml-auto text-[10px] font-mono text-gray-400">{{ $ev['value'] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Active Toggle -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div>
                            <span class="text-sm font-bold text-gray-800">Activer imm&#233;diatement</span>
                            <p class="text-xs text-gray-500 mt-0.5">Le webhook recevra les &#233;v&#233;nements d&#232;s la cr&#233;ation</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer ml-4">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700 active:scale-[0.98] transition-all duration-200 shadow-lg shadow-blue-600/20 flex justify-center items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Cr&#233;er le webhook
                    </button>
                </form>
            </section>

            <!-- Security Info -->
            <section class="bg-amber-50 border border-amber-100 rounded-2xl p-5">
                <h4 class="text-sm font-bold text-amber-900 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    S&#233;curit&#233;
                </h4>
                <p class="text-xs text-amber-700 leading-relaxed">
                    Chaque requ&#234;te inclut un header <code class="bg-amber-100 px-1 rounded font-mono">X-TaskFlow-Signature</code> pour v&#233;rifier l'authenticit&#233; du payload.
                </p>
            </section>
        </div>
    </div>
</div>

<!-- Delete Webhook Modal -->
<div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 animate-slide-up">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Supprimer le webhook</h3>
                <p class="text-sm text-gray-500 mt-0.5">Cette action est irr&#233;versible</p>
            </div>
        </div>
        <p class="text-gray-600 text-sm mb-2">Vous &#234;tes sur le point de supprimer le webhook :</p>
        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 mb-6">
            <code class="text-xs font-mono text-gray-700 break-all" id="delete-url"></code>
        </div>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-50 transition-all">
                Annuler
            </button>
            <form id="delete-form" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white rounded-xl font-bold text-sm hover:bg-red-700 transition-all shadow-lg shadow-red-600/20">
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Copy to clipboard
function copyToClipboard(elementId, btn) {
    const el = document.getElementById(elementId);
    const text = el.textContent || el.innerText;
    navigator.clipboard.writeText(text.trim()).then(() => {
        const original = btn.innerHTML;
        btn.innerHTML = '<svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>';
        setTimeout(() => { btn.innerHTML = original; }, 2000);
    });
}

// Delete modal
function openDeleteModal(btn) {
    const deleteUrl = btn.dataset.deleteUrl;
    const url = btn.dataset.webhookUrl;
    document.getElementById('delete-url').textContent = url;
    document.getElementById('delete-form').action = deleteUrl;
    const modal = document.getElementById('delete-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeDeleteModal() {
    const modal = document.getElementById('delete-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Webhook toggle (active/inactive)
document.querySelectorAll('.webhook-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const id = this.dataset.id;
        const isActive = this.checked;
        const label = document.getElementById('status-label-' + id);

        fetch('/webhooks/' + id + '/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ active: isActive })
        }).then(res => {
            if (res.ok) {
                if (label) {
                    label.textContent = isActive ? 'Actif' : 'Inactif';
                    label.className = 'text-xs font-bold uppercase tracking-widest ' + (isActive ? 'text-emerald-600' : 'text-gray-400');
                }
            } else {
                this.checked = !isActive; // revert
            }
        }).catch(() => { this.checked = !isActive; });
    });
});

// Auto-dismiss flash messages
setTimeout(() => {
    ['flash-success', 'flash-error'].forEach(id => {
        const el = document.getElementById(id);
        if (el) { el.style.transition = 'opacity 0.5s'; el.style.opacity = '0'; setTimeout(() => el.remove(), 500); }
    });
}, 5000);
</script>
@endsection
