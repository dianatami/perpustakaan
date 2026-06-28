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
        Schema::table('bookrent', function (Blueprint $table) {
            $table->enum('jenis_peminjam', ['murid', 'guru'])->nullable();
            $table->timestamp('di_acc_at')->nullable();
            $table->timestamp('tgl_kembali_maksimal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookrent', function (Blueprint $table) {
            $table->dropColumn(['jenis_peminjam', 'di_acc_at', 'tgl_kembali_maksimal']);
        });
    }
};
