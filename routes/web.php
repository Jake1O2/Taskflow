<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Routes publiques
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

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
    })->name('search');

    // Routes pour les projets
    Route::get('/projects', [ProjectController::class , 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class , 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class , 'store'])->name('projects.store');
    Route::get('/projects/{id}/kanban', [ProjectController::class , 'kanban'])->name('projects.kanban');
    Route::get('/projects/{id}/calendar', [ProjectController::class , 'calendar'])->name('projects.calendar');
    Route::get('/projects/{id}/edit', [ProjectController::class , 'edit'])->name('projects.edit');
    Route::get('/projects/{id}', [ProjectController::class , 'show'])->name('projects.show');
    Route::put('/projects/{id}', [ProjectController::class , 'update'])->name('projects.update');
    Route::delete('/projects/{id}', [ProjectController::class , 'destroy'])->name('projects.destroy');
    Route::get('/search/projects', [ProjectController::class, 'search'])->name('projects.search');

    // Routes pour les tâches
    Route::get('/projects/{projectId}/tasks/create', [TaskController::class , 'create'])->name('tasks.create');
    Route::post('/projects/{projectId}/tasks', [TaskController::class , 'store'])->name('tasks.store');
    Route::get('/tasks/{id}/edit', [TaskController::class , 'edit'])->name('tasks.edit');
    Route::put('/tasks/{id}', [TaskController::class , 'update'])->name('tasks.update');
    Route::get('/tasks/{id}', [TaskController::class , 'show'])->name('tasks.show');
    Route::delete('/tasks/{id}', [TaskController::class , 'destroy'])->name('tasks.destroy');
    Route::get('/search/tasks', [TaskController::class, 'search'])->name('tasks.search');

    // Routes pour les commentaires
    Route::post('/tasks/{id}/comments', [CommentController::class , 'store'])->name('comments.store');
    Route::delete('/comments/{id}', [CommentController::class , 'destroy'])->name('comments.destroy');

    // Routes pour les équipes
    Route::resource('teams', TeamController::class);
    Route::post('/teams/{teamId}/members', [TeamController::class, 'addMember'])->name('teams.addMember');
    Route::delete('/teams/{teamId}/members/{userId}', [TeamController::class , 'removeMember'])->name('teams.removeMember');

    // API JSON pour animations (middleware auth)
    Route::prefix('api')->middleware('auth')->group(function () {
        Route::get('/stats', [ProjectController::class, 'getStats'])->name('api.stats');
        Route::patch('/tasks/{id}/status', [TaskController::class, 'updateStatus'])->name('api.tasks.updateStatus');
    });
});