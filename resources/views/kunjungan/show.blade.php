@extends('layouts.admin')

@section('title', 'Detail Kunjungan')

@section('breadcrumb')
    <li class="flex items-center">
        <i class="fas fa-home text-gray-400 mr-2"></i>
        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <a href="{{ route('kunjungan.index') }}" class="text-gray-600 hover:text-gray-900">Riwayat Kunjungan</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-gray-600">Detail Kunjungan</span>
    </li>
@endsection

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Kunjungan</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap kunjungan {{ $kunjungan->nomor_antrian }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('kunjungan.struk', $kunjungan) }}" target="_blank"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-print mr-2"></i>Cetak Struk
                </a>
                <a href="{{ route('kunjungan.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="bg-white rounded-lg shadow card-shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Kunjungan</h3>
            <div class="relative">
                <div class="flex items-center justify-between">
                    <!-- Daftar -->
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white mb-2">
                            <i class="fas fa-plus"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-900">Daftar</span>
                        <span class="text-xs text-gray-500">{{ $kunjungan->waktu_daftar->format('d/m/Y H:i') }}</span>
                    </div>

                    <!-- Progress Line -->
                    <div class="flex-1 h-0.5 mx-4 {{ $kunjungan->status != 'menunggu' ? 'bg-blue-600' : 'bg-gray-300' }}">
                    </div>

                    <!-- Dipanggil -->
                    <div class="flex flex-col items-center">
                        <div
                            class="w-10 h-10 {{ $kunjungan->waktu_panggil ? 'bg-yellow-600' : 'bg-gray-300' }} rounded-full flex items-center justify-center text-white mb-2">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-900">Dipanggil</span>
                        <span
                            class="text-xs text-gray-500">{{ $kunjungan->waktu_panggil ? $kunjungan->waktu_panggil->format('d/m/Y H:i') : '-' }}</span>
                    </div>

                    <!-- Progress Line -->
                    <div class="flex-1 h-0.5 mx-4 {{ $kunjungan->waktu_mulai ? 'bg-green-600' : 'bg-gray-300' }}"></div>

                    <!-- Berlangsung -->
                    <div class="flex flex-col items-center">
                        <div
                            class="w-10 h-10 {{ $kunjungan->waktu_mulai ? 'bg-green-600' : 'bg-gray-300' }} rounded-full flex items-center justify-center text-white mb-2">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-900">Berlangsung</span>
                        <span
                            class="text-xs text-gray-500">{{ $kunjungan->waktu_mulai ? $kunjungan->waktu_mulai->format('d/m/Y H:i') : '-' }}</span>
                    </div>

                    <!-- Progress Line -->
                    <div class="flex-1 h-0.5 mx-4 {{ $kunjungan->waktu_selesai ? 'bg-purple-600' : 'bg-gray-300' }}"></div>

                    <!-- Selesai -->
                    <div class="flex flex-col items-center">
                        <div
                            class="w-10 h-10 {{ $kunjungan->waktu_selesai ? 'bg-purple-600' : 'bg-gray-300' }} rounded-full flex items-center justify-center text-white mb-2">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-900">Selesai</span>
                        <span
                            class="text-xs text-gray-500">{{ $kunjungan->waktu_selesai ? $kunjungan->waktu_selesai->format('d/m/Y H:i') : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informasi Kunjungan -->
            <div class="bg-white rounded-lg shadow card-shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Kunjungan</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Antrian</label>
                        <p class="mt-1 text-lg font-mono font-semibold text-blue-600">{{ $kunjungan->nomor_antrian }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Pengunjung</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $kunjungan->nama_pengunjung }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hubungan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $kunjungan->hubungan }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <p class="mt-1 text-sm text-gray-900">
                            <a href="tel:{{ $kunjungan->phone_pengunjung }}" class="text-blue-600 hover:text-blue-800">
                                {{ $kunjungan->phone_pengunjung }}
                            </a>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $kunjungan->alamat_pengunjung }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span
                            class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @switch($kunjungan->status)
                                @case('menunggu') bg-yellow-100 text-yellow-800 @break
                                @case('dipanggil') bg-blue-100 text-blue-800 @break
                                @case('berlangsung') bg-green-100 text-green-800 @break
                                @case('selesai') bg-gray-100 text-gray-800 @break
                                @case('dibatalkan') bg-red-100 text-red-800 @break
                            @endswitch">
                            {{ ucfirst($kunjungan->status) }}
                        </span>
                    </div>
                    @if ($kunjungan->catatan)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Catatan</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded">{{ $kunjungan->catatan }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Santri -->
            <div class="bg-white rounded-lg shadow card-shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Santri</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-xl font-bold">{{ substr($kunjungan->santri->nama, 0, 1) }}</span>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">{{ $kunjungan->santri->nama }}</h4>
                            <p class="text-gray-600">{{ $kunjungan->santri->nim }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $kunjungan->santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kamar</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $kunjungan->santri->kamar ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Wali</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $kunjungan->santri->nama_wali }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Telepon Wali</label>
                        <p class="mt-1 text-sm text-gray-900">
                            <a href="tel:{{ $kunjungan->santri->phone_wali }}" class="text-blue-600 hover:text-blue-800">
                                {{ $kunjungan->santri->phone_wali }}
                            </a>
                        </p>
                    </div>
                    <div class="pt-3 border-t">
                        <a href="{{ route('santri.show', $kunjungan->santri) }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="fas fa-external-link-alt mr-1"></i>Lihat Detail Santri
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barang Titipan -->
        @if ($kunjungan->barangTitipan->count() > 0)
            <div class="bg-white rounded-lg shadow card-shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Barang Titipan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kode & Barang
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah
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
                            @foreach ($kunjungan->barangTitipan as $barang)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $barang->kode_barang }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $barang->nama_barang }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $barang->jumlah }}
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('barang-titipan.show', $barang) }}"
                                            class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Statistik Waktu -->
        <div class="bg-white rounded-lg shadow card-shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Statistik Waktu</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">
                            @if ($kunjungan->waktu_panggil && $kunjungan->waktu_daftar)
                                {{ $kunjungan->waktu_daftar->diffInMinutes($kunjungan->waktu_panggil) }} menit
                            @else
                                -
                            @endif
                        </div>
                        <div class="text-sm text-gray-600">Waktu Tunggu</div>
                        <div class="text-xs text-gray-500 mt-1">Daftar → Dipanggil</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">
                            @if ($kunjungan->waktu_mulai && $kunjungan->waktu_selesai)
                                {{ $kunjungan->waktu_mulai->diffInMinutes($kunjungan->waktu_selesai) }} menit
                            @else
                                -
                            @endif
                        </div>
                        <div class="text-sm text-gray-600">Durasi Kunjungan</div>
                        <div class="text-xs text-gray-500 mt-1">Berlangsung → Selesai</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">
                            @if ($kunjungan->waktu_selesai && $kunjungan->waktu_daftar)
                                {{ $kunjungan->waktu_daftar->diffInMinutes($kunjungan->waktu_selesai) }} menit
                            @else
                                -
                            @endif
                        </div>
                        <div class="text-sm text-gray-600">Total Waktu</div>
                        <div class="text-xs text-gray-500 mt-1">Daftar → Selesai</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Log -->
        @if ($kunjungan->logs && $kunjungan->logs->count() > 0)
            <div class="bg-white rounded-lg shadow card-shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Riwayat Aktivitas</h3>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @foreach ($kunjungan->logs->sortByDesc('created_at') as $index => $log)
                                <li>
                                    <div class="relative pb-8">
                                        @if (!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                                aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span
                                                    class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-clock text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-900">{{ $log->description }}</p>
                                                    @if ($log->user)
                                                        <p class="text-xs text-gray-500">oleh {{ $log->user->name }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time
                                                        datetime="{{ $log->created_at }}">{{ $log->created_at->format('d/m/Y H:i') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Aksi Tambahan -->
        @if ($kunjungan->status != 'selesai' && $kunjungan->status != 'dibatalkan')
            <div class="bg-white rounded-lg shadow card-shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Aksi</h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-3">
                        @if ($kunjungan->status == 'menunggu')
                            <form action="{{ route('kunjungan.panggil', $kunjungan) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-bullhorn mr-2"></i>Panggil
                                </button>
                            </form>
                        @endif

                        @if ($kunjungan->status == 'dipanggil')
                            <form action="{{ route('kunjungan.mulai', $kunjungan) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-play mr-2"></i>Mulai Kunjungan
                                </button>
                            </form>
                        @endif

                        @if ($kunjungan->status == 'berlangsung')
                            <form action="{{ route('kunjungan.selesai', $kunjungan) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-check mr-2"></i>Selesai
                                </button>
                            </form>
                        @endif

                        @if (in_array($kunjungan->status, ['menunggu', 'dipanggil']))
                            <button type="button" onclick="batalkanKunjungan()"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-times mr-2"></i>Batalkan
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Konfirmasi Pembatalan -->
    <div id="modalBatalkan" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Batalkan Kunjungan</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Apakah Anda yakin ingin membatalkan kunjungan ini? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <form id="formBatalkan" action="{{ route('kunjungan.batal', $kunjungan) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="px-7 py-3">
                        <textarea name="alasan_batal" rows="3" placeholder="Alasan pembatalan (opsional)"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm"></textarea>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Ya, Batalkan
                        </button>
                        <button type="button" onclick="tutupModal()"
                            class="mt-3 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function batalkanKunjungan() {
            document.getElementById('modalBatalkan').classList.remove('hidden');
        }

        function tutupModal() {
            document.getElementById('modalBatalkan').classList.add('hidden');
        }

        // Tutup modal jika klik di luar modal
        document.getElementById('modalBatalkan').addEventListener('click', function(e) {
            if (e.target === this) {
                tutupModal();
            }
        });

        // Auto refresh status setiap 30 detik
        setInterval(function() {
            if (document.visibilityState === 'visible') {
                location.reload();
            }
        }, 30000);
    </script>
@endsection
