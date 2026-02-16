<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Affiche les notifications de l'utilisateur.
     */
    public function index(): View
    {
        $notifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->get();
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(string $id): RedirectResponse
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->update(['read_at' => now()]);

        return back()->with('success', 'Notifications marquées');
    }

    public function markAllAsRead(): RedirectResponse
    {
        Auth::user()->notifications()->unread()->update(['read_at' => now()]);

        return back()->with('success', 'Notifications marquées');
    }
}