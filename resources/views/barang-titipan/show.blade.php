@extends('layouts.admin')

@section('title', 'Detail Barang Titipan')

@section('breadcrumb')
    <li class="flex items-center">
        <i class="fas fa-home text-gray-400 mr-2"></i>
        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <a href="{{ route('barang-titipan.index') }}" class="text-gray-600 hover:text-gray-900">Barang Titipan</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-gray-600">Detail Barang</span>
    </li>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Barang Titipan</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap barang titipan</p>
            </div>
            <div class="flex space-x-3">
                @if ($barangTitipan->status == 'dititipkan')
                    <button onclick="serahkanBarang({{ $barangTitipan->id }})"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-hand-holding mr-2"></i>Serahkan ke Santri
                    </button>
                    <a href="{{ route('barang-titipan.edit', $barangTitipan) }}"
                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @elseif($barangTitipan->status == 'diserahkan')
                    <button onclick="ambilBarang({{ $barangTitipan->id }})"
                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-check-circle mr-2"></i>Tandai Diambil
                    </button>
                @endif
                <a href="{{ route('barang-titipan.struk', $barangTitipan) }}" target="_blank"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-print mr-2"></i>Cetak Struk
                </a>
                <a href="{{ route('barang-titipan.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="bg-white rounded-lg shadow card-shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Barang</h3>
            <div class="relative">
                <div class="flex items-center justify-between">
                    <!-- Dititipkan -->
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white mb-2">
                            <i class="fas fa-box"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-900">Dititipkan</span>
                        <span
                            class="text-xs text-gray-500">{{ $barangTitipan->waktu_dititipkan->format('d/m/Y H:i') }}</span>
                    </div>

                    <!-- Progress Line -->
                    <div
                        class="flex-1 h-0.5 mx-4 {{ $barangTitipan->status != 'dititipkan' ? 'bg-blue-600' : 'bg-gray-300' }}">
                    </div>

                    <!-- Diserahkan -->
                    <div class="flex flex-col items-center">
                        <div
                            class="w-10 h-10 {{ $barangTitipan->status == 'diserahkan' || $barangTitipan->status == 'diambil' ? 'bg-green-600' : 'bg-gray-300' }} rounded-full flex items-center justify-center text-white mb-2">
                            <i class="fas fa-hand-holding"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-900">Diserahkan</span>
                        <span class="text-xs text-gray-500">
                            {{ $barangTitipan->waktu_diserahkan ? $barangTitipan->waktu_diserahkan->format('d/m/Y H:i') : '-' }}
                        </span>
                    </div>

                    <!-- Progress Line -->
                    <div
                        class="flex-1 h-0.5 mx-4 {{ $barangTitipan->status == 'diambil' ? 'bg-green-600' : 'bg-gray-300' }}">
                    </div>

                    <!-- Diambil -->
                    <div class="flex flex-col items-center">
                        <div
                            class="w-10 h-10 {{ $barangTitipan->status == 'diambil' ? 'bg-green-600' : 'bg-gray-300' }} rounded-full flex items-center justify-center text-white mb-2">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-900">Diambil</span>
                        <span class="text-xs text-gray-500">
                            {{ $barangTitipan->waktu_diambil ? $barangTitipan->waktu_diambil->format('d/m/Y H:i') : '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informasi Barang -->
            <div class="bg-white rounded-lg shadow card-shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Barang</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kode Barang</label>
                        <p class="mt-1 text-lg font-mono font-semibold text-blue-600">{{ $barangTitipan->kode_barang }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $barangTitipan->nama_barang }}</p>
                    </div>
                    @if ($barangTitipan->deskripsi)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $barangTitipan->deskripsi }}</p>
                        </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $barangTitipan->jumlah }} item</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span
                            class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @switch($barangTitipan->status)
                                @case('dititipkan') bg-yellow-100 text-yellow-800 @break
                                @case('diserahkan') bg-blue-100 text-blue-800 @break
                                @case('diambil') bg-green-100 text-green-800 @break
                            @endswitch">
                            {{ ucfirst($barangTitipan->status) }}
                        </span>
                    </div>
                    @if ($barangTitipan->catatan)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Catatan</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded">{{ $barangTitipan->catatan }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Kunjungan -->
            <div class="bg-white rounded-lg shadow card-shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Kunjungan</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Antrian</label>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $barangTitipan->kunjungan->nomor_antrian }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Pengunjung</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $barangTitipan->kunjungan->nama_pengunjung }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hubungan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $barangTitipan->kunjungan->hubungan }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Santri yang Dikunjungi</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $barangTitipan->kunjungan->santri->nama }}</p>
                        <p class="text-xs text-gray-500">{{ $barangTitipan->kunjungan->santri->nim }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status Kunjungan</label>
                        <span
                            class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @switch($barangTitipan->kunjungan->status)
                                @case('menunggu') bg-yellow-100 text-yellow-800 @break
                                @case('dipanggil') bg-blue-100 text-blue-800 @break
                                @case('berlangsung') bg-green-100 text-green-800 @break
                                @case('selesai') bg-gray-100 text-gray-800 @break
                                @case('dibatalkan') bg-red-100 text-red-800 @break
                            @endswitch">
                            {{ ucfirst($barangTitipan->kunjungan->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Waktu Kunjungan</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $barangTitipan->kunjungan->waktu_daftar->format('d F Y, H:i') }}</p>
                    </div>
                    <div class="pt-3 border-t">
                        <a href="{{ route('kunjungan.show', $barangTitipan->kunjungan) }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="fas fa-external-link-alt mr-1"></i>Lihat Detail Kunjungan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Admin -->
        <div class="bg-white rounded-lg shadow card-shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Riwayat Admin</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $barangTitipan->adminPenerima->name }}</p>
                                <p class="text-xs text-gray-500">Admin Penerima</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-900">{{ $barangTitipan->waktu_dititipkan->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $barangTitipan->waktu_dititipkan->format('H:i') }}</p>
                        </div>
                    </div>

                    @if ($barangTitipan->adminPenyerah)
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-hand-holding text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $barangTitipan->adminPenyerah->name }}
                                    </p>
                                    <p class="text-xs text-gray-500">Admin Penyerah</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-900">{{ $barangTitipan->waktu_diserahkan->format('d/m/Y') }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $barangTitipan->waktu_diserahkan->format('H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function serahkanBarang(id) {
            if (confirm('Yakin barang ini sudah diserahkan ke santri?')) {
                fetch(`/barang-titipan/${id}/serahkan`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message, 'success');
                            location.reload();
                        } else {
                            showNotification(data.error || 'Terjadi kesalahan', 'error');
                        }
                    });
            }
        }

        function ambilBarang(id) {
            if (confirm('Yakin barang ini sudah diambil?')) {
                fetch(`/barang-titipan/${id}/ambil`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message, 'success');
                            location.reload();
                        } else {
                            showNotification(data.error || 'Terjadi kesalahan', 'error');
                        }
                    });
            }
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
    </script>
@endpush
