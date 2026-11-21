<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        DB::table('users')->insert([
            ['nama_lengkap' => 'Administrator', 'username' => 'admin',   'password' => Hash::make('admin123'),   'role' => 'admin',   'created_at' => now(), 'updated_at' => now()],
            ['nama_lengkap' => 'Kasir Utama',   'username' => 'kasir',   'password' => Hash::make('kasir123'),   'role' => 'kasir',   'created_at' => now(), 'updated_at' => now()],
            ['nama_lengkap' => 'Staf Dapur',    'username' => 'kitchen', 'password' => Hash::make('kitchen123'), 'role' => 'kitchen', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Menu
        DB::table('menus')->insert([
            ['nama' => 'Ayam Goreng Crispy',  'kategori' => 'Makanan',  'harga' => 15000, 'stok' => 18, 'emoji' => '🍗', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Burger Sapi Spesial', 'kategori' => 'Makanan',  'harga' => 25000, 'stok' => 0,  'emoji' => '🍔', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Kentang Goreng',      'kategori' => 'Camilan',  'harga' => 10000, 'stok' => 0,  'emoji' => '🍟', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Es Teh Manis',        'kategori' => 'Minuman',  'harga' => 5000,  'stok' => 9,  'emoji' => '🥤', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Kopi Susu Dingin',    'kategori' => 'Minuman',  'harga' => 12000, 'stok' => 10, 'emoji' => '☕', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Es Krim Sundae',      'kategori' => 'Dessert',  'harga' => 8000,  'stok' => 10, 'emoji' => '🍦', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Spaghetti Bolognese', 'kategori' => 'Makanan',  'harga' => 25000, 'stok' => 8,  'emoji' => '🍝', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
