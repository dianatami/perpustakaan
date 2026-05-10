<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_bookrent', function (Blueprint $table) {

            $table->id();

            // relasi ke tabel bookrent
            $table->foreignId('bookrent_id')
                  ->constrained('bookrent')
                  ->onDelete('cascade');

            // relasi ke books
            $table->foreignId('book_id')
                  ->constrained('books')
                  ->onDelete('cascade');

            $table->integer('qty');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_bookrent');
    }
};