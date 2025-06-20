<?php

namespace App\Services;

use App\Models\Kunjungan;
use App\Models\BarangTitipan;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Dompdf\Dompdf;
use Dompdf\Options;

class ExportService
{
     /**
      * Export kunjungan to Excel
      */
     public function exportKunjunganExcel($tanggalMulai, $tanggalSelesai, $status = null)
     {
          $spreadsheet = new Spreadsheet();
          $sheet = $spreadsheet->getActiveSheet();

          // Set document properties
          $spreadsheet->getProperties()
               ->setCreator('Sistem Kunjungan Santri')
               ->setTitle('Laporan Kunjungan')
               ->setSubject('Data Kunjungan Santri')
               ->setDescription('Laporan data kunjungan santri periode ' . $tanggalMulai . ' s/d ' . $tanggalSelesai);

          // Header
          $sheet->setCellValue('A1', 'LAPORAN KUNJUNGAN SANTRI');
          $sheet->setCellValue('A2', 'Pondok Pesantren Salafiyah Al-Jawahir');
          $sheet->setCellValue('A3', 'Periode: ' . Carbon::parse($tanggalMulai)->format('d/m/Y') . ' - ' . Carbon::parse($tanggalSelesai)->format('d/m/Y'));

          // Style header
          $sheet->mergeCells('A1:H1');
          $sheet->mergeCells('A2:H2');
          $sheet->mergeCells('A3:H3');
          $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
          $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);

          // Column headers
          $headers = [
               'A5' => 'No',
               'B5' => 'Tanggal',
               'C5' => 'Nomor Antrian',
               'D5' => 'Nama Pengunjung',
               'E5' => 'Hubungan',
               'F5' => 'Nama Santri',
               'G5' => 'Status',
               'H5' => 'Admin'
          ];

          foreach ($headers as $cell => $value) {
               $sheet->setCellValue($cell, $value);
          }

          // Style column headers
          $sheet->getStyle('A5:H5')->getFont()->setBold(true);
          $sheet->getStyle('A5:H5')->getFill()
               ->setFillType(Fill::FILL_SOLID)
               ->getStartColor()->setRGB('E2E8F0');

          // Get data
          $query = Kunjungan::with(['santri', 'admin'])
               ->whereDate('waktu_daftar', '>=', $tanggalMulai)
               ->whereDate('waktu_daftar', '<=', $tanggalSelesai);

          if ($status) {
               $query->where('status', $status);
          }

          $kunjungan = $query->latest('waktu_daftar')->get();

          // Fill data
          $row = 6;
          foreach ($kunjungan as $index => $visit) {
               $sheet->setCellValue('A' . $row, $index + 1);
               $sheet->setCellValue('B' . $row, $visit->waktu_daftar->format('d/m/Y'));
               $sheet->setCellValue('C' . $row, $visit->nomor_antrian);
               $sheet->setCellValue('D' . $row, $visit->nama_pengunjung);
               $sheet->setCellValue('E' . $row, $visit->hubungan);
               $sheet->setCellValue('F' . $row, $visit->santri->nama);
               $sheet->setCellValue('G' . $row, ucfirst($visit->status));
               $sheet->setCellValue('H' . $row, $visit->admin->name);
               $row++;
          }

          // Add borders
          $lastRow = $row - 1;
          $sheet->getStyle('A5:H' . $lastRow)->getBorders()->getAllBorders()
               ->setBorderStyle(Border::BORDER_THIN);

          // Auto-size columns
          foreach (range('A', 'H') as $column) {
               $sheet->getColumnDimension($column)->setAutoSize(true);
          }

          // Generate file
          $writer = new Xlsx($spreadsheet);
          $filename = 'laporan_kunjungan_' . date('YmdHis') . '.xlsx';
          $tempFile = tempnam(sys_get_temp_dir(), $filename);
          $writer->save($tempFile);

          return [
               'file' => $tempFile,
               'filename' => $filename,
               'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
          ];
     }

     /**
      * Export barang titipan to Excel
      */
     public function exportBarangTitipanExcel($tanggalMulai, $tanggalSelesai, $status = null)
     {
          $spreadsheet = new Spreadsheet();
          $sheet = $spreadsheet->getActiveSheet();

          // Set document properties
          $spreadsheet->getProperties()
               ->setCreator('Sistem Kunjungan Santri')
               ->setTitle('Laporan Barang Titipan')
               ->setSubject('Data Barang Titipan')
               ->setDescription('Laporan data barang titipan periode ' . $tanggalMulai . ' s/d ' . $tanggalSelesai);

          // Header
          $sheet->setCellValue('A1', 'LAPORAN BARANG TITIPAN');
          $sheet->setCellValue('A2', 'Pondok Pesantren Salafiyah Al-Jawahir');
          $sheet->setCellValue('A3', 'Periode: ' . Carbon::parse($tanggalMulai)->format('d/m/Y') . ' - ' . Carbon::parse($tanggalSelesai)->format('d/m/Y'));

          // Style header
          $sheet->mergeCells('A1:I1');
          $sheet->mergeCells('A2:I2');
          $sheet->mergeCells('A3:I3');
          $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
          $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);

          // Column headers
          $headers = [
               'A5' => 'No',
               'B5' => 'Tanggal',
               'C5' => 'Kode Barang',
               'D5' => 'Nama Barang',
               'E5' => 'Jumlah',
               'F5' => 'Pengunjung',
               'G5' => 'Santri',
               'H5' => 'Status',
               'I5' => 'Admin'
          ];

          foreach ($headers as $cell => $value) {
               $sheet->setCellValue($cell, $value);
          }

          // Style column headers
          $sheet->getStyle('A5:I5')->getFont()->setBold(true);
          $sheet->getStyle('A5:I5')->getFill()
               ->setFillType(Fill::FILL_SOLID)
               ->getStartColor()->setRGB('E2E8F0');

          // Get data
          $query = BarangTitipan::with(['kunjungan.santri', 'adminPenerima'])
               ->whereDate('waktu_dititipkan', '>=', $tanggalMulai)
               ->whereDate('waktu_dititipkan', '<=', $tanggalSelesai);

          if ($status) {
               $query->where('status', $status);
          }

          $barangTitipan = $query->latest('waktu_dititipkan')->get();

          // Fill data
          $row = 6;
          foreach ($barangTitipan as $index => $barang) {
               $sheet->setCellValue('A' . $row, $index + 1);
               $sheet->setCellValue('B' . $row, $barang->waktu_dititipkan->format('d/m/Y'));
               $sheet->setCellValue('C' . $row, $barang->kode_barang);
               $sheet->setCellValue('D' . $row, $barang->nama_barang);
               $sheet->setCellValue('E' . $row, $barang->jumlah);
               $sheet->setCellValue('F' . $row, $barang->kunjungan->nama_pengunjung);
               $sheet->setCellValue('G' . $row, $barang->kunjungan->santri->nama);
               $sheet->setCellValue('H' . $row, ucfirst($barang->status));
               $sheet->setCellValue('I' . $row, $barang->adminPenerima->name);
               $row++;
          }

          // Add borders
          $lastRow = $row - 1;
          $sheet->getStyle('A5:I' . $lastRow)->getBorders()->getAllBorders()
               ->setBorderStyle(Border::BORDER_THIN);

          // Auto-size columns
          foreach (range('A', 'I') as $column) {
               $sheet->getColumnDimension($column)->setAutoSize(true);
          }

          // Generate file
          $writer = new Xlsx($spreadsheet);
          $filename = 'laporan_barang_titipan_' . date('YmdHis') . '.xlsx';
          $tempFile = tempnam(sys_get_temp_dir(), $filename);
          $writer->save($tempFile);

          return [
               'file' => $tempFile,
               'filename' => $filename,
               'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
          ];
     }

     /**
      * Export kunjungan to PDF
      */
     public function exportKunjunganPdf($tanggalMulai, $tanggalSelesai, $status = null)
     {
          // Get data
          $query = Kunjungan::with(['santri', 'admin'])
               ->whereDate('waktu_daftar', '>=', $tanggalMulai)
               ->whereDate('waktu_daftar', '<=', $tanggalSelesai);

          if ($status) {
               $query->where('status', $status);
          }

          $kunjungan = $query->latest('waktu_daftar')->get();

          // Generate HTML
          $html = $this->generateKunjunganPdfHtml($kunjungan, $tanggalMulai, $tanggalSelesai);

          // Create PDF
          $options = new Options();
          $options->set('defaultFont', 'Arial');
          $options->set('isHtml5ParserEnabled', true);
          $options->set('isPhpEnabled', true);

          $dompdf = new Dompdf($options);
          $dompdf->loadHtml($html);
          $dompdf->setPaper('A4', 'landscape');
          $dompdf->render();

          $filename = 'laporan_kunjungan_' . date('YmdHis') . '.pdf';
          $tempFile = tempnam(sys_get_temp_dir(), $filename);
          file_put_contents($tempFile, $dompdf->output());

          return [
               'file' => $tempFile,
               'filename' => $filename,
               'mime' => 'application/pdf'
          ];
     }

     /**
      * Export barang titipan to PDF
      */
     public function exportBarangTitipanPdf($tanggalMulai, $tanggalSelesai, $status = null)
     {
          // Get data
          $query = BarangTitipan::with(['kunjungan.santri', 'adminPenerima'])
               ->whereDate('waktu_dititipkan', '>=', $tanggalMulai)
               ->whereDate('waktu_dititipkan', '<=', $tanggalSelesai);

          if ($status) {
               $query->where('status', $status);
          }

          $barangTitipan = $query->latest('waktu_dititipkan')->get();

          // Generate HTML
          $html = $this->generateBarangTitipanPdfHtml($barangTitipan, $tanggalMulai, $tanggalSelesai);

          // Create PDF
          $options = new Options();
          $options->set('defaultFont', 'Arial');
          $options->set('isHtml5ParserEnabled', true);
          $options->set('isPhpEnabled', true);

          $dompdf = new Dompdf($options);
          $dompdf->loadHtml($html);
          $dompdf->setPaper('A4', 'landscape');
          $dompdf->render();

          $filename = 'laporan_barang_titipan_' . date('YmdHis') . '.pdf';
          $tempFile = tempnam(sys_get_temp_dir(), $filename);
          file_put_contents($tempFile, $dompdf->output());

          return [
               'file' => $tempFile,
               'filename' => $filename,
               'mime' => 'application/pdf'
          ];
     }

     /**
      * Generate HTML for kunjungan PDF
      */
     private function generateKunjunganPdfHtml($kunjungan, $tanggalMulai, $tanggalSelesai)
     {
          $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Laporan Kunjungan</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 12px; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h1 { margin: 0; font-size: 18px; }
                .header h2 { margin: 5px 0; font-size: 14px; }
                .header p { margin: 5px 0; font-size: 12px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th, td { border: 1px solid #000; padding: 5px; text-align: left; }
                th { background-color: #f0f0f0; font-weight: bold; }
                .text-center { text-align: center; }
                .footer { margin-top: 20px; font-size: 10px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>LAPORAN KUNJUNGAN SANTRI</h1>
                <h2>Pondok Pesantren Salafiyah Al-Jawahir</h2>
                <p>Periode: ' . Carbon::parse($tanggalMulai)->format('d/m/Y') . ' - ' . Carbon::parse($tanggalSelesai)->format('d/m/Y') . '</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Tanggal</th>
                        <th width="12%">No. Antrian</th>
                        <th width="20%">Pengunjung</th>
                        <th width="12%">Hubungan</th>
                        <th width="20%">Santri</th>
                        <th width="10%">Status</th>
                        <th width="11%">Admin</th>
                    </tr>
                </thead>
                <tbody>';

          foreach ($kunjungan as $index => $visit) {
               $html .= '<tr>
                <td class="text-center">' . ($index + 1) . '</td>
                <td>' . $visit->waktu_daftar->format('d/m/Y') . '</td>
                <td>' . $visit->nomor_antrian . '</td>
                <td>' . $visit->nama_pengunjung . '</td>
                <td>' . $visit->hubungan . '</td>
                <td>' . $visit->santri->nama . '</td>
                <td>' . ucfirst($visit->status) . '</td>
                <td>' . $visit->admin->name . '</td>
            </tr>';
          }

          $html .= '
                </tbody>
            </table>
            
            <div class="footer">
                <p>Total Data: ' . $kunjungan->count() . ' kunjungan</p>
                <p>Dicetak pada: ' . now()->format('d F Y, H:i:s') . '</p>
            </div>
        </body>
        </html>';

          return $html;
     }

     /**
      * Generate HTML for barang titipan PDF
      */
     private function generateBarangTitipanPdfHtml($barangTitipan, $tanggalMulai, $tanggalSelesai)
     {
          $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Laporan Barang Titipan</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 12px; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h1 { margin: 0; font-size: 18px; }
                .header h2 { margin: 5px 0; font-size: 14px; }
                .header p { margin: 5px 0; font-size: 12px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th, td { border: 1px solid #000; padding: 5px; text-align: left; }
                th { background-color: #f0f0f0; font-weight: bold; }
                .text-center { text-align: center; }
                .footer { margin-top: 20px; font-size: 10px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>LAPORAN BARANG TITIPAN</h1>
                <h2>Pondok Pesantren Salafiyah Al-Jawahir</h2>
                <p>Periode: ' . Carbon::parse($tanggalMulai)->format('d/m/Y') . ' - ' . Carbon::parse($tanggalSelesai)->format('d/m/Y') . '</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Tanggal</th>
                        <th width="12%">Kode</th>
                        <th width="18%">Barang</th>
                        <th width="8%">Jml</th>
                        <th width="17%">Pengunjung</th>
                        <th width="17%">Santri</th>
                        <th width="8%">Status</th>
                        <th width="5%">Admin</th>
                    </tr>
                </thead>
                <tbody>';

          foreach ($barangTitipan as $index => $barang) {
               $html .= '<tr>
                <td class="text-center">' . ($index + 1) . '</td>
                <td>' . $barang->waktu_dititipkan->format('d/m/Y') . '</td>
                <td>' . $barang->kode_barang . '</td>
                <td>' . $barang->nama_barang . '</td>
                <td class="text-center">' . $barang->jumlah . '</td>
                <td>' . $barang->kunjungan->nama_pengunjung . '</td>
                <td>' . $barang->kunjungan->santri->nama . '</td>
                <td>' . ucfirst($barang->status) . '</td>
                <td>' . $barang->adminPenerima->name . '</td>
            </tr>';
          }

          $html .= '
                </tbody>
            </table>
            
            <div class="footer">
                <p>Total Data: ' . $barangTitipan->count() . ' barang titipan</p>
                <p>Dicetak pada: ' . now()->format('d F Y, H:i:s') . '</p>
            </div>
        </body>
        </html>';

          return $html;
     }

     /**
      * Export to CSV
      */
     public function exportKunjunganCsv($tanggalMulai, $tanggalSelesai, $status = null)
     {
          // Get data
          $query = Kunjungan::with(['santri', 'admin'])
               ->whereDate('waktu_daftar', '>=', $tanggalMulai)
               ->whereDate('waktu_daftar', '<=', $tanggalSelesai);

          if ($status) {
               $query->where('status', $status);
          }

          $kunjungan = $query->latest('waktu_daftar')->get();

          // Generate CSV
          $filename = 'laporan_kunjungan_' . date('YmdHis') . '.csv';
          $tempFile = tempnam(sys_get_temp_dir(), $filename);
          $file = fopen($tempFile, 'w');

          // Add BOM for UTF-8
          fwrite($file, "\xEF\xBB\xBF");

          // Header
          fputcsv($file, [
               'No',
               'Tanggal',
               'Nomor Antrian',
               'Nama Pengunjung',
               'Hubungan',
               'Nama Santri',
               'Status',
               'Admin'
          ]);

          // Data
          foreach ($kunjungan as $index => $visit) {
               fputcsv($file, [
                    $index + 1,
                    $visit->waktu_daftar->format('d/m/Y'),
                    $visit->nomor_antrian,
                    $visit->nama_pengunjung,
                    $visit->hubungan,
                    $visit->santri->nama,
                    ucfirst($visit->status),
                    $visit->admin->name
               ]);
          }

          fclose($file);

          return [
               'file' => $tempFile,
               'filename' => $filename,
               'mime' => 'text/csv'
          ];
     }

     public function exportBarangTitipanCsv($tanggalMulai, $tanggalSelesai, $status = null)
     {
          // Get data
          $query = BarangTitipan::with(['kunjungan.santri', 'adminPenerima'])
               ->whereDate('waktu_dititipkan', '>=', $tanggalMulai)
               ->whereDate('waktu_dititipkan', '<=', $tanggalSelesai);

          if ($status) {
               $query->where('status', $status);
          }

          $barangTitipan = $query->latest('waktu_dititipkan')->get();

          // Generate CSV
          $filename = 'laporan_barang_titipan_' . date('YmdHis') . '.csv';
          $tempFile = tempnam(sys_get_temp_dir(), $filename);
          $file = fopen($tempFile, 'w');

          // Add BOM for UTF-8
          fwrite($file, "\xEF\xBB\xBF");

          // Header
          fputcsv($file, [
               'No',
               'Tanggal',
               'Kode Barang',
               'Nama Barang',
               'Jumlah',
               'Pengunjung',
               'Santri',
               'Status',
               'Admin'
          ]);

          // Data
          foreach ($barangTitipan as $index => $barang) {
               fputcsv($file, [
                    $index + 1,
                    $barang->waktu_dititipkan->format('d/m/Y'),
                    $barang->kode_barang,
                    $barang->nama_barang,
                    $barang->jumlah,
                    $barang->kunjungan->nama_pengunjung,
                    $barang->kunjungan->santri->nama,
                    ucfirst($barang->status),
                    $barang->adminPenerima->name
               ]);
          }

          fclose($file);

          return [
               'file' => $tempFile,
               'filename' => $filename,
               'mime' => 'text/csv'
          ];
     }
}
