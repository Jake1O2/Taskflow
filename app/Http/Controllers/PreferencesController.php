<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PreferencesController extends Controller
{
    public function index(): View
    {
        $preference = UserPreference::firstOrCreate(
            ['user_id' => Auth::id()],
            ['theme' => 'auto', 'language' => 'en', 'timezone' => 'UTC']
        );
        
        return view('preferences.index', compact('preference'));
    }

    public function updateTheme(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark,auto',
        ]);

        $this->updatePreference($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'theme' => $validated['theme']]);
        }

        return back()->with('success', 'Thème mis à jour.');
    }

    public function updateColors(Request $request)
    {
        $validated = $request->validate([
            'primary_color' => 'nullable|hex_color',
            'accent_color' => 'nullable|hex_color',
        ]);

        $this->updatePreference($validated);

        return response()->json(['success' => true, 'colors' => $validated]);
    }

    public function updateLanguage(Request $request)
    {
        $validated = $request->validate([
            'language' => 'required|string|size:2',
        ]);

        $this->updatePreference($validated);

        return back()->with('success', 'Langue mise à jour.');
    }

    public function updateTimezone(Request $request)
    {
        $validated = $request->validate([
            'timezone' => 'required|timezone',
        ]);

        $this->updatePreference($validated);

        return back()->with('success', 'Fuseau horaire mis à jour.');
    }

    private function updatePreference(array $data)
    {
        UserPreference::updateOrCreate(
            ['user_id' => Auth::id()],
            $data
        );
    }
}