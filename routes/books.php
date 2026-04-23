<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;

/**
 * ADMIN ROUTES - Kelola Buku
 */
Route::middleware(['auth', 'role:Admin,Petugas'])->group(function () {
    Route::resource('books', BooksController::class);
    Route::post('/books/{book}/check-availability', [BooksController::class, 'checkAvailability'])->name('books.checkAvailability');
});

/**
 * SISWA ROUTES - Lihat Katalog Buku
 */
Route::middleware(['auth', 'role:Siswa'])->group(function () {
    Route::get('/books/catalog', [BooksController::class, 'catalog'])->name('books.catalog');
    Route::get('/books/search', [BooksController::class, 'search'])->name('books.search');
    Route::post('/books/{book}/borrow', [BooksController::class, 'borrow'])->name('books.borrow');
});
