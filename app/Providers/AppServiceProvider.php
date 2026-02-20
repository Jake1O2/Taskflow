<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\TeamMember;
use App\Models\Webhook;
use App\Observers\CommentObserver;
use App\Observers\ProjectObserver;
use App\Observers\TaskObserver;
use App\Observers\TeamMemberObserver;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use App\Policies\WebhookPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Task::class, TaskPolicy::class);
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(Webhook::class, WebhookPolicy::class);

        Task::observe(TaskObserver::class);
        Project::observe(ProjectObserver::class);
        Comment::observe(CommentObserver::class);
        TeamMember::observe(TeamMemberObserver::class);
    }
}
