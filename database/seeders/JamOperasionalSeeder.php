<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JamOperasional;

class JamOperasionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jadwal = [
            [
                'hari' => 'senin',
                'jam_buka' => '08:00:00',
                'jam_tutup' => '16:00:00',
                'is_active' => true,
            ],
            [
                'hari' => 'selasa',
                'jam_buka' => '08:00:00',
                'jam_tutup' => '16:00:00',
                'is_active' => true,
            ],
            [
                'hari' => 'rabu',
                'jam_buka' => '08:00:00',
                'jam_tutup' => '16:00:00',
                'is_active' => true,
            ],
            [
                'hari' => 'kamis',
                'jam_buka' => '08:00:00',
                'jam_tutup' => '16:00:00',
                'is_active' => true,
            ],
            [
                'hari' => 'jumat',
                'jam_buka' => '08:00:00',
                'jam_tutup' => '11:30:00',
                'is_active' => true,
            ],
            [
                'hari' => 'sabtu',
                'jam_buka' => '08:00:00',
                'jam_tutup' => '16:00:00',
                'is_active' => true,
            ],
            [
                'hari' => 'minggu',
                'jam_buka' => '08:00:00',
                'jam_tutup' => '16:00:00',
                'is_active' => true,
            ],
        ];

        foreach ($jadwal as $jam) {
            JamOperasional::create($jam);
        }
    }
}
