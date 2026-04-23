<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;

Route::middleware(['auth', 'role:Siswa'])->group(function () {
    // Dashboard & Jelajahi
    Route::get('/siswa/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');
    Route::get('/siswa/jelajahi', [SiswaController::class, 'jelajahi'])->name('siswa.jelajahi');

    // Peminjaman
    Route::get('/siswa/riwayat-peminjaman', [SiswaController::class, 'riwayatPeminjaman'])->name('siswa.riwayat');
    Route::get('/siswa/tambah-peminjaman', [SiswaController::class, 'tambahPeminjaman'])->name('siswa.tambah-peminjaman');
    Route::post('/siswa/pinjam', [SiswaController::class, 'pinjam'])->name('siswa.pinjam');
    Route::post('/siswa/pinjam-multiple', [SiswaController::class, 'pinjamMultiple'])->name('siswa.pinjam-multiple');
    Route::post('/siswa/kembalikan', [SiswaController::class, 'kembalikan'])->name('siswa.kembalikan');
    Route::post('/siswa/borrow', [SiswaController::class, 'pinjam'])->name('borrow');
    Route::post('/borrow/{equipment}', [SiswaController::class, 'pinjam'])->name('borrow-legacy');

    // Profil Siswa
    Route::get('/siswa/profil', [SiswaController::class, 'profile'])->name('siswa.profil');
    Route::put('/siswa/profil', [SiswaController::class, 'updateProfile'])->name('siswa.profil.update');

    // Wishlist
    Route::get('/siswa/wishlist', [SiswaController::class, 'wishlist'])->name('siswa.wishlist');
    Route::post('/siswa/wishlist/add', [SiswaController::class, 'addToWishlist'])->name('siswa.wishlist.add');
    Route::delete('/siswa/wishlist/{book}', [SiswaController::class, 'removeFromWishlist'])->name('siswa.wishlist.remove');

    // Review Buku
    Route::post('/siswa/review/{book}', [SiswaController::class, 'addReview'])->name('siswa.review.add');

    // Reservasi Buku
    Route::get('/siswa/reservasi', [SiswaController::class, 'reservations'])->name('siswa.reservasi');
    Route::post('/siswa/reservasi/add', [SiswaController::class, 'addReservation'])->name('siswa.reservasi.add');
    Route::delete('/siswa/reservasi/{reservation}', [SiswaController::class, 'cancelReservation'])->name('siswa.reservasi.cancel');

    // Perpanjangan Pinjaman
    Route::get('/siswa/perpanjangan', [SiswaController::class, 'extensions'])->name('siswa.perpanjangan');
    Route::post('/siswa/perpanjangan/request', [SiswaController::class, 'requestExtension'])->name('siswa.perpanjangan.request');

    // Notifikasi
    Route::get('/siswa/notifikasi', [SiswaController::class, 'notifications'])->name('siswa.notifikasi');
    Route::post('/siswa/notifikasi/{notification}/read', [SiswaController::class, 'markNotificationAsRead'])->name('siswa.notifikasi.read');
    Route::post('/siswa/notifikasi/read-all', [SiswaController::class, 'markAllNotificationsAsRead'])->name('siswa.notifikasi.read-all');

    // Denda
    Route::get('/siswa/denda', [SiswaController::class, 'denda'])->name('siswa.denda');

    // Download Riwayat
    Route::get('/siswa/download-riwayat', [SiswaController::class, 'downloadHistory'])->name('siswa.download-riwayat');
});
