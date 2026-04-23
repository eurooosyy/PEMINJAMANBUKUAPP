## 🚀 QUICK START GUIDE - Fitur Baru Role Siswa

### Langkah-Langkah Setup

#### 1️⃣ **Jalankan Migrations**

```bash
php artisan migrate
```

#### 2️⃣ **Clear Cache** (Opsional)

```bash
php artisan cache:clear
php artisan config:clear
```

#### 3️⃣ **Update Navigation Menu**

Tambahkan link-link baru di sidebar/navbar layout siswa. Berikut template untuk menambahkan ke `resources/views/dashboard/siswa-layout.blade.php`:

```blade
<!-- Profil Siswa -->
<li>
    <a href="{{ route('siswa.profil') }}"
        class="nav-link {{ request()->routeIs('siswa.profil') ? 'active' : '' }}">
        <i class="fas fa-user-circle"></i>
        <span>Profil Saya</span>
    </a>
</li>

<!-- Wishlist -->
<li>
    <a href="{{ route('siswa.wishlist') }}"
        class="nav-link {{ request()->routeIs('siswa.wishlist') ? 'active' : '' }}">
        <i class="fas fa-heart"></i>
        <span>Wishlist</span>
    </a>
</li>

<!-- Reservasi -->
<li>
    <a href="{{ route('siswa.reservasi') }}"
        class="nav-link {{ request()->routeIs('siswa.reservasi') ? 'active' : '' }}">
        <i class="fas fa-bookmark"></i>
        <span>Reservasi</span>
    </a>
</li>

<!-- Perpanjangan -->
<li>
    <a href="{{ route('siswa.perpanjangan') }}"
        class="nav-link {{ request()->routeIs('siswa.perpanjangan') ? 'active' : '' }}">
        <i class="fas fa-sync-alt"></i>
        <span>Perpanjangan</span>
    </a>
</li>

<!-- Notifikasi -->
<li>
    <a href="{{ route('siswa.notifikasi') }}"
        class="nav-link {{ request()->routeIs('siswa.notifikasi') ? 'active' : '' }}">
        <i class="fas fa-bell"></i>
        <span>
            Notifikasi
            @php
                $unreadCount = \App\Models\StudentNotification::where('user_id', auth()->id())
                    ->where('is_read', false)->count();
            @endphp
            @if($unreadCount > 0)
                <span class="badge badge-danger">{{ $unreadCount }}</span>
            @endif
        </span>
    </a>
</li>

<!-- Denda -->
<li>
    <a href="{{ route('siswa.denda') }}"
        class="nav-link {{ request()->routeIs('siswa.denda') ? 'active' : '' }}">
        <i class="fas fa-file-invoice-dollar"></i>
        <span>Daftar Denda</span>
    </a>
</li>
```

#### 4️⃣ **Test Fitur**

Login sebagai siswa dan akses:

- `/siswa/profil` - Profil Siswa
- `/siswa/wishlist` - Wishlist
- `/siswa/reservasi` - Reservasi
- `/siswa/perpanjangan` - Perpanjangan
- `/siswa/notifikasi` - Notifikasi
- `/siswa/denda` - Daftar Denda

---

### 📊 Daftar Fitur & Aksesnya

| Fitur        | URL                       | Route Name               | Icon |
| ------------ | ------------------------- | ------------------------ | ---- |
| Profil       | `/siswa/profil`           | `siswa.profil`           | 👤   |
| Wishlist     | `/siswa/wishlist`         | `siswa.wishlist`         | ❤️   |
| Review       | `/siswa/review/{book}`    | `siswa.review.add`       | ⭐   |
| Reservasi    | `/siswa/reservasi`        | `siswa.reservasi`        | 🔖   |
| Perpanjangan | `/siswa/perpanjangan`     | `siswa.perpanjangan`     | 🔄   |
| Notifikasi   | `/siswa/notifikasi`       | `siswa.notifikasi`       | 🔔   |
| Denda        | `/siswa/denda`            | `siswa.denda`            | 💰   |
| Download     | `/siswa/download-riwayat` | `siswa.download-riwayat` | ⬇️   |

---

### 🔧 Konfigurasi Sistem

**File Konfigurasi yang Penting:**

- `app/Models/Loan.php` - Model peminjaman (sudah diupdate dengan relationships)
- `app/Http/Controllers/SiswaController.php` - Controller siswa (sudah diupdate)
- `routes/siswa.php` - Routes siswa (sudah diupdate)

---

### 💾 Kebutuhan Database

Pastikan sudah menjalankan migrations:

```bash
php artisan migrate --path=database/migrations/2026_04_20_000001_create_book_reviews_table.php
php artisan migrate --path=database/migrations/2026_04_20_000002_create_wishlist_table.php
php artisan migrate --path=database/migrations/2026_04_20_000003_create_book_reservations_table.php
php artisan migrate --path=database/migrations/2026_04_20_000004_create_loan_extensions_table.php
php artisan migrate --path=database/migrations/2026_04_20_000005_create_student_notifications_table.php
```

Atau migrate sekaligus:

```bash
php artisan migrate
```

---

### 🎨 Styling & UI

Semua view menggunakan:

- ✅ CSS Grid untuk responsive layout
- ✅ Gradient backgrounds
- ✅ Box shadows untuk depth
- ✅ Icon FontAwesome untuk visual
- ✅ Hover effects & transitions

---

### ⚙️ Variabel Konfigurasi yang Dapat Diubah

**1. Tarif Denda** (di `SiswaController.php`, method `denda()`)

```php
$fineAmount = $daysOverdue * 5000; // Ubah 5000 ke nilai lain
```

**2. Durasi Perpanjangan** (di method `requestExtension()`)

```php
$extensionDays = 7; // Ubah ke nilai lain
```

**3. Durasi Reservasi** (di method `addReservation()`)

```php
'reserved_until' => now()->addDays(3), // Ubah 3 ke nilai lain
```

**4. Maksimal Perpanjangan** (di method `requestExtension()`)

```php
if ($previousExt >= 2) { // Ubah 2 ke nilai lain
```

---

### 🔐 Keamanan

✅ Semua route dilindungi middleware `auth` dan `role:Siswa`  
✅ Ownership check di setiap method yang sensitive  
✅ Validation di setiap input form  
✅ Logging untuk audit trail

---

### 📱 Responsive Design

Semua view sudah responsive dan teruji di:

- ✅ Desktop (1920px+)
- ✅ Laptop (1366px)
- ✅ Tablet (768px)
- ✅ Mobile (375px)

---

### 🐛 Troubleshooting

**Error: Table not found**
→ Jalankan migrations: `php artisan migrate`

**Error: Route not found**
→ Pastikan sudah update `routes/siswa.php`

**Error: Model not found**
→ Pastikan semua file model sudah ada di `app/Models/`

**View tidak tampil**
→ Pastikan file view ada di `resources/views/siswa/`

---

### 📞 Support

Jika ada masalah atau pertanyaan, silakan:

1. Cek file `FITUR_BARU_SISWA.md` untuk dokumentasi lengkap
2. Review model relationships di `app/Models/`
3. Check controller methods di `SiswaController.php`
4. Verifikasi routes di `routes/siswa.php`

---

**Setup Selesai! 🎉**

Semua fitur baru siap digunakan untuk role Siswa.
