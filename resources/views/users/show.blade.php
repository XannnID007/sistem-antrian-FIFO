@extends('layouts.admin')

@section('title', 'Detail User')

@section('breadcrumb')
    <li class="flex items-center">
        <i class="fas fa-home text-gray-400 mr-2"></i>
        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <a href="{{ route('users.index') }}" class="text-gray-600 hover:text-gray-900">Kelola User</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-gray-600">Detail User</span>
    </li>
@endsection

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail User</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap data user</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('users.edit', $user) }}"
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit User
                </a>
                <a href="{{ route('users.index') }}"
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
                                <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    <a href="mailto:{{ $user->email }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $user->email }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Username</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->username }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if ($user->phone)
                                        <a href="tel:{{ $user->phone }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $user->phone }}
                                        </a>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->address ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Info -->
                <div class="bg-white rounded-lg shadow card-shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Akun</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Role</label>
                                <p class="mt-1">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $user->role === 'pengasuh' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <p class="mt-1">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Terdaftar Sejak</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d F Y H:i') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Terakhir Diperbarui</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Statistics -->
                @if ($user->role === 'admin')
                    <div class="bg-white rounded-lg shadow card-shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Statistik Aktivitas</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ $user->kunjunganAdmin->count() }}
                                    </div>
                                    <div class="text-sm text-gray-500">Kunjungan Diproses</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">
                                        {{ $user->barangTitipanPenerima->count() }}</div>
                                    <div class="text-sm text-gray-500">Barang Diterima</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-purple-600">
                                        {{ $user->barangTitipanPenyerah->count() }}</div>
                                    <div class="text-sm text-gray-500">Barang Diserahkan</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Profile Card -->
                <div class="bg-white rounded-lg shadow card-shadow">
                    <div class="p-6 text-center">
                        <div
                            class="w-20 h-20 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-white text-2xl font-bold">
                                {{ substr($user->name, 0, 1) }}
                            </span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500">{{ ucfirst($user->role) }}</p>

                        <div class="mt-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Role Permissions -->
                <div class="bg-white rounded-lg shadow card-shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Akses & Permissions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @if ($user->role === 'pengasuh')
                            <div class="flex items-center text-sm text-green-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                Data Santri
                            </div>
                            <div class="flex items-center text-sm text-green-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                Kelola User
                            </div>
                            <div class="flex items-center text-sm text-green-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                Pengaturan Sistem
                            </div>
                            <div class="flex items-center text-sm text-green-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                Laporan Lanjutan
                            </div>
                        @else
                            <div class="flex items-center text-sm text-blue-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                Kunjungan
                            </div>
                            <div class="flex items-center text-sm text-blue-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                Barang Titipan
                            </div>
                            <div class="flex items-center text-sm text-blue-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                Laporan
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow card-shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <button onclick="toggleStatus({{ $user->id }})"
                            class="block w-full {{ $user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white text-center py-2 px-4 rounded-lg transition-colors">
                            <i class="fas {{ $user->is_active ? 'fa-times' : 'fa-check' }} mr-2"></i>
                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                        <button onclick="resetPassword({{ $user->id }})"
                            class="block w-full bg-orange-600 hover:bg-orange-700 text-white text-center py-2 px-4 rounded-lg transition-colors">
                            <i class="fas fa-key mr-2"></i>Reset Password
                        </button>
                        @if ($user->id !== auth()->id())
                            <button onclick="deleteUser({{ $user->id }})"
                                class="block w-full bg-red-600 hover:bg-red-700 text-white text-center py-2 px-4 rounded-lg transition-colors">
                                <i class="fas fa-trash mr-2"></i>Hapus User
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleStatus(id) {
            if (confirm('Yakin ingin mengubah status user ini?')) {
                fetch(`/users/${id}/toggle-status`, {
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

        function resetPassword(id) {
            if (confirm('Yakin ingin reset password user ini?')) {
                fetch(`/users/${id}/reset-password`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(`Password berhasil direset. Password baru: ${data.new_password}`,
                                'success');
                        } else {
                            showNotification(data.error || 'Terjadi kesalahan', 'error');
                        }
                    });
            }
        }

        function deleteUser(id) {
            if (confirm('Yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/users/${id}`;
                form.innerHTML = `
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                <input type="hidden" name="_method" value="DELETE">
            `;
                document.body.appendChild(form);
                form.submit();
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
