<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ReportController;

Route::middleware(['auth', 'role:Petugas,Admin'])->group(function () {
    Route::get('/petugas/dashboard', [PetugasController::class, 'dashboard'])->name('petugas.dashboard');
    Route::get('/petugas/buku', [PetugasController::class, 'buku'])->name('petugas.buku');
    Route::get('/petugas/peminjaman', [PetugasController::class, 'peminjaman'])->name('petugas.peminjaman');
    Route::get('/petugas/pengembalian', [PetugasController::class, 'pengembalian'])->name('petugas.pengembalian');

    // Laporan untuk petugas
    Route::get('/reports/borrowing', [ReportController::class, 'borrowingReport'])->name('reports.borrowing');

    // Extension confirmation for admin/petugas
    Route::post('/loans/{loan}/extension/approve', [\App\Http\Controllers\LoanController::class, 'approveExtension'])->name('loans.extension.approve');
    Route::post('/loans/{loan}/extension/reject', [\App\Http\Controllers\LoanController::class, 'rejectExtension'])->name('loans.extension.reject');
    Route::get('/reports/overdue', [ReportController::class, 'overdueReport'])->name('reports.overdue');
    Route::get('/reports/popular-equipment', [ReportController::class, 'popularEquipmentReport'])->name('reports.popular-equipment');
    Route::get('/reports/returns', [ReportController::class, 'returnReport'])->name('reports.returns');
    Route::get('/reports/borrowers', [ReportController::class, 'borrowerReport'])->name('reports.borrowers');
});
