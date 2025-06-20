@extends('layouts.admin')

@section('title', 'Laporan')

@section('breadcrumb')
    <li class="flex items-center">
        <i class="fas fa-home text-gray-400 mr-2"></i>
        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-gray-600">Laporan</span>
    </li>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow card-shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Pusat Laporan</h1>
                    <p class="text-gray-600 mt-1">Akses semua laporan dan analisis sistem kunjungan santri</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Tanggal Generate</div>
                    <div class="text-lg font-semibold text-gray-900">{{ now()->format('d F Y, H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow card-shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Kunjungan Hari Ini</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $summary['total_kunjungan_hari_ini'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow card-shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Kunjungan Bulan Ini</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $summary['total_kunjungan_bulan_ini'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-line text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow card-shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Santri Aktif</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $summary['total_santri_aktif'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow card-shadow p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Barang Titipan Aktif</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $summary['total_barang_titipan_aktif'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-box text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Categories -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Laporan Kunjungan -->
            <div class="bg-white rounded-lg shadow card-shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-users text-blue-600 mr-3"></i>
                        Laporan Kunjungan
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">Laporan data kunjungan santri, statistik antrian, dan analisis waktu
                        tunggu</p>
                    <div class="space-y-3">
                        <a href="{{ route('laporan.kunjungan') }}"
                            class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition-colors">
                            <i class="fas fa-chart-bar mr-2"></i>Lihat Laporan Kunjungan
                        </a>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('laporan.kunjungan') }}?periode=hari_ini"
                                class="text-center py-2 px-3 border border-blue-300 text-blue-600 rounded hover:bg-blue-50 transition-colors text-sm">
                                Hari Ini
                            </a>
                            <a href="{{ route('laporan.kunjungan') }}?periode=minggu_ini"
                                class="text-center py-2 px-3 border border-blue-300 text-blue-600 rounded hover:bg-blue-50 transition-colors text-sm">
                                Minggu Ini
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laporan Barang Titipan -->
            <div class="bg-white rounded-lg shadow card-shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-box text-green-600 mr-3"></i>
                        Laporan Barang Titipan
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">Laporan barang titipan, status penyerahan, dan analisis penggunaan</p>
                    <div class="space-y-3">
                        <a href="{{ route('laporan.barang-titipan') }}"
                            class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg transition-colors">
                            <i class="fas fa-chart-bar mr-2"></i>Lihat Laporan Barang
                        </a>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('laporan.barang-titipan') }}?status=dititipkan"
                                class="text-center py-2 px-3 border border-green-300 text-green-600 rounded hover:bg-green-50 transition-colors text-sm">
                                Dititipkan
                            </a>
                            <a href="{{ route('laporan.barang-titipan') }}?status=diserahkan"
                                class="text-center py-2 px-3 border border-green-300 text-green-600 rounded hover:bg-green-50 transition-colors text-sm">
                                Diserahkan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laporan Statistik -->
            <div class="bg-white rounded-lg shadow card-shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-chart-line text-purple-600 mr-3"></i>
                        Laporan Statistik
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">Analisis statistik, tren kunjungan, dan data historis sistem</p>
                    <div class="space-y-3">
                        <a href="{{ route('laporan.statistik') }}"
                            class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center py-2 px-4 rounded-lg transition-colors">
                            <i class="fas fa-chart-line mr-2"></i>Lihat Statistik
                        </a>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('laporan.statistik') }}?periode=30"
                                class="text-center py-2 px-3 border border-purple-300 text-purple-600 rounded hover:bg-purple-50 transition-colors text-sm">
                                30 Hari
                            </a>
                            <a href="{{ route('laporan.statistik') }}?periode=90"
                                class="text-center py-2 px-3 border border-purple-300 text-purple-600 rounded hover:bg-purple-50 transition-colors text-sm">
                                90 Hari
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Tools -->
            <div class="bg-white rounded-lg shadow card-shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-download text-orange-600 mr-3"></i>
                        Export & Backup
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">Export data ke berbagai format dan backup sistem</p>
                    <div class="space-y-3">
                        <button onclick="exportData('excel')"
                            class="block w-full bg-orange-600 hover:bg-orange-700 text-white text-center py-2 px-4 rounded-lg transition-colors">
                            <i class="fas fa-file-excel mr-2"></i>Export ke Excel
                        </button>
                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="exportData('pdf')"
                                class="text-center py-2 px-3 border border-orange-300 text-orange-600 rounded hover:bg-orange-50 transition-colors text-sm">
                                Export PDF
                            </button>
                            <button onclick="exportData('csv')"
                                class="text-center py-2 px-3 border border-orange-300 text-orange-600 rounded hover:bg-orange-50 transition-colors text-sm">
                                Export CSV
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Reports -->
        <div class="bg-white rounded-lg shadow card-shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Laporan Cepat</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <h4 class="font-medium text-gray-900 mb-2">Top 10 Santri Dikunjungi</h4>
                        <p class="text-sm text-gray-600 mb-3">Santri dengan kunjungan terbanyak bulan ini</p>
                        <a href="{{ route('laporan.statistik') }}?report=top_santri"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat Laporan <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>

                    <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <h4 class="font-medium text-gray-900 mb-2">Jam Sibuk</h4>
                        <p class="text-sm text-gray-600 mb-3">Analisis jam kunjungan tersibuk</p>
                        <a href="{{ route('laporan.statistik') }}?report=peak_hours"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat Laporan <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>

                    <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <h4 class="font-medium text-gray-900 mb-2">Waktu Tunggu Rata-rata</h4>
                        <p class="text-sm text-gray-600 mb-3">Analisis efisiensi waktu tunggu antrian</p>
                        <a href="{{ route('laporan.kunjungan') }}?report=wait_time"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat Laporan <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow card-shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-file-alt text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Laporan Kunjungan</div>
                                <div class="text-xs text-gray-500">Terakhir diakses 2 jam yang lalu</div>
                            </div>
                        </div>
                        <a href="{{ route('laporan.kunjungan') }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-chart-bar text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Export Data Excel</div>
                                <div class="text-xs text-gray-500">Terakhir diakses kemarin</div>
                            </div>
                        </div>
                        <button onclick="exportData('excel')" class="text-green-600 hover:text-green-800">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-chart-line text-purple-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Statistik Mingguan</div>
                                <div class="text-xs text-gray-500">Terakhir diakses 3 hari yang lalu</div>
                            </div>
                        </div>
                        <a href="{{ route('laporan.statistik') }}" class="text-purple-600 hover:text-purple-800">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function exportData(format) {
            // Show loading notification
            showNotification('Memproses export data...', 'info');

            // You can implement actual export logic here
            fetch('{{ route('laporan.export') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        format: format,
                        type: 'all'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    showNotification('Export berhasil!', 'success');
                    // Handle download or redirect as needed
                })
                .catch(error => {
                    showNotification('Export gagal. Silakan coba lagi.', 'error');
                });
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        // Auto refresh statistics every 5 minutes
        setInterval(function() {
            location.reload();
        }, 300000);
    </script>
@endpush
