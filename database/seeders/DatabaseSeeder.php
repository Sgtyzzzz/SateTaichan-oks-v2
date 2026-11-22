<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Owner/Admin & Kasir (sudah verified)
        User::create([
            'name' => 'Owner Sate Taichan Oks',
            'email' => 'owner@taichanoks.com',
            'phone' => '081234567890',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Kasir Shift 1',
            'email' => 'kasir@taichanoks.com',
            'phone' => '081234567891',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
            'email_verified_at' => now(),
        ]);

        // 2. Data Meja (Meja 01 - Meja 08)
        for ($i = 1; $i <= 8; $i++) {
            Table::create([
                'table_number' => 'Meja 0' . $i,
                'capacity' => $i <= 4 ? 2 : 4,
                'status' => 'available',
            ]);
        }

        // 3. Menu sesuai desain Figma
        $menus = [
            ['name' => 'Thaichan Original', 'category' => 'Sate Favorit', 'badge' => 'PALING LARIS', 'description' => '10 tusuk ayam juicy, sambal korek, jeruk limau.', 'price' => 28000],
            ['name' => 'Thaichan Mozzarella', 'category' => 'Sate Favorit', 'badge' => 'FAVORIT BARU', 'description' => 'Gurih pedas dengan lelehan mozzarella creamy.', 'price' => 35000],
            ['name' => 'Kulit Kriuk Oks', 'category' => 'Sate Favorit', 'badge' => 'KRIUK MAKSIMAL', 'description' => 'Kulit ayam garing, asin gurih, sambal bawang.', 'price' => 26000],
            ['name' => 'Paket Thaichan Party', 'category' => 'Paket Hemat', 'badge' => 'HEMAT RP25.000', 'description' => '35 tusuk sate campur, 3 nasi hangat, dan 3 es teh.', 'price' => 119000],
            ['name' => 'Paket Sendiri Oks', 'category' => 'Paket Hemat', 'badge' => '1 ORANG', 'description' => '10 tusuk Thaichan Original, 1 nasi hangat, dan 1 es teh.', 'price' => 39000],
            ['name' => 'Paket Berdua Oks', 'category' => 'Paket Hemat', 'badge' => '2 ORANG', 'description' => '20 tusuk Thaichan Original, 2 nasi hangat, dan 2 es teh.', 'price' => 75000],
            ['name' => 'Nasi Hangat', 'category' => 'Pelengkap', 'badge' => null, 'description' => 'Pulen pas buat teman sate.', 'price' => 6000],
            ['name' => 'Lontong', 'category' => 'Pelengkap', 'badge' => null, 'description' => 'Irisan lontong lembut, 1 porsi.', 'price' => 6000],
            ['name' => 'Ekstra Sambal', 'category' => 'Pelengkap', 'badge' => null, 'description' => 'Sambal korek, sambal matah.', 'price' => 5000],
            ['name' => 'Es Teh Manis', 'category' => 'Minuman', 'badge' => null, 'description' => 'Teh segar manis pas.', 'price' => 7000],
            ['name' => 'Es Jeruk Limau', 'category' => 'Minuman', 'badge' => null, 'description' => 'Asam segar penyeimbang pedas.', 'price' => 12000],
            ['name' => 'Air Mineral', 'category' => 'Minuman', 'badge' => null, 'description' => 'Air mineral dingin 600 ml.', 'price' => 5000],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}