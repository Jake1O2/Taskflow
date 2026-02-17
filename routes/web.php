<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Routes publiques
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/pricing', [PaymentController::class , 'showPlans'])->name('pricing.index');
Route::post('/webhook/stripe', [PaymentController::class , 'webhook'])->name('webhook.stripe');

Route::middleware('guest')->group(function () {
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
    Route::get('/search', function (Request $request) {
            $q = $request->input('q');
            $projects = collect();
            $tasks = collect();

            if ($q) {
                $userProjects = Auth::user()->projects()->where('title', 'like', "%$q%")->get();
                $projects = $userProjects;

                $allProjects = Auth::user()->projects()->with('tasks')->get();
                foreach ($allProjects as $project) {
                    foreach ($project->tasks as $task) {
                        if (stripos($task->title, $q) !== false) {
                            $tasks->push($task);
                        }
                    }
                }
            }

            return view('search', compact('projects', 'tasks', 'q'));
        }
        )->name('search');

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

        // Routes pour les tâches
        Route::get('/projects/{projectId}/tasks/create', [TaskController::class , 'create'])->name('tasks.create');
        Route::post('/projects/{projectId}/tasks', [TaskController::class , 'store'])->name('tasks.store');
        Route::get('/tasks/{id}/edit', [TaskController::class , 'edit'])->name('tasks.edit');
        Route::put('/tasks/{id}', [TaskController::class , 'update'])->name('tasks.update');
        Route::get('/tasks/{id}', [TaskController::class , 'show'])->name('tasks.show');
        Route::delete('/tasks/{id}', [TaskController::class , 'destroy'])->name('tasks.destroy');
        Route::get('/search/tasks', [TaskController::class , 'search'])->name('tasks.search');

        // Routes pour les commentaires
        Route::post('/tasks/{id}/comments', [CommentController::class , 'store'])->name('comments.store');
        Route::delete('/comments/{id}', [CommentController::class , 'destroy'])->name('comments.destroy');

        // Routes pour les équipes
        Route::post('/teams', [TeamController::class , 'store'])->middleware('check.team.limit')->name('teams.store');
        Route::resource('teams', TeamController::class)->except(['store']);
        Route::post('/teams/{teamId}/members', [TeamController::class , 'addMember'])->name('teams.addMember');
        Route::delete('/teams/{teamId}/members/{userId}', [TeamController::class , 'removeMember'])->name('teams.removeMember');

        // API JSON pour animations
        Route::prefix('api')->group(function () {
            Route::get('/stats', [ProjectController::class , 'getStats'])->name('api.stats');
            Route::get('/dashboard/analytics', [DashboardController::class , 'getAnalytics'])->name('api.dashboard.analytics');
            Route::patch('/tasks/{id}/status', [TaskController::class , 'updateStatus'])->name('api.tasks.updateStatus');
        }
        );    });