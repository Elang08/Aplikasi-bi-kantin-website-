<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['username' => 'admin_kantin'],
            ['password' => Hash::make('admin123'), 'role' => 'admin'],
        );

        User::query()->firstOrCreate(
            ['username' => 'budi_siswa'],
            ['password' => Hash::make('123'), 'role' => 'siswa'],
        );

        User::query()->firstOrCreate(
            ['username' => 'kasir_kantin'],
            ['password' => Hash::make('kasir12345'), 'role' => 'kasir'],
        );

        Menu::query()->firstOrCreate(
            ['nama_menu' => 'Nasi Goreng Spesial'],
            ['harga' => 15000, 'kategori' => 'Makanan'],
        );

        Menu::query()->firstOrCreate(
            ['nama_menu' => 'Es Teh Manis'],
            ['harga' => 4000, 'kategori' => 'Minuman'],
        );
    }
}
