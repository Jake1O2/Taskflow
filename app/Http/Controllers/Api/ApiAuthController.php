<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ApiAuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($validated)) {
            return response()->json([
                'success' => false,
                'error' => 'Identifiants incorrects.',
                'code' => 'invalid_credentials',
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $plainToken = 'sk_live_' . Str::random(48);

        $user->apiTokens()->create([
            'name' => 'API Login ' . now()->format('Y-m-d H:i'),
            'token' => hash('sha256', $plainToken),
            'expires_at' => now()->addDays(90),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user->only(['id', 'name', 'email']),
                'token' => $plainToken,
                'token_type' => 'Bearer',
            ],
            'message' => 'Connexion reussie',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $apiToken = $request->attributes->get('api_token_instance');

        if ($apiToken instanceof ApiToken) {
            $apiToken->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Deconnexion reussie',
        ]);
    }
}
