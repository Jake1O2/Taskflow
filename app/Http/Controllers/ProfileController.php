<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Affiche le profil de l'utilisateur connecté.
     */
    public function show(): View
    {
        return view('profile');
    }
}
