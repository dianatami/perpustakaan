<?php

namespace Database\Seeders;

use App\Models\Bookrent;
use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Seeder;

class BookrentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $books = Book::all();

        if ($users->isEmpty() || $books->isEmpty()) {
            return;
        }

        // Buat data peminjaman contoh
        $peminjamanData = [
            [
                'user_id' => $users->first()->id,
                'borrow_date' => now()->subDays(5),
                'return_date' => null,
                'status' => 'dipinjam',
            ],
            [
                'user_id' => $users->count() > 1 ? $users->get(1)->id : $users->first()->id,
                'borrow_date' => now()->subDays(10),
                'return_date' => now()->subDays(3),
                'status' => 'kembali',
            ],
            [
                'user_id' => $users->count() > 2 ? $users->get(2)->id : $users->first()->id,
                'borrow_date' => now()->subDays(2),
                'return_date' => null,
                'status' => 'dipinjam',
            ],
        ];

        foreach ($peminjamanData as $data) {
            Bookrent::create($data);
        }
    }
}
