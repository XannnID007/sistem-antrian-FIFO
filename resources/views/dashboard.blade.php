@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="flex items-center">
        <i class="fas fa-home text-gray-400 mr-2"></i>
        <span class="text-gray-600">Dashboard</span>
    </li>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg text-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}!</h1>
                    <p class="text-blue-100">Sistem Pengelolaan Kunjungan Santri - Pondok Pesantren Salafiyah Al-Jawahir</p>
                </div>
                <div class="text-right">
                    <div id="real-time-clock" class="text-lg font-semibold"></div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Today's Visits -->
            <div class="bg-white rounded-lg shadow card-shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Kunjungan Hari Ini</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_kunjungan_hari_ini'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-gray-500">Total kunjungan hari ini</span>
                </div>
            </div>

            <!-- Waiting Queue -->
            <div class="bg-white rounded-lg shadow card-shadow p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Antrian Menunggu</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_antrian_menunggu'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-gray-500">Pengunjung dalam antrian</span>
                </div>
            </div>

            <!-- Ongoing Visits -->
            <div class="bg-white rounded-lg shadow card-shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Sedang Berlangsung</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_kunjungan_berlangsung'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-gray-500">Kunjungan aktif</span>
                </div>
            </div>

            <!-- Total Santri -->
            <div class="bg-white rounded-lg shadow card-shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Santri</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_santri_aktif'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-gray-500">Santri aktif</span>
                </div>
            </div>
        </div>

        <!-- Charts and Tables Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Daily Statistics Chart -->
            <div class="bg-white rounded-lg shadow card-shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik 7 Hari Terakhir</h3>
                <div class="h-64">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>

            <!-- Current Queue -->
            <div class="bg-white rounded-lg shadow card-shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Antrian Saat Ini</h3>
                    <button onclick="refreshQueue()" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <div id="queue-list" class="space-y-3 max-h-64 overflow-y-auto">
                    @forelse($currentQueue as $queue)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-semibold text-sm">{{ $queue->nomor_antrian }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $queue->nama_pengunjung }}</p>
                                    <p class="text-sm text-gray-500">{{ $queue->santri->nama }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if ($queue->status === 'menunggu') bg-yellow-100 text-yellow-800
                            @elseif($queue->status === 'dipanggil') bg-blue-100 text-blue-800
                            @else bg-green-100 text-green-800 @endif">
                                    {{ ucfirst($queue->status) }}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $queue->waktu_daftar->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-list text-gray-300 text-3xl mb-3"></i>
                            <p class="text-gray-500">Tidak ada antrian saat ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Visits -->
        <div class="bg-white rounded-lg shadow card-shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Kunjungan Terbaru</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pengunjung
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Santri
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Waktu Daftar
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Admin
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentVisits as $visit)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $visit->nomor_antrian }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $visit->nama_pengunjung }}</div>
                                        <div class="text-sm text-gray-500">{{ $visit->hubungan }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $visit->santri->nama }}</div>
                                    <div class="text-sm text-gray-500">{{ $visit->santri->nim }}</div>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $visit->waktu_daftar->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $visit->admin->name }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-3xl mb-3 text-gray-300"></i>
                                    <p>Belum ada kunjungan terbaru</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($recentVisits->count() > 0)
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                    <a href="{{ route('kunjungan.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Lihat semua kunjungan <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push('scripts')
    <script>
        // Daily Statistics Chart
        const ctx = document.getElementById('dailyChart').getContext('2d');
        const dailyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($dailyStats, 'day')) !!},
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
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
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

        // Refresh queue function
        function refreshQueue() {
            fetch('{{ route('dashboard.queue-status') }}')
                .then(response => response.json())
                .then(data => {
                    const queueList = document.getElementById('queue-list');

                    if (data.length === 0) {
                        queueList.innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-list text-gray-300 text-3xl mb-3"></i>
                            <p class="text-gray-500">Tidak ada antrian saat ini</p>
                        </div>
                    `;
                        return;
                    }

                    let html = '';
                    data.forEach(queue => {
                        let statusClass = '';
                        switch (queue.status) {
                            case 'menunggu':
                                statusClass = 'bg-yellow-100 text-yellow-800';
                                break;
                            case 'dipanggil':
                                statusClass = 'bg-blue-100 text-blue-800';
                                break;
                            case 'berlangsung':
                                statusClass = 'bg-green-100 text-green-800';
                                break;
                        }

                        html += `
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-semibold text-sm">${queue.nomor_antrian}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">${queue.nama_pengunjung}</p>
                                    <p class="text-sm text-gray-500">${queue.nama_santri}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">
                                    ${queue.status.charAt(0).toUpperCase() + queue.status.slice(1)}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">${queue.waktu_daftar}</p>
                            </div>
                        </div>
                    `;
                    });

                    queueList.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error refreshing queue:', error);
                });
        }

        // Auto refresh queue every 30 seconds
        setInterval(refreshQueue, 30000);

        // Real-time updates via polling (you can replace this with WebSocket for better performance)
        function updateStats() {
            // Update statistics every minute
            fetch('{{ route('dashboard') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Parse and update only the stats section if needed
                    // This is a simple implementation, you might want to use more sophisticated updates
                })
                .catch(error => console.error('Error updating stats:', error));
        }

        // Update stats every 2 minutes
        setInterval(updateStats, 120000);

        // Notification system (you can extend this)
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

        // Example usage: showNotification('Antrian baru ditambahkan!', 'success');
    </script>
@endpush
