<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\EventController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\Web\TaskController;
use App\Http\Controllers\Web\PartnerController;
use App\Http\Controllers\Web\CertificateController;
use App\Http\Controllers\Web\DocumentController;
use App\Http\Controllers\Web\ExpenseController;
use App\Http\Controllers\Web\ExpenseCategoryController;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\EventCommitteeController;
use App\Http\Controllers\Api\ExpenseReportController;
use App\Http\Controllers\Api\PartnerController as ApiPartnerController;


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
    return redirect('/login');
});

// AUTH ROUTES FROM API
Route::post('/login', [ApiAuthController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/me', [ApiAuthController::class, 'me']);
    Route::post('/logout', [ApiAuthController::class, 'logout']);
});


Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

});
Route::middleware('auth')->group(function () {
    Route::get('/organizations/create', [OrganizationController::class, 'create']);
    Route::get('/organizations', [OrganizationController::class, 'index']);
    Route::post('/organizations', [OrganizationController::class, 'store']);
    Route::get('/organizations/{id}', [OrganizationController::class, 'show']);
    Route::post('/organizations/{id}/invite', [OrganizationController::class, 'invite'])->middleware('org.role:admin_org');
    Route::get('/organizations/{id}', [OrganizationController::class, 'show'])->name('organizations.show');
    Route::get('/organizations/{id}/edit', [OrganizationController::class, 'edit'])->name('organizations.edit');
    Route::put('/organizations/{id}', [OrganizationController::class, 'update'])->name('organizations.update');
    Route::post('/organizations/{id}/invite-bulk', [OrganizationController::class, 'inviteBulk']);
});

Route::middleware('auth')->group(function () {

    Route::get('/events/create', [EventController::class, 'create']);
    Route::post('/events', [EventController::class, 'store']);
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{id}/edit', [EventController::class, 'edit']);
    Route::put('/events/{id}', [EventController::class, 'update']);
    Route::get('/events/{id}/details', [EventController::class, 'show'])->name('events.details');
    Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
    

    // 🔥 AUTO UPDATE PROGRESS
    Route::get('/events/{id}/progress', [EventController::class, 'progress']);
    Route::post('/events/{id}/assign', [EventController::class, 'assignMember']);
    Route::post('/events/{id}/assign-bulk', [EventController::class, 'assignMembersBulk']);
    Route::delete('/events/{eventId}/committees/{committeeId}', [EventController::class, 'removeCommittee']);
    Route::post('/events/{id}/rundown', [EventController::class, 'storeRundown']);
    Route::get('/events/{id}/rundown',[EventController::class, 'rundownPage']);
    Route::put('/events/{eventId}/rundown/{rundownId}', [EventController::class, 'updateRundown']);
    Route::delete('/events/{eventId}/rundown/{rundownId}', [EventController::class, 'destroyRundown']);
    Route::get('/events/{eventId}/rundown/export', [EventController::class, 'exportRundown'])->name('web.events.rundown.export');
    Route::post('/events/{eventId}/rundown/import', [EventController::class, 'importRundown'])->name('web.events.rundown.import');

    Route::get('/rundown', [EventController::class, 'rundownList'])->name('web.rundown.index');
    Route::get('/rundown/template', [EventController::class, 'downloadTemplate'])->name('web.rundown.template');
    Route::post('/events/{id}/partners', [EventController::class, 'storePartner']);
    Route::put('/events/{eventId}/partners/{partnerId}', [EventController::class, 'updatePartner']);
    Route::delete('/events/{eventId}/partners/{partnerId}', [EventController::class, 'destroyPartner']);
    Route::post('/events/{id}/certificates', [EventController::class, 'storeCertificate']);
    Route::put('/events/{eventId}/certificates/{certId}', [EventController::class, 'updateCertificate']);
    Route::delete('/events/{eventId}/certificates/{certId}', [EventController::class, 'destroyCertificate']);
    Route::post('/events/{eventId}/certificates/upload-template', [EventController::class, 'uploadCertificateTemplate']);
    Route::post('/events/{eventId}/certificates/bulk-insert', [EventController::class, 'bulkInsertCertificates']);
    Route::get('/certificates/template/download', [EventController::class, 'downloadCertificateTemplate']);
    Route::post('/events/{eventId}/certificates/generate', [EventController::class, 'generateCertificates']);
    Route::post('/events/{eventId}/certificates/save-config', [EventController::class, 'saveCertificateConfig']);
    Route::post('/events/{eventId}/certificates/send', [EventController::class, 'sendCertificates']);
    Route::post('/events/{id}/documents', [EventController::class, 'storeDocument']);
    Route::put('/events/{eventId}/documents/{documentId}', [EventController::class, 'updateDocument']);
    Route::delete('/events/{eventId}/documents/{documentId}', [EventController::class, 'destroyDocument']);
    Route::post('/events/{id}/tasks', [TaskController::class, 'store']);
    Route::get('/events/{id}/tasks', [EventController::class, 'getTasks']);
    Route::get('/events/{id}/kanban', [TaskController::class, 'kanban']);
    Route::post('/events/{id}/expenses', [ExpenseController::class, 'store']);
    Route::get('/events/{id}/expenses', [ExpenseController::class, 'page']);
    Route::get('/events/{id}/expenses/export', [ExpenseController::class, 'export'])->name('web.events.expenses.export');
    Route::post('/events/{id}/expenses/import', [ExpenseController::class, 'import'])->name('web.events.expenses.import');
    Route::post('/events/{id}/budgets', [EventController::class, 'storeBudget']);
    Route::put('/events/{eventId}/budgets/{budgetId}', [EventController::class, 'updateBudget']);
    Route::delete('/events/{eventId}/budgets/{budgetId}', [EventController::class, 'destroyBudget']);
    Route::get('/finance', [ExpenseController::class, 'home']);

    Route::get('/tasks', [TaskController::class, 'listEvent']);
    Route::get('/tasks/event/{eventId}', [TaskController::class, 'index']);
    Route::get('/documents',[DocumentController::class, 'listEvent'])->name('web.documents.index');
    Route::get('/events/{eventId}/documents',[DocumentController::class, 'index'])->name('web.events.documents');
    Route::get('/partners', [PartnerController::class, 'index'])->name('web.partners.index');
    Route::get('/certificates', [CertificateController::class, 'index'])->name('web.certificates.index');
    Route::post('/tasks/{id}/status', [TaskController::class, 'updateStatus']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
    Route::get('/tasks/{id}', [TaskController::class, 'show']);
Route::put('/tasks/{id}', [TaskController::class, 'update']);


});

// API RESOURCES FROM API.PHP
Route::middleware('auth')->group(function () {
    Route::apiResource('committees', EventCommitteeController::class);
    Route::apiResource('expenses', ExpenseReportController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::put('/expenses/{id}', [ExpenseController::class, 'update']);
    Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy']);
    Route::post('/expenses/{id}/status', [ExpenseController::class, 'updateStatus']);

    Route::get('/expense-categories', [ExpenseCategoryController::class, 'index']);
    Route::post('/expense-categories', [ExpenseCategoryController::class, 'store']);
    Route::delete('/expense-categories/{id}', [ExpenseCategoryController::class, 'destroy']);

});


    Route::get('/divisions', [DivisionController::class, 'index']);
    Route::post('/divisions', [DivisionController::class, 'store']);
    Route::prefix('divisions')->name('divisions.')->group(function () {
    Route::get('/{id}/edit', [DivisionController::class, 'edit'])->name('edit');
    Route::put('/{id}', [DivisionController::class, 'update'])->name('update');
    Route::delete('/{id}', [DivisionController::class, 'destroy'])->name('destroy');
});


require __DIR__.'/auth.php';
