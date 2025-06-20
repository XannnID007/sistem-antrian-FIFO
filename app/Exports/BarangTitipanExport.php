<?php

namespace App\Exports;

use App\Models\BarangTitipan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BarangTitipanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
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
          $query = BarangTitipan::with(['kunjungan.santri', 'adminPenerima', 'adminPenyerah']);

          if ($this->tanggalMulai) {
               $query->whereDate('waktu_dititipkan', '>=', $this->tanggalMulai);
          }

          if ($this->tanggalSelesai) {
               $query->whereDate('waktu_dititipkan', '<=', $this->tanggalSelesai);
          }

          if ($this->status) {
               $query->where('status', $this->status);
          }

          return $query->latest('waktu_dititipkan')->get();
     }

     public function headings(): array
     {
          return [
               'No',
               'Tanggal',
               'Kode Barang',
               'Nama Barang',
               'Deskripsi',
               'Jumlah',
               'Nomor Antrian',
               'Nama Pengunjung',
               'Hubungan',
               'Nama Santri',
               'NIM',
               'Status',
               'Waktu Dititipkan',
               'Waktu Diserahkan',
               'Waktu Diambil',
               'Admin Penerima',
               'Admin Penyerah',
               'Catatan'
          ];
     }

     public function map($barang): array
     {
          static $no = 1;

          return [
               $no++,
               $barang->waktu_dititipkan->format('d/m/Y'),
               $barang->kode_barang,
               $barang->nama_barang,
               $barang->deskripsi ?? '-',
               $barang->jumlah,
               $barang->kunjungan->nomor_antrian,
               $barang->kunjungan->nama_pengunjung,
               $barang->kunjungan->hubungan,
               $barang->kunjungan->santri->nama,
               $barang->kunjungan->santri->nim,
               ucfirst($barang->status),
               $barang->waktu_dititipkan->format('d/m/Y H:i'),
               $barang->waktu_diserahkan ? $barang->waktu_diserahkan->format('d/m/Y H:i') : '-',
               $barang->waktu_diambil ? $barang->waktu_diambil->format('d/m/Y H:i') : '-',
               $barang->adminPenerima->name,
               $barang->adminPenyerah ? $barang->adminPenyerah->name : '-',
               $barang->catatan ?? '-'
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
          return 'Laporan Barang Titipan';
     }
}
