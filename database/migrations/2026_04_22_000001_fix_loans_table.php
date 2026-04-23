<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            // Drop the old petugas_id constraint and make it nullable
            $table->dropForeign(['petugas_id']);
            $table->foreignId('petugas_id')->nullable()->change()->references('id')->on('users')->onDelete('set null');
        });

        // Drop and recreate status enum with correct values
        DB::statement("ALTER TABLE loans MODIFY status ENUM('active', 'returned', 'overdue') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['petugas_id']);
            $table->foreignId('petugas_id')->references('id')->on('users')->onDelete('cascade');
        });

        DB::statement("ALTER TABLE loans MODIFY status ENUM('dipinjam', 'dikembalikan', 'terlambat') DEFAULT 'dipinjam'");
    }
};
