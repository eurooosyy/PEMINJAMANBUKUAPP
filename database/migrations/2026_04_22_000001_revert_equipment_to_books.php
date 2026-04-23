<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Kembali ke sistem peminjaman BUKU dari EQUIPMENT
     * 
     * Perubahan:
     * - Rename table equipment → books
     * - Restore kolom: title, author, publisher, year, isbn
     * - Hapus kolom: kategori, kondisi, lokasi, pemakaian
     * - Update foreign keys di loan_items
     * - Update foreign keys di wishlist
     * - Rename table equipment_reviews → book_reviews
     * - Rename table equipment_reservations → book_reservations
     * - Update kolom di tabel loan_items: equipment_id → book_id
     * - Update kolom di tabel wishlist: equipment_id → book_id
     */
    public function up(): void
    {
        // 1. Rename equipment_reviews ke book_reviews
        Schema::rename('equipment_reviews', 'book_reviews');

        // 2. Update foreign key di book_reviews
        Schema::table('book_reviews', function (Blueprint $table) {
            $table->renameColumn('equipment_id', 'book_id');
        });

        // 3. Rename equipment_reservations ke book_reservations
        Schema::rename('equipment_reservations', 'book_reservations');

        // 4. Update foreign key di book_reservations
        Schema::table('book_reservations', function (Blueprint $table) {
            $table->renameColumn('equipment_id', 'book_id');
        });

        // 5. Update wishlist table
        Schema::table('wishlist', function (Blueprint $table) {
            $table->renameColumn('equipment_id', 'book_id');
        });

        // 6. Update loan_items table
        Schema::table('loan_items', function (Blueprint $table) {
            $table->renameColumn('equipment_id', 'book_id');
        });

        // 7. Update equipment table column names dan restore schema
        Schema::table('equipment', function (Blueprint $table) {
            // Rename kembali ke nama buku
            $table->renameColumn('nama_peralatan', 'title');
            $table->renameColumn('merk', 'author');
            $table->renameColumn('nomor_identitas', 'isbn');
            $table->renameColumn('tahun_pembelian', 'publisher');

            // Restore kolom year
            $table->year('year')->nullable()->after('publisher');

            // Hapus kolom equipment
            $table->dropColumn(['kategori', 'kondisi', 'lokasi', 'pemakaian']);
        });

        // 8. Rename table equipment ke books
        Schema::rename('equipment', 'books');

        // 9. Rebuild foreign keys di loan_items
        Schema::table('loan_items', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
            $table->foreign('book_id')
                ->references('id')
                ->on('books')
                ->onDelete('cascade');
        });

        // 10. Rebuild foreign keys di wishlist
        Schema::table('wishlist', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
            $table->foreign('book_id')
                ->references('id')
                ->on('books')
                ->onDelete('cascade');
        });

        // 11. Rebuild foreign keys di book_reviews
        Schema::table('book_reviews', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
            $table->foreign('book_id')
                ->references('id')
                ->on('books')
                ->onDelete('cascade');
        });

        // 12. Rebuild foreign keys di book_reservations
        Schema::table('book_reservations', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
            $table->foreign('book_id')
                ->references('id')
                ->on('books')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('books', 'equipment');

        Schema::table('equipment', function (Blueprint $table) {
            $table->renameColumn('title', 'nama_peralatan');
            $table->renameColumn('author', 'merk');
            $table->renameColumn('isbn', 'nomor_identitas');
            $table->renameColumn('publisher', 'tahun_pembelian');
            $table->dropColumn('year');

            $table->string('kategori')->nullable();
            $table->enum('kondisi', ['baik', 'cukup', 'rusak'])->default('baik');
            $table->string('lokasi')->nullable();
            $table->integer('pemakaian')->default(0);
        });

        Schema::table('loan_items', function (Blueprint $table) {
            $table->renameColumn('book_id', 'equipment_id');
        });

        Schema::table('wishlist', function (Blueprint $table) {
            $table->renameColumn('book_id', 'equipment_id');
        });

        Schema::rename('book_reviews', 'equipment_reviews');
        Schema::table('equipment_reviews', function (Blueprint $table) {
            $table->renameColumn('book_id', 'equipment_id');
        });

        Schema::rename('book_reservations', 'equipment_reservations');
        Schema::table('equipment_reservations', function (Blueprint $table) {
            $table->renameColumn('book_id', 'equipment_id');
        });
    }
};
