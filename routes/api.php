<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('projects', ProjectController::class);

    Route::apiResource('projects.boards', BoardController::class);

    Route::apiResource('boards.columns', ColumnController::class);

    Route::apiResource('columns.tasks', TaskController::class);

    Route::apiResource('tasks.subtasks', SubtaskController::class);
});
