@extends('layouts.admin')

@section('title', 'Jam Operasional')

@section('breadcrumb')
    <li class="flex items-center">
        <i class="fas fa-home text-gray-400 mr-2"></i>
        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-gray-600">Jam Operasional</span>
    </li>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Jam Operasional</h1>
                <p class="text-gray-600 mt-1">Atur jam operasional kunjungan santri</p>
            </div>
            <button onclick="openAddModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>Tambah Jam Operasional
            </button>
        </div>

        <!-- Current Status -->
        <div class="bg-white rounded-lg shadow card-shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Status Saat Ini</h3>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full pulse-animation"></div>
                    <span class="text-sm font-medium text-green-600" id="operational-status">
                        Sistem Operasional
                    </span>
                </div>
            </div>
            <p class="text-gray-600" id="current-time-info">
                Saat ini: <span class="font-medium" id="current-datetime"></span>
            </p>
        </div>

        <!-- Jam Operasional Table -->
        <div class="bg-white rounded-lg shadow card-shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hari
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jam Buka
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jam Tutup
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Durasi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($jamOperasional as $jam)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-calendar-day text-blue-600 text-sm"></i>
                                        </div>
                                        <div class="text-sm font-medium text-gray-900">{{ ucfirst($jam->hari) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($jam->jam_buka)->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($jam->jam_tutup)->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($jam->jam_buka)->diffInHours(\Carbon\Carbon::parse($jam->jam_tutup)) }}
                                        jam
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button onclick="toggleStatus({{ $jam->id }})"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                           {{ $jam->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $jam->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button
                                            onclick="editJam({{ $jam->id }}, '{{ $jam->hari }}', '{{ \Carbon\Carbon::parse($jam->jam_buka)->format('H:i') }}', '{{ \Carbon\Carbon::parse($jam->jam_tutup)->format('H:i') }}', {{ $jam->is_active ? 'true' : 'false' }})"
                                            class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteJam({{ $jam->id }})"
                                            class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-clock text-3xl mb-3 text-gray-300"></i>
                                    <p>Belum ada jam operasional yang diatur</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Weekly Overview -->
        <div class="bg-white rounded-lg shadow card-shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Mingguan</h3>
            <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
                @php
                    $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
                    $dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                @endphp
                @foreach ($days as $index => $day)
                    @php
                        $jamHari = $jamOperasional->where('hari', $day)->first();
                    @endphp
                    <div
                        class="text-center p-4 border rounded-lg {{ $jamHari && $jamHari->is_active ? 'border-green-200 bg-green-50' : 'border-gray-200 bg-gray-50' }}">
                        <div class="font-medium text-gray-900 mb-2">{{ $dayNames[$index] }}</div>
                        @if ($jamHari && $jamHari->is_active)
                            <div class="text-sm text-green-600">
                                {{ \Carbon\Carbon::parse($jamHari->jam_buka)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($jamHari->jam_tutup)->format('H:i') }}
                            </div>
                            <div class="text-xs text-green-500 mt-1">Buka</div>
                        @else
                            <div class="text-sm text-gray-400">Tutup</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="jamModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">Tambah Jam Operasional</h3>

                <form id="jamForm" method="POST">
                    @csrf
                    <div id="methodField"></div>

                    <div class="space-y-4">
                        <div>
                            <label for="hari" class="block text-sm font-medium text-gray-700 mb-2">
                                Hari <span class="text-red-500">*</span>
                            </label>
                            <select name="hari" id="hari" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Hari --</option>
                                <option value="senin">Senin</option>
                                <option value="selasa">Selasa</option>
                                <option value="rabu">Rabu</option>
                                <option value="kamis">Kamis</option>
                                <option value="jumat">Jumat</option>
                                <option value="sabtu">Sabtu</option>
                                <option value="minggu">Minggu</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="jam_buka" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jam Buka <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="jam_buka" id="jam_buka" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="jam_tutup" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jam Tutup <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="jam_tutup" id="jam_tutup" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                Aktif
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeModal()"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Update current time
        function updateCurrentTime() {
            const now = new Date();
            const timeString = now.toLocaleString('id-ID');
            document.getElementById('current-datetime').textContent = timeString;
        }

        // Open add modal
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Jam Operasional';
            document.getElementById('jamForm').action = '{{ route('jam-operasional.store') }}';
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('jamForm').reset();
            document.getElementById('is_active').checked = true;
            document.getElementById('jamModal').classList.remove('hidden');
        }

        // Edit jam operasional
        function editJam(id, hari, jamBuka, jamTutup, isActive) {
            document.getElementById('modalTitle').textContent = 'Edit Jam Operasional';
            document.getElementById('jamForm').action = `/jam-operasional/${id}`;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            document.getElementById('hari').value = hari;
            document.getElementById('jam_buka').value = jamBuka;
            document.getElementById('jam_tutup').value = jamTutup;
            document.getElementById('is_active').checked = isActive;

            document.getElementById('jamModal').classList.remove('hidden');
        }

        // Close modal
        function closeModal() {
            document.getElementById('jamModal').classList.add('hidden');
        }

        // Toggle status
        function toggleStatus(id) {
            if (confirm('Yakin ingin mengubah status jam operasional ini?')) {
                fetch(`/jam-operasional/${id}/toggle-status`, {
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

        // Delete jam operasional
        function deleteJam(id) {
            if (confirm('Yakin ingin menghapus jam operasional ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/jam-operasional/${id}`;
                form.innerHTML = `
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                <input type="hidden" name="_method" value="DELETE">
            `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Show notification
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

        // Close modal when clicking outside
        document.getElementById('jamModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateCurrentTime();
            setInterval(updateCurrentTime, 1000);
        });
    </script>
@endpush

@push('styles')
    <style>
        .pulse-animation {
            animation: pulse 2s infinite;
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
    </style>
@endpush
