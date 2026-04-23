# 📚 Fitur-Fitur Baru Role Siswa - Sistem Peminjaman Buku

## 🎯 Ringkasan Fitur yang Ditambahkan

Berikut adalah daftar lengkap fitur-fitur baru yang telah ditambahkan untuk role siswa dalam aplikasi Sistem Peminjaman Buku:

---

## 📋 Fitur-Fitur Baru

### 1. **Profil Siswa**

- **Route:** `/siswa/profil`
- **Controller Method:** `profile()`, `updateProfile()`
- **Fitur:**
    - Lihat informasi profil lengkap
    - Statistik peminjaman (total, aktif, dikembalikan, terlambat)
    - Edit data pribadi (nama, email)
    - Quick links ke fitur-fitur lainnya

### 2. **Wishlist (Daftar Favorit)**

- **Route:** `/siswa/wishlist`
- **Controller Methods:** `wishlist()`, `addToWishlist()`, `removeFromWishlist()`
- **Database:** Tabel `wishlist`
- **Model:** `Wishlist`
- **Fitur:**
    - Tambah buku favorit ke wishlist
    - Lihat daftar wishlist dengan rating buku
    - Hapus dari wishlist
    - Pinjam langsung dari wishlist
    - Paging untuk navigasi

### 3. **Review & Rating Buku**

- **Route:** `/siswa/review/{book}`
- **Controller Method:** `addReview()`
- **Database:** Tabel `book_reviews`
- **Model:** `BookReview`
- **Fitur:**
    - Berikan rating 1-5 bintang untuk buku yang pernah dipinjam
    - Tulis review/komentar tentang buku
    - Lihat rata-rata rating dan total review di setiap buku
    - Update review yang sudah dibuat

### 4. **Reservasi Buku**

- **Route:** `/siswa/reservasi`
- **Controller Methods:** `reservations()`, `addReservation()`, `cancelReservation()`
- **Database:** Tabel `book_reservations`
- **Model:** `BookReservation`
- **Fitur:**
    - Reservasi buku yang sedang habis
    - Lihat status reservasi (pending, ready, cancelled, expired)
    - Reservasi berlaku 3 hari setelah disetujui
    - Batalkan reservasi jika diperlukan
    - Notifikasi otomatis ketika buku siap diambil

### 5. **Perpanjangan Pinjaman**

- **Route:** `/siswa/perpanjangan`
- **Controller Methods:** `extensions()`, `requestExtension()`
- **Database:** Tabel `loan_extensions`
- **Model:** `LoanExtension`
- **Fitur:**
    - Ajukan perpanjangan untuk buku yang sedang dipinjam
    - Maksimal perpanjangan 2 kali per buku
    - Setiap perpanjangan menambah 7 hari
    - Lihat riwayat permintaan perpanjangan
    - Status permintaan (pending, approved, rejected)
    - Alasan perpanjangan (opsional)

### 6. **Notifikasi**

- **Route:** `/siswa/notifikasi`
- **Controller Methods:** `notifications()`, `markNotificationAsRead()`, `markAllNotificationsAsRead()`
- **Database:** Tabel `student_notifications`
- **Model:** `StudentNotification`
- **Tipe Notifikasi:**
    - **Loan Reminder:** Pengingat peminjaman
    - **Due Soon:** Jatuh tempo dalam 7 hari
    - **Overdue:** Buku terlambat dikembalikan
    - **Reservation Ready:** Reservasi siap diambil
    - **Extension Approved:** Perpanjangan disetujui
    - **New Book:** Buku baru tersedia
    - **Announcement:** Pengumuman perpustakaan
- **Fitur:**
    - Notifikasi real-time untuk aktivitas penting
    - Tandai notifikasi sebagai terbaca
    - Tandai semua notifikasi sebagai terbaca
    - Filter berdasarkan tipe notifikasi
    - Batching yang rapi dengan ikon berbeda untuk setiap tipe

### 7. **Daftar Denda**

- **Route:** `/siswa/denda`
- **Controller Method:** `denda()`
- **Fitur:**
    - Lihat total denda peminjaman yang terlambat
    - Detail denda per buku (nama, tanggal jatuh tempo, hari terlambat, jumlah denda)
    - Tarif denda: Rp 5.000,- per hari
    - Informasi lengkap peminjaman terlambat
    - Status pembayaran denda

### 8. **Download Riwayat Peminjaman**

- **Route:** `/siswa/download-riwayat`
- **Controller Method:** `downloadHistory()`
- **Fitur:**
    - Download riwayat peminjaman dalam format TXT
    - Informasi lengkap: nama, email, tanggal laporan
    - Detail setiap peminjaman (ID, tanggal, status, buku)
    - File otomatis dengan format: `Riwayat_Peminjaman_[user_id]_[tanggal].txt`

---

## 📊 Database Schema

### Tabel-tabel Baru yang Dibuat:

1. **book_reviews**
    - id, book_id, user_id, rating, review, helpful_count, timestamps

2. **wishlist**
    - id, user_id, book_id, timestamps

3. **book_reservations**
    - id, book_id, user_id, status, reserved_until, timestamps

4. **loan_extensions**
    - id, loan_id, old_due_date, new_due_date, status, reason, admin_notes, extension_days, timestamps

5. **student_notifications**
    - id, user_id, type, title, message, related_loan_id, related_book_id, is_read, read_at, timestamps

---

## 🔗 Routes yang Ditambahkan

```php
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
```

---

## 📁 File-File yang Dibuat/Dimodifikasi

### Migrations (Database):

- `2026_04_20_000001_create_book_reviews_table.php`
- `2026_04_20_000002_create_wishlist_table.php`
- `2026_04_20_000003_create_book_reservations_table.php`
- `2026_04_20_000004_create_loan_extensions_table.php`
- `2026_04_20_000005_create_student_notifications_table.php`

### Models:

- `BookReview.php` (baru)
- `Wishlist.php` (baru)
- `BookReservation.php` (baru)
- `LoanExtension.php` (baru)
- `StudentNotification.php` (baru)
- `Book.php` (dimodifikasi - tambah relations)
- `User.php` (dimodifikasi - tambah relations)
- `Loan.php` (dimodifikasi - tambah relations)

### Controllers:

- `SiswaController.php` (dimodifikasi - tambah methods baru)

### Views:

- `resources/views/siswa/profil.blade.php` (baru)
- `resources/views/siswa/wishlist.blade.php` (baru)
- `resources/views/siswa/reservasi.blade.php` (baru)
- `resources/views/siswa/perpanjangan.blade.php` (baru)
- `resources/views/siswa/notifikasi.blade.php` (baru)
- `resources/views/siswa/denda.blade.php` (baru)

### Routes:

- `routes/siswa.php` (dimodifikasi - tambah 20+ routes baru)

---

## 🚀 Cara Menggunakan Fitur-Fitur Baru

### 1. **Setup Database**

```bash
php artisan migrate
```

### 2. **Akses Fitur**

Setelah login sebagai Siswa, Anda dapat mengakses:

- Dashboard (sudah ada)
- Profil Saya
- Wishlist
- Reservasi
- Perpanjangan
- Notifikasi
- Daftar Denda
- Download Riwayat

### 3. **Endpoint API/Views**

Semua fitur dapat diakses melalui web interface dengan URL:

- `http://yourapp.local/siswa/profil`
- `http://yourapp.local/siswa/wishlist`
- `http://yourapp.local/siswa/reservasi`
- `http://yourapp.local/siswa/perpanjangan`
- `http://yourapp.local/siswa/notifikasi`
- `http://yourapp.local/siswa/denda`

---

## 💡 Fitur-Fitur Highlight

✅ **Wishlist Management** - Simpan buku favorit  
✅ **Book Reviews** - Berikan rating dan review  
✅ **Reservasi** - Pesan buku yang habis  
✅ **Perpanjangan** - Perpanjang peminjaman hingga 2x  
✅ **Notifikasi** - Pengingat dan update otomatis  
✅ **Denda Tracking** - Pantau denda dengan rinci  
✅ **Download History** - Export riwayat peminjaman  
✅ **Profil Lengkap** - Kelola data pribadi  
✅ **Statistik** - Lihat statistik peminjaman

---

## 🔒 Keamanan & Validasi

Semua fitur dilengkapi dengan:

- ✅ Authentication check (hanya untuk role Siswa)
- ✅ Authorization check (hanya target user)
- ✅ Form validation
- ✅ Error handling
- ✅ Logging untuk audit trail

---

## 📝 Catatan Pengembang

**Tarif Denda:** Rp 5.000,- per hari keterlambatan (dapat diubah di method `denda()`)

**Durasi Perpanjangan:** 7 hari per perpanjangan (dapat diubah di method `requestExtension()`)

**Durasi Reservasi:** 3 hari setelah siap (dapat diubah di method `addReservation()`)

---

## ✨ Fitur Tambahan yang Bisa Dikembangkan

1. **Payment Integration** - Pembayaran denda online
2. **Email Notifications** - Kirim notifikasi ke email
3. **Analytics Dashboard** - Statistik penggunaan
4. **Book Recommendations** - Rekomendasi berbasis AI
5. **Wishlist Sharing** - Bagikan wishlist dengan teman
6. **Digital Library** - E-books viewer
7. **QR Code** - Scanning QR untuk peminjaman cepat

---

**Terakhir Diperbarui:** 20 April 2026  
**Versi:** 2.0 (dengan fitur-fitur baru)
