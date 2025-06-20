<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Barang Titipan - {{ $barangTitipan->kode_barang }}</title>
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
        <a href="{{ route('barang-titipan.index') }}"
            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Thermal Paper Container -->
    <div class="thermal-paper bg-white mx-auto p-4 shadow-lg">
        <!-- Header -->
        <div class="center mb-3">
            <div class="large">PONDOK PESANTREN</div>
            <div class="large">SALAFIYAH AL-JAWAHIR</div>
            <div class="text-xs mt-1">Sistem Barang Titipan</div>
        </div>

        <div class="dotted-line"></div>

        <!-- Struk Info -->
        <div class="center mb-3">
            <div class="text-lg bold">STRUK BARANG TITIPAN</div>
            <div class="text-2xl bold mt-1">{{ $barangTitipan->kode_barang }}</div>
        </div>

        <div class="dotted-line"></div>

        <!-- Barang Details -->
        <div class="space-y-1 text-xs">
            <div class="flex justify-between">
                <span>Tanggal:</span>
                <span class="bold">{{ $barangTitipan->waktu_dititipkan->format('d/m/Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Waktu:</span>
                <span class="bold">{{ $barangTitipan->waktu_dititipkan->format('H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Nama Barang:</span>
                <span class="bold">{{ $barangTitipan->nama_barang }}</span>
            </div>
            <div class="flex justify-between">
                <span>Jumlah:</span>
                <span class="bold">{{ $barangTitipan->jumlah }}</span>
            </div>
            @if ($barangTitipan->deskripsi)
                <div class="mt-2">
                    <div class="bold">Deskripsi:</div>
                    <div>{{ $barangTitipan->deskripsi }}</div>
                </div>
            @endif
        </div>

        <div class="dotted-line"></div>

        <!-- Kunjungan Info -->
        <div class="space-y-1 text-xs">
            <div class="bold center mb-1">INFORMASI KUNJUNGAN</div>
            <div class="flex justify-between">
                <span>No. Antrian:</span>
                <span class="bold">{{ $barangTitipan->kunjungan->nomor_antrian }}</span>
            </div>
            <div class="flex justify-between">
                <span>Pengunjung:</span>
                <span class="bold">{{ $barangTitipan->kunjungan->nama_pengunjung }}</span>
            </div>
            <div class="flex justify-between">
                <span>Santri:</span>
                <span class="bold">{{ $barangTitipan->kunjungan->santri->nama }}</span>
            </div>
            <div class="flex justify-between">
                <span>NIM:</span>
                <span>{{ $barangTitipan->kunjungan->santri->nim }}</span>
            </div>
            @if ($barangTitipan->kunjungan->santri->kamar)
                <div class="flex justify-between">
                    <span>Kamar:</span>
                    <span>{{ $barangTitipan->kunjungan->santri->kamar }}</span>
                </div>
            @endif
            <div class="flex justify-between">
                <span>Hubungan:</span>
                <span>{{ $barangTitipan->kunjungan->hubungan }}</span>
            </div>
        </div>

        @if ($barangTitipan->catatan)
            <div class="dotted-line"></div>
            <div class="text-xs">
                <div class="bold mb-1">Catatan:</div>
                <div>{{ $barangTitipan->catatan }}</div>
            </div>
        @endif

        <div class="dotted-line"></div>

        <!-- Status -->
        <div class="center text-xs">
            <div class="bold">STATUS BARANG</div>
            <div class="mt-1 p-2 border-2 border-dashed">
                <div class="text-lg bold">{{ strtoupper($barangTitipan->status) }}</div>
                @switch($barangTitipan->status)
                    @case('dititipkan')
                        <div class="text-xs mt-1">Barang sedang dititipkan</div>
                        <div class="text-xs">Dapat diambil kapan saja</div>
                    @break

                    @case('diserahkan')
                        <div class="text-xs mt-1">Barang telah diserahkan ke santri</div>
                        @if ($barangTitipan->waktu_diserahkan)
                            <div class="text-xs">Diserahkan: {{ $barangTitipan->waktu_diserahkan->format('d/m/Y H:i') }}</div>
                        @endif
                    @break

                    @case('diambil')
                        <div class="text-xs mt-1">Barang telah diambil</div>
                        @if ($barangTitipan->waktu_diambil)
                            <div class="text-xs">Diambil: {{ $barangTitipan->waktu_diambil->format('d/m/Y H:i') }}</div>
                        @endif
                    @break

                @endswitch
            </div>
        </div>

        <!-- Timeline -->
        <div class="dotted-line"></div>
        <div class="text-xs">
            <div class="bold center mb-2">RIWAYAT BARANG</div>

            <div class="space-y-2">
                <!-- Dititipkan -->
                <div class="flex justify-between">
                    <span>✓ Dititipkan:</span>
                    <span>{{ $barangTitipan->waktu_dititipkan->format('d/m H:i') }}</span>
                </div>

                @if ($barangTitipan->adminPenerima)
                    <div class="text-xs pl-2">
                        Admin: {{ $barangTitipan->adminPenerima->name }}
                    </div>
                @endif

                <!-- Diserahkan -->
                @if ($barangTitipan->waktu_diserahkan)
                    <div class="flex justify-between">
                        <span>✓ Diserahkan:</span>
                        <span>{{ $barangTitipan->waktu_diserahkan->format('d/m H:i') }}</span>
                    </div>

                    @if ($barangTitipan->adminPenyerah)
                        <div class="text-xs pl-2">
                            Admin: {{ $barangTitipan->adminPenyerah->name }}
                        </div>
                    @endif
                @else
                    <div class="flex justify-between text-gray-500">
                        <span>○ Diserahkan:</span>
                        <span>Belum</span>
                    </div>
                @endif

                <!-- Diambil -->
                @if ($barangTitipan->waktu_diambil)
                    <div class="flex justify-between">
                        <span>✓ Diambil:</span>
                        <span>{{ $barangTitipan->waktu_diambil->format('d/m H:i') }}</span>
                    </div>
                @else
                    <div class="flex justify-between text-gray-500">
                        <span>○ Diambil:</span>
                        <span>Belum</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="dotted-line"></div>

        <!-- Instructions -->
        @if ($barangTitipan->status === 'dititipkan')
            <div class="center text-xs">
                <div class="bold">PETUNJUK PENGAMBILAN</div>
                <div class="mt-1 space-y-1">
                    <div>1. Tunjukkan struk ini saat mengambil</div>
                    <div>2. Konfirmasi identitas pengunjung</div>
                    <div>3. Periksa kondisi barang</div>
                    <div>4. Tanda tangan bukti serah terima</div>
                </div>
            </div>
        @elseif($barangTitipan->status === 'diserahkan')
            <div class="center text-xs">
                <div class="bold">BARANG TELAH DISERAHKAN</div>
                <div class="mt-1">
                    Barang telah diserahkan kepada santri.<br>
                    Proses penitipan selesai.
                </div>
            </div>
        @endif

        <div class="dotted-line"></div>

        <!-- Footer -->
        <div class="center text-xs space-y-1">
            <div>Dicetak: {{ now()->format('d/m/Y H:i:s') }}</div>
            <div class="mt-2 text-xs">
                Simpan struk ini hingga barang<br>
                selesai diambil
            </div>
        </div>

        <div class="dotted-line"></div>

        <!-- QR Code Placeholder -->
        <div class="center text-xs">
            <div class="w-16 h-16 border-2 border-dashed border-gray-400 mx-auto flex items-center justify-center">
                QR
            </div>
            <div class="mt-1">{{ $barangTitipan->kode_barang }}</div>
        </div>

        <div class="center text-xs mt-3">
            <div>Terima kasih telah menggunakan</div>
            <div>layanan barang titipan</div>
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
