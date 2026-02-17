@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 animate-slide-up">
    <div class="mb-12">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Documentation API</h1>
        <p class="text-gray-500 mt-2">Apprenez à intégrer TaskFlow avec vos outils préférés.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <section class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Introduction</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    L'API TaskFlow est une API RESTful qui vous permet de gérer vos projets, tâches et équipes de manière programmatique.
                </p>
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 font-mono text-sm text-blue-600">
                    Base URL: https://api.taskflow.com/v1
                </div>
            </section>

            <section class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Authentification</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Toutes les requêtes à l'API doivent inclure votre token d'API dans le header <code class="bg-gray-100 px-1 rounded">Authorization</code>.
                </p>
                <pre class="bg-gray-900 text-emerald-400 p-4 rounded-xl text-xs overflow-x-auto">
Authorization: Bearer YOUR_API_TOKEN</pre>
            </section>
        </div>

        <div class="space-y-8">
            <section class="bg-blue-600 rounded-2xl p-8 text-white shadow-lg shadow-blue-600/20">
                <h3 class="text-lg font-bold mb-4">Besoin d'aide ?</h3>
                <p class="text-blue-100 text-sm leading-relaxed mb-6">
                    Notre équipe est disponible pour vous aider à intégrer notre API.
                </p>
                <a href="mailto:support@taskflow.com" class="inline-block px-6 py-2 bg-white text-blue-600 font-bold rounded-xl hover:bg-blue-50 transition-all">
                    Contact Support
                </a>
            </section>
        </div>
    </div>
</div>
@endsection
