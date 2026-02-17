<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckProjectLimit
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $subscription = $user->subscription;

        if (!$subscription || !$subscription->canCreateProjects()) {
            return redirect()->route('pricing.index')->with('error', 'Vous avez atteint la limite de projets');
        }

        return $next($request);
    }
}