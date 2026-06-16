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
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            if (DB::getDriverName() === 'sqlite') {
                $table->string('role')->default('0');
            } else {
                $table->enum('role',[0,1])->default(0);//0=Anggota, 1=Admin
            }
            $table->boolean('status');
            $table->string('password');
            $table->string('hp',13);
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->datetime('birthdate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
