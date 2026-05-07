<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name_category' => 'Fiksi'],
            ['name_category' => 'Non-Fiksi'],
            ['name_category' => 'Biografi'],
            ['name_category' => 'Sains'],
            ['name_category' => 'Sejarah'],
            ['name_category' => 'Teknologi'],
            ['name_category' => 'Seni'],
            ['name_category' => 'Olahraga'],
        ];

        foreach ($categories as $category) {
            Kategori::updateOrCreate(
                ['name_category' => $category['name_category']],
                $category
            );
        }
    }
}
