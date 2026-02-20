<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentObserver
{
    public function created(Comment $comment): void
    {
        $this->log($comment, 'created', $this->snapshot($comment));
    }

    public function updated(Comment $comment): void
    {
        $changes = [];

        foreach ($comment->getChanges() as $field => $newValue) {
            if ($field === 'updated_at') {
                continue;
            }

            $changes[$field] = [
                'old' => $comment->getOriginal($field),
                'new' => $newValue,
            ];
        }

        if ($changes !== []) {
            $this->log($comment, 'updated', $changes);
        }
    }

    public function deleted(Comment $comment): void
    {
        $this->log($comment, 'deleted', $this->snapshot($comment));
    }

    private function log(Comment $comment, string $action, array $changes = []): void
    {
        ActivityLog::create([
            'user_id' => Auth::id() ?? $comment->user_id,
            'loggable_type' => Comment::class,
            'loggable_id' => $comment->id,
            'action' => $action,
            'changes' => $changes,
            'created_at' => now(),
        ]);
    }

    private function snapshot(Comment $comment): array
    {
        return collect($comment->getAttributes())
            ->except(['created_at', 'updated_at'])
            ->toArray();
    }
}
