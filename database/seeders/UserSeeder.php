<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'name' => 'Administrator EventKu',
            'email' => 'admin@eventku.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'telepon' => '081234567890',
            'alamat' => 'Jakarta, Indonesia',
        ]);

        // 2. Akun Customer untuk Uji Coba
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'customer@eventku.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'telepon' => '089876543210',
            'alamat' => 'Bandung, Jawa Barat',
        ]);
    }
}