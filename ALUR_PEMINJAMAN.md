# 📚 Alur Lengkap Peminjaman Buku - Perpustakaan Digital

## 🔐 Login Siswa

- Email: `siswa@mail.com`
- Password: `12345678`

---

## 📋 Alur Peminjaman

### 1️⃣ **Akses Katalog Buku**

```
Login Siswa → Redirect ke Dashboard
Dashboard → Click "Jelajahi Buku" → Buka Catalog
```

### 2️⃣ **Pilih & Pinjam Buku**

- Di Catalog, lihat grid buku
- Click tombol "Pinjam Buku" pada buku pilihan
- Confirm: "Apakah Anda ingin meminjam buku ini?"
- ✅ Sistem membuat Loan record
- ✅ Sistem membuat LoanItem (link buku ke peminjaman)
- ✅ Sistem mengurangi stock buku
- ✅ Redirect dengan pesan success

### 3️⃣ **Lihat Peminjaman Aktif**

- Dashboard Siswa → Tabel "Buku yang Sedang Dipinjam"
- Tampil: Judul, Tanggal Pinjam, Batas Kembali, Sisa Hari, Status

### 4️⃣ **Lihat Riwayat Peminjaman**

- Dashboard → Click "Riwayat Peminjaman"
- Atau Menu Sidebar → Riwayat Peminjaman
- Tampil: Semua peminjaman (aktif + selesai) dengan pagination

### 5️⃣ **Kembalikan Buku**

- Di Dashboard atau Riwayat, click "Kembalikan" pada buku aktif
- Confirm pengembalian
- ✅ Sistem update status loan → "returned"
- ✅ Sistem kembalikan stock buku
- ✅ Sistem set return_date
- ✅ Data hilang dari "Buku Dipinjam" di Dashboard
- ✅ Data tetap di Riwayat dengan status "Dikembalikan"

---

## 🗄️ Database Flow

```
users (Siswa / role_id = 4)
  ↓
loans (borrower_id = user.id, status = 'active')
  ↓ (1 loan has many)
  ↓
loan_items (loan_id, book_id)
  ↓ (1 item references)
  ↓
books (stock -= 1)
```

### Loan Table

| Field       | Tipe      | Keterangan                      |
| ----------- | --------- | ------------------------------- |
| id          | int       | Primary key                     |
| borrower_id | int FK    | User ID yang pinjam             |
| loan_date   | timestamp | Kapan dipinjam                  |
| due_date    | timestamp | Batas pengembalian              |
| return_date | timestamp | Tanggal sebenarnya dikembalikan |
| status      | string    | 'active', 'returned', 'overdue' |

### LoanItem Table

| Field   | Tipe   | Keterangan  |
| ------- | ------ | ----------- |
| id      | int    | Primary key |
| loan_id | int FK | Loan ID     |
| book_id | int FK | Book ID     |

---

## 🔧 Routes & Controllers

### Routes (routes/siswa.php)

```php
Route::middleware(['auth', 'role:Siswa'])->group(function () {
    // Dashboard Siswa
    GET  /siswa/dashboard  → SiswaController@dashboard

    // Riwayat Peminjaman
    GET  /siswa/riwayat-peminjaman  → SiswaController@riwayatPeminjaman

    // Proses Peminjaman
    POST /siswa/pinjam  → SiswaController@pinjam (dari form)
    POST /borrow/{book} → SiswaController@pinjam (dari catalog)

    // Pengembalian
    POST /siswa/kembalikan  → SiswaController@kembalikan
});
```

### Controller Methods (app/Http/Controllers/SiswaController.php)

#### `dashboard()`

- Query active loans untuk user
- Hitung total buku tersedia, buku dipinjam, total peminjaman, overdue
- Return view dengan data
- **View**: `siswa.dashboard`

#### `riwayatPeminjaman()`

- Query ALL loans (active + returned)
- Order by loan_date desc
- Paginate 10 per page
- Return view dengan data
- **View**: `siswa.riwayat`

#### `pinjam(Request $request, Book $book = null)`

1. Ambil book dari route param atau request.book_id
2. Validasi stock > 0
3. Create Loan record (borrower_id, loan_date, due_date=+7days, status='active')
4. Create LoanItem (loan_id, book_id)
5. `book->decrement('stock')`
6. Log & return success/error

- **Routes**: POST `/siswa/pinjam`, POST `/borrow/{book}`

#### `kembalikan(Request $request)`

1. Get Loan by loan_id
2. Validasi ownership (borrower_id == auth user)
3. Loop LoanItems → increment book stock
4. Update Loan (status='returned', return_date=now())
5. Log & return success/error

- **Routes**: POST `/siswa/kembalikan`

---

## 🎨 Views & Layouts

### siswa-layout.blade.php

- Sidebar dengan gradient cyan (#4facfe → #00f2fe)
- Menu: Dashboard, Riwayat, Jelajahi Buku, Logout
- Page content area
- Responsive mobile

### siswa/dashboard.blade.php

- Page header
- 4 Stat Cards (Buku Tersedia, Buku Dipinjam, Total Peminjaman, Terlambat)
- Table: Buku yang Sedang Dipinjam
    - Columns: Judul, Tgl Pinjam, Tgl Kembali, Sisa Hari, Status, Action (Kembalikan)
- Quick Actions: Jelajahi Buku, Riwayat Peminjaman

### siswa/riwayat.blade.php

- Page header
- Table: Daftar Semua Peminjaman
    - Columns: No, Judul, Tgl Pinjam, Tgl Kembali, Durasi, Status, Action
    - Status badges: Active (info), Returned (success)
- Bootstrap Pagination

### catalog.blade.php

- Header & Navbar (Search, Dashboard, Logout)
- Search input (client-side filtering)
- Books Grid (3-4 columns responsive)
- Book Card: Image, Title, Author, Publisher, ISBN, Stock, Borrow Button
- Pagination (server-side)
- Footer

---

## ✅ Checklist Implementasi

- [x] SiswaController dengan 4 methods
- [x] Routes siswa dengan 4 endpoints + borrow route
- [x] Loan & LoanItem models
- [x] siswa-layout.blade.php (cyan theme)
- [x] siswa/dashboard.blade.php (4 stats + active loans table)
- [x] siswa/riwayat.blade.php (all loans history with pagination)
- [x] catalog.blade.php (books grid with search + borrow buttons)
- [x] Error handling & logging
- [x] CSRF protection
- [x] Role middleware protection

---

## 🐛 Testing

### 1. Buka Debug Page

```
http://localhost:8000/debug
```

Lihat: loans, loanItems data

### 2. Login & Test Alur

1. Login Siswa → dashboard
2. Klik "Jelajahi Buku" → catalog
3. Klik "Pinjam Buku" → success message
4. Check dashboard "Buku Dipinjam" table
5. Klik view riwayat
6. Klik "Kembalikan" → status berubah

### 3. Database Check (Tinker)

```bash
php artisan tinker
>>> $user = App\Models\User::where('email', 'siswa@mail.com')->first();
>>> $user->loans; // Semua loans user
>>> $user->loans()->where('status', 'active')->get(); // Active loans
>>> exit
```

---

## 🚀 Troubleshoot

**Q: Buku tidak muncul di riwayat setelah pinjam?**
A: Check `storage/logs/laravel.log` untuk error. Pastikan:

- Loan record created di database
- LoanItem created di database
- Stock berkurang
- borrower_id = auth user id

**Q: Error 419 saat submit form?**
A: CSRF token missing. Verify:

- `{{ csrf_field() }}` ada di form
- Session table exists
- `.env` SESSION_DRIVER=database

**Q: Tombol Kembalikan tidak muncul?**
A: Check status di database harus 'active' bukan string lain
