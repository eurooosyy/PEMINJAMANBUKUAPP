<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Manajemen petugas
    Route::resource('petugas', AdminController::class, [
        'parameters' => ['petugas' => 'petugas']
    ]);

    Route::get('/extensions', [\App\Http\Controllers\LoanController::class, 'pendingExtensions'])->name('admin.extensions');
    // Laporan

    Route::get('/reports/borrowing', [ReportController::class, 'borrowingReport'])->name('reports.borrowing');
    Route::get('/reports/overdue', [ReportController::class, 'overdueReport'])->name('reports.overdue');
    Route::get('/reports/popular-equipment', [ReportController::class, 'popularEquipmentReport'])->name('reports.popular-equipment');
    Route::get('/reports/statistics', [ReportController::class, 'statisticsReport'])->name('reports.statistics');
    Route::get('/reports/returns', [ReportController::class, 'returnReport'])->name('reports.returns');
    Route::get('/reports/borrowers', [ReportController::class, 'borrowerReport'])->name('reports.borrowers');
});
