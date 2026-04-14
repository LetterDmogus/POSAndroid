<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Akun Admin
        User::create([
            'name' => 'Admin POS',
            'email' => 'admin@pos.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Akun Kasir
        User::create([
            'name' => 'Kasir Toko',
            'email' => 'kasir@pos.com',
            'password' => Hash::make('kasir123'),
            'role' => 'kasir',
        ]);
    }
}
