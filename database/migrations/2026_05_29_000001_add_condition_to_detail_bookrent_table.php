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
        Schema::table('detail_bookrent', function (Blueprint $table) {
            $table->enum('condition', ['baik', 'rusak', 'hilang'])->default('baik')->after('qty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_bookrent', function (Blueprint $table) {
            $table->dropColumn('condition');
        });
    }
};
