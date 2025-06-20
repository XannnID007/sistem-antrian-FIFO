<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrian - {{ $pesantrenInfo['nama'] }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        .bounce-animation {
            animation: bounce 1s infinite;
        }

        .slide-up {
            animation: slideUp 0.5s ease-out;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        @keyframes bounce {

            0%,
            20%,
            53%,
            80%,
            100% {
                transform: translateY(0);
            }

            40%,
            43% {
                transform: translateY(-10px);
            }

            70% {
                transform: translateY(-5px);
            }

            90% {
                transform: translateY(-2px);
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .digital-clock {
            font-family: 'Courier New', monospace;
            text-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
        }

        .queue-card {
            transition: all 0.3s ease;
        }

        .queue-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .scrolling-text {
            animation: scroll 15s linear infinite;
        }

        @keyframes scroll {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .fullscreen-mode {
            font-size: 1.2em;
        }

        .fullscreen-mode h1 {
            font-size: 3rem;
        }

        .fullscreen-mode .queue-number {
            font-size: 4rem;
        }

        .blink {
            animation: blink 1s infinite;
        }

        @keyframes blink {

            0%,
            50% {
                opacity: 1;
            }

            51%,
            100% {
                opacity: 0.3;
            }
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">
    <!-- Header -->
    <header class="gradient-bg text-white py-6 shadow-lg">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-mosque text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">{{ $pesantrenInfo['nama'] }}</h1>
                        <p class="text-lg opacity-90">Sistem Antrian Kunjungan Santri</p>
                    </div>
                </div>
                <div class="text-right">
                    <div id="current-time" class="text-2xl font-bold digital-clock"></div>
                    <div id="current-date" class="text-lg opacity-90"></div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-8">
        <!-- Current Time & Status -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <div class="flex items-center justify-center space-x-4 mb-4">
                    <div class="w-3 h-3 bg-green-500 rounded-full pulse-animation"></div>
                    <span class="text-lg font-semibold text-gray-800">Sistem Aktif</span>
                </div>

                <!-- Scrolling Information -->
                <div class="bg-blue-50 rounded-lg p-4 overflow-hidden">
                    <div class="scrolling-text text-blue-800 font-medium">
                        📢 Selamat datang di {{ $pesantrenInfo['nama'] }} • Jam operasional:
                        {{ $pesantrenInfo['jam_operasional'] }} • Untuk informasi lebih lanjut hubungi:
                        {{ $pesantrenInfo['telepon'] }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Queue Display Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Current Queue (Being Called) -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="gradient-bg text-white px-6 py-4">
                    <h2 class="text-2xl font-bold flex items-center">
                        <i class="fas fa-bullhorn mr-3"></i>
                        Sedang Dipanggil
                    </h2>
                </div>
                <div class="p-6">
                    <div id="current-queue-list" class="space-y-4 min-h-[300px]">
                        @forelse($currentQueue as $queue)
                            <div
                                class="queue-card bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-6 border-l-4 border-blue-500 slide-up">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div
                                            class="queue-number text-4xl font-bold text-blue-600 mb-2 bounce-animation">
                                            {{ $queue['nomor_antrian'] }}
                                        </div>
                                        <div class="text-xl font-semibold text-gray-800">{{ $queue['nama_pengunjung'] }}
                                        </div>
                                        <div class="text-gray-600">Mengunjungi: {{ $queue['nama_santri'] }}</div>
                                        <div class="text-sm text-gray-500 mt-2">
                                            @if ($queue['status'] == 'dipanggil')
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                                    <i class="fas fa-bullhorn mr-1"></i>Dipanggil:
                                                    {{ $queue['waktu_panggil'] }}
                                                </span>
                                            @elseif($queue['status'] == 'berlangsung')
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800 blink">
                                                    <i class="fas fa-users mr-1"></i>Berlangsung sejak:
                                                    {{ $queue['waktu_mulai'] }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-6xl">
                                        @if ($queue['status'] == 'dipanggil')
                                            <i class="fas fa-bullhorn text-blue-500 pulse-animation"></i>
                                        @else
                                            <i class="fas fa-users text-green-500"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <i class="fas fa-bullhorn text-gray-300 text-6xl mb-4"></i>
                                <p class="text-gray-500 text-xl">Belum ada antrian yang dipanggil</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Waiting Queue -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-yellow-500 text-white px-6 py-4">
                    <h2 class="text-2xl font-bold flex items-center">
                        <i class="fas fa-clock mr-3"></i>
                        Antrian Menunggu
                    </h2>
                </div>
                <div class="p-6">
                    <div id="waiting-queue-list" class="space-y-3 max-h-[300px] overflow-y-auto">
                        @forelse($waitingQueue as $index => $queue)
                            <div class="queue-card bg-yellow-50 rounded-lg p-4 border-l-4 border-yellow-400 slide-up"
                                style="animation-delay: {{ $index * 0.1 }}s">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="w-12 h-12 bg-yellow-200 rounded-full flex items-center justify-center">
                                            <span
                                                class="font-bold text-yellow-800 text-lg">{{ $queue['nomor_antrian'] }}</span>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-800">{{ $queue['nama_pengunjung'] }}
                                            </div>
                                            <div class="text-sm text-gray-600">{{ $queue['nama_santri'] }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm text-gray-500">Daftar: {{ $queue['waktu_daftar'] }}</div>
                                        <div class="text-xs text-yellow-600 font-medium">Urutan ke-{{ $index + 1 }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-clock text-gray-300 text-4xl mb-3"></i>
                                <p class="text-gray-500">Tidak ada antrian menunggu</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Estimated Wait Time -->
        @if ($estimatedWaitTime > 0)
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-hourglass-half text-orange-500 mr-2"></i>
                        Estimasi Waktu Tunggu
                    </h3>
                    <div class="text-4xl font-bold text-orange-600 mb-2">
                        {{ $estimatedWaitTime }} menit
                    </div>
                    <p class="text-gray-600">
                        Berdasarkan {{ count($waitingQueue) }} antrian menunggu × 15 menit per kunjungan
                    </p>
                    <div class="mt-4 bg-orange-50 rounded-lg p-4">
                        <p class="text-sm text-orange-700">
                            <i class="fas fa-info-circle mr-2"></i>
                            Waktu tunggu dapat berubah tergantung durasi kunjungan masing-masing pengunjung
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Statistics Display -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $currentQueue->count() }}</div>
                <div class="text-gray-600">Sedang Dilayani</div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <div class="text-3xl font-bold text-yellow-600 mb-2">{{ $waitingQueue->count() }}</div>
                <div class="text-gray-600">Menunggu</div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">{{ $estimatedWaitTime }}</div>
                <div class="text-gray-600">Menit Tunggu</div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <div class="text-3xl font-bold text-purple-600 mb-2" id="total-today">-</div>
                <div class="text-gray-600">Total Hari Ini</div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="gradient-bg text-white py-6 mt-12">
        <div class="container mx-auto px-6 text-center">
            <div class="flex items-center justify-center space-x-2 mb-2">
                <i class="fas fa-mosque"></i>
                <span class="font-semibold">{{ $pesantrenInfo['nama'] }}</span>
            </div>
            @if ($pesantrenInfo['alamat'])
                <p class="text-sm opacity-90 mb-1">{{ $pesantrenInfo['alamat'] }}</p>
            @endif
            <p class="text-sm opacity-90">
                Telepon: {{ $pesantrenInfo['telepon'] }} •
                Jam Operasional: {{ $pesantrenInfo['jam_operasional'] }}
            </p>
            <p class="text-xs opacity-75 mt-2">© {{ date('Y') }} Sistem Kunjungan Santri</p>
        </div>
    </footer>

    <!-- Control Panel (Hidden by default) -->
    <div id="control-panel" class="fixed bottom-4 right-4 hidden">
        <button onclick="toggleFullscreen()"
            class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full shadow-lg">
            <i class="fas fa-expand"></i>
        </button>
    </div>

    <script>
        // Update time and date
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID');
            const dateString = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            document.getElementById('current-time').textContent = timeString;
            document.getElementById('current-date').textContent = dateString;
        }

        // Auto refresh queue data
        function refreshQueueData() {
            fetch('/display/antrian')
                .then(response => response.json())
                .then(data => {
                    updateCurrentQueue(data.current_queue);
                    updateWaitingQueue(data.waiting_queue);
                    updateEstimatedTime(data.estimated_wait_time);
                })
                .catch(error => {
                    console.log('Error refreshing data:', error);
                });
        }

        // Update current queue display
        function updateCurrentQueue(currentQueue) {
            const container = document.getElementById('current-queue-list');

            if (currentQueue.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-12">
                        <i class="fas fa-bullhorn text-gray-300 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-xl">Belum ada antrian yang dipanggil</p>
                    </div>
                `;
                return;
            }

            let html = '';
            currentQueue.forEach((queue, index) => {
                const statusIcon = queue.status === 'dipanggil' ?
                    '<i class="fas fa-bullhorn text-blue-500 pulse-animation"></i>' :
                    '<i class="fas fa-users text-green-500"></i>';

                const statusBadge = queue.status === 'dipanggil' ?
                    `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                        <i class="fas fa-bullhorn mr-1"></i>Dipanggil: ${queue.waktu_panggil || '-'}
                    </span>` :
                    `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800 blink">
                        <i class="fas fa-users mr-1"></i>Berlangsung sejak: ${queue.waktu_mulai || '-'}
                    </span>`;

                html += `
                    <div class="queue-card bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-6 border-l-4 border-blue-500 slide-up">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="queue-number text-4xl font-bold text-blue-600 mb-2 bounce-animation">
                                    ${queue.nomor_antrian}
                                </div>
                                <div class="text-xl font-semibold text-gray-800">${queue.nama_pengunjung}</div>
                                <div class="text-gray-600">Mengunjungi: ${queue.nama_santri}</div>
                                <div class="text-sm text-gray-500 mt-2">
                                    ${statusBadge}
                                </div>
                            </div>
                            <div class="text-6xl">
                                ${statusIcon}
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        // Update waiting queue display
        function updateWaitingQueue(waitingQueue) {
            const container = document.getElementById('waiting-queue-list');

            if (waitingQueue.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-clock text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">Tidak ada antrian menunggu</p>
                    </div>
                `;
                return;
            }

            let html = '';
            waitingQueue.forEach((queue, index) => {
                html += `
                    <div class="queue-card bg-yellow-50 rounded-lg p-4 border-l-4 border-yellow-400 slide-up" 
                         style="animation-delay: ${index * 0.1}s">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-yellow-200 rounded-full flex items-center justify-center">
                                    <span class="font-bold text-yellow-800 text-lg">${queue.nomor_antrian}</span>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">${queue.nama_pengunjung}</div>
                                    <div class="text-sm text-gray-600">${queue.nama_santri}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-500">Daftar: ${queue.waktu_daftar}</div>
                                <div class="text-xs text-yellow-600 font-medium">Urutan ke-${index + 1}</div>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        // Update estimated wait time
        function updateEstimatedTime(estimatedTime) {
            // This would update the estimated time display
            // Implementation depends on how you want to handle this
        }

        // Toggle fullscreen mode
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().then(() => {
                    document.body.classList.add('fullscreen-mode');
                });
            } else {
                document.exitFullscreen().then(() => {
                    document.body.classList.remove('fullscreen-mode');
                });
            }
        }

        // Show control panel on double click
        document.addEventListener('dblclick', function() {
            const controlPanel = document.getElementById('control-panel');
            controlPanel.classList.toggle('hidden');
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateTime();
            setInterval(updateTime, 1000);

            // Refresh queue data every 10 seconds
            setInterval(refreshQueueData, 10000);

            // Initial data refresh
            refreshQueueData();
        });

        // Add sound notification (optional)
        function playNotificationSound() {
            // You can add sound notification here
            // const audio = new Audio('/sounds/notification.mp3');
            // audio.play();
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // F11 for fullscreen
            if (e.key === 'F11') {
                e.preventDefault();
                toggleFullscreen();
            }

            // R for refresh
            if (e.key === 'r' || e.key === 'R') {
                refreshQueueData();
            }
        });
    </script>
</body>

</html>
