<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'nama' => 'Administrator',
                'role' => User::ROLE_ADMIN,
                'status' => 1,
                'hp' => '0812934010540',
                'password' => Hash::make('P@55word'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'guru@smkn1.sch.id'],
            [
                'nama' => 'Guru Perpustakaan',
                'role' => User::ROLE_GURU,
                'status' => 1,
                'hp' => '081200000222',
                'password' => Hash::make('Guru12345'),
            ]
        );

        // Jalankan seeder tambahan
        $this->call([
            BookrentSeeder::class,
        ]);
    }
}
