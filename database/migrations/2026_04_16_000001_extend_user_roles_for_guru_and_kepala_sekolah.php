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
            DB::statement("ALTER TABLE user MODIFY role ENUM('0','1','2','3') NOT NULL DEFAULT '0'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE user SET role = '0' WHERE role IN ('2','3')");
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE user MODIFY role ENUM('0','1') NOT NULL DEFAULT '0'");
        }
    }
};