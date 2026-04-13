<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Barang;
use App\Models\Category;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $minuman = Category::where('nama_kategori', 'Minuman')->first();
        $makanan = Category::where('nama_kategori', 'Makanan')->first();

        Barang::create([
            'sku' => 'MNM-001',
            'nama_barang' => 'Kopi Susu Gula Aren',
            'deskripsi' => 'Kopi susu dengan gula aren pilihan.',
            'harga_beli' => 10000,
            'harga_jual' => 18000,
            'stok' => 50,
            'satuan' => 'cup',
            'category_id' => $minuman->id,
            'is_active' => true,
        ]);

        Barang::create([
            'sku' => 'MKN-001',
            'nama_barang' => 'Nasi Goreng Spesial',
            'deskripsi' => 'Nasi goreng dengan telur dan ayam.',
            'harga_beli' => 15000,
            'harga_jual' => 25000,
            'stok' => 20,
            'satuan' => 'porsi',
            'category_id' => $makanan->id,
            'is_active' => true,
        ]);

        Barang::create([
            'sku' => 'MNM-002',
            'nama_barang' => 'Es Teh Manis',
            'deskripsi' => 'Teh melati dingin dengan gula asli.',
            'harga_beli' => 2000,
            'harga_jual' => 5000,
            'stok' => 100,
            'satuan' => 'gelas',
            'category_id' => $minuman->id,
            'is_active' => true,
        ]);
    }
}
