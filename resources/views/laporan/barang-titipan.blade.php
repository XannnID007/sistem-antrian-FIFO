@extends('layouts.admin')

@section('title', 'Laporan Barang Titipan')

@section('breadcrumb')
    <li class="flex items-center">
        <i class="fas fa-home text-gray-400 mr-2"></i>
        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <a href="{{ route('laporan.index') }}" class="text-gray-600 hover:text-gray-900">Laporan</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-gray-600">Laporan Barang Titipan</span>
    </li>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Barang Titipan</h1>
                <p class="text-gray-600 mt-1">Analisis data barang titipan periode
                    {{ \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }} -
                    {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d/m/Y') }}</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="exportData('pdf')"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-file-pdf mr-2"></i>Export PDF
                </button>
                <button onclick="exportData('excel')"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </button>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-lg shadow card-shadow p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="dititipkan" {{ request('status') == 'dititipkan' ? 'selected' : '' }}>Dititipkan
                        </option>
                        <option value="diserahkan" {{ request('status') == 'diserahkan' ? 'selected' : '' }}>Diserahkan
                        </option>
                        <option value="diambil" {{ request('status') == 'diambil' ? 'selected' : '' }}>Diambil</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistics Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow card-shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Barang</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-box text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow card-shadow p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Dititipkan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['dititipkan'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow card-shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Diserahkan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['diserahkan'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-hand-holding text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow card-shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Diambil</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['diambil'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-lg shadow card-shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Detail Barang Titipan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode
                                Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                                Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pengunjung</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Santri</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($barangTitipan as $index => $barang)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $barang->waktu_dititipkan->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-blue-600">{{ $barang->kode_barang }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $barang->nama_barang }}</div>
                                        @if ($barang->deskripsi)
                                            <div class="text-sm text-gray-500">{{ $barang->deskripsi }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $barang->jumlah }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $barang->kunjungan->nama_pengunjung }}</div>
                                        <div class="text-sm text-gray-500">{{ $barang->kunjungan->hubungan }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $barang->kunjungan->santri->nama }}</div>
                                        <div class="text-sm text-gray-500">{{ $barang->kunjungan->santri->nim }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @switch($barang->status)
                                            @case('dititipkan') bg-yellow-100 text-yellow-800 @break
                                            @case('diserahkan') bg-purple-100 text-purple-800 @break
                                            @case('diambil') bg-green-100 text-green-800 @break
                                        @endswitch">
                                        {{ ucfirst($barang->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $barang->adminPenerima->name }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-3xl mb-3 text-gray-300"></i>
                                    <p>Tidak ada data barang titipan untuk periode ini</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
