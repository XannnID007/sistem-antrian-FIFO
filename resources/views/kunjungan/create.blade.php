@extends('layouts.admin')

@section('title', 'Daftar Kunjungan Baru')

@section('breadcrumb')
    <li class="flex items-center">
        <i class="fas fa-home text-gray-400 mr-2"></i>
        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-gray-600">Daftar Kunjungan</span>
    </li>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow card-shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Daftar Kunjungan Baru</h2>
                <p class="text-sm text-gray-600 mt-1">Silakan isi formulir di bawah ini untuk mendaftarkan kunjungan baru
                </p>
            </div>

            <form action="{{ route('kunjungan.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Informasi Santri -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Santri</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="santri_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Santri <span class="text-red-500">*</span>
                            </label>
                            <select name="santri_id" id="santri_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Santri --</option>
                                @foreach ($santri as $s)
                                    <option value="{{ $s->id }}" data-nama="{{ $s->nama }}"
                                        data-nim="{{ $s->nim }}" data-kamar="{{ $s->kamar }}"
                                        {{ old('santri_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->nama }} - {{ $s->nim }}
                                    </option>
                                @endforeach
                            </select>
                            @error('santri_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="santri-info" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Informasi Santri</label>
                            <div class="bg-white p-3 rounded border">
                                <p class="text-sm"><strong>Nama:</strong> <span id="info-nama">-</span></p>
                                <p class="text-sm"><strong>NIM:</strong> <span id="info-nim">-</span></p>
                                <p class="text-sm"><strong>Kamar:</strong> <span id="info-kamar">-</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pengunjung -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pengunjung</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nama_pengunjung" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Pengunjung <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_pengunjung" id="nama_pengunjung" required
                                value="{{ old('nama_pengunjung') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan nama lengkap pengunjung">
                            @error('nama_pengunjung')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="hubungan" class="block text-sm font-medium text-gray-700 mb-2">
                                Hubungan dengan Santri <span class="text-red-500">*</span>
                            </label>
                            <select name="hubungan" id="hubungan" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Hubungan --</option>
                                <option value="Orang Tua" {{ old('hubungan') == 'Orang Tua' ? 'selected' : '' }}>Orang Tua
                                </option>
                                <option value="Wali" {{ old('hubungan') == 'Wali' ? 'selected' : '' }}>Wali</option>
                                <option value="Saudara" {{ old('hubungan') == 'Saudara' ? 'selected' : '' }}>Saudara
                                </option>
                                <option value="Kerabat" {{ old('hubungan') == 'Kerabat' ? 'selected' : '' }}>Kerabat
                                </option>
                                <option value="Lainnya" {{ old('hubungan') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                </option>
                            </select>
                            @error('hubungan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone_pengunjung" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone_pengunjung" id="phone_pengunjung" required
                                value="{{ old('phone_pengunjung') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Contoh: 08123456789">
                            @error('phone_pengunjung')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="alamat_pengunjung" class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat <span class="text-red-500">*</span>
                            </label>
                            <textarea name="alamat_pengunjung" id="alamat_pengunjung" rows="3" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan alamat lengkap">{{ old('alamat_pengunjung') }}</textarea>
                            @error('alamat_pengunjung')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Barang Titipan -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Barang Titipan (Opsional)</h3>
                        <button type="button" onclick="addBarangTitipan()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-plus mr-1"></i>Tambah Barang
                        </button>
                    </div>

                    <div id="barang-titipan-container">
                        <!-- Barang titipan items will be added here -->
                    </div>
                </div>

                <!-- Catatan -->
                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Tambahan
                    </label>
                    <textarea name="catatan" id="catatan" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('dashboard') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>Daftar Kunjungan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let barangIndex = 0;

        // Handle santri selection
        document.getElementById('santri_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const infoDiv = document.getElementById('santri-info');

            if (this.value) {
                document.getElementById('info-nama').textContent = selectedOption.dataset.nama;
                document.getElementById('info-nim').textContent = selectedOption.dataset.nim;
                document.getElementById('info-kamar').textContent = selectedOption.dataset.kamar || '-';
                infoDiv.classList.remove('hidden');
            } else {
                infoDiv.classList.add('hidden');
            }
        });

        // Add barang titipan
        function addBarangTitipan() {
            const container = document.getElementById('barang-titipan-container');
            const item = document.createElement('div');
            item.className = 'grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-white rounded border mb-3';
            item.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                <input type="text" name="barang_titipan[${barangIndex}][nama_barang]" 
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Nama barang">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                <input type="number" name="barang_titipan[${barangIndex}][jumlah]" min="1" value="1"
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <input type="text" name="barang_titipan[${barangIndex}][deskripsi]" 
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Deskripsi singkat">
            </div>
            <div class="flex items-end">
                <button type="button" onclick="removeBarangTitipan(this)" 
                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
            container.appendChild(item);
            barangIndex++;
        }

        // Remove barang titipan
        function removeBarangTitipan(button) {
            button.closest('.grid').remove();
        }

        // Auto-add one barang titipan field on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there are validation errors for barang_titipan
            @if (old('barang_titipan'))
                @foreach (old('barang_titipan') as $index => $barang)
                    addBarangTitipan();
                    const container = document.getElementById('barang-titipan-container');
                    const lastItem = container.lastElementChild;
                    lastItem.querySelector('input[name*="[nama_barang]"]').value =
                        '{{ $barang['nama_barang'] ?? '' }}';
                    lastItem.querySelector('input[name*="[jumlah]"]').value = '{{ $barang['jumlah'] ?? 1 }}';
                    lastItem.querySelector('input[name*="[deskripsi]"]').value =
                    '{{ $barang['deskripsi'] ?? '' }}';
                @endforeach
            @endif
        });
    </script>
@endpush
