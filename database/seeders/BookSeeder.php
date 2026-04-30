<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriMap = Kategori::all()->keyBy('name_category');

        $books = [
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'year' => '2005',
                'book_code' => 'BK001',
                'category_id' => $kategoriMap['Fiksi']?->id ?? 1,
                'stock' => 5,
                'description' => 'Novel tentang perjuangan anak-anak di Kepulauan Belitong',
            ],
            [
                'title' => 'Sejarah Indonesia',
                'author' => 'Soekarno',
                'publisher' => 'Gramedia',
                'year' => '2010',
                'book_code' => 'BK002',
                'category_id' => $kategoriMap['Sejarah']?->id ?? 5,
                'stock' => 3,
                'description' => 'Buku referensi sejarah Indonesia lengkap',
            ],
            [
                'title' => 'Fisika Modern',
                'author' => 'Dr. Bambang Setiawan',
                'publisher' => 'Erlangga',
                'year' => '2015',
                'book_code' => 'BK003',
                'category_id' => $kategoriMap['Sains']?->id ?? 4,
                'stock' => 4,
                'description' => 'Buku pelajaran fisika untuk tingkat atas',
            ],
            [
                'title' => 'Pemrograman Python',
                'author' => 'Mark Lutz',
                'publisher' => 'O\'Reilly',
                'year' => '2018',
                'book_code' => 'BK004',
                'category_id' => $kategoriMap['Teknologi']?->id ?? 6,
                'stock' => 2,
                'description' => 'Panduan lengkap belajar Python',
            ],
            [
                'title' => 'Biografi Steve Jobs',
                'author' => 'Walter Isaacson',
                'publisher' => 'Gramedia',
                'year' => '2011',
                'book_code' => 'BK005',
                'category_id' => $kategoriMap['Biografi']?->id ?? 3,
                'stock' => 3,
                'description' => 'Kisah hidup dan karir Steve Jobs',
            ],
        ];

        foreach ($books as $book) {
            Book::updateOrCreate(
                ['book_code' => $book['book_code']],
                $book
            );
        }
    }
}
