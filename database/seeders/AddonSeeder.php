<?php

namespace Database\Seeders;

use App\Models\Addon;
use Illuminate\Database\Seeder;

class AddonSeeder extends Seeder
{
    public function run(): void
    {
        $addons = [
            ['nama_addon' => 'Drone Video Cinematic 4K', 'kategori' => 'Dokumentasi', 'harga' => 1500000],
            ['nama_addon' => 'Photobooth Unlimited 3 Jam', 'kategori' => 'Dokumentasi', 'harga' => 2500000],
            ['nama_addon' => 'Live Band Acoustic Full Band', 'kategori' => 'Entertainment', 'harga' => 3000000],
            ['nama_addon' => 'Master of Ceremony (MC) Bilingual', 'kategori' => 'Entertainment', 'harga' => 1200000],
            ['nama_addon' => 'Dry Ice & Pyro Effect (Stage)', 'kategori' => 'Efek Panggung', 'harga' => 1000000],
            ['nama_addon' => 'Extra Sound System 5000 Watt', 'kategori' => 'Audio', 'harga' => 1800000],
        ];

        foreach ($addons as $addon) {
            Addon::create($addon);
        }
    }
}