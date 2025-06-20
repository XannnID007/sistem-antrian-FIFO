<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Kunjungan - {{ $kunjungan->nomor_antrian }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 10px;
                font-size: 12px;
            }

            .no-print {
                display: none;
            }

            .print-only {
                display: block;
            }

            @page {
                size: 58mm auto;
                margin: 0;
            }
        }

        .thermal-paper {
            width: 58mm;
            font-family: 'Courier New', monospace;
            font-size: 11px;
            line-height: 1.2;
        }

        .dotted-line {
            border-bottom: 1px dashed #000;
            margin: 8px 0;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .large {
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-gray-100 p-4">
    <!-- Print Button -->
    <div class="no-print mb-4 text-center">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg mr-2">
            <i class="fas fa-print mr-2"></i>Cetak Struk
        </button>
        <a href="{{ route('kunjungan.antrian') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Thermal Paper Container -->
    <div class="thermal-paper bg-white mx-auto p-4 shadow-lg">
        <!-- Header -->
        <div class="center mb-3">
            <div class="large">PONDOK PESANTREN</div>
            <div class="large">SALAFIYAH AL-JAWAHIR</div>
            <div class="text-xs mt-1">Sistem Antrian Kunjungan Santri</div>
        </div>

        <div class="dotted-line"></div>

        <!-- Struk Info -->
        <div class="center mb-3">
            <div class="text-lg bold">STRUK KUNJUNGAN</div>
            <div class="text-2xl bold mt-1">{{ $kunjungan->nomor_antrian }}</div>
        </div>

        <div class="dotted-line"></div>

        <!-- Kunjungan Details -->
        <div class="space-y-1 text-xs">
            <div class="flex justify-between">
                <span>Tanggal:</span>
                <span class="bold">{{ $kunjungan->waktu_daftar->format('d/m/Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Waktu Daftar:</span>
                <span class="bold">{{ $kunjungan->waktu_daftar->format('H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Pengunjung:</span>
                <span class="bold">{{ $kunjungan->nama_pengunjung }}</span>
            </div>
            <div class="flex justify-between">
                <span>Hubungan:</span>
                <span>{{ $kunjungan->hubungan }}</span>
            </div>
            <div class="flex justify-between">
                <span>Santri:</span>
                <span class="bold">{{ $kunjungan->santri->nama }}</span>
            </div>
            <div class="flex justify-between">
                <span>NIM:</span>
                <span>{{ $kunjungan->santri->nim }}</span>
            </div>
            @if ($kunjungan->santri->kamar)
                <div class="flex justify-between">
                    <span>Kamar:</span>
                    <span>{{ $kunjungan->santri->kamar }}</span>
                </div>
            @endif
            <div class="flex justify-between">
                <span>No. HP:</span>
                <span>{{ $kunjungan->phone_pengunjung }}</span>
            </div>
        </div>

        @if ($kunjungan->catatan)
            <div class="dotted-line"></div>
            <div class="text-xs">
                <div class="bold mb-1">Catatan:</div>
                <div>{{ $kunjungan->catatan }}</div>
            </div>
        @endif

        <!-- Barang Titipan -->
        @if ($kunjungan->barangTitipan->count() > 0)
            <div class="dotted-line"></div>
            <div class="text-xs">
                <div class="bold mb-2 center">BARANG TITIPAN</div>
                @foreach ($kunjungan->barangTitipan as $barang)
                    <div class="mb-2 p-2 border border-dashed border-gray-400">
                        <div class="flex justify-between">
                            <span>Kode:</span>
                            <span class="bold">{{ $barang->kode_barang }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Barang:</span>
                            <span>{{ $barang->nama_barang }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Jumlah:</span>
                            <span>{{ $barang->jumlah }}</span>
                        </div>
                        @if ($barang->deskripsi)
                            <div class="mt-1">
                                <span class="text-xs">{{ $barang->deskripsi }}</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <div class="dotted-line"></div>

        <!-- Status -->
        <div class="center text-xs">
            <div class="bold">STATUS KUNJUNGAN</div>
            <div class="mt-1 p-2 border-2 border-dashed">
                <div class="text-lg bold">{{ strtoupper($kunjungan->status) }}</div>
                @if ($kunjungan->status === 'menunggu')
                    <div class="text-xs mt-1">Mohon menunggu panggilan</div>
                @elseif($kunjungan->status === 'dipanggil')
                    <div class="text-xs mt-1">Silakan menuju ruang kunjungan</div>
                @elseif($kunjungan->status === 'berlangsung')
                    <div class="text-xs mt-1">Kunjungan sedang berlangsung</div>
                @elseif($kunjungan->status === 'selesai')
                    <div class="text-xs mt-1">Kunjungan telah selesai</div>
                    @if ($kunjungan->waktu_selesai)
                        <div class="text-xs">Selesai: {{ $kunjungan->waktu_selesai->format('H:i') }}</div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Estimasi Waktu -->
        @if ($kunjungan->status === 'menunggu')
            @php
                $antrianSebelum = \App\Models\Kunjungan::where('waktu_daftar', '<', $kunjungan->waktu_daftar)
                    ->whereIn('status', ['menunggu', 'dipanggil', 'berlangsung'])
                    ->count();
                $estimasiMenit = $antrianSebelum * 15; // 15 menit per kunjungan
            @endphp

            @if ($antrianSebelum > 0)
                <div class="dotted-line"></div>
                <div class="center text-xs">
                    <div class="bold">ESTIMASI WAKTU TUNGGU</div>
                    <div class="text-lg bold mt-1">{{ $estimasiMenit }} Menit</div>
                    <div class="text-xs mt-1">{{ $antrianSebelum }} antrian sebelum Anda</div>
                </div>
            @endif
        @endif

        <div class="dotted-line"></div>

        <!-- Footer -->
        <div class="center text-xs space-y-1">
            <div>Admin: {{ $kunjungan->admin->name }}</div>
            <div>{{ now()->format('d/m/Y H:i:s') }}</div>
            <div class="mt-2 text-xs">
                Simpan struk ini sebagai bukti<br>
                kunjungan Anda
            </div>
        </div>

        <div class="dotted-line"></div>

        <!-- QR Code Placeholder -->
        <div class="center text-xs">
            <div class="w-16 h-16 border-2 border-dashed border-gray-400 mx-auto flex items-center justify-center">
                QR
            </div>
            <div class="mt-1">{{ $kunjungan->nomor_antrian }}</div>
        </div>

        <div class="center text-xs mt-3">
            <div>Terima kasih atas kunjungan Anda</div>
            <div>Semoga bermanfaat</div>
        </div>
    </div>

    <script>
        // Auto print on load (optional)
        // window.onload = function() { window.print(); }

        // Print function
        function printStruk() {
            window.print();
        }

        // Add keyboard shortcut
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                printStruk();
            }
        });
    </script>
</body>

</html>
