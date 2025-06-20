<?php

namespace App\Exports;

use App\Models\Kunjungan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KunjunganExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
     protected $tanggalMulai;
     protected $tanggalSelesai;
     protected $status;

     public function __construct($tanggalMulai = null, $tanggalSelesai = null, $status = null)
     {
          $this->tanggalMulai = $tanggalMulai;
          $this->tanggalSelesai = $tanggalSelesai;
          $this->status = $status;
     }

     public function collection()
     {
          $query = Kunjungan::with(['santri', 'admin']);

          if ($this->tanggalMulai) {
               $query->whereDate('waktu_daftar', '>=', $this->tanggalMulai);
          }

          if ($this->tanggalSelesai) {
               $query->whereDate('waktu_daftar', '<=', $this->tanggalSelesai);
          }

          if ($this->status) {
               $query->where('status', $this->status);
          }

          return $query->latest('waktu_daftar')->get();
     }

     public function headings(): array
     {
          return [
               'No',
               'Tanggal',
               'Nomor Antrian',
               'Nama Pengunjung',
               'Hubungan',
               'Telepon',
               'Alamat',
               'Nama Santri',
               'NIM',
               'Status',
               'Waktu Daftar',
               'Waktu Panggil',
               'Waktu Mulai',
               'Waktu Selesai',
               'Waktu Tunggu (menit)',
               'Durasi Kunjungan (menit)',
               'Admin',
               'Catatan'
          ];
     }

     public function map($kunjungan): array
     {
          static $no = 1;

          return [
               $no++,
               $kunjungan->waktu_daftar->format('d/m/Y'),
               $kunjungan->nomor_antrian,
               $kunjungan->nama_pengunjung,
               $kunjungan->hubungan,
               $kunjungan->phone_pengunjung,
               $kunjungan->alamat_pengunjung,
               $kunjungan->santri->nama,
               $kunjungan->santri->nim,
               ucfirst($kunjungan->status),
               $kunjungan->waktu_daftar->format('d/m/Y H:i'),
               $kunjungan->waktu_panggil ? $kunjungan->waktu_panggil->format('d/m/Y H:i') : '-',
               $kunjungan->waktu_mulai ? $kunjungan->waktu_mulai->format('d/m/Y H:i') : '-',
               $kunjungan->waktu_selesai ? $kunjungan->waktu_selesai->format('d/m/Y H:i') : '-',
               $kunjungan->waktu_tunggu ?? '-',
               $kunjungan->durasi_kunjungan ?? '-',
               $kunjungan->admin->name,
               $kunjungan->catatan ?? '-'
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
          return 'Laporan Kunjungan';
     }
}
