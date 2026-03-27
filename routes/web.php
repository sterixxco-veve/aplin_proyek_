<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcademicController;

/*
|--------------------------------------------------------------------------
| Web Routes - EduPath Optimizer
|--------------------------------------------------------------------------
*/

// Halaman Utama Dashboard
Route::get('/', [AcademicController::class, 'getDashboardData'])->name('dashboard');

// Action Magic Box Parser
Route::post('/parse-academic', [AcademicController::class, 'storeFromMagicBox'])->name('academic.parse');

/**
 * Route Export PDF Khusus Jadwal
 * Pastikan nama rute ini adalah 'academic.pdf' agar sesuai dengan yang 
 * dipanggil di dashboard.blade.php
 */
Route::get('/export-pdf', [AcademicController::class, 'exportPdf'])->name('academic.pdf');