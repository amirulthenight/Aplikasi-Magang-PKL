<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User Kepala (Hanya bisa lihat laporan & edit profil)
        \App\Models\User::create([
            'name' => 'Bapak Kepala Divisi',
            'email' => 'kepala@buma.test',
            'password' => bcrypt('password'), // Password default
            'role' => 'kepala', // Pastikan kolom role sudah ada di tabel users
        ]);
    }
}
