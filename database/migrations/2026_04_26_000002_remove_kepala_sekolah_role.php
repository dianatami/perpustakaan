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
        // Preserve old users by demoting legacy role 3 to anggota and disabling access.
        DB::table('user')
            ->where('role', '3')
            ->update([
                'role' => '0',
                'status' => 0,
            ]);

        DB::statement("ALTER TABLE user MODIFY role ENUM('0','1','2') NOT NULL DEFAULT '0'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE user MODIFY role ENUM('0','1','2','3') NOT NULL DEFAULT '0'");
    }
};
