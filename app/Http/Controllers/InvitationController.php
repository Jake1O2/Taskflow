<?php

namespace App\Http\Controllers;

use App\Mail\TeamInvitationMail;
use App\Models\TeamInvitation;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    /**
     * Envoie une invitation par email.
     */
    public function sendEmailInvitation(Request $request, string $teamId): RedirectResponse
    {
        $team = Auth::user()->teams()->findOrFail($teamId);
        $validated = $request->validate(['email' => 'required|email|exists:users,email']);

        $invitedUser = User::where('email', $validated['email'])->first();
        if ($invitedUser && $team->members()->where('user_id', $invitedUser->id)->exists()) {
            return redirect()->route('teams.show', $teamId)->with('error', 'Cet utilisateur est déjà membre.');
        }

        // Utilisation du nouveau modèle TeamInvitation
        $invitation = TeamInvitation::updateOrCreate(
            ['team_id' => $teamId, 'email' => $validated['email']],
            ['email' => $validated['email']],
            ['token' => Str::random(64), 'accepted_at' => null]
        );

        Mail::to($validated['email'])->send(new TeamInvitationMail($invitation));

        return redirect()->route('teams.show', $teamId)->with('success', 'Invitation envoyée');
    }

    /**
     * Accepte l'invitation et crée le TeamMember.
     */
    public function acceptInvitation(string $token): RedirectResponse
    {
        $invitation = TeamInvitation::where('token', $token)->whereNull('accepted_at')->firstOrFail();
        abort_if(Auth::user()->email !== $invitation->email, 403);

        TeamMember::firstOrCreate(
            ['team_id' => $invitation->team_id, 'user_id' => Auth::id()],
            ['team_id' => $invitation->team_id, 'user_id' => Auth::id()]
        );
        $invitation->update(['accepted_at' => now()]);

        return redirect()->route('teams.show', $invitation->team_id)->with('success', 'Invitation acceptée');
    }

    /**
     * Refuse l'invitation.
     */
    public function declineInvitation(string $token): RedirectResponse
    {
        $invitation = TeamInvitation::where('token', $token)->whereNull('accepted_at')->firstOrFail();
        abort_if(Auth::user()->email !== $invitation->email, 403);

        $invitation->delete(); // On supprime l'invitation si refusée

        return redirect()->route('teams.index')->with('success', 'Invitation refusée');
    }
}
