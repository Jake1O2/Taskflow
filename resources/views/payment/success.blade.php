@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="bg-white border border-emerald-200 rounded-2xl p-8 shadow-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">OK</div>
            <h1 class="text-2xl font-bold text-gray-900">Paiement confirme</h1>
        </div>

        <p class="text-gray-600 mb-8">
            Votre achat a ete traite. Votre abonnement est en cours de synchronisation avec Stripe.
        </p>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('billing.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-colors">
                Voir la facturation
            </a>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-gray-300 text-gray-800 font-semibold hover:bg-gray-50 transition-colors">
                Retour au tableau de bord
            </a>
        </div>
    </div>
</div>
@endsection
