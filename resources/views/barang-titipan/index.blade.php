@extends('layouts.admin')

@section('title', 'Barang Titipan')

@section('breadcrumb')
    <li class="flex items-center">
        <i class="fas fa-home text-gray-400 mr-2"></i>
        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-gray-600">Barang Titipan</span>
    </li>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Barang Titipan</h1>
                <p class="text-gray-600 mt-1">Kelola barang titipan pengunjung</p>
            </div>
            <a href="{{ route('barang-titipan.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>Tambah Barang Titipan
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-600 text-sm font-medium">Dititipkan</p>
                        <p class="text-yellow-900 text-2xl font-bold">
                            {{ $barangTitipan->where('status', 'dititipkan')->count() }}
                        </p>
                    </div>
                    <i class="fas fa-box text-yellow-500 text-2xl"></i>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-medium">Diserahkan</p>
                        <p class="text-blue-900 text-2xl font-bold">
                            {{ $barangTitipan->where('status', 'diserahkan')->count() }}
                        </p>
                    </div>
                    <i class="fas fa-hand-holding text-blue-500 text-2xl"></i>
                </div>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-medium">Diambil</p>
                        <p class="text-green-900 text-2xl font-bold">
                            {{ $barangTitipan->where('status', 'diambil')->count() }}
                        </p>
                    </div>
                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                </div>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total</p>
                        <p class="text-gray-900 text-2xl font-bold">{{ $barangTitipan->count() }}</p>
                    </div>
                    <i class="fas fa-boxes text-gray-500 text-2xl"></i>
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
                        placeholder="Kode, nama barang, pengunjung..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Status Filter -->
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

        <!-- Barang Titipan Table -->
        <div class="bg-white rounded-lg shadow card-shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kode & Barang
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pengunjung & Santri
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
                        @forelse($barangTitipan as $barang)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $barang->kode_barang }}</div>
                                        <div class="text-sm text-gray-500">{{ $barang->nama_barang }}</div>
                                        <div class="text-xs text-gray-400">Jumlah: {{ $barang->jumlah }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $barang->kunjungan->nama_pengunjung }}</div>
                                        <div class="text-sm text-gray-500">{{ $barang->kunjungan->santri->nama }}</div>
                                        <div class="text-xs text-gray-400">{{ $barang->kunjungan->nomor_antrian }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <div>Titip: {{ $barang->waktu_dititipkan->format('d/m/Y H:i') }}</div>
                                        @if ($barang->waktu_diserahkan)
                                            <div class="text-blue-600">Serah:
                                                {{ $barang->waktu_diserahkan->format('d/m/Y H:i') }}</div>
                                        @endif
                                        @if ($barang->waktu_diambil)
                                            <div class="text-green-600">Ambil:
                                                {{ $barang->waktu_diambil->format('d/m/Y H:i') }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @switch($barang->status)
                                    @case('dititipkan') bg-yellow-100 text-yellow-800 @break
                                    @case('diserahkan') bg-blue-100 text-blue-800 @break
                                    @case('diambil') bg-green-100 text-green-800 @break
                                @endswitch">
                                        {{ ucfirst($barang->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $barang->adminPenerima->name }}</div>
                                    @if ($barang->adminPenyerah)
                                        <div class="text-xs text-gray-500">Serah: {{ $barang->adminPenyerah->name }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('barang-titipan.show', $barang) }}"
                                            class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if ($barang->status == 'dititipkan')
                                            <button onclick="serahkanBarang({{ $barang->id }})"
                                                class="text-green-600 hover:text-green-900" title="Serahkan ke Santri">
                                                <i class="fas fa-hand-holding"></i>
                                            </button>
                                            <a href="{{ route('barang-titipan.edit', $barang) }}"
                                                class="text-yellow-600 hover:text-yellow-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif

                                        @if ($barang->status == 'diserahkan')
                                            <button onclick="ambilBarang({{ $barang->id }})"
                                                class="text-purple-600 hover:text-purple-900"
                                                title="Tandai Sudah Diambil">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        @endif

                                        <a href="{{ route('barang-titipan.struk', $barang) }}"
                                            class="text-gray-600 hover:text-gray-900" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>

                                        @if ($barang->status == 'dititipkan')
                                            <button onclick="deleteBarang({{ $barang->id }})"
                                                class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-box text-3xl mb-3 text-gray-300"></i>
                                    <p>Belum ada data barang titipan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($barangTitipan->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200">
                    {{ $barangTitipan->links() }}
                </div>
            @endif
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

        function deleteBarang(id) {
            if (confirm('Yakin ingin menghapus data barang titipan ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/barang-titipan/${id}`;
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
