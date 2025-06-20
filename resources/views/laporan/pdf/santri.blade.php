<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Santri</title>
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

        .status-aktif {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }

        .status-tidak-aktif {
            background-color: #f8d7da;
            color: #721c24;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN DATA SANTRI</h1>
        <h2>Pondok Pesantren Salafiyah Al-Jawahir</h2>
    </div>

    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Status:</span>
            <span>{{ $status }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total Data:</span>
            <span>{{ $total }} santri</span>
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
                <th width="12%">NIM</th>
                <th width="20%">Nama</th>
                <th width="8%">JK</th>
                <th width="15%">Tempat/Tgl Lahir</th>
                <th width="18%">Nama Wali</th>
                <th width="10%">Kamar</th>
                <th width="8%">Status</th>
                <th width="4%">Visit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($santri as $index => $s)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $s->nim }}</td>
                    <td>{{ $s->nama }}</td>
                    <td class="text-center">{{ $s->jenis_kelamin == 'L' ? 'L' : 'P' }}</td>
                    <td>{{ $s->tempat_lahir }}<br><small>{{ $s->tanggal_lahir->format('d/m/Y') }}</small></td>
                    <td>{{ $s->nama_wali }}<br><small>{{ $s->phone_wali }}</small></td>
                    <td class="text-center">{{ $s->kamar ?? '-' }}</td>
                    <td>
                        <span class="status-{{ $s->is_active ? 'aktif' : 'tidak-aktif' }}">
                            {{ $s->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </td>
                    <td class="text-center">{{ $s->kunjungan_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data santri</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini digenerate otomatis oleh Sistem Kunjungan Santri Al-Jawahir</p>
        <p>Dicetak pada: {{ now()->format('d F Y, H:i:s') }} WIB</p>
    </div>
</body>

</html>
