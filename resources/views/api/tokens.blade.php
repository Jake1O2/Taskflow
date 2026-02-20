@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 animate-slide-up">

    <!-- Header -->
    <div class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Tokens API</h1>
            <p class="text-gray-500 mt-1">G&#233;rez vos cl&#233;s d'acc&#232;s pour l'API TaskFlow.</p>
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

    {{-- New Token Banner (shown once after creation) --}}
    @if(session('new_token'))
        <div class="mb-8 p-5 bg-blue-50 border border-blue-200 rounded-2xl shadow-sm" id="new-token-banner">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-bold text-blue-900 mb-1">Token cr&#233;&#233; avec succ&#232;s &#8212; Copiez-le maintenant !</h4>
                    <p class="text-xs text-blue-700 mb-3">Ce token ne sera plus visible apr&#232;s avoir quitt&#233; cette page.</p>
                    <div class="flex items-center gap-2 p-3 bg-white border border-blue-200 rounded-xl">
                        <code class="flex-1 text-sm font-mono text-gray-800 break-all" id="new-token-value">{{ session('new_token') }}</code>
                        <button onclick="copyToClipboard('new-token-value', this)" class="flex-shrink-0 flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-all duration-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            Copier
                        </button>
                    </div>
                </div>
                <button onclick="document.getElementById('new-token-banner').remove()" class="flex-shrink-0 text-blue-400 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Tokens List -->
        <div class="lg:col-span-2 space-y-6">
            <section class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Tokens actifs</h3>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $tokens->count() }} token{{ $tokens->count() > 1 ? 's' : '' }} enregistr&#233;{{ $tokens->count() > 1 ? 's' : '' }}</p>
                    </div>
                </div>

                @if($tokens->isEmpty())
                    <div class="py-16 px-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-50 border border-gray-100 mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        </div>
                        <h4 class="text-gray-900 font-bold mb-1">Aucun token cr&#233;&#233;</h4>
                        <p class="text-gray-500 text-sm">Cr&#233;ez votre premier token pour acc&#233;der &#224; l'API.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($tokens as $token)
                            <div class="p-6 hover:bg-gray-50/50 transition-colors duration-150">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 text-sm">{{ $token->name }}</div>
                                                <div class="text-xs text-gray-400">Cr&#233;&#233; le {{ $token->created_at->format('j M Y') }}</div>
                                            </div>
                                        </div>

                                        <!-- Token preview with copy -->
                                        <div class="flex items-center gap-2 p-2.5 bg-gray-50 border border-gray-100 rounded-xl mb-3">
                                            <code class="flex-1 text-xs font-mono text-gray-500 truncate" id="token-{{ $token->id }}">{{ $token->token }}</code>
                                            <button onclick="copyToClipboard('token-{{ $token->id }}', this)" class="flex-shrink-0 p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200" title="Copier le token">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                            </button>
                                        </div>

                                        <!-- Permissions -->
                                        @php $perms = isset($token->permissions) ? $token->permissions : (isset($token->abilities) ? $token->abilities : []); @endphp
                                        @if(is_array($perms) && count($perms) > 0)
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach($perms as $perm)
                                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-lg uppercase tracking-wide">{{ $perm }}</span>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if($token->last_used_at)
                                            <div class="mt-2 text-xs text-gray-400">
                                                Dernier usage : {{ $token->last_used_at->diffForHumans() }}
                                            </div>
                                        @else
                                            <div class="mt-2 text-xs text-gray-400 italic">Jamais utilis&#233;</div>
                                        @endif
                                    </div>

                                    <!-- Revoke button -->
                                    <button onclick="openRevokeModal('{{ $token->id }}', '{{ $token->name }}')" class="flex-shrink-0 p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200" title="R&#233;voquer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- Usage Example -->
            <section class="bg-gray-900 rounded-2xl overflow-hidden shadow-xl">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700/50">
                    <div class="flex items-center gap-3">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-red-500/70"></div>
                            <div class="w-3 h-3 rounded-full bg-amber-500/70"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-500/70"></div>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Exemple d'utilisation</span>
                    </div>
                    <button onclick="copyToClipboard('usage-example', this)" class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white rounded-lg text-xs font-semibold transition-all duration-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        Copier
                    </button>
                </div>
                <pre class="text-blue-300 font-mono text-xs leading-relaxed overflow-x-auto p-6" id="usage-example">curl -H "Authorization: Bearer sk_live_xxxxx" \
     -H "Content-Type: application/json" \
     https://taskflow.com/api/projects</pre>
            </section>
        </div>

        <!-- Create Token Form -->
        <div class="space-y-6">
            <section class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Nouveau token</h3>
                </div>

                <form action="{{ route('api.tokens.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nom du token <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Ex: Application mobile" required
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none @error('name') border-red-400 bg-red-50 @enderror">
                        @error('name')
                            <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">Permissions</label>
                        <div class="space-y-3">
                            @foreach(['projects' => 'Projets', 'tasks' => 'T&#226;ches', 'teams' => '&#201;quipes'] as $res => $label)
                                <div class="p-3 border border-gray-100 rounded-xl bg-gray-50/50">
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">{!! $label !!}</div>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="checkbox" name="abilities[]" value="read:{{ $res }}"
                                                   {{ in_array('read:'.$res, old('abilities', [])) ? 'checked' : '' }}
                                                   class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500/20">
                                            <span class="text-xs text-gray-600 font-semibold group-hover:text-gray-900 transition-colors">Lecture</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="checkbox" name="abilities[]" value="write:{{ $res }}"
                                                   {{ in_array('write:'.$res, old('abilities', [])) ? 'checked' : '' }}
                                                   class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500/20">
                                            <span class="text-xs text-gray-600 font-semibold group-hover:text-gray-900 transition-colors">&#201;criture</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700 active:scale-[0.98] transition-all duration-200 shadow-lg shadow-blue-600/20 flex justify-center items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        G&#233;n&#233;rer le token
                    </button>
                </form>
            </section>

            <!-- Security Warning -->
            <section class="bg-amber-50 border border-amber-100 rounded-2xl p-5">
                <h4 class="text-sm font-bold text-amber-900 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    S&#233;curit&#233; importante
                </h4>
                <p class="text-xs text-amber-700 leading-relaxed">
                    Le token complet n'est affich&#233; <strong>qu'une seule fois</strong> apr&#232;s la cr&#233;ation. Conservez-le dans un endroit s&#251;r.
                </p>
            </section>

            <!-- Rate Limit Info -->
            <section class="bg-gray-50 border border-gray-100 rounded-2xl p-5">
                <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Limites de taux
                </h4>
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500">Requ&#234;tes / heure</span>
                        <span class="text-xs font-bold text-gray-800">1 000</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: 15%"></div>
                    </div>
                    <p class="text-[10px] text-gray-400">Header : <code class="font-mono">X-RateLimit-Remaining</code></p>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Revoke Token Modal -->
<div id="revoke-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeRevokeModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 animate-slide-up">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">R&#233;voquer le token</h3>
                <p class="text-sm text-gray-500 mt-0.5">Cette action est irr&#233;versible</p>
            </div>
        </div>
        <p class="text-gray-600 text-sm mb-2">Vous &#234;tes sur le point de r&#233;voquer le token :</p>
        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 mb-4">
            <span class="text-sm font-bold text-gray-800" id="revoke-name"></span>
        </div>
        <p class="text-xs text-red-600 mb-6 font-medium">Toutes les applications utilisant ce token perdront imm&#233;diatement l'acc&#232;s &#224; l'API.</p>
        <div class="flex gap-3">
            <button onclick="closeRevokeModal()" class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-50 transition-all">
                Annuler
            </button>
            <form id="revoke-form" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white rounded-xl font-bold text-sm hover:bg-red-700 transition-all shadow-lg shadow-red-600/20">
                    R&#233;voquer
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

// Revoke modal
function openRevokeModal(id, name) {
    document.getElementById('revoke-name').textContent = name;
    document.getElementById('revoke-form').action = '/api/tokens/' + id;
    const modal = document.getElementById('revoke-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeRevokeModal() {
    const modal = document.getElementById('revoke-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Auto-dismiss flash messages
setTimeout(() => {
    ['flash-success', 'flash-error'].forEach(id => {
        const el = document.getElementById(id);
        if (el) { el.style.transition = 'opacity 0.5s'; el.style.opacity = '0'; setTimeout(() => el.remove(), 500); }
    });
}, 5000);
</script>
@endsection
