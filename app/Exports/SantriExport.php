<?php

namespace App\Exports;

use App\Models\Santri;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SantriExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
     protected $status;
     protected $tahunMasuk;
     protected $jenisKelamin;

     public function __construct($status = null, $tahunMasuk = null, $jenisKelamin = null)
     {
          $this->status = $status;
          $this->tahunMasuk = $tahunMasuk;
          $this->jenisKelamin = $jenisKelamin;
     }

     public function collection()
     {
          $query = Santri::withCount('kunjungan');

          if ($this->status !== null) {
               $isActive = $this->status === 'active';
               $query->where('is_active', $isActive);
          }

          if ($this->tahunMasuk) {
               $query->where('tahun_masuk', $this->tahunMasuk);
          }

          if ($this->jenisKelamin) {
               $query->where('jenis_kelamin', $this->jenisKelamin);
          }

          return $query->latest()->get();
     }

     public function headings(): array
     {
          return [
               'No',
               'NIM',
               'Nama',
               'Jenis Kelamin',
               'Tempat Lahir',
               'Tanggal Lahir',
               'Umur',
               'Alamat',
               'Nama Wali',
               'Telepon Wali',
               'Kamar',
               'Tahun Masuk',
               'Status',
               'Total Kunjungan',
               'Tanggal Daftar'
          ];
     }

     public function map($santri): array
     {
          static $no = 1;

          return [
               $no++,
               $santri->nim,
               $santri->nama,
               $santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
               $santri->tempat_lahir,
               $santri->tanggal_lahir->format('d/m/Y'),
               $santri->umur . ' tahun',
               $santri->alamat,
               $santri->nama_wali,
               $santri->phone_wali,
               $santri->kamar ?? '-',
               $santri->tahun_masuk,
               $santri->is_active ? 'Aktif' : 'Tidak Aktif',
               $santri->kunjungan_count,
               $santri->created_at->format('d/m/Y H:i')
          ];
     }

     public function styles(Worksheet $sheet)
     {
          return [
               1 => [
                    'font' => ['bold' => true],
                    'fill' => [
                         'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                         'startColor' => ['rgb' => 'E5E7EB']
                    ]
               ],
          ];
     }

     public function title(): string
     {
          return 'Data Santri';
     }
}
