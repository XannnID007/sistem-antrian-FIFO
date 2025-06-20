@extends('layouts.admin')

@section('title', 'Kelola Antrian')

@section('breadcrumb')
    <li class="flex items-center">
        <i class="fas fa-home text-gray-400 mr-2"></i>
        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-gray-600">Kelola Antrian</span>
    </li>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Antrian Kunjungan</h1>
                <p class="text-gray-600 mt-1">Sistem antrian menggunakan algoritma FIFO (First In, First Out)</p>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="refreshAntrian()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh
                </button>
                <a href="{{ route('kunjungan.create') }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Daftar Baru
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-600 text-sm font-medium">Menunggu</p>
                        <p class="text-yellow-900 text-2xl font-bold">{{ $menunggu->count() }}</p>
                    </div>
                    <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-medium">Dipanggil</p>
                        <p class="text-blue-900 text-2xl font-bold">{{ $dipanggil->count() }}</p>
                    </div>
                    <i class="fas fa-bullhorn text-blue-500 text-2xl"></i>
                </div>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-medium">Berlangsung</p>
                        <p class="text-green-900 text-2xl font-bold">{{ $berlangsung->count() }}</p>
                    </div>
                    <i class="fas fa-users text-green-500 text-2xl"></i>
                </div>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Hari Ini</p>
                        <p class="text-gray-900 text-2xl font-bold">
                            {{ $menunggu->count() + $dipanggil->count() + $berlangsung->count() }}</p>
                    </div>
                    <i class="fas fa-chart-bar text-gray-500 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Queue Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Antrian Menunggu -->
            <div class="bg-white rounded-lg shadow card-shadow">
                <div class="px-6 py-4 border-b border-gray-200 bg-yellow-50">
                    <h3 class="text-lg font-semibold text-yellow-800">
                        <i class="fas fa-clock mr-2"></i>Antrian Menunggu ({{ $menunggu->count() }})
                    </h3>
                </div>
                <div class="p-4 space-y-3 max-h-96 overflow-y-auto">
                    @forelse($menunggu as $kunjungan)
                        <div
                            class="border border-yellow-200 rounded-lg p-4 bg-yellow-50 hover:bg-yellow-100 transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-yellow-200 rounded-full flex items-center justify-center">
                                        <span class="font-bold text-yellow-800">{{ $kunjungan->nomor_antrian }}</span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $kunjungan->nama_pengunjung }}</h4>
                                        <p class="text-sm text-gray-600">{{ $kunjungan->santri->nama }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">{{ $kunjungan->waktu_daftar->format('H:i') }}</p>
                                    <p class="text-xs text-yellow-600">
                                        {{ $kunjungan->waktu_daftar->diffInMinutes(now()) }} menit
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ $kunjungan->hubungan }}</span>
                                <div class="flex space-x-2">
                                    <button onclick="panggilAntrian({{ $kunjungan->id }})"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                        <i class="fas fa-bullhorn mr-1"></i>Panggil
                                    </button>
                                    <button onclick="batalkanAntrian({{ $kunjungan->id }})"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                        <i class="fas fa-times mr-1"></i>Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-clock text-gray-300 text-3xl mb-3"></i>
                            <p class="text-gray-500">Tidak ada antrian menunggu</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Antrian Dipanggil -->
            <div class="bg-white rounded-lg shadow card-shadow">
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                    <h3 class="text-lg font-semibold text-blue-800">
                        <i class="fas fa-bullhorn mr-2"></i>Sudah Dipanggil ({{ $dipanggil->count() }})
                    </h3>
                </div>
                <div class="p-4 space-y-3 max-h-96 overflow-y-auto">
                    @forelse($dipanggil as $kunjungan)
                        <div class="border border-blue-200 rounded-lg p-4 bg-blue-50 hover:bg-blue-100 transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-200 rounded-full flex items-center justify-center">
                                        <span class="font-bold text-blue-800">{{ $kunjungan->nomor_antrian }}</span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $kunjungan->nama_pengunjung }}</h4>
                                        <p class="text-sm text-gray-600">{{ $kunjungan->santri->nama }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">Dipanggil:
                                        {{ $kunjungan->waktu_panggil->format('H:i') }}</p>
                                    <p class="text-xs text-blue-600">
                                        {{ $kunjungan->waktu_panggil->diffInMinutes(now()) }} menit lalu
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ $kunjungan->hubungan }}</span>
                                <div class="flex space-x-2">
                                    <button onclick="mulaiKunjungan({{ $kunjungan->id }})"
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                        <i class="fas fa-play mr-1"></i>Mulai
                                    </button>
                                    <button onclick="batalkanAntrian({{ $kunjungan->id }})"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                        <i class="fas fa-times mr-1"></i>Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-bullhorn text-gray-300 text-3xl mb-3"></i>
                            <p class="text-gray-500">Tidak ada yang dipanggil</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Kunjungan Berlangsung -->
            <div class="bg-white rounded-lg shadow card-shadow">
                <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                    <h3 class="text-lg font-semibold text-green-800">
                        <i class="fas fa-users mr-2"></i>Sedang Berlangsung ({{ $berlangsung->count() }})
                    </h3>
                </div>
                <div class="p-4 space-y-3 max-h-96 overflow-y-auto">
                    @forelse($berlangsung as $kunjungan)
                        <div
                            class="border border-green-200 rounded-lg p-4 bg-green-50 hover:bg-green-100 transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-200 rounded-full flex items-center justify-center">
                                        <span class="font-bold text-green-800">{{ $kunjungan->nomor_antrian }}</span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $kunjungan->nama_pengunjung }}</h4>
                                        <p class="text-sm text-gray-600">{{ $kunjungan->santri->nama }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">Mulai: {{ $kunjungan->waktu_mulai->format('H:i') }}
                                    </p>
                                    <p class="text-xs text-green-600">
                                        {{ $kunjungan->waktu_mulai->diffInMinutes(now()) }} menit
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ $kunjungan->hubungan }}</span>
                                <div class="flex space-x-2">
                                    <button onclick="selesaiKunjungan({{ $kunjungan->id }})"
                                        class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                        <i class="fas fa-check mr-1"></i>Selesai
                                    </button>
                                    <a href="{{ route('kunjungan.show', $kunjungan) }}"
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                        <i class="fas fa-eye mr-1"></i>Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-users text-gray-300 text-3xl mb-3"></i>
                            <p class="text-gray-500">Tidak ada kunjungan berlangsung</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Display Antrian Publik -->
        <div class="bg-white rounded-lg shadow card-shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Display Antrian Publik</h3>
                    <button onclick="toggleFullscreen()"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-expand mr-2"></i>Fullscreen
                    </button>
                </div>
            </div>
            <div id="public-display" class="p-6">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Antrian Kunjungan Santri</h2>
                    <p class="text-lg text-gray-600 mb-8">Pondok Pesantren Salafiyah Al-Jawahir</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Nomor Antrian Aktif -->
                        <div class="bg-blue-50 rounded-lg p-6">
                            <h3 class="text-xl font-semibold text-blue-800 mb-4">Nomor Antrian Aktif</h3>
                            <div class="space-y-4">
                                @if ($dipanggil->isNotEmpty())
                                    @foreach ($dipanggil->take(3) as $kunjungan)
                                        <div class="bg-white rounded-lg p-4 border-2 border-blue-200">
                                            <div class="text-4xl font-bold text-blue-600 mb-2">
                                                {{ $kunjungan->nomor_antrian }}</div>
                                            <div class="text-lg text-gray-700">{{ $kunjungan->nama_pengunjung }}</div>
                                            <div class="text-sm text-gray-500">{{ $kunjungan->santri->nama }}</div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-gray-500">Belum ada antrian dipanggil</div>
                                @endif
                            </div>
                        </div>

                        <!-- Antrian Selanjutnya -->
                        <div class="bg-yellow-50 rounded-lg p-6">
                            <h3 class="text-xl font-semibold text-yellow-800 mb-4">Antrian Selanjutnya</h3>
                            <div class="space-y-3">
                                @if ($menunggu->isNotEmpty())
                                    @foreach ($menunggu->take(5) as $kunjungan)
                                        <div
                                            class="bg-white rounded-lg p-3 border border-yellow-200 flex justify-between items-center">
                                            <span
                                                class="text-lg font-semibold text-yellow-600">{{ $kunjungan->nomor_antrian }}</span>
                                            <span class="text-gray-700">{{ $kunjungan->nama_pengunjung }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-gray-500">Tidak ada antrian menunggu</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Estimasi Waktu Tunggu -->
                    @if ($menunggu->isNotEmpty())
                        <div class="mt-8 bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Estimasi Waktu Tunggu</h3>
                            <div class="text-2xl font-bold text-gray-900">
                                {{ $menunggu->count() * 15 }} menit
                            </div>
                            <p class="text-sm text-gray-600 mt-2">Berdasarkan {{ $menunggu->count() }} antrian × 15 menit
                                per kunjungan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Panggil antrian
        function panggilAntrian(id) {
            if (confirm('Yakin ingin memanggil antrian ini?')) {
                fetch(`/kunjungan/${id}/panggil`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
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
                    })
                    .catch(error => {
                        showNotification('Terjadi kesalahan sistem', 'error');
                        console.error('Error:', error);
                    });
            }
        }

        // Mulai kunjungan
        function mulaiKunjungan(id) {
            if (confirm('Yakin ingin memulai kunjungan ini?')) {
                fetch(`/kunjungan/${id}/mulai`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
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
                    })
                    .catch(error => {
                        showNotification('Terjadi kesalahan sistem', 'error');
                        console.error('Error:', error);
                    });
            }
        }

        // Selesai kunjungan
        function selesaiKunjungan(id) {
            if (confirm('Yakin kunjungan ini sudah selesai?')) {
                fetch(`/kunjungan/${id}/selesai`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
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
                    })
                    .catch(error => {
                        showNotification('Terjadi kesalahan sistem', 'error');
                        console.error('Error:', error);
                    });
            }
        }

        // Batalkan antrian
        function batalkanAntrian(id) {
            if (confirm('Yakin ingin membatalkan antrian ini?')) {
                fetch(`/kunjungan/${id}/batal`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
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
                    })
                    .catch(error => {
                        showNotification('Terjadi kesalahan sistem', 'error');
                        console.error('Error:', error);
                    });
            }
        }

        // Refresh antrian
        function refreshAntrian() {
            location.reload();
        }

        // Toggle fullscreen untuk display publik
        function toggleFullscreen() {
            const element = document.getElementById('public-display');

            if (!document.fullscreenElement) {
                element.requestFullscreen().then(() => {
                    element.classList.add('fullscreen-display');
                });
            } else {
                document.exitFullscreen().then(() => {
                    element.classList.remove('fullscreen-display');
                });
            }
        }

        // Notification function
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
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
            </div>
        `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        // Auto refresh setiap 30 detik
        setInterval(function() {
            if (!document.hidden) {
                refreshAntrian();
            }
        }, 30000);

        // Sound notification (optional)
        function playNotificationSound() {
            // Anda bisa menambahkan suara notifikasi di sini
            // const audio = new Audio('/sounds/notification.mp3');
            // audio.play();
        }
    </script>

    <style>
        .fullscreen-display {
            background: white;
            padding: 2rem;
        }

        .fullscreen-display h2 {
            font-size: 4rem;
            margin-bottom: 2rem;
        }

        .fullscreen-display .text-4xl {
            font-size: 6rem;
        }

        .fullscreen-display .text-lg {
            font-size: 2rem;
        }

        .fullscreen-display .text-xl {
            font-size: 3rem;
        }
    </style>
@endpush
