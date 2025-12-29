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
        // Membuat satu user default untuk login
        User::create([
            'name' => 'Admin BUMA',
            'email' => 'admin@buma.test',
            'password' => Hash::make('password'), // Ganti 'password' dengan yang Anda inginkan
            'role' => 'admin', // Pastikan kolom 'role' ada di tabel 'users' Anda
        ]);
    }
}
