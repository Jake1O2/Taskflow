@extends('layouts.guest')

@section('content')
    <div class="bg-white rounded-[16px] shadow-[0_4px_12px_rgba(0,0,0,0.15)] border border-gray-200 p-10 animate-fade-in">
        <div class="flex flex-col items-center mb-8 animate-slide-in-up">
            {{-- Logo TaskFlow --}}
            <div class="w-6 h-6 bg-blue-600 rounded flex items-center justify-center mb-4">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="text-[28px] font-bold text-gray-900 leading-tight">Connexion</h2>
            <p class="text-sm text-gray-600 mt-1">Accédez à votre tableau de bord</p>
        </div>

        @if ($errors->any())
            <div class="bg-[#fee2e2] border border-[#fecaca] text-[#dc2626] p-3 rounded-lg mb-5 text-[14px]">
                <ul class="list-none p-0 m-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="animate-slide-in-up space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-[14px] font-semibold text-gray-700 mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-[12px] py-[12px] text-[16px] text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:focus-blue-ring transition-all duration-200"
                    placeholder="votre@email.com">
            </div>

            <div>
                <label for="password" class="block text-[14px] font-semibold text-gray-700 mb-2">Mot de passe</label>
                <input id="password" type="password" name="password" required
                    class="w-full px-[12px] py-[12px] text-[16px] text-gray-900 border border-gray-300 rounded-lg focus:outline-none focus:focus-blue-ring transition-all duration-200"
                    placeholder="••••••••">
            </div>

            <div class="flex items-center mt-3">
                <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
            </div>

            <div class="pt-2">
                <button type="submit" 
                    class="w-full bg-blue-600 text-white font-bold py-[12px] rounded-lg hover:brightness-110 active:scale-[0.98] transition-all duration-200">
                    Connexion
                </button>
            </div>

            <div class="text-center pt-5">
                <p class="text-sm text-gray-600">
                    Pas encore inscrit ? 
                    <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline transition-colors">
                        S'inscrire
                    </a>
                </p>
            </div>
        </form>
    </div>
@endsection
