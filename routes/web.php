<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\SlackController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\TeamInvitationController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/pricing', [PaymentController::class , 'showPlans'])->name('pricing.index');
Route::post('/webhook/stripe', [PaymentController::class , 'webhook'])->name('webhook.stripe');
Route::post('/slack/commands', [SlackController::class, 'commands'])->name('slack.commands');

// Invitations (accept/decline - public links from email; logic in controller)
Route::get('/invitations/{token}/accept', [TeamInvitationController::class, 'acceptInvitation'])->name('invitations.accept');
Route::get('/invitations/{token}/decline', [TeamInvitationController::class, 'declineInvitation'])->name('invitations.decline');

Route::get('/api/docs', function () {
    return view('api.docs');

})->name('api.docs');

Route::middleware(['guest', 'throttle.login'])->group(function () {
    Route::get('/register', [AuthController::class , 'register'])->name('register');
    Route::post('/register', [AuthController::class , 'storeRegister'])->name('register.store');
    Route::get('/login', [AuthController::class , 'login'])->name('login');
    Route::post('/login', [AuthController::class , 'storeLogin'])->name('login.store');
});

// Routes protégées
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class , 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class , 'show'])->name('profile');
    Route::get('/dashboard', [DashboardController::class , 'index'])->name('dashboard');

    // Payment & Billing
    Route::post('/checkout/{planId}', [PaymentController::class , 'checkout'])->name('payment.checkout');
    Route::get('/payment/success', [PaymentController::class , 'success'])->name('payment.success');
    Route::get('/billing', [BillingController::class , 'index'])->name('billing.index');
    Route::post('/billing/manage', [BillingController::class , 'manageBilling'])->name('billing.manage');
    Route::post('/billing/cancel', [BillingController::class , 'cancelSubscription'])->name('billing.cancel');

    // Routes Notifications
    Route::get('/notifications', [NotificationController::class , 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class , 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class , 'markAllAsRead'])->name('notifications.readAll');

    // Route de recherche globale
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/api/users/search', [SearchController::class, 'searchUsers'])->name('api.users.search');

        // Routes pour les projets
        Route::get('/projects', [ProjectController::class , 'index'])->name('projects.index');
        Route::get('/projects/create', [ProjectController::class , 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class , 'store'])->middleware('check.project.limit')->name('projects.store');
        Route::get('/projects/{id}/kanban', [ProjectController::class , 'kanban'])->name('projects.kanban');
        Route::get('/projects/{id}/calendar', [ProjectController::class , 'calendar'])->name('projects.calendar');
        Route::get('/projects/{id}/edit', [ProjectController::class , 'edit'])->name('projects.edit');
        Route::get('/projects/{id}', [ProjectController::class , 'show'])->name('projects.show');
        Route::put('/projects/{id}', [ProjectController::class , 'update'])->name('projects.update');
        Route::delete('/projects/{id}', [ProjectController::class , 'destroy'])->name('projects.destroy');
        Route::get('/search/projects', [ProjectController::class , 'search'])->name('projects.search');

        // Routes Export
        Route::get('/projects/{id}/export/pdf', [ExportController::class , 'exportProjectPDF'])->name('projects.export.pdf');
        Route::get('/projects/{id}/export/csv', [ExportController::class , 'exportTasksCSV'])->name('projects.export.csv');
        Route::get('/projects/{project}/activity', [ProjectController::class, 'activity'])
            ->middleware('can:view,project')
            ->name('projects.activity');

        // Routes pour les tâches
        Route::get('/projects/{projectId}/tasks/create', [TaskController::class , 'create'])->name('tasks.create');
        Route::post('/projects/{projectId}/tasks', [TaskController::class , 'store'])->name('tasks.store');
        Route::get('/tasks/{id}/edit', [TaskController::class , 'edit'])->name('tasks.edit');
        Route::put('/tasks/{id}', [TaskController::class , 'update'])->name('tasks.update');
        Route::get('/tasks/{id}', [TaskController::class , 'show'])->name('tasks.show');
        Route::delete('/tasks/{id}', [TaskController::class , 'destroy'])->name('tasks.destroy');
        Route::patch('/tasks/{id}/assign/{userId}', [TaskController::class, 'assign'])
            ->middleware('check.task.permission:assign')
            ->name('tasks.assign');
        Route::patch('/tasks/{id}/unassign', [TaskController::class, 'unassign'])
            ->middleware('check.task.permission:assign')
            ->name('tasks.unassign');
        Route::get('/tasks/{task}/activity', [TaskController::class, 'activity'])
            ->middleware('can:view,task')
            ->name('tasks.activity');
        Route::get('/search/tasks', [TaskController::class , 'search'])->name('tasks.search');

        // Routes pour les commentaires
        Route::post('/tasks/{id}/comments', [CommentController::class , 'store'])->name('comments.store');
        Route::delete('/comments/{id}', [CommentController::class , 'destroy'])->name('comments.destroy');

        // Routes pour les équipes
        Route::post('/teams', [TeamController::class , 'store'])->middleware('check.team.limit')->name('teams.store');
        Route::get('/teams/{team}/invitations', [TeamController::class, 'invitations'])->name('teams.invitations');
        Route::delete('/teams/invitations/{invitation}', [TeamInvitationController::class, 'cancelInvitation'])->name('teams.invitations.cancel');
        Route::resource('teams', TeamController::class)->except(['store']);
        Route::post('/teams/{teamId}/members', [TeamController::class , 'addMember'])->name('teams.addMember');
        // Backward-compatible fallback when a stale form submits POST without _method=DELETE
        Route::post('/teams/{teamId}/members/{userId}', [TeamController::class , 'removeMember']);
        Route::delete('/teams/{teamId}/members/{userId}', [TeamController::class , 'removeMember'])->name('teams.removeMember');
        Route::post('/teams/{teamId}/invite-email', [InvitationController::class , 'sendEmailInvitation'])->name('teams.inviteEmail');
        Route::post('/teams/{teamId}/send-invitation', [TeamInvitationController::class, 'sendInvitation'])->name('teams.sendInvitation');

        // API Tokens
        Route::get('/api/tokens', [ApiTokenController::class , 'index'])->name('api.tokens.index');
        Route::post('/api/tokens', [ApiTokenController::class , 'store'])->name('api.tokens.store');
        Route::delete('/api/tokens/{id}', [ApiTokenController::class , 'destroy'])->name('api.tokens.destroy');

        // Webhooks
        Route::get('/webhooks', [WebhookController::class, 'index'])->name('webhooks.index');
        Route::get('/webhooks/create', [WebhookController::class, 'create'])->name('webhooks.create');
        Route::post('/webhooks', [WebhookController::class, 'store'])->name('webhooks.store');
        Route::get('/webhooks/{webhook}/edit', [WebhookController::class, 'edit'])->name('webhooks.edit');
        Route::put('/webhooks/{webhook}', [WebhookController::class, 'update'])->name('webhooks.update');
        Route::delete('/webhooks/{id}', [WebhookController::class, 'destroy'])->name('webhooks.destroy');
        Route::post('/webhooks/{id}/toggle', [WebhookController::class, 'toggle'])->name('webhooks.toggle');
        Route::post('/webhooks/{webhook}/test', [WebhookController::class, 'testWebhook'])->name('webhooks.test');
        Route::get('/webhooks/{webhook}/logs', [WebhookController::class, 'showLogs'])->name('webhooks.logs');

        // Slack Integrations
        Route::get('/slack/integrations', [SlackController::class, 'index'])->name('slack.index');
        Route::get('/slack/connect/{teamId}', [SlackController::class, 'connect'])->name('slack.connect');
        Route::get('/slack/callback', [SlackController::class, 'callback'])->name('slack.callback');
        Route::delete('/slack/{id}', [SlackController::class, 'disconnect'])->name('slack.disconnect');
        Route::post('/slack/{id}/channel', [SlackController::class, 'selectChannel'])->name('slack.selectChannel');
        Route::put('/slack/{id}/events', [SlackController::class, 'updateEvents'])->name('slack.updateEvents');

        // API JSON pour animations
        Route::prefix('api')->group(function () {
            Route::get('/stats', [ProjectController::class , 'getStats'])->name('api.stats');
            Route::get('/dashboard/analytics', [DashboardController::class , 'getAnalytics'])->name('api.dashboard.analytics');
        }
        );
    });
