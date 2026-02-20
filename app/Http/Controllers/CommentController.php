<?php

namespace App\Http\Controllers;

use App\Helpers\MentionParser;
use App\Helpers\NotificationHelper;
use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use App\Services\SlackNotifier;
use App\Services\WebhookDispatcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CommentController extends Controller
{
    public function store(Request $request, string $taskId, WebhookDispatcher $webhookDispatcher, SlackNotifier $slackNotifier): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'required_without:message|string|max:1000',
            'message' => 'nullable|string|max:1000',
        ]);

        $content = trim((string) ($validated['content'] ?? $validated['message'] ?? ''));
        $task = Task::with('project')->findOrFail($taskId);
        $this->authorize('comment', $task);

        $comment = $task->comments()->create([
            'user_id' => Auth::id(),
            'content' => $content,
        ]);

        NotificationHelper::createNotification(
            $task->project->created_by,
            'comment_added',
            'New comment',
            "A new comment was posted on task: {$task->title}",
            route('tasks.show', $task->id)
        );

        $this->handleMentions($comment, $task, $content);
        $slackNotifier->notifyCommentAdded($comment);
        $webhookDispatcher->dispatch('comment.added', $comment->toArray(), Auth::id(), false);

        return redirect(route('tasks.show', $task->id))->with('success', 'Comment added');
    }

    public function destroy(string $id): RedirectResponse
    {
        $comment = Comment::findOrFail($id);
        abort_if($comment->user_id !== Auth::id(), 403);
        $comment->delete();

        return redirect(route('tasks.show', $comment->task_id))->with('success', 'Deleted');
    }

    private function handleMentions(Comment $comment, Task $task, string $content): void
    {
        $parsed = MentionParser::parseMentions($content);
        $mentionedIds = collect($parsed['user_ids'])
            ->filter(fn ($id) => (int) $id !== (int) Auth::id())
            ->unique()
            ->values()
            ->all();

        $comment->update(['mentioned_users' => $mentionedIds]);

        if ($mentionedIds === []) {
            return;
        }

        $mentionedUsers = User::query()
            ->whereIn('id', $mentionedIds)
            ->get(['id', 'name', 'email']);

        foreach ($mentionedUsers as $mentionedUser) {
            NotificationHelper::createNotification(
                $mentionedUser->id,
                'comment_mention',
                'You were mentioned',
                "You were mentioned in a comment on task: {$task->title}",
                route('tasks.show', $task->id)
            );

            $this->sendMentionEmail($mentionedUser, $task, $comment);
            $this->sendSlackMentionNotification($mentionedUser, $task, $comment);
        }
    }

    private function sendMentionEmail(User $mentionedUser, Task $task, Comment $comment): void
    {
        try {
            Mail::raw(
                "You were mentioned in task '{$task->title}': {$comment->content}",
                function ($message) use ($mentionedUser): void {
                    $message->to($mentionedUser->email)->subject('Task mention notification');
                }
            );
        } catch (\Throwable $exception) {
            Log::warning('Failed to send mention email.', [
                'user_id' => $mentionedUser->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function sendSlackMentionNotification(User $mentionedUser, Task $task, Comment $comment): void
    {
        $token = config('services.slack.notifications.bot_user_oauth_token');
        $channel = config('services.slack.notifications.channel');

        if (! $token || ! $channel) {
            return;
        }

        try {
            Http::withToken($token)
                ->acceptJson()
                ->post('https://slack.com/api/chat.postMessage', [
                    'channel' => $channel,
                    'text' => "User {$mentionedUser->name} was mentioned in task '{$task->title}': {$comment->content}",
                ]);
        } catch (\Throwable $exception) {
            Log::warning('Failed to send slack mention notification.', [
                'user_id' => $mentionedUser->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
