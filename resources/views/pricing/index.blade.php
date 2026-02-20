@extends(Auth::check() ? 'layouts.app' : 'layouts.guest')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 animate-slide-up">
    @php
        $featureCatalog = collect($plans)
            ->pluck('features')
            ->flatten()
            ->filter(fn ($feature) => $feature !== 'all_features')
            ->unique()
            ->values();

        $comparisonFeatures = collect($plans)
            ->pluck('features')
            ->flatten()
            ->unique()
            ->values();

        $resolvedFeaturesByPlan = collect($plans)->mapWithKeys(function ($plan) use ($featureCatalog) {
            $features = collect($plan->features ?? []);
            $isUnlimitedPlan = $plan->max_projects === null && $plan->max_teams === null;

            if ($isUnlimitedPlan) {
                $features = $featureCatalog;
            }

            return [$plan->id => $features->unique()->values()->all()];
        });
    @endphp

    @if(session('success'))
        <div class="mb-8 p-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-8 p-4 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm font-semibold">
            {{ session('error') }}
        </div>
    @endif

    @if(session('info'))
        <div class="mb-8 p-4 rounded-xl border border-blue-200 bg-blue-50 text-blue-700 text-sm font-semibold">
            {{ session('info') }}
        </div>
    @endif

    <!-- Hero Section -->
    <div class="text-center space-y-4 mb-16">
        <h1 class="text-4xl font-bold text-gray-900 tracking-tight">Tarification simple et transparente</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Choisissez le plan qui vous convient pour booster votre productivité.</p>
    </div>

    <!-- Pricing Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($plans as $plan)
            @php
                $isPro = $plan->name === 'Pro';
                $isCurrent = Auth::check() && Auth::user()->hasPlan($plan->name);
                $planFeatures = $resolvedFeaturesByPlan[$plan->id] ?? [];
            @endphp
            <div class="relative group h-full">
                <!-- Pro Badge -->
                @if($isPro)
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 z-10">
                        <span class="bg-blue-600 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest shadow-lg">
                            PLUS POPULAIRE
                        </span>
                    </div>
                @endif

                <div class="h-full bg-white border {{ $isPro ? 'border-blue-600 ring-2 ring-blue-600/10' : 'border-gray-200' }} rounded-2xl p-8 flex flex-col transition-all duration-300 group-hover:shadow-2xl group-hover:-translate-y-1">
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-bold text-gray-900">${{ number_format($plan->price / 100, 0) }}</span>
                            <span class="text-gray-500 font-medium">/mois</span>
                        </div>
                        <p class="mt-4 text-sm text-gray-600">
                            {{ $plan->name === 'Free' ? 'Parfait pour s\'initier.' : ($plan->name === 'Pro' ? 'Idéal pour les professionnels.' : 'Puissance pour les grandes équipes.') }}
                        </p>
                    </div>

                    <!-- Features List -->
                    <ul class="space-y-4 mb-8 flex-1">
                        @foreach($planFeatures as $feature)
                            <li class="flex items-start gap-3">
                                <div class="w-5 h-5 rounded-full bg-emerald-50 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700 leading-tight">
                                    {{ str_replace('_', ' ', ucfirst($feature)) }}
                                </span>
                            </li>
                        @endforeach
                        
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-emerald-50 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="text-gray-700 leading-tight">
                                {{ $plan->max_projects ? $plan->max_projects . ' projets' : 'Projets illimités' }}
                            </span>
                        </li>
                        
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-emerald-50 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="text-gray-700 leading-tight">
                                {{ $plan->max_teams ? $plan->max_teams . ' équipes' : 'Équipes illimitées' }}
                            </span>
                        </li>
                    </ul>

                    <!-- CTA Button -->
                    @if($isCurrent)
                        <button disabled class="w-full py-3 px-6 rounded-xl bg-gray-100 text-gray-400 font-bold cursor-not-allowed">
                            Plan actuel
                        </button>
                    @else
                        @if(!Auth::check() && $plan->name === 'Free')
                            <a href="{{ route('register') }}" class="w-full block text-center py-3 px-6 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/20">
                                Commencer gratuitement
                            </a>
                        @else
                            <form action="{{ Auth::check() ? route('payment.checkout', $plan->id) : route('login') }}" method="{{ Auth::check() ? 'POST' : 'GET' }}">
                                @csrf
                                <button type="submit" class="w-full py-3 px-6 rounded-xl {{ $isPro ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20 hover:bg-blue-700' : 'bg-gray-900 text-white hover:bg-gray-800' }} font-bold transition-all">
                                    {{ Auth::check() ? 'Choisir ce plan' : 'Se connecter pour choisir' }}
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Comparison Table -->
    <div class="mt-24 space-y-8">
        <h2 class="text-2xl font-bold text-gray-900 text-center">Comparatif détaillé</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-4 text-sm font-bold text-gray-500 uppercase tracking-widest">Fonctionnalité</th>
                        @foreach($plans as $plan)
                            <th class="px-6 py-4 text-center font-bold text-gray-900">{{ $plan->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="px-6 py-4 text-gray-700 font-medium">Projets</td>
                        @foreach($plans as $plan)
                            <td class="px-6 py-4 text-center text-gray-600">{{ $plan->max_projects ?: 'Illimité' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-gray-700 font-medium">Équipes</td>
                        @foreach($plans as $plan)
                            <td class="px-6 py-4 text-center text-gray-600">{{ $plan->max_teams ?: 'Illimité' }}</td>
                        @endforeach
                    </tr>
                    @foreach($comparisonFeatures as $feature)
                        <tr>
                            <td class="px-6 py-4 text-gray-700 font-medium">{{ str_replace('_', ' ', ucfirst($feature)) }}</td>
                            @foreach($plans as $plan)
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $planFeatures = $resolvedFeaturesByPlan[$plan->id] ?? [];
                                        $isUnlimitedPlan = $plan->max_projects === null && $plan->max_teams === null;
                                    @endphp
                                    @if(in_array($feature, $planFeatures, true) || ($feature === 'all_features' && $isUnlimitedPlan))
                                        <svg class="w-5 h-5 text-emerald-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <span class="text-gray-300 font-bold">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
