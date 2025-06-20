@extends('layouts.admin')

@section('title', 'Edit Barang Titipan')

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
        <span class="text-gray-600">Edit Barang Titipan</span>
    </li>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow card-shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Edit Barang Titipan</h2>
                <p class="text-sm text-gray-600 mt-1">Perbarui informasi barang titipan</p>
            </div>

            <form action="{{ route('barang-titipan.update', $barangTitipan) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Info Kunjungan -->
                <div class="bg-blue-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Kunjungan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Antrian</label>
                            <p class="text-sm text-gray-900 bg-white p-2 rounded border">
                                {{ $barangTitipan->kunjungan->nomor_antrian }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pengunjung</label>
                            <p class="text-sm text-gray-900 bg-white p-2 rounded border">
                                {{ $barangTitipan->kunjungan->nama_pengunjung }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Santri</label>
                            <p class="text-sm text-gray-900 bg-white p-2 rounded border">
                                {{ $barangTitipan->kunjungan->santri->nama }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang</label>
                            <p class="text-sm text-gray-900 bg-white p-2 rounded border font-mono">
                                {{ $barangTitipan->kode_barang }}</p>
                        </div>
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
                                value="{{ old('nama_barang', $barangTitipan->nama_barang) }}"
                                placeholder="Contoh: Makanan, Pakaian, Buku"
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
                                value="{{ old('jumlah', $barangTitipan->jumlah) }}"
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('deskripsi', $barangTitipan->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Tambahan
                            </label>
                            <textarea name="catatan" id="catatan" rows="2" placeholder="Catatan khusus atau instruksi khusus"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('catatan', $barangTitipan->catatan) }}</textarea>
                            @error('catatan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Info Waktu -->
                <div class="bg-yellow-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Waktu</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Dititipkan</label>
                            <p class="text-sm text-gray-900">{{ $barangTitipan->waktu_dititipkan->format('d F Y, H:i') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Admin Penerima</label>
                            <p class="text-sm text-gray-900">{{ $barangTitipan->adminPenerima->name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('barang-titipan.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Barang Titipan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
