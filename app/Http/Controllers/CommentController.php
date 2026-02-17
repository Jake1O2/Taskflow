<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Services\WebhookDispatcher;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Enregistre un nouveau commentaire sur une tâche.
     */
    public function store(Request $request, string $taskId, WebhookDispatcher $webhookDispatcher): RedirectResponse
    {
        $request->validate(['content' => 'required|string|max:1000']);
        $comment = Comment::create([
            'task_id' => $taskId,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        $task = Task::with('project')->findOrFail($taskId);
        // Notifier le créateur du projet (propriétaire)
        NotificationHelper::createNotification(
            $task->project->created_by,
            'comment_added',
            "Nouveau commentaire",
            "Quelqu'un a commenté votre tâche",
            route('tasks.show', $taskId)
        );

        $webhookDispatcher->dispatch('comment.added', $comment->toArray(), Auth::id());

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
