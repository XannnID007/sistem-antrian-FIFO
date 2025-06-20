@extends('layouts.admin')

@section('title', 'Riwayat Kunjungan')

@section('breadcrumb')
    <li class="flex items-center">
        <i class="fas fa-home text-gray-400 mr-2"></i>
        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-gray-600">Riwayat Kunjungan</span>
    </li>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Riwayat Kunjungan</h1>
                <p class="text-gray-600 mt-1">Data riwayat kunjungan santri</p>
            </div>
            <a href="{{ route('kunjungan.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>Daftar Kunjungan Baru
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-medium">Selesai</p>
                        <p class="text-green-900 text-2xl font-bold">
                            {{ $kunjungan->where('status', 'selesai')->count() }}
                        </p>
                    </div>
                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-medium">Berlangsung</p>
                        <p class="text-blue-900 text-2xl font-bold">
                            {{ $kunjungan->where('status', 'berlangsung')->count() }}
                        </p>
                    </div>
                    <i class="fas fa-users text-blue-500 text-2xl"></i>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-600 text-sm font-medium">Menunggu</p>
                        <p class="text-yellow-900 text-2xl font-bold">
                            {{ $kunjungan->where('status', 'menunggu')->count() }}
                        </p>
                    </div>
                    <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                </div>
            </div>

            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-600 text-sm font-medium">Dibatalkan</p>
                        <p class="text-red-900 text-2xl font-bold">
                            {{ $kunjungan->where('status', 'dibatalkan')->count() }}
                        </p>
                    </div>
                    <i class="fas fa-times-circle text-red-500 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow card-shadow p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nomor antrian, pengunjung, santri..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="dipanggil" {{ request('status') == 'dipanggil' ? 'selected' : '' }}>Dipanggil
                        </option>
                        <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung
                        </option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan
                        </option>
                    </select>
                </div>

                <!-- Tanggal Mulai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Tanggal Selesai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Submit Button -->
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Kunjungan Table -->
        <div class="bg-white rounded-lg shadow card-shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Antrian & Pengunjung
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Santri
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Waktu
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Admin
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($kunjungan as $visit)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $visit->nomor_antrian }}</div>
                                        <div class="text-sm text-gray-600">{{ $visit->nama_pengunjung }}</div>
                                        <div class="text-xs text-gray-500">{{ $visit->hubungan }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $visit->santri->nama }}</div>
                                        <div class="text-sm text-gray-500">{{ $visit->santri->nim }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <div>Daftar: {{ $visit->waktu_daftar->format('d/m/Y H:i') }}</div>
                                        @if ($visit->waktu_selesai)
                                            <div class="text-green-600">Selesai:
                                                {{ $visit->waktu_selesai->format('d/m/Y H:i') }}</div>
                                        @endif
                                        @if ($visit->waktu_tunggu)
                                            <div class="text-xs text-gray-500">Tunggu: {{ $visit->waktu_tunggu }} menit
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @switch($visit->status)
                                            @case('menunggu') bg-yellow-100 text-yellow-800 @break
                                            @case('dipanggil') bg-blue-100 text-blue-800 @break
                                            @case('berlangsung') bg-green-100 text-green-800 @break
                                            @case('selesai') bg-gray-100 text-gray-800 @break
                                            @case('dibatalkan') bg-red-100 text-red-800 @break
                                        @endswitch">
                                        {{ ucfirst($visit->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $visit->admin->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('kunjungan.show', $visit) }}"
                                            class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('kunjungan.struk', $visit) }}" target="_blank"
                                            class="text-gray-600 hover:text-gray-900">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-3xl mb-3 text-gray-300"></i>
                                    <p>Belum ada data kunjungan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($kunjungan->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200">
                    {{ $kunjungan->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
