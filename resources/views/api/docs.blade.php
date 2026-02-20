@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 animate-slide-up">
    <div class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Documentation API</h1>
            <p class="text-gray-500 mt-1">Intégrez TaskFlow dans vos applications avec notre API RESTful.</p>
        </div>
        <a href="{{ route('api.tokens.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition-all duration-200 shadow-lg shadow-blue-600/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
            Mes tokens API
        </a>
    </div>

    <div class="flex flex-col lg:flex-row gap-10">
        <!-- Sidebar Navigation -->
        <aside class="w-full lg:w-64 flex-shrink-0">
            <div class="sticky top-24 bg-white border border-gray-200 rounded-2xl p-4 shadow-sm space-y-1">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest px-3 mb-3">Navigation</p>
                <a href="#intro" class="block px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-xl">Introduction</a>
                <a href="#auth" class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl font-medium transition-colors">Authentification</a>
                <a href="#rate-limits" class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl font-medium transition-colors">Rate limiting</a>
                <a href="#projects" class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl font-medium transition-colors">Projets</a>
                <a href="#tasks" class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl font-medium transition-colors">Tâches</a>
                <a href="#teams" class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl font-medium transition-colors">Équipes</a>
                <a href="#comments" class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl font-medium transition-colors">Commentaires</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 min-w-0 space-y-12">
            <!-- Introduction -->
            <section id="intro">
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Introduction</h2>
                <p class="text-gray-600 leading-relaxed">
                    L’API TaskFlow est une API REST JSON, disponible sur l’URL de base :
                    <code class="px-1 py-0.5 bg-gray-100 rounded text-xs font-mono text-gray-700">
                        {{ rtrim(config('app.url', 'https://taskflow.com'), '/') }}/api
                    </code>
                </p>
                <p class="text-gray-600 leading-relaxed mt-3">
                    Toutes les réponses suivent la structure suivante&nbsp;:
                </p>
                <pre class="mt-3 bg-gray-900 rounded-xl p-4 text-xs text-blue-100 font-mono overflow-x-auto">
{
  "success": true,
  "data": { ... },
  "message": "Message de succès"
}
                </pre>
                <p class="text-gray-600 leading-relaxed mt-3">
                    En cas d’erreur&nbsp;:
                </p>
                <pre class="mt-3 bg-gray-900 rounded-xl p-4 text-xs text-red-100 font-mono overflow-x-auto">
{
  "success": false,
  "error": "Message d'erreur",
  "code": "error_code"
}
                </pre>
            </section>

            <!-- Auth -->
            <section id="auth">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Authentification</h2>
                <p class="text-gray-600 mb-4">
                    L’API utilise des <strong>API Tokens</strong> transmis via le header
                    <code class="px-1 py-0.5 bg-gray-100 rounded text-xs font-mono text-gray-700">Authorization: Bearer &lt;token&gt;</code>.
                    Vous pouvez générer ces tokens depuis la page
                    <a href="{{ route('api.tokens.index') }}" class="text-blue-600 hover:underline">Tokens API</a>.
                </p>
                <div class="bg-gray-900 rounded-xl p-4 overflow-x-auto mb-4">
                    <pre class="text-xs font-mono text-blue-100">Authorization: Bearer sk_live_xxxxxxxxxxxxxxxxxxxxx</pre>
                </div>

                <h3 class="text-sm font-bold text-gray-900 mb-2">Connexion par email / mot de passe</h3>
                <p class="text-gray-600 mb-2">
                    Vous pouvez également obtenir dynamiquement un token via l’endpoint public suivant&nbsp;:
                </p>
                <div class="border border-gray-200 rounded-xl overflow-hidden mb-4">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center gap-3">
                        <span class="px-2 py-1 text-xs font-bold text-emerald-700 bg-emerald-100 rounded uppercase">POST</span>
                        <code class="text-sm font-mono text-gray-700">/api/auth/login</code>
                    </div>
                    <div class="p-4 space-y-2 text-xs font-mono bg-gray-900 text-gray-100">
<pre>{
  "email": "user@example.com",
  "password": "secret"
}</pre>
                        <p class="text-[11px] text-gray-400 mt-2">
                            La réponse contient un champ <code>token</code> utilisable dans le header <code>Authorization</code>.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Rate limits -->
            <section id="rate-limits">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Rate limiting</h2>
                <p class="text-gray-600 mb-2">
                    Toutes les routes protégées sont limitées à <strong>1 000 requêtes par heure</strong> par token.
                </p>
                <p class="text-gray-600 mb-2">
                    Les headers suivants sont renvoyés avec chaque réponse&nbsp;:
                </p>
                <ul class="list-disc list-inside text-gray-600 text-sm">
                    <li><code class="font-mono">X-RateLimit-Remaining</code> – requêtes restantes</li>
                    <li><code class="font-mono">X-RateLimit-Reset</code> – timestamp de réinitialisation</li>
                </ul>
            </section>

            <!-- Projects -->
            <section id="projects">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Projets</h2>
                <div class="space-y-6">
                    <!-- GET /projects -->
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center gap-3">
                            <span class="px-2 py-1 text-xs font-bold text-blue-700 bg-blue-100 rounded uppercase">GET</span>
                            <code class="text-sm font-mono text-gray-700">/api/projects</code>
                        </div>
                        <div class="p-4">
                            <p class="text-sm text-gray-600 mb-3">Liste tous les projets de l’utilisateur authentifié.</p>
                            <pre class="bg-gray-50 p-3 rounded-lg text-xs font-mono text-gray-700 overflow-x-auto">
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Refonte Site Web",
      "status": "in_progress"
    }
  ],
  "message": "Liste des projets"
}</pre>
                        </div>
                    </div>

                    <!-- POST /projects -->
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center gap-3">
                            <span class="px-2 py-1 text-xs font-bold text-emerald-700 bg-emerald-100 rounded uppercase">POST</span>
                            <code class="text-sm font-mono text-gray-700">/api/projects</code>
                        </div>
                        <div class="p-4 space-y-2">
                            <p class="text-sm text-gray-600">
                                Crée un nouveau projet.
                            </p>
                            <pre class="bg-gray-900 p-3 rounded-lg text-xs font-mono text-gray-100 overflow-x-auto">
{
  "title": "Nouveau projet",
  "description": "Optionnel",
  "status": "preparation"
}</pre>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tasks -->
            <section id="tasks">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Tâches</h2>
                <div class="space-y-6">
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center gap-3">
                            <span class="px-2 py-1 text-xs font-bold text-blue-700 bg-blue-100 rounded uppercase">GET</span>
                            <code class="text-sm font-mono text-gray-700">/api/projects/{projectId}/tasks</code>
                        </div>
                        <div class="p-4">
                            <p class="text-sm text-gray-600">
                                Liste les tâches d’un projet donné.
                            </p>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center gap-3">
                            <span class="px-2 py-1 text-xs font-bold text-amber-700 bg-amber-100 rounded uppercase">PATCH</span>
                            <code class="text-sm font-mono text-gray-700">/api/tasks/{id}/status</code>
                        </div>
                        <div class="p-4 space-y-2">
                            <p class="text-sm text-gray-600">
                                Met à jour uniquement le statut d’une tâche (<code>todo</code>, <code>in_progress</code>, <code>done</code>).
                            </p>
                            <pre class="bg-gray-900 p-3 rounded-lg text-xs font-mono text-gray-100 overflow-x-auto">
{
  "status": "in_progress"
}</pre>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Teams -->
            <section id="teams">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Équipes</h2>
                <div class="space-y-6">
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center gap-3">
                            <span class="px-2 py-1 text-xs font-bold text-blue-700 bg-blue-100 rounded uppercase">GET</span>
                            <code class="text-sm font-mono text-gray-700">/api/teams</code>
                        </div>
                        <div class="p-4">
                            <p class="text-sm text-gray-600">
                                Récupère la liste des équipes appartenant à l’utilisateur.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Comments -->
            <section id="comments">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Commentaires</h2>
                <div class="space-y-6">
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center gap-3">
                            <span class="px-2 py-1 text-xs font-bold text-emerald-700 bg-emerald-100 rounded uppercase">POST</span>
                            <code class="text-sm font-mono text-gray-700">/api/tasks/{taskId}/comments</code>
                        </div>
                        <div class="p-4 space-y-2">
                            <p class="text-sm text-gray-600">
                                Ajoute un commentaire à une tâche.
                            </p>
                            <pre class="bg-gray-900 p-3 rounded-lg text-xs font-mono text-gray-100 overflow-x-auto">
{
  "content": "Mon commentaire"
}</pre>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>
@endsection
