<?php

namespace App\Http\Controllers;

use App\Mail\TeamInvitationMail;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\TeamMember;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TeamInvitationController extends Controller
{
    public function sendInvitation(Request $request, int $teamId)
    {
        $request->validate(['email' => 'required|email']);
        $team = Auth::user()->teams()->findOrFail($teamId);

        $alreadyMember = $team->members()->whereHas('user', fn ($q) => $q->where('email', $request->email))->exists();
        if ($alreadyMember || $team->teamInvitations()->where('email', $request->email)->whereNull('accepted_at')->exists()) {
            return back()->with('error', 'Cet utilisateur est déjà membre ou a déjà été invité.');
        }

        $invitation = $team->teamInvitations()->create([
            'email' => $request->email,
            'token' => Str::random(64),
        ]);

        Mail::to($request->email)->send(new TeamInvitationMail($invitation));

        return back()->with('success', 'Invitation envoyée avec succès.');
    }

    public function acceptInvitation(string $token)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Connectez-vous pour accepter l\'invitation.');
        }
        $invitation = TeamInvitation::where('token', $token)->firstOrFail();

        if ($invitation->accepted_at) {
            return redirect()->route('dashboard')->with('error', 'Cette invitation a déjà été acceptée.');
        }

        if (Auth::user()->email !== $invitation->email) {
            return redirect()->route('dashboard')->with('error', 'Cette invitation est destinée à un autre utilisateur.');
        }

        TeamMember::firstOrCreate(
            ['team_id' => $invitation->team_id, 'user_id' => Auth::id()],
            ['role' => RolePermission::COMMENTER]
        );

        $invitation->update(['accepted_at' => now()]);

        return redirect()->route('teams.show', $invitation->team_id)->with('success', 'Vous avez rejoint l\'équipe ' . $invitation->team->name . ' !');
    }

    public function declineInvitation(string $token)
    {
        $invitation = TeamInvitation::where('token', $token)->first();

        if (! $invitation) {
            return redirect()->route(Auth::check() ? 'dashboard' : 'welcome')->with('error', 'Invitation invalide.');
        }

        if (Auth::check() && Auth::user()->email !== $invitation->email) {
            return redirect()->route('dashboard')->with('error', 'Cette invitation ne vous est pas destinée.');
        }

        $invitation->delete();

        return redirect()->route(Auth::check() ? 'dashboard' : 'welcome')->with('success', 'Vous avez refusé l\'invitation.');
    }

    public function cancelInvitation(TeamInvitation $invitation): \Illuminate\Http\RedirectResponse
    {
        if ($invitation->team->user_id !== Auth::id()) {
            abort(403);
        }
        $invitation->delete();

        return redirect()->back()->with('success', 'Invitation annulée.');
    }
}
