<?php

namespace App\Http\Middleware;

use App\Models\Task;
use Closure;
use Illuminate\Http\Request;

class CheckTaskPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission = 'view')
    {
        $taskParam = $request->route('task') ?? $request->route('id');
        $task = $taskParam instanceof Task
            ? $taskParam
            : Task::with('project')->findOrFail((string) $taskParam);

        abort_unless($request->user() && $request->user()->can($permission, $task), 403);
        $request->attributes->set('authorized_task', $task);

        return $next($request);
    }
}
