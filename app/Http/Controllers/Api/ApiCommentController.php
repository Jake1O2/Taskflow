<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiCommentController extends Controller
{
    public function store(Request $request, string $taskId): JsonResponse
    {
        $validated = $request->validate(['content' => 'required|string|max:1000']);
        $task = Task::whereHas('project', fn ($q) => $q->where('created_by', Auth::id()))->find($taskId);
        if (! $task) {
            return response()->json([
                'success' => false,
                'error' => 'Tâche non trouvée.',
                'code' => 'not_found',
            ], 404);
        }
        $comment = $task->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);
        return response()->json([
            'success' => true,
            'data' => $comment->load('user'),
            'message' => 'Commentaire ajouté',
        ], 201);
    }

    public function destroy(string $id): JsonResponse
    {
        $comment = Comment::find($id);
        if (! $comment) {
            return response()->json([
                'success' => false,
                'error' => 'Commentaire non trouvé.',
                'code' => 'not_found',
            ], 404);
        }
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'error' => 'Non autorisé.',
                'code' => 'forbidden',
            ], 403);
        }
        $comment->delete();
        return response()->json(['success' => true, 'message' => 'Commentaire supprimé']);
    }
}
