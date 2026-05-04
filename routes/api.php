<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\EventCommitteeController;
use App\Http\Controllers\Api\ExpenseReportController;
use App\Http\Controllers\Api\PartnerController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // AUTH
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // EVENTS
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{id}', [EventController::class, 'show']);

    Route::middleware('role:super_admin,leader')->group(function () {
        Route::post('/events', [EventController::class, 'store']);
        Route::put('/events/{id}', [EventController::class, 'update']);
    });

    Route::middleware('role:super_admin')->group(function () {
        Route::delete('/events/{id}', [EventController::class, 'destroy']);
    });

    // TASKS
    Route::apiResource('tasks', TaskController::class);

    // KANBAN
    Route::get('/events/{id}/kanban', [TaskController::class, 'kanban']);

    // OTHER
    Route::apiResource('committees', EventCommitteeController::class);
    Route::apiResource('expenses', ExpenseReportController::class);
    Route::apiResource('partners', PartnerController::class);
});