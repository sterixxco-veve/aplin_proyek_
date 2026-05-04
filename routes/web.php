<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\EventController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\Web\TaskController;
use App\Http\Controllers\Web\ExpenseController;
use App\Http\Controllers\Web\ExpenseCategoryController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

});
Route::get('/organizations/create', [OrganizationController::class, 'create']);
Route::get('/organizations', [OrganizationController::class, 'index']);
Route::post('/organizations', [OrganizationController::class, 'store']);
Route::get('/organizations/{id}', [OrganizationController::class, 'show']);
Route::post('/organizations/{id}/invite', [OrganizationController::class, 'invite'])->middleware('org.role:super_admin');

Route::middleware('auth')->group(function () {

    Route::get('/events/create', [EventController::class, 'create']);
    Route::post('/events', [EventController::class, 'store']);
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{id}/edit', [EventController::class, 'edit']);
    Route::put('/events/{id}', [EventController::class, 'update']);
    Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
    

    // 🔥 AUTO UPDATE PROGRESS
    Route::get('/events/{id}/progress', [EventController::class, 'progress']);
    Route::post('/events/{id}/assign', [EventController::class, 'assignMember']);
    Route::post('/events/{id}/tasks', [TaskController::class, 'store']);
    Route::get('/events/{id}/tasks', [EventController::class, 'getTasks']);
    Route::post('/events/{id}/expenses', [ExpenseController::class, 'store']);
    Route::get('/events/{id}/expenses', [ExpenseController::class, 'page']);

    Route::get('/tasks', [TaskController::class, 'listEvent']);
    Route::get('/tasks/event/{eventId}', [TaskController::class, 'index']);
    Route::post('/tasks/{id}/status', [TaskController::class, 'updateStatus']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
    Route::get('/tasks/{id}', [TaskController::class, 'show']);
Route::put('/tasks/{id}', [TaskController::class, 'update']);


});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::put('/expenses/{id}', [ExpenseController::class, 'update']);
    Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy']);

    Route::get('/expense-categories', [ExpenseCategoryController::class, 'index']);
    Route::post('/expense-categories', [ExpenseCategoryController::class, 'store']);
    Route::delete('/expense-categories/{id}', [ExpenseCategoryController::class, 'destroy']);

});


Route::get('/divisions', [DivisionController::class, 'index']);
Route::post('/divisions', [DivisionController::class, 'store']);


require __DIR__.'/auth.php';
