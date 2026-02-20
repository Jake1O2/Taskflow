<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Models\RolePermission;
use App\Services\SlackNotifier;
use App\Services\WebhookDispatcher;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
        $teams = Auth::user()->teams()->orderBy('created_at', 'desc')->get();
        return view('teams.index', compact('teams'));
    }

    public function create(): View
    {
        return view('teams.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
        ]);

        $team = Auth::user()->teams()->create($validated);
        Auth::user()->forgetStatsCache();

        return redirect()->route('teams.show', $team->id)->with('success', 'Équipe créée');
    }

    public function show(string $id): View
    {
        // Valider que l'ID est un entier valide
        abort_unless(is_numeric($id) && $id > 0, 400, 'ID équipe invalide');
        
        $team = $this->getTeamForUser($id);
        $team->load(['owner', 'members.user']);
        $members = $team->members;

        return view('teams.show', compact('team', 'members'));
    }

    public function invitations(Team $team): View
    {
        // Vérifier que l'utilisateur est propriétaire de l'équipe
        abort_if($team->user_id !== Auth::id(), 403, 'Seul le propriétaire peut voir les invitations');
        
        $pendingInvitations = $team->teamInvitations()->whereNull('accepted_at')->orderBy('created_at', 'desc')->get();

        return view('teams.invitations', compact('team', 'pendingInvitations'));
    }

    public function edit(Team $team): View
    {
        // Vérifier que l'utilisateur est propriétaire de l'équipe
        abort_if($team->user_id !== Auth::id(), 403, 'Seul le propriétaire peut modifier l\'équipe');
        
        return view('teams.edit', compact('team'));
    }

    public function update(Request $request, Team $team): RedirectResponse
    {
        // Vérifier que l'utilisateur est propriétaire de l'équipe
        abort_if($team->user_id !== Auth::id(), 403, 'Seul le propriétaire peut modifier l\'équipe');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
        ]);

        $team->update($validated);
        Auth::user()->forgetStatsCache();

        return redirect()->route('teams.show', $team->id)->with('success', 'Équipe modifiée');
    }

    public function destroy(Team $team): RedirectResponse
    {
        // Vérifier que l'utilisateur est propriétaire de l'équipe
        abort_if($team->user_id !== Auth::id(), 403, 'Seul le propriétaire peut supprimer l\'équipe');
        
        $team->delete();
        Auth::user()->forgetStatsCache();

        return redirect()->route('teams.index')->with('success', 'Équipe supprimée');
    }

    public function addMember(Request $request, string $teamId, WebhookDispatcher $webhookDispatcher, SlackNotifier $slackNotifier): RedirectResponse
    {
        // Vérifier que l'utilisateur est propriétaire de l'équipe
        $team = Auth::user()->teams()->findOrFail($teamId);
        abort_if($team->user_id !== Auth::id(), 403, 'Seul le propriétaire peut ajouter des membres');

        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'nullable|in:' . implode(',', RolePermission::roles()),
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();

        // Empêcher d'ajouter le propriétaire comme membre
        if ($team->user_id === $user->id) {
            return redirect()->route('teams.show', $teamId)->with('error', 'Le propriétaire de l\'équipe est déjà membre');
        }

        if ($team->members()->where('user_id', $user->id)->exists()) {
            return redirect()->route('teams.show', $teamId)->with('error', 'Cet utilisateur est déjà membre');
        }

        TeamMember::create([
            'team_id' => $team->id,
            'user_id' => $user->id,
            'role' => $validated['role'] ?? RolePermission::COMMENTER,
        ]);

        NotificationHelper::createNotification(
            $user->id,
            'member_invited',
            "Invitation d'équipe",
            "Vous avez été ajouté à une équipe",
            route('teams.show', $team->id)
        );

        $slackNotifier->notifyMemberAdded($team, $user);

        $webhookDispatcher->dispatch('team.member.added', [
            'team_id' => $team->id,
            'team_name' => $team->name,
            'user_id' => $user->id,
            'email' => $user->email,
        ], Auth::id(), false);

        return redirect()->route('teams.show', $team->id)->with('success', 'Membre ajouté');
    }

    public function removeMember(string $teamId, string $userId): RedirectResponse
    {
        // Vérifier que l'utilisateur est propriétaire de l'équipe
        $team = Auth::user()->teams()->findOrFail($teamId);
        abort_if($team->user_id !== Auth::id(), 403, 'Seul le propriétaire peut retirer des membres');

        // Valider que userId est un entier valide
        abort_unless(is_numeric($userId) && $userId > 0, 400, 'ID utilisateur invalide');

        // Empêcher la suppression du propriétaire
        abort_if($team->user_id == $userId, 403, 'Le propriétaire de l\'équipe ne peut pas être retiré');

        $member = TeamMember::where('team_id', $team->id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $member->delete();

        return redirect()->route('teams.show', $team->id)->with('success', 'Membre retiré');
    }

    private function getTeamForUser(string $id): Team
    {
        // Valider que l'ID est un entier valide
        abort_unless(is_numeric($id) && $id > 0, 400, 'ID équipe invalide');
        
        // Vérifier que l'utilisateur est membre de l'équipe (propriétaire ou membre)
        $team = Team::findOrFail($id);
        $user = $this->currentUser();
        
        // Vérifier si l'utilisateur est propriétaire ou membre
        $isOwner = $team->user_id === $user->id;
        $isMember = $team->members()->where('user_id', $user->id)->exists();
        
        abort_unless($isOwner || $isMember, 403, 'Vous n\'avez pas accès à cette équipe');
        
        return $team;
    }

    private function currentUser(): \App\Models\User
    {
        $user = Auth::user();
        abort_unless($user, 401, 'Utilisateur non authentifié');
        
        return $user;
    }
}
