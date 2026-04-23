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
        Schema::create('student_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['loan_reminder', 'due_soon', 'overdue', 'reservation_ready', 'extension_approved', 'new_equipment', 'announcement'])->default('announcement');
            $table->string('title');
            $table->text('message');
            $table->foreignId('related_loan_id')->nullable()->constrained('loans')->onDelete('set null');
            $table->foreignId('related_book_id')->nullable()->constrained('books')->onDelete('set null');
            $table->boolean('is_read')->default(false);
            $table->dateTime('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_notifications');
    }
};