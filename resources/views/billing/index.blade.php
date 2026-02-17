@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 animate-slide-up">
    <!-- Header -->
    <div class="mb-12">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Facturation</h1>
        <p class="text-gray-500 mt-2">Gérez votre abonnement, vos factures et vos méthodes de paiement.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Current Subscription & Usage -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Current Plan Card -->
            <section class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Abonnement actuel</span>
                        <h2 class="text-2xl font-bold text-gray-900">
                            {{ $subscription ? $subscription->plan->name : 'Aucun plan' }}
                        </h2>
                    </div>
                    @if($subscription)
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-xs font-bold rounded-full border border-emerald-100 uppercase tracking-widest">
                            {{ ucfirst($subscription->status) }}
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 border-t border-gray-50 pt-8">
                    <div>
                        <span class="block text-sm text-gray-500 mb-1">Prix</span>
                        <p class="text-lg font-bold text-gray-900">
                            {{ $subscription ? '$' . number_format($subscription->plan->price / 100, 2) : '$0.00' }}/mois
                        </p>
                    </div>
                    <div>
                        <span class="block text-sm text-gray-500 mb-1">Prochain renouvellement</span>
                        <p class="text-lg font-bold text-gray-900">
                            {{ $subscription && $subscription->current_period_end ? $subscription->current_period_end->format('j F Y') : 'N/A' }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4">
                    <form action="{{ route('billing.manage') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20 text-sm">
                            Gérer la facturation
                        </button>
                    </form>
                    <a href="{{ route('pricing.index') }}" class="bg-gray-50 text-gray-900 px-6 py-2.5 rounded-xl font-bold hover:bg-gray-100 border border-gray-200 transition-all text-sm">
                        Changer de plan
                    </a>
                    @if($subscription && $subscription->status !== 'canceled')
                        <form action="{{ route('billing.cancel') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler votre abonnement ?');">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-600 font-bold text-sm px-4 py-2.5">
                                Annuler l'abonnement
                            </button>
                        </form>
                    @endif
                </div>
            </section>

            <!-- Usage Section -->
            <section class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-8">Utilisation des ressources</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <!-- Projects Usage -->
                    <div class="space-y-4">
                        <div class="flex justify-between items-end">
                            <span class="text-sm font-bold text-gray-700">Projets</span>
                            <span class="text-sm font-medium text-gray-500">
                                {{ $usage['projects'] }} / {{ $limits['projects'] ?: 'Illimités' }}
                            </span>
                        </div>
                        <div class="h-3 w-full bg-gray-100 rounded-full overflow-hidden">
                            @php
                                $projPercent = $limits['projects'] ? ($usage['projects'] / $limits['projects']) * 100 : 0;
                            @endphp
                            <div class="h-full bg-blue-600 rounded-full transition-all duration-1000" style="width: {{ min($projPercent, 100) }}%"></div>
                        </div>
                    </div>

                    <!-- Teams Usage -->
                    <div class="space-y-4">
                        <div class="flex justify-between items-end">
                            <span class="text-sm font-bold text-gray-700">Équipes</span>
                            <span class="text-sm font-medium text-gray-500">
                                {{ $usage['teams'] }} / {{ $limits['teams'] ?: 'Illimitées' }}
                            </span>
                        </div>
                        <div class="h-3 w-full bg-gray-100 rounded-full overflow-hidden">
                            @php
                                $teamPercent = $limits['teams'] ? ($usage['teams'] / $limits['teams']) * 100 : 0;
                            @endphp
                            <div class="h-full bg-blue-600 rounded-full transition-all duration-1000" style="width: {{ min($teamPercent, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Invoices Table -->
            <section class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-8 border-b border-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Historique des factures</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Date</th>
                                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Montant</th>
                                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Statut</th>
                                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            {{-- Mock data as backend is stubbed --}}
                            <tr>
                                <td class="px-8 py-4 text-sm text-gray-600 font-medium">17 Fév 2026</td>
                                <td class="px-8 py-4 text-sm text-gray-900 font-bold text-right">$29.00</td>
                                <td class="px-8 py-4 text-right">
                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-full border border-emerald-100 uppercase tracking-widest">Payée</span>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <a href="#" class="text-blue-600 hover:text-blue-700 flex items-center justify-end gap-2 text-sm font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        PDF
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-8 py-4 text-sm text-gray-600 font-medium">17 Jan 2026</td>
                                <td class="px-8 py-4 text-sm text-gray-900 font-bold text-right">$29.00</td>
                                <td class="px-8 py-4 text-right">
                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-full border border-emerald-100 uppercase tracking-widest">Payée</span>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <a href="#" class="text-blue-600 hover:text-blue-700 flex items-center justify-end gap-2 text-sm font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        PDF
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <!-- Right Column: Payment Methods -->
        <div class="space-y-8">
            <section class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Méthodes de paiement</h3>
                
                <div class="space-y-4 mb-8">
                    <div class="flex items-center gap-4 p-4 border border-gray-100 rounded-xl bg-gray-50/50">
                        <div class="w-12 h-8 bg-gray-900 rounded-md flex items-center justify-center text-white font-bold text-[10px]">VISA</div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-gray-900">•••• 4242</p>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Expire 04/28</p>
                        </div>
                        <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest bg-blue-50 px-2 py-0.5 rounded-full border border-blue-100">Défaut</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <button class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl border-2 border-dashed border-gray-200 text-gray-500 font-bold hover:border-blue-300 hover:text-blue-600 transition-all text-sm group">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        Ajouter une carte
                    </button>
                    <form action="{{ route('billing.manage') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gray-50 text-gray-900 font-bold border border-gray-200 hover:bg-gray-100 transition-all text-sm">
                            Gérer sur Stripe
                        </button>
                    </form>
                </div>
            </section>

            <!-- Billing Support Card -->
            <section class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-8 shadow-xl shadow-blue-600/20 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-bl-full -mr-8 -mt-8"></div>
                <h3 class="text-lg font-bold mb-2">Besoin d'aide ?</h3>
                <p class="text-blue-100 text-sm mb-6 leading-relaxed">Une question sur votre facture ou votre forfait ? Contactez notre équipe.</p>
                <a href="mailto:support@taskflow.com" class="inline-flex items-center gap-2 bg-white text-blue-600 px-6 py-2.5 rounded-xl font-bold text-sm hover:scale-105 transition-transform">
                    Nous contacter
                </a>
            </section>
        </div>
    </div>
</div>
@endsection
