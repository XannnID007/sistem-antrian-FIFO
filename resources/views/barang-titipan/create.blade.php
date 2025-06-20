@extends('layouts.admin')

@section('title', 'Tambah Barang Titipan')

@section('breadcrumb')
    <li class="flex items-center">
        <i class="fas fa-home text-gray-400 mr-2"></i>
        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <a href="{{ route('barang-titipan.index') }}" class="text-gray-600 hover:text-gray-900">Barang Titipan</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-gray-600">Tambah Barang Titipan</span>
    </li>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow card-shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Tambah Barang Titipan</h2>
                <p class="text-sm text-gray-600 mt-1">Daftarkan barang titipan dari pengunjung</p>
            </div>

            <form action="{{ route('barang-titipan.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Pilih Kunjungan -->
                <div class="bg-blue-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Kunjungan Aktif</h3>

                    <div>
                        <label for="kunjungan_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Kunjungan <span class="text-red-500">*</span>
                        </label>
                        <select name="kunjungan_id" id="kunjungan_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Kunjungan --</option>
                            @foreach ($kunjunganAktif as $kunjungan)
                                <option value="{{ $kunjungan->id }}"
                                    {{ old('kunjungan_id') == $kunjungan->id ? 'selected' : '' }}>
                                    {{ $kunjungan->nomor_antrian }} - {{ $kunjungan->nama_pengunjung }}
                                    ({{ $kunjungan->santri->nama }})
                                    - {{ ucfirst($kunjungan->status) }}
                                </option>
                            @endforeach
                        </select>
                        @error('kunjungan_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        @if ($kunjunganAktif->isEmpty())
                            <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded">
                                <p class="text-yellow-700 text-sm">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Tidak ada kunjungan aktif saat ini. Barang titipan hanya bisa didaftarkan untuk
                                    kunjungan yang sedang dipanggil atau berlangsung.
                                </p>
                                <a href="{{ route('kunjungan.antrian') }}"
                                    class="text-yellow-800 font-medium hover:underline">
                                    Lihat antrian kunjungan →
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Data Barang -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Barang</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Barang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_barang" id="nama_barang" required
                                value="{{ old('nama_barang') }}" placeholder="Contoh: Makanan, Pakaian, Buku"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('nama_barang')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="jumlah" id="jumlah" required min="1"
                                value="{{ old('jumlah', 1) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('jumlah')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Detail
                            </label>
                            <textarea name="deskripsi" id="deskripsi" rows="3"
                                placeholder="Deskripsi lebih detail tentang barang (warna, ukuran, merek, dll)"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Tambahan
                            </label>
                            <textarea name="catatan" id="catatan" rows="2" placeholder="Catatan khusus atau instruksi khusus"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('barang-titipan.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit" {{ $kunjunganAktif->isEmpty() ? 'disabled' : '' }}
                        class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-6 py-2 rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan Barang Titipan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
