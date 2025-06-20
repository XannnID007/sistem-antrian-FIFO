<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengaturan;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'pesantren_nama',
                'value' => 'Pondok Pesantren Salafiyah Al-Jawahir',
                'description' => 'Nama lengkap pesantren'
            ],
            [
                'key' => 'pesantren_alamat',
                'value' => 'Jl. Raya Pesantren No. 123, Bandung, Jawa Barat 40123',
                'description' => 'Alamat lengkap pesantren'
            ],
            [
                'key' => 'pesantren_telepon',
                'value' => '(022) 1234567',
                'description' => 'Nomor telepon pesantren'
            ],
            [
                'key' => 'pesantren_email',
                'value' => 'info@aljawahir.ac.id',
                'description' => 'Email resmi pesantren'
            ],
            [
                'key' => 'avg_visit_duration',
                'value' => '15',
                'description' => 'Rata-rata durasi kunjungan dalam menit'
            ],
            [
                'key' => 'max_concurrent_visits',
                'value' => '5',
                'description' => 'Maksimal kunjungan bersamaan'
            ],
            [
                'key' => 'notification_sound',
                'value' => 'true',
                'description' => 'Aktifkan notifikasi suara'
            ],
            [
                'key' => 'auto_refresh_interval',
                'value' => '30',
                'description' => 'Interval refresh otomatis dalam detik'
            ],
            [
                'key' => 'jam_operasional_display',
                'value' => 'Senin - Minggu: 08:00 - 16:00',
                'description' => 'Jam operasional untuk ditampilkan'
            ],
            [
                'key' => 'timezone',
                'value' => 'Asia/Jakarta',
                'description' => 'Zona waktu sistem'
            ]
        ];

        foreach ($settings as $setting) {
            Pengaturan::create($setting);
        }
    }
}
