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
        Schema::create('bookrent', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user')->cascadeOnDelete();
            if (DB::getDriverName() !== 'sqlite') {
                $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            }
            $table->date('borrow_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['menunggu_acc', 'dipinjam', 'ditolak', 'proses_kembali', 'kembali', 'dikembalikan']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookrent');
    }
};
