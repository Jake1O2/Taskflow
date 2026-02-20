<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPreferencesApiController extends Controller
{
    public function getTheme(Request $request)
    {
        $preference = UserPreference::firstOrCreate(
            ['user_id' => Auth::id()],
            ['theme' => 'auto']
        );

        return response()->json(['theme' => $preference->theme]);
    }

    public function updateTheme(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark,auto',
        ]);

        $preference = UserPreference::updateOrCreate(
            ['user_id' => Auth::id()],
            ['theme' => $validated['theme']]
        );

        return response()->json(['success' => true, 'theme' => $preference->theme]);
    }

    public function getPreferences(Request $request)
    {
        $preference = UserPreference::firstOrCreate(['user_id' => Auth::id()]);
        return response()->json($preference);
    }

    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'sometimes|in:light,dark,auto',
            'primary_color' => 'nullable|hex_color',
            'accent_color' => 'nullable|hex_color',
            'language' => 'sometimes|string|size:2',
            'timezone' => 'sometimes|timezone',
        ]);

        $preference = UserPreference::updateOrCreate(
            ['user_id' => Auth::id()],
            $validated
        );

        return response()->json($preference);
    }
}