@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <section class="card-premium text-center">
            <p class="mb-3 text-xs font-bold uppercase tracking-[0.18em] text-primary">TaskFlow</p>
            <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">Gestion de projet, claire et rapide</h1>
            <p class="mx-auto mt-4 max-w-2xl text-gray-600">
                Cette page est prête pour accueillir ton tableau d'accueil personnalisé.
            </p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('projects.index') }}" class="rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white shadow-md shadow-primary/30 hover:bg-primary-dark">
                    Voir les projets
                </a>
                <a href="{{ route('projects.create') }}" class="btn-secondary">
                    Nouveau projet
                </a>
            </div>
        </section>
    </div>
@endsection
