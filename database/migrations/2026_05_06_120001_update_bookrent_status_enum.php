<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE bookrent MODIFY COLUMN status ENUM('dipinjam','dikembalikan','menunggu_acc','ditolak','proses_kembali','kembali') NOT NULL DEFAULT 'menunggu_acc'");
        }
        DB::table('bookrent')->where('status', 'dikembalikan')->update(['status' => 'kembali']);
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE bookrent MODIFY COLUMN status ENUM('menunggu_acc','dipinjam','ditolak','proses_kembali','kembali') NOT NULL DEFAULT 'menunggu_acc'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE bookrent MODIFY COLUMN status ENUM('menunggu_acc','dipinjam','ditolak','proses_kembali','kembali','dikembalikan') NOT NULL DEFAULT 'dipinjam'");
        }
        DB::table('bookrent')->where('status', 'kembali')->update(['status' => 'dikembalikan']);
        DB::table('bookrent')->whereIn('status', ['menunggu_acc', 'ditolak', 'proses_kembali'])->update(['status' => 'dipinjam']);
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE bookrent MODIFY COLUMN status ENUM('dipinjam','dikembalikan') NOT NULL DEFAULT 'dipinjam'");
        }
    }
};
