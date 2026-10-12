<?php

namespace Database\Seeders;

use App\Models\Paket;
use Illuminate\Database\Seeder;

class PaketSeeder extends Seeder
{
    public function run(): void
    {
        $pakets = [
            [
                'nama_paket' => 'Royal Wedding Glamour',
                'slug' => 'royal-wedding-glamour',
                'kategori' => 'Pernikahan',
                'deskripsi' => 'Paket pernikahan mewah lengkap dengan dekorasi pelaminan modern, catering 500 porsi, wedding planner, MC profesional, dan live acoustic music.',
                'harga' => 35000000,
                'gambar' => 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'nama_paket' => 'Sweet Seventeen Celebration',
                'slug' => 'sweet-seventeen-celebration',
                'kategori' => 'Ulang Tahun',
                'deskripsi' => 'Perayaan ulang tahun spektakuler dengan tema neon party, backdrop 3D, sound system menggelegar, DJ performance, dan photobooth unlimited print 2 jam.',
                'harga' => 12500000,
                'gambar' => 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'nama_paket' => 'Corporate Seminar & Gathering',
                'slug' => 'corporate-seminar-gathering',
                'kategori' => 'Seminar',
                'deskripsi' => 'Paket pertemuan bisnis dan seminar kapasitas 200 orang. Sudah termasuk proyektor laser, seminar kit, audio wireless, coffee break, dan dokumentasi foto/video.',
                'harga' => 18000000,
                'gambar' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
            [
                'nama_paket' => 'Intimate Garden Wedding',
                'slug' => 'intimate-garden-wedding',
                'kategori' => 'Pernikahan',
                'deskripsi' => 'Konsep pernikahan outdoor hangat dan estetik di taman, kapasitas 150 tamu undangan dengan tata lampu fairy lights dan bunga segar.',
                'harga' => 22000000,
                'gambar' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
            ],
        ];

        foreach ($pakets as $paket) {
            Paket::create($paket);
        }
    }
}