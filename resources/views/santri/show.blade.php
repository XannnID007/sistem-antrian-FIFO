@extends('layouts.admin')

@section('title', 'Detail Santri')

@section('breadcrumb')
    <li class="flex items-center">
        <i class="fas fa-home text-gray-400 mr-2"></i>
        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <a href="{{ route('santri.index') }}" class="text-gray-600 hover:text-gray-900">Data Santri</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-gray-600">Detail Santri</span>
    </li>
@endsection

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Santri</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap data santri</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('santri.edit', $santri) }}"
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit Data
                </a>
                <a href="{{ route('santri.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Info -->
                <div class="bg-white rounded-lg shadow card-shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Personal</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $santri->nama }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIM</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $santri->nim }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Umur</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $santri->umur }} tahun</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tempat, Tanggal Lahir</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $santri->tempat_lahir }}, {{ $santri->tanggal_lahir->format('d F Y') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tahun Masuk</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $santri->tahun_masuk }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $santri->alamat }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wali Information -->
                <div class="bg-white rounded-lg shadow card-shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Wali</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Wali</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $santri->nama_wali }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    <a href="tel:{{ $santri->phone_wali }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $santri->phone_wali }}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Kunjungan -->
                <div class="bg-white rounded-lg shadow card-shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Riwayat Kunjungan Terbaru</h3>
                    </div>
                    <div class="overflow-hidden">
                        @if ($santri->kunjungan->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Pengunjung
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Hubungan
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tanggal
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Admin
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($santri->kunjungan as $kunjungan)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $kunjungan->nama_pengunjung }}</div>
                                                        <div class="text-sm text-gray-500">{{ $kunjungan->nomor_antrian }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $kunjungan->hubungan }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        {{ $kunjungan->waktu_daftar->format('d/m/Y') }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $kunjungan->waktu_daftar->format('H:i') }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @switch($kunjungan->status)
                                                            @case('menunggu') bg-yellow-100 text-yellow-800 @break
                                                            @case('dipanggil') bg-blue-100 text-blue-800 @break
                                                            @case('berlangsung') bg-green-100 text-green-800 @break
                                                            @case('selesai') bg-gray-100 text-gray-800 @break
                                                            @case('dibatalkan') bg-red-100 text-red-800 @break
                                                        @endswitch">
                                                        {{ ucfirst($kunjungan->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $kunjungan->admin->name }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-inbox text-gray-300 text-3xl mb-3"></i>
                                <p class="text-gray-500">Belum ada riwayat kunjungan</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Profile Card -->
                <div class="bg-white rounded-lg shadow card-shadow">
                    <div class="p-6 text-center">
                        <div
                            class="w-20 h-20 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-white text-2xl font-bold">
                                {{ substr($santri->nama, 0, 1) }}
                            </span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $santri->nama }}</h3>
                        <p class="text-sm text-gray-500">{{ $santri->nim }}</p>

                        <div class="mt-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $santri->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $santri->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Pesantren Info -->
                <div class="bg-white rounded-lg shadow card-shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Info Pesantren</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kamar</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $santri->kamar ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lama Studi</label>
                            <p class="mt-1 text-sm text-gray-900">{{ date('Y') - $santri->tahun_masuk }} tahun</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Terdaftar Sejak</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $santri->created_at->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-lg shadow card-shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Statistik Kunjungan</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Kunjungan</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $santri->kunjungan->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Kunjungan Selesai</span>
                            <span
                                class="text-sm font-semibold text-gray-900">{{ $santri->kunjungan->where('status', 'selesai')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Kunjungan Dibatalkan</span>
                            <span
                                class="text-sm font-semibold text-gray-900">{{ $santri->kunjungan->where('status', 'dibatalkan')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Kunjungan Bulan Ini</span>
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $santri->kunjungan->where('waktu_daftar', '>=', now()->startOfMonth())->count() }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow card-shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('kunjungan.create') }}?santri_id={{ $santri->id }}"
                            class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>Daftar Kunjungan
                        </a>
                        <button onclick="toggleStatus({{ $santri->id }})"
                            class="block w-full {{ $santri->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white text-center py-2 px-4 rounded-lg transition-colors">
                            <i class="fas {{ $santri->is_active ? 'fa-times' : 'fa-check' }} mr-2"></i>
                            {{ $santri->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleStatus(id) {
            if (confirm('Yakin ingin mengubah status santri ini?')) {
                fetch(`/santri/${id}/toggle-status`, {
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
