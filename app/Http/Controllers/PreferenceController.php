<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    public function index()
    {
        return view('preferences', [
            'preference' => Auth::user()->preference
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $preference = $user->preference;

        $preference->update($request->only([
            'theme',
            'primary_color',
            'accent_color',
            'language',
            'timezone',
            'notifications_email',
            'notifications_weekly_summary',
            'notifications_marketing',
        ]));

        if ($request->has('language')) {
            app()->setLocale($request->language);
            session()->put('locale', $request->language);
        }

        return back()->with('success', 'Préférences mises à jour.');
    }

    public function updateTheme(Request $request)
    {
        $request->validate(['theme' => 'required|string|in:light,dark,auto']);

        Auth::user()->preference->update(['theme' => $request->theme]);

        return response()->json(['success' => true, 'theme' => $request->theme]);
    }
}
