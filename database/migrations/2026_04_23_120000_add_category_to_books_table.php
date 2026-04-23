<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('books', 'category')) {
            Schema::table('books', function (Blueprint $table) {
                $table->string('category')->nullable()->after('author');
            });
        }

        DB::table('books')
            ->whereNull('category')
            ->update(['category' => 'Lainnya']);
    }

    public function down(): void
    {
        if (Schema::hasColumn('books', 'category')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }
    }
};
