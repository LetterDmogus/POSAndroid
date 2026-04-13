<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Makanan', 'Minuman', 'Snack', 'Lain-lain'];

        foreach ($categories as $cat) {
            Category::create([
                'nama_kategori' => $cat,
                'slug' => Str::slug($cat),
            ]);
        }
    }
}
