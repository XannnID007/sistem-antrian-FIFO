<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Santri;
use Carbon\Carbon;

class SantriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $santriData = [
            [
                'nama' => 'Muhammad Ridwan',
                'nim' => 'S001',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2005-01-15',
                'alamat' => 'Jl. Merdeka No. 123, Bandung',
                'nama_wali' => 'Abdul Rahman',
                'phone_wali' => '081234567890',
                'kamar' => 'A-101',
                'tahun_masuk' => 2023,
            ],
            [
                'nama' => 'Ahmad Fadhil',
                'nim' => 'S002',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2004-03-20',
                'alamat' => 'Jl. Kebon Jeruk No. 45, Jakarta',
                'nama_wali' => 'Usman bin Affan',
                'phone_wali' => '081234567891',
                'kamar' => 'A-102',
                'tahun_masuk' => 2022,
            ],
            [
                'nama' => 'Zaid ibn Haritsah',
                'nim' => 'S003',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2005-07-10',
                'alamat' => 'Jl. Pemuda No. 67, Surabaya',
                'nama_wali' => 'Ali ibn Abi Thalib',
                'phone_wali' => '081234567892',
                'kamar' => 'A-103',
                'tahun_masuk' => 2023,
            ],
            [
                'nama' => 'Omar Al-Farisi',
                'nim' => 'S004',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '2004-12-05',
                'alamat' => 'Jl. Malioboro No. 89, Yogyakarta',
                'nama_wali' => 'Abu Bakar As-Siddiq',
                'phone_wali' => '081234567893',
                'kamar' => 'B-201',
                'tahun_masuk' => 2022,
            ],
            [
                'nama' => 'Khalid ibn Walid',
                'nim' => 'S005',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '2005-05-25',
                'alamat' => 'Jl. Sisingamangaraja No. 12, Medan',
                'nama_wali' => 'Sa\'ad ibn Abi Waqqas',
                'phone_wali' => '081234567894',
                'kamar' => 'B-202',
                'tahun_masuk' => 2023,
            ],
            [
                'nama' => 'Anas ibn Malik',
                'nim' => 'S006',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '2004-09-18',
                'alamat' => 'Jl. Pandanaran No. 34, Semarang',
                'nama_wali' => 'Abdurrahman ibn Auf',
                'phone_wali' => '081234567895',
                'kamar' => 'B-203',
                'tahun_masuk' => 2022,
            ],
            [
                'nama' => 'Mu\'adz ibn Jabal',
                'nim' => 'S007',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Palembang',
                'tanggal_lahir' => '2005-11-30',
                'alamat' => 'Jl. Sudirman No. 56, Palembang',
                'nama_wali' => 'Talhah ibn Ubaidillah',
                'phone_wali' => '081234567896',
                'kamar' => 'C-301',
                'tahun_masuk' => 2023,
            ],
            [
                'nama' => 'Abu Ubaidah',
                'nim' => 'S008',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Makassar',
                'tanggal_lahir' => '2004-04-14',
                'alamat' => 'Jl. Pettarani No. 78, Makassar',
                'nama_wali' => 'Az-Zubair ibn Awwam',
                'phone_wali' => '081234567897',
                'kamar' => 'C-302',
                'tahun_masuk' => 2022,
            ],
            [
                'nama' => 'Bilal ibn Rabah',
                'nim' => 'S009',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Denpasar',
                'tanggal_lahir' => '2005-08-22',
                'alamat' => 'Jl. Gajah Mada No. 90, Denpasar',
                'nama_wali' => 'Sa\'id ibn Zaid',
                'phone_wali' => '081234567898',
                'kamar' => 'C-303',
                'tahun_masuk' => 2023,
            ],
            [
                'nama' => 'Salman Al-Farisi',
                'nim' => 'S010',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Banjarmasin',
                'tanggal_lahir' => '2004-06-08',
                'alamat' => 'Jl. A. Yani No. 11, Banjarmasin',
                'nama_wali' => 'Abu Hurairah',
                'phone_wali' => '081234567899',
                'kamar' => 'D-401',
                'tahun_masuk' => 2022,
            ]
        ];

        foreach ($santriData as $data) {
            Santri::create($data);
        }
    }
}
