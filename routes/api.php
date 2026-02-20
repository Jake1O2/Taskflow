<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiCommentController;
use App\Http\Controllers\Api\ApiProjectController;
use App\Http\Controllers\Api\ApiTaskController;
use App\Http\Controllers\Api\ApiTeamController;
use Illuminate\Support\Facades\Route;

// Public auth endpoint
Route::post('/auth/login', [ApiAuthController::class, 'login']);

Route::middleware(['api.auth', 'throttle:1000,60'])->group(function () {

    Route::post('/auth/logout', [ApiAuthController::class, 'logout']);

    // Projects
    Route::get('/projects', [ApiProjectController::class, 'index']);
    Route::get('/projects/{id}', [ApiProjectController::class, 'show']);
    Route::post('/projects', [ApiProjectController::class, 'store']);
    Route::put('/projects/{id}', [ApiProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ApiProjectController::class, 'destroy']);

    // Tasks
    Route::get('/projects/{projectId}/tasks', [ApiTaskController::class, 'index']);
    Route::get('/tasks/{id}', [ApiTaskController::class, 'show']);
    Route::post('/tasks', [ApiTaskController::class, 'store']);
    Route::put('/tasks/{id}', [ApiTaskController::class, 'update']);
    Route::delete('/tasks/{id}', [ApiTaskController::class, 'destroy']);
    Route::patch('/tasks/{id}/status', [ApiTaskController::class, 'updateStatus']);

    // Teams
    Route::get('/teams', [ApiTeamController::class, 'index']);
    Route::get('/teams/{id}', [ApiTeamController::class, 'show']);
    Route::post('/teams', [ApiTeamController::class, 'store']);
    Route::put('/teams/{id}', [ApiTeamController::class, 'update']);
    Route::delete('/teams/{id}', [ApiTeamController::class, 'destroy']);

    // Comments
    Route::post('/tasks/{taskId}/comments', [ApiCommentController::class, 'store']);
    Route::delete('/comments/{id}', [ApiCommentController::class, 'destroy']);
});
