<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTeamLimit
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $subscription = $user->subscription;

        if (!$subscription || !$subscription->canCreateTeams()) {
            return redirect()->route('pricing.index')->with('error', 'Vous avez atteint la limite d\'équipes');
        }

        return $next($request);
    }
}