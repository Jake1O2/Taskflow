<?php

namespace App\Http\Middleware;

use App\Helpers\ThemeHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ThemeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $userId = Auth::id();
            
            View::share('userTheme', ThemeHelper::getUserTheme($userId));
            View::share('themeColors', ThemeHelper::getThemeColors($userId));
        }

        return $next($request);
    }
}