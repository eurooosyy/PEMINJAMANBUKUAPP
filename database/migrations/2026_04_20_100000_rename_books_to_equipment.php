<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create equipment table dari books table yang sudah ada
        Schema::table('books', function (Blueprint $table) {
            // Tambah kolom baru untuk peralatan
            $table->string('kategori')->nullable()->after('title');
            $table->enum('kondisi', ['baik', 'cukup', 'rusak'])->default('baik')->after('kategori');
            $table->string('lokasi')->nullable()->after('kondisi');
            $table->integer('pemakaian')->default(0)->after('lokasi');

            // Rename kolom
            $table->renameColumn('title', 'nama_peralatan');
            $table->renameColumn('author', 'merk');
            $table->renameColumn('isbn', 'nomor_identitas');
            $table->renameColumn('publisher', 'tahun_pembelian');
            $table->dropColumn('year');
        });

        // Rename table
        Schema::rename('books', 'equipment');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse table rename
        Schema::rename('equipment', 'books');

        // Reverse column changes
        Schema::table('books', function (Blueprint $table) {
            // Rename kolom kembali
            $table->renameColumn('nama_peralatan', 'title');
            $table->renameColumn('merk', 'author');
            $table->renameColumn('nomor_identitas', 'isbn');
            $table->renameColumn('tahun_pembelian', 'publisher');

            // Hapus kolom baru
            $table->dropColumn(['kategori', 'kondisi', 'lokasi', 'pemakaian']);

            // Tambah kembali year
            $table->integer('year')->nullable();
        });
    }
};
