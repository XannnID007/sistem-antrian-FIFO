@extends('layouts.admin')

@section('title', 'Laporan Statistik')

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
        <span class="text-gray-600">Statistik</span>
    </li>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Statistik</h1>
                <p class="text-gray-600 mt-1">Analisis statistik dan tren data {{ $periode }} hari terakhir</p>
            </div>
            <div class="flex space-x-3">
                <select onchange="changePeriode(this.value)"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="30" {{ $periode == 30 ? 'selected' : '' }}>30 Hari</option>
                    <option value="60" {{ $periode == 60 ? 'selected' : '' }}>60 Hari</option>
                    <option value="90" {{ $periode == 90 ? 'selected' : '' }}>90 Hari</option>
                </select>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Daily Statistics Chart -->
            <div class="bg-white rounded-lg shadow card-shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tren Kunjungan Harian</h3>
                <div class="h-64">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>

            <!-- Peak Hours Chart -->
            <div class="bg-white rounded-lg shadow card-shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Jam Sibuk</h3>
                <div class="h-64">
                    <canvas id="peakHoursChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Santri Section -->
        <div class="bg-white rounded-lg shadow card-shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top 10 Santri Paling Sering Dikunjungi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ranking</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Santri</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kamar
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                                Kunjungan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Terakhir Dikunjungi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topSantri as $index => $santri)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if ($index == 0)
                                            <i class="fas fa-trophy text-yellow-500 text-lg mr-2"></i>
                                        @elseif($index == 1)
                                            <i class="fas fa-medal text-gray-400 text-lg mr-2"></i>
                                        @elseif($index == 2)
                                            <i class="fas fa-award text-yellow-600 text-lg mr-2"></i>
                                        @else
                                            <span class="text-sm font-medium text-gray-900 ml-6">{{ $index + 1 }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center mr-3">
                                            <span
                                                class="text-white text-sm font-medium">{{ substr($santri->nama, 0, 1) }}</span>
                                        </div>
                                        <div class="text-sm font-medium text-gray-900">{{ $santri->nama }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $santri->nim }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $santri->kamar ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $santri->kunjungan_count }} kunjungan
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $santri->kunjungan->first() ? $santri->kunjungan->first()->waktu_daftar->format('d/m/Y') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-chart-bar text-3xl mb-3 text-gray-300"></i>
                                    <p>Belum ada data kunjungan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Daily Statistics Table -->
        <div class="bg-white rounded-lg shadow card-shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Data Harian</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kunjungan Masuk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kunjungan Selesai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Barang Titipan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($dailyStats as $stat)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($stat['tanggal'])->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $stat['hari'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $stat['kunjungan'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $stat['selesai'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $stat['barang_titipan'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push('scripts')
    <script>
        // Daily Statistics Chart
        const dailyCtx = document.getElementById('dailyChart').getContext('2d');
        const dailyChart = new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($dailyStats, 'hari')) !!},
                datasets: [{
                    label: 'Kunjungan Masuk',
                    data: {!! json_encode(array_column($dailyStats, 'kunjungan')) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Kunjungan Selesai',
                    data: {!! json_encode(array_column($dailyStats, 'selesai')) !!},
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Barang Titipan',
                    data: {!! json_encode(array_column($dailyStats, 'barang_titipan')) !!},
                    borderColor: 'rgb(245, 158, 11)',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Peak Hours Chart
        const peakCtx = document.getElementById('peakHoursChart').getContext('2d');
        const peakChart = new Chart(peakCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(
                    $peakHours->pluck('hour')->map(function ($hour) {
                        return sprintf('%02d:00', $hour);
                    }),
                ) !!},
                datasets: [{
                    label: 'Jumlah Kunjungan',
                    data: {!! json_encode($peakHours->pluck('count')) !!},
                    backgroundColor: 'rgba(99, 102, 241, 0.8)',
                    borderColor: 'rgb(99, 102, 241)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        function changePeriode(periode) {
            const url = new URL(window.location);
            url.searchParams.set('periode', periode);
            window.location.href = url.toString();
        }
    </script>
@endpush
