<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
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
        Auth::user()->forgetStatsCache();

        return redirect()->route('teams.show', $team->id)->with('success', 'Équipe créée');
    }

    /**
     * Affiche l'équipe et ses membres.
     */
    public function show(string $id): View
    {
        $team = $this->getTeamForUser($id);
        $team->load(['owner', 'members.user']);
        $members = $team->members;

        return view('teams.show', compact('team', 'members'));
    }

    /**
     * Affiche le formulaire d'édition.
     */
    public function edit(Team $team): View
    {
        abort_if($team->user_id !== Auth::id(), 403);
        return view('teams.edit', compact('team'));
    }

    /**
     * Valide et modifie l'équipe.
     */
    public function update(Request $request, Team $team): RedirectResponse
    {
        abort_if($team->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $team->update($validated);
        Auth::user()->forgetStatsCache();

        return redirect()->route('teams.show', $team->id)->with('success', 'Équipe modifiée');
    }

    /**
     * Supprime l'équipe.
     */
    public function destroy(Team $team): RedirectResponse
    {
        abort_if($team->user_id !== Auth::id(), 403);
        $team->delete();
        Auth::user()->forgetStatsCache();

        return redirect()->route('teams.index')->with('success', 'Équipe supprimée');
    }

    /**
     * Ajoute un membre à l'équipe.
     */
    public function addMember(Request $request, string $teamId): RedirectResponse
    {
        $team = Auth::user()->teams()->findOrFail($teamId);

        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($team->members()->where('user_id', $user->id)->exists()) {
            return redirect()->route('teams.show', $teamId)->with('error', 'Cet utilisateur est déjà membre');
        }

        TeamMember::create([
            'team_id' => $teamId,
            'user_id' => $user->id,
            'role' => 'member',
        ]);

        NotificationHelper::createNotification(
            $user->id,
            'member_invited',
            "Invitation d'équipe",
            "Vous avez été ajouté à une équipe",
            route('teams.show', $teamId)
        );

        return redirect()->route('teams.show', $teamId)->with('success', 'Membre ajouté');
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

    /**
     * Helper pour récupérer l'équipe pour l'utilisateur.
     */
    private function getTeamForUser(string $id): Team
    {
        return $this->currentUser()->teams()->findOrFail($id);
    }

    /**
     * Helper pour récupérer l'utilisateur connecté avec le bon type.
     */
    private function currentUser(): \App\Models\User
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user;
    }
}