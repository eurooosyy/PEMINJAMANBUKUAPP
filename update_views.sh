#!/bin/bash
# Quick Find & Replace untuk update semua siswa views ke terminology Equipment

cd /c/laragon/www/peminjamanbukuapp

echo "🔄 Updating siswa views..."

# Update variable names
find resources/views/siswa -name "*.blade.php" -exec sed -i \
  's/\$books/\$equipment/g; \
   s/\$book/\$equipment/g; \
   s/->book/->equipment/g; \
   s/totalBooks/totalEquipment/g; \
   s/popularBooks/popularEquipment/g; \
   s/recentBooks/recentEquipment/g; \
   s/overdueBooks/overdueEquipment/g; \
   s/activeBooks/activeEquipment/g; \
   s/book_ids/equipment_ids/g; \
   s/book-id/equipment-id/g; \
   s/book_id/equipment_id/g' {} \;

# Update field names
find resources/views/siswa -name "*.blade.php" -exec sed -i \
  "s/->title/->nama_peralatan/g; \
   s/->author/->merk/g; \
   s/->isbn/->nomor_identitas/g" {} \;

# Update Blade template text
find resources/views/siswa -name "*.blade.php" -exec sed -i \
  "s/Judul Buku/Nama Peralatan/g; \
   s/Buku Tersedia/Peralatan Tersedia/g; \
   s/Jelajahi Buku/Jelajahi Peralatan/g; \
   s/Pinjam Buku/Pinjam Peralatan/g; \
   s/Stok Habis/Stok Habis/g; \
   s/buku perpustakaan/peralatan sekolah/g; \
   s/buku yang/peralatan yang/g; \
   s/Buku Populer/Peralatan Populer/g; \
   s/Buku Terbaru/Peralatan Terbaru/g" {} \;

echo "✅ All siswa views updated!"
echo "📄 Files updated:"
find resources/views/siswa -name "*.blade.php" | wc -l
echo "   blade files modified"
