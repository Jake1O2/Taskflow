<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    /**
     * Crée une invitation pour rejoindre l'équipe (email requis).
     */
    public function store(Request $request, string $teamId): RedirectResponse
    {
        $team = Auth::user()->teams()->findOrFail($teamId);
        $validated = $request->validate(['email' => 'required|email|exists:users,email']);

        $invitedUser = User::where('email', $validated['email'])->first();
        if ($invitedUser && $team->members()->where('user_id', $invitedUser->id)->exists()) {
            return redirect()->route('teams.show', $teamId)->with('error', 'Cet utilisateur est déjà membre.');
        }

        $invitation = $team->invitations()->updateOrCreate(
            ['email' => $validated['email']],
            ['token' => Str::random(64), 'status' => 'pending']
        );

        return redirect()->route('teams.show', $teamId)->with('success', 'Invitation envoyée');
    }

    /**
     * Accepte l'invitation et crée le TeamMember.
     */
    public function accept(string $token): RedirectResponse
    {
        $invitation = Invitation::where('token', $token)->where('status', 'pending')->firstOrFail();
        abort_if(Auth::user()->email !== $invitation->email, 403);

        TeamMember::firstOrCreate(
            ['team_id' => $invitation->team_id, 'user_id' => Auth::id()],
            ['team_id' => $invitation->team_id, 'user_id' => Auth::id()]
        );
        $invitation->update(['status' => 'accepted']);

        return redirect()->route('teams.show', $invitation->team_id)->with('success', 'Invitation acceptée');
    }

    /**
     * Refuse l'invitation.
     */
    public function decline(string $token): RedirectResponse
    {
        $invitation = Invitation::where('token', $token)->where('status', 'pending')->firstOrFail();
        abort_if(Auth::user()->email !== $invitation->email, 403);

        $invitation->update(['status' => 'declined']);

        return redirect()->route('teams.index')->with('success', 'Invitation refusée');
    }
}
