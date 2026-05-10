<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookrent', function (Blueprint $table) {

            // hapus foreign key dulu
            $table->dropForeign(['book_id']);

            // hapus kolom
            $table->dropColumn('book_id');

        });
    }

    public function down(): void
    {
        Schema::table('bookrent', function (Blueprint $table) {

            $table->foreignId('book_id')
                  ->constrained('books');

        });
    }
};