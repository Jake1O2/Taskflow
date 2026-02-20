<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ThrottleLoginAttempts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'login_attempts:' . $request->ip();
        $attempts = Cache::get($key, 0);
        $maxAttempts = 5;
        $lockoutDuration = 900; // 15 minutes

        if ($attempts >= $maxAttempts) {
            $lockoutKey = 'login_lockout:' . $request->ip();
            $lockoutUntil = Cache::get($lockoutKey);

            if ($lockoutUntil && $lockoutUntil > now()->timestamp) {
                $remaining = $lockoutUntil - now()->timestamp;
                return back()->withErrors([
                    'email' => "Trop de tentatives de connexion. Veuillez réessayer dans " . ceil($remaining / 60) . " minutes.",
                ])->withInput($request->only('email'));
            }

            // Réinitialiser si le lockout est expiré
            Cache::forget($key);
            Cache::forget($lockoutKey);
        }

        $response = $next($request);

        // Si la connexion a échoué, incrémenter le compteur
        if ($request->is('login') && $request->method() === 'POST') {
            if (!Auth::check()) {
                $attempts++;
                Cache::put($key, $attempts, now()->addMinutes(15));

                if ($attempts >= $maxAttempts) {
                    Cache::put('login_lockout:' . $request->ip(), now()->addSeconds($lockoutDuration)->timestamp, now()->addMinutes(15));
                }
            } else {
                // Réinitialiser en cas de succès
                Cache::forget($key);
                Cache::forget('login_lockout:' . $request->ip());
            }
        }

        return $response;
    }
}
