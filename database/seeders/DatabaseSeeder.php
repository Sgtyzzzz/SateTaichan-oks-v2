<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Menu;
use App\Models\Table;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Default
        User::create([
            'name' => 'Admin Taichan',
            'email' => 'bbareng27@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Kasir Shift 1',
            'email' => 'psigitn12@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
            'email_verified_at' => now(),
        ]);

        // Data Meja
        for ($i = 1; $i <= 8; $i++) {
            Table::create([
                'table_number' => 'M-0' . $i,
                'capacity' => $i <= 4 ? 2 : 4,
                'status' => 'available',
            ]);
        }

        // Data Menu
        Menu::insert([
            ['name' => 'Sate Taichan Dada (10 Tusuk)', 'description' => 'Daging dada ayam bakar gurih dengan sambal pedas & jeruk nipis', 'price' => 25000, 'category' => 'sate', 'stock' => 50, 'is_available' => true],
            ['name' => 'Sate Taichan Kulit (10 Tusuk)', 'description' => 'Kulit ayam crispy bakar bumbu gurih', 'price' => 28000, 'category' => 'sate', 'stock' => 40, 'is_available' => true],
            ['name' => 'Sate Taichan Campur (Dada + Kulit)', 'description' => '5 tusuk dada + 5 tusuk kulit', 'price' => 27000, 'category' => 'sate', 'stock' => 45, 'is_available' => true],
            ['name' => 'Lontong', 'description' => 'Lontong lembut pendamping sate', 'price' => 5000, 'category' => 'pelengkap', 'stock' => 100, 'is_available' => true],
            ['name' => 'Es Teh Manis', 'description' => 'Es teh manis segar pereda pedas', 'price' => 6000, 'category' => 'minuman', 'stock' => 100, 'is_available' => true],
        ]);
    }
}