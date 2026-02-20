<?php

namespace App\Helpers;

use App\Models\UserPreference;
use Illuminate\Support\Facades\Auth;

class ThemeHelper
{
    public static function getUserTheme(?int $userId = null): string
    {
        if (!$userId) {
            $userId = Auth::id();
        }

        if (!$userId) {
            return 'light';
        }

        $preference = UserPreference::where('user_id', $userId)->first();

        return $preference ? $preference->theme : 'auto';
    }

    public static function getThemeColors(?int $userId = null): array
    {
        if (!$userId) {
            $userId = Auth::id();
        }

        $defaults = [
            'primary_color' => '#3b82f6', // blue-500
            'accent_color' => '#f59e0b', // amber-500
        ];

        if (!$userId) {
            return $defaults;
        }

        $preference = UserPreference::where('user_id', $userId)->first();

        return [
            'primary_color' => $preference->primary_color ?? $defaults['primary_color'],
            'accent_color' => $preference->accent_color ?? $defaults['accent_color'],
        ];
    }
}