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
        // Rename book_reviews ke equipment_reviews
        Schema::rename('book_reviews', 'equipment_reviews');

        // Update foreign key di equipment_reviews
        Schema::table('equipment_reviews', function (Blueprint $table) {
            $table->renameColumn('book_id', 'equipment_id');
        });

        // Rename book_reservations ke equipment_reservations
        Schema::rename('book_reservations', 'equipment_reservations');

        // Update foreign key di equipment_reservations
        Schema::table('equipment_reservations', function (Blueprint $table) {
            $table->renameColumn('book_id', 'equipment_id');
        });

        // Update wishlist table - rename book_id ke equipment_id
        Schema::table('wishlist', function (Blueprint $table) {
            $table->renameColumn('book_id', 'equipment_id');
        });

        // Update loan_items table - rename book_id ke equipment_id
        Schema::table('loan_items', function (Blueprint $table) {
            $table->renameColumn('book_id', 'equipment_id');
        });

        // Update student_notifications table
        Schema::table('student_notifications', function (Blueprint $table) {
            if (Schema::hasColumn('student_notifications', 'related_book_id')) {
                $table->renameColumn('related_book_id', 'related_equipment_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse all renames
        Schema::table('equipment_reviews', function (Blueprint $table) {
            $table->renameColumn('equipment_id', 'book_id');
        });
        Schema::rename('equipment_reviews', 'book_reviews');

        Schema::table('equipment_reservations', function (Blueprint $table) {
            $table->renameColumn('equipment_id', 'book_id');
        });
        // REMOVED: Schema::rename('equipment_reservations', 'book_reservations'); // Prevent table recreation issue

        Schema::table('wishlist', function (Blueprint $table) {
            $table->renameColumn('equipment_id', 'book_id');
        });

        Schema::table('loan_items', function (Blueprint $table) {
            $table->renameColumn('equipment_id', 'book_id');
        });

        Schema::table('student_notifications', function (Blueprint $table) {
            if (Schema::hasColumn('student_notifications', 'related_equipment_id')) {
                $table->renameColumn('related_equipment_id', 'related_book_id');
            }
        });
    }
};
