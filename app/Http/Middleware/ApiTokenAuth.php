<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiTokenAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json([
                'success' => false,
                'error' => 'Authentication token not provided.',
                'code' => 'unauthenticated',
            ], 401);
        }

        $hashedToken = hash('sha256', $token);
        $apiToken = ApiToken::where('token', $hashedToken)->first();

        // Backward compatibility: support legacy plaintext tokens and upgrade in place.
        if (! $apiToken) {
            $apiToken = ApiToken::where('token', $token)->first();

            if ($apiToken) {
                $apiToken->forceFill(['token' => $hashedToken])->save();
            }
        }

        if (! $apiToken) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid authentication token.',
                'code' => 'unauthenticated',
            ], 401);
        }

        if ($apiToken->expires_at && $apiToken->expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'error' => 'Authentication token has expired.',
                'code' => 'token_expired',
            ], 401);
        }

        $apiToken->touch('last_used_at');
        Auth::login($apiToken->user);

        $request->attributes->add(['api_token_instance' => $apiToken]);

        return $next($request);
    }
}
