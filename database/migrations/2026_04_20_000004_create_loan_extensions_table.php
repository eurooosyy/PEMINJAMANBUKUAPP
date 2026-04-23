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
        Schema::create('loan_extensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->onDelete('cascade');
            $table->dateTime('old_due_date');
            $table->dateTime('new_due_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('reason')->nullable();
            $table->text('admin_notes')->nullable();
            $table->integer('extension_days')->default(7);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_extensions');
    }
};
