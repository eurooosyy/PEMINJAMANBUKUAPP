# TODO Tracker: Lanjutkan Pembuatan Aplikasi Peminjaman Buku

Status: 🚀 Dimulai

## Phase 1: 🔄 Complete TODO.md Fitur Konfirmasi Perpanjangan [1/2]

- [✅] 1. Test alur: Tinker skipped (manual by user)

- [ ]   1. Test alur: Buat test data loan + extension via tinker
- [ ]   2. Update TODO.md → mark complete

## Phase 2: 🛠️ Revert Equipment → Books [3/4]

- [✅] 1. Edit resources/views/loans/show.blade.php (equipment → book)
- [✅] 2. Edit routes/petugas.php (peralatan → buku)
- [✅] 3. Edit app/Models/LoanItem.php (remove equipment)

- [✅] 1. Edit resources/views/loans/show.blade.php (equipment → book)

- [ ]   1. Edit resources/views/loans/show.blade.php (equipment → book)
- [ ]   2. Edit routes/petugas.php (peralatan → buku)
- [ ]   3. Run migration revert_equipment_to_books.php
- [ ]   4. Update TODO_REVERT_EQUIPMENT.md

## Phase 3: ✨ Final Polish [0/3]

- [ ]   1. Clear caches (route, config, view)
- [ ]   2. Test full flow (siswa request → petugas approve)
- [ ]   3. Mark all ✅ & attempt_completion

**Next Step:** Run migration revert manually (php artisan migrate --path="database/migrations/2026_04_22_000001_revert_equipment_to_books.php") then clear caches
