<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'storeRegister'])->name('register.store');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'storeLogin'])->name('login.store');
});

// Routes protégées
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routes pour les projets
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{id}/kanban', [ProjectController::class, 'kanban'])->name('projects.kanban');
    Route::get('/projects/{id}/calendar', [ProjectController::class, 'calendar'])->name('projects.calendar');
    Route::get('/projects/{id}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{id}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Routes pour les tâches
    Route::get('/projects/{projectId}/tasks/create', function ($projectId) {
        return 'Créer une tâche pour le projet ' . $projectId;
    })->name('tasks.create');

    Route::post('/projects/{projectId}/tasks', function ($projectId) {
        return 'Enregistrer une tâche pour le projet ' . $projectId;
    })->name('tasks.store');

    Route::get('/tasks/{id}/edit', function ($id) {
        return 'Éditer la tâche ' . $id;
    })->name('tasks.edit');

    Route::put('/tasks/{id}', function ($id) {
        return 'Mettre à jour la tâche ' . $id;
    })->name('tasks.update');

    Route::delete('/tasks/{id}', function ($id) {
        return 'Supprimer la tâche ' . $id;
    })->name('tasks.destroy');
});