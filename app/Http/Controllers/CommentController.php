<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Enregistre un nouveau commentaire sur une tâche.
     */
    public function store(Request $request, string $taskId): RedirectResponse
    {
        $request->validate(['content' => 'required|string|max:1000']);
        Comment::create([
            'task_id' => $taskId,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);
        return redirect(route('tasks.show', $taskId))->with('success', 'Commentaire ajouté');
    }

    /**
     * Supprime un commentaire.
     */
    public function destroy(string $id): RedirectResponse
    {
        $comment = Comment::findOrFail($id);
        abort_if($comment->user_id !== Auth::id(), 403);
        $comment->delete();
        return redirect(route('tasks.show', $comment->task_id))->with('success', 'Supprimé');
    }
}
