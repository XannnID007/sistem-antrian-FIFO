<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang Titipan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            margin: 0;
            color: #333;
        }

        .header h2 {
            font-size: 16px;
            margin: 5px 0;
            color: #666;
        }

        .info-box {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
            color: #333;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .status-dititipkan {
            background-color: #fff3cd;
            color: #856404;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }

        .status-diserahkan {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }

        .status-diambil {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN BARANG TITIPAN</h1>
        <h2>Pondok Pesantren Salafiyah Al-Jawahir</h2>
    </div>

    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Periode:</span>
            <span>{{ $tanggalMulai }} s/d {{ $tanggalSelesai }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status:</span>
            <span>{{ $status }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total Data:</span>
            <span>{{ $total }} barang titipan</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Cetak:</span>
            <span>{{ now()->format('d/m/Y H:i:s') }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Tanggal</th>
                <th width="12%">Kode</th>
                <th width="20%">Nama Barang</th>
                <th width="8%">Jml</th>
                <th width="15%">Pengunjung</th>
                <th width="15%">Santri</th>
                <th width="10%">Status</th>
                <th width="5%">Admin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangTitipan as $index => $barang)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $barang->waktu_dititipkan->format('d/m/Y') }}</td>
                    <td>{{ $barang->kode_barang }}</td>
                    <td>
                        {{ $barang->nama_barang }}
                        @if ($barang->deskripsi)
                            <br><small>{{ $barang->deskripsi }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $barang->jumlah }}</td>
                    <td>{{ $barang->kunjungan->nama_pengunjung }}<br><small>{{ $barang->kunjungan->hubungan }}</small>
                    </td>
                    <td>{{ $barang->kunjungan->santri->nama }}<br><small>{{ $barang->kunjungan->santri->nim }}</small>
                    </td>
                    <td>
                        <span class="status-{{ $barang->status }}">
                            {{ ucfirst($barang->status) }}
                        </span>
                    </td>
                    <td>{{ $barang->adminPenerima->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data barang titipan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Summary Statistics -->
    <div
        style="margin-top: 30px; display: flex; justify-content: space-around; background-color: #f8f9fa; padding: 15px; border-radius: 5px;">
        <div style="text-align: center;">
            <div style="font-weight: bold; font-size: 14px; color: #333;">Total Barang</div>
            <div style="font-size: 18px; color: #007bff;">{{ $barangTitipan->count() }}</div>
        </div>
        <div style="text-align: center;">
            <div style="font-weight: bold; font-size: 14px; color: #333;">Dititipkan</div>
            <div style="font-size: 18px; color: #ffc107;">{{ $barangTitipan->where('status', 'dititipkan')->count() }}
            </div>
        </div>
        <div style="text-align: center;">
            <div style="font-weight: bold; font-size: 14px; color: #333;">Diserahkan</div>
            <div style="font-size: 18px; color: #17a2b8;">{{ $barangTitipan->where('status', 'diserahkan')->count() }}
            </div>
        </div>
        <div style="text-align: center;">
            <div style="font-weight: bold; font-size: 14px; color: #333;">Diambil</div>
            <div style="font-size: 18px; color: #28a745;">{{ $barangTitipan->where('status', 'diambil')->count() }}
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Laporan ini digenerate otomatis oleh Sistem Kunjungan Santri Al-Jawahir</p>
        <p>Dicetak pada: {{ now()->format('d F Y, H:i:s') }} WIB</p>
    </div>
</body>

</html>
