<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Auth::routes();  // Includes routes for login, registration, etc.
});

Route::middleware(['auth'])->group(function () {
    Route::prefix('projects/')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
        Route::post('/', [ProjectController::class, 'store'])->name('projects.store');
        Route::prefix('{project}')->group(function () {
            Route::get('/', [ProjectController::class, 'edit']);
            Route::put('/', [ProjectController::class, 'update'])->name('projects.update');
            Route::delete('/', [ProjectController::class, 'destroy'])->name('projects.delete');
            Route::prefix('tasks')->group(function () {
                Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
                Route::get('/', [ProjectController::class, 'showTasks'])->name('projects.tasks');
                Route::prefix('{task}')->group(function () {
                    Route::get('/', [TaskController::class, 'edit'])->name('tasks.edit');
                    Route::put('/', [TaskController::class, 'update'])->name('tasks.update');
                    Route::patch('/', [TaskController::class, 'updateStatus'])->name('tasks.update.status');
                    Route::delete('/', [TaskController::class, 'destroy'])->name('tasks.delete');
                });
            });
        });
    });
});
Route::get('/', function () {
    return view('welcome');
});
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');



