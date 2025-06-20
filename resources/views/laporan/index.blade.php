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
        // Fixed Export JavaScript untuk Laravel
        function exportData(format, type = 'kunjungan') {
            showNotification('Memproses export data...', 'info');

            // Get current page parameters
            const urlParams = new URLSearchParams(window.location.search);
            const tanggalMulai = urlParams.get('tanggal_mulai') ||
                document.querySelector('input[name="tanggal_mulai"]')?.value ||
                new Date(Date.now() - 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
            const tanggalSelesai = urlParams.get('tanggal_selesai') ||
                document.querySelector('input[name="tanggal_selesai"]')?.value ||
                new Date().toISOString().split('T')[0];
            const status = urlParams.get('status') || document.querySelector('select[name="status"]')?.value || '';

            // Determine the type based on current page if not specified
            if (type === 'kunjungan' && window.location.pathname.includes('barang-titipan')) {
                type = 'barang-titipan';
            } else if (type === 'kunjungan' && window.location.pathname.includes('santri')) {
                type = 'santri';
            }

            // Create form for file download
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/laporan/export';
            form.style.display = 'none';
            form.target = '_blank'; // Open in new tab to prevent page refresh

            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            // Add parameters
            const params = {
                type: type,
                format: format
            };

            // Add date parameters only for kunjungan and barang-titipan
            if (type === 'kunjungan' || type === 'barang-titipan') {
                params.tanggal_mulai = tanggalMulai;
                params.tanggal_selesai = tanggalSelesai;
            }

            // Add status if exists
            if (status) {
                params.status = status;
            }

            // Create form inputs
            for (const [key, value] of Object.entries(params)) {
                if (value) { // Only add if value exists
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                }
            }

            // Submit form
            document.body.appendChild(form);
            form.submit();

            // Clean up and show success message
            setTimeout(() => {
                document.body.removeChild(form);
                showNotification(`Export ${format.toUpperCase()} berhasil! File akan didownload.`, 'success');
            }, 1000);
        }

        // Export function with date range modal
        function exportWithDateRange(format) {
            // Remove existing modal if any
            const existingModal = document.getElementById('export-modal');
            if (existingModal) {
                existingModal.remove();
            }

            // Create modal for date selection
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
            modal.id = 'export-modal';
            modal.innerHTML = `
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Export Data</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" id="export-start-date" value="${new Date(Date.now() - 30*24*60*60*1000).toISOString().split('T')[0]}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                        <input type="date" id="export-end-date" value="${new Date().toISOString().split('T')[0]}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Data</label>
                        <select id="export-type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="kunjungan">Kunjungan</option>
                            <option value="barang-titipan">Barang Titipan</option>
                            <option value="santri">Data Santri</option>
                        </select>
                    </div>
                </div>
                <div class="items-center px-4 py-3 mt-6">
                    <button onclick="executeExport('${format}')" 
                            class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        Export ${format.toUpperCase()}
                    </button>
                    <button onclick="closeExportModal()" 
                            class="mt-3 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    `;

            document.body.appendChild(modal);

            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeExportModal();
                }
            });
        }

        function executeExport(format) {
            const startDate = document.getElementById('export-start-date').value;
            const endDate = document.getElementById('export-end-date').value;
            const type = document.getElementById('export-type').value;

            if (!startDate || !endDate) {
                showNotification('Mohon pilih tanggal mulai dan selesai', 'error');
                return;
            }

            if (new Date(startDate) > new Date(endDate)) {
                showNotification('Tanggal mulai tidak boleh lebih besar dari tanggal selesai', 'error');
                return;
            }

            // Close modal
            closeExportModal();

            // Create export form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/laporan/export';
            form.style.display = 'none';
            form.target = '_blank';

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            const params = {
                type: type,
                format: format,
                tanggal_mulai: startDate,
                tanggal_selesai: endDate
            };

            for (const [key, value] of Object.entries(params)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }

            document.body.appendChild(form);
            form.submit();

            setTimeout(() => {
                document.body.removeChild(form);
                showNotification(`Export ${format.toUpperCase()} berhasil!`, 'success');
            }, 1000);
        }

        function closeExportModal() {
            const modal = document.getElementById('export-modal');
            if (modal) {
                modal.remove();
            }
        }

        // Enhanced notification function
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notification => notification.remove());

            const notification = document.createElement('div');
            notification.className = `notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transition-all duration-300 transform ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        type === 'warning' ? 'bg-yellow-500 text-white' :
        'bg-blue-500 text-white'
    }`;

            notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${
                type === 'success' ? 'check-circle' : 
                type === 'error' ? 'exclamation-circle' : 
                type === 'warning' ? 'exclamation-triangle' :
                'info-circle'
            } mr-2"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        if (notification.parentElement) {
                            notification.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }

        // Quick export functions for specific data types
        function exportKunjungan(format) {
            exportData(format, 'kunjungan');
        }

        function exportBarangTitipan(format) {
            exportData(format, 'barang-titipan');
        }

        function exportSantri(format) {
            exportData(format, 'santri');
        }

        // Initialize export functionality on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Add click handlers for export buttons that might be dynamically added
            document.addEventListener('click', function(e) {
                if (e.target.matches('[onclick*="exportData"]')) {
                    e.preventDefault();
                    const onclickAttr = e.target.getAttribute('onclick');
                    const matches = onclickAttr.match(/exportData\('(\w+)'(?:,\s*'(\w+)')?\)/);
                    if (matches) {
                        const format = matches[1];
                        const type = matches[2] || 'kunjungan';
                        exportData(format, type);
                    }
                }
            });
        });

        // Keyboard shortcuts for export
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.shiftKey) {
                switch (e.key) {
                    case 'E':
                        e.preventDefault();
                        exportWithDateRange('excel');
                        break;
                    case 'P':
                        e.preventDefault();
                        exportWithDateRange('pdf');
                        break;
                    case 'C':
                        e.preventDefault();
                        exportWithDateRange('csv');
                        break;
                }
            }
        });

        // Global error handler for export failures
        window.addEventListener('error', function(e) {
            if (e.message.includes('export')) {
                showNotification('Terjadi kesalahan saat export. Silakan coba lagi.', 'error');
            }
        });

        // Check if export was successful (for form submissions)
        function checkExportStatus() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('exported') && urlParams.get('exported') === 'success') {
                showNotification('Export berhasil!', 'success');
                // Remove the parameter from URL without refreshing
                const newUrl = window.location.pathname + (window.location.search.replace(/[?&]exported=success/, ''));
                window.history.replaceState({}, document.title, newUrl);
            }
        }
    </script>
@endpush
