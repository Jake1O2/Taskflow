<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TeamController extends Controller
{
    /**
     * Affiche la liste des équipes de l'utilisateur.
     */
    public function index(): View
    {
        $teams = Auth::user()->teams()->orderBy('created_at', 'desc')->get();
        return view('teams.index', compact('teams'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create(): View
    {
        return view('teams.create');
    }

    /**
     * Valide et crée une équipe.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $team = Auth::user()->teams()->create($validated);

        return redirect()->route('teams.show', $team->id)->with('success', 'Équipe créée');
    }

    /**
     * Affiche les détails de l'équipe + ses membres.
     */
    public function show(string $id): View
    {
        $team = Auth::user()->teams()->with('members')->findOrFail($id);
        $members = $team->members()->with('user')->get();
        return view('teams.show', compact('team', 'members'));
    }

    /**
     * Affiche le formulaire d'édition.
     */
    public function edit(string $id): View
    {
        $team = Auth::user()->teams()->findOrFail($id);
        return view('teams.edit', compact('team'));
    }

    /**
     * Valide et modifie l'équipe.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $team = Auth::user()->teams()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $team->update($validated);

        return redirect()->route('teams.show', $id)->with('success', 'Équipe modifiée');
    }

    /**
     * Supprime l'équipe.
     */
    public function destroy(string $id): RedirectResponse
    {
        $team = Auth::user()->teams()->findOrFail($id);
        $team->delete();

        return redirect()->route('teams.index')->with('success', 'Équipe supprimée');
    }

    /**
     * Ajoute un membre à l'équipe.
     */
    public function addMember(Request $request, string $id): RedirectResponse
    {
        $team = Auth::user()->teams()->findOrFail($id);
        
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $validated['email'])->first();
        
        if ($team->members()->where('user_id', $user->id)->exists()) {
            return redirect()->route('teams.show', $id)->with('error', 'Cet utilisateur est déjà membre');
        }

        TeamMember::create([
            'team_id' => $id,
            'user_id' => $user->id,
            'role' => 'member',
        ]);

        return redirect()->route('teams.show', $id)->with('success', 'Membre ajouté');
    }

    /**
     * Retire un membre de l'équipe.
     */
    public function removeMember(string $teamId, string $userId): RedirectResponse
    {
        $team = Auth::user()->teams()->findOrFail($teamId);
        
        TeamMember::where('team_id', $teamId)
                   ->where('user_id', $userId)
                   ->delete();

        return redirect()->route('teams.show', $teamId)->with('success', 'Membre retiré');
    }
}