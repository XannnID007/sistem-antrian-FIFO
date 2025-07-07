@extends('layouts.admin')

@section('title', 'Pengaturan Umum')

@section('breadcrumb')
    <li class="flex items-center">
        <i class="fas fa-home text-gray-400 mr-2"></i>
        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-gray-600">Pengaturan Umum</span>
    </li>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pengaturan Umum</h1>
                <p class="text-gray-600 mt-1">Konfigurasi sistem dan preferensi aplikasi</p>
            </div>
            <button onclick="openAddModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>Tambah Pengaturan
            </button>
        </div>

        <!-- Pengaturan by Category -->
        @foreach ($pengaturan as $category => $settings)
            <div class="bg-white rounded-lg shadow card-shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 capitalize">
                        <i
                            class="fas fa-{{ $category === 'pesantren' ? 'mosque' : ($category === 'sistem' ? 'cog' : 'sliders-h') }} mr-2"></i>
                        {{ ucfirst(str_replace('_', ' ', $category)) }}
                    </h3>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($settings as $setting)
                            <div
                                class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $setting->key }}</h4>
                                            @if ($setting->description)
                                                <p class="text-sm text-gray-500 mt-1">{{ $setting->description }}</p>
                                            @endif
                                        </div>
                                        <div class="ml-4 flex items-center space-x-2">
                                            <div class="text-sm text-gray-700 max-w-xs truncate">
                                                <span
                                                    class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $setting->value }}</span>
                                            </div>
                                            <button
                                                onclick="editSetting({{ $setting->id }}, '{{ $setting->key }}', '{{ addslashes($setting->value) }}', '{{ addslashes($setting->description ?? '') }}')"
                                                class="text-yellow-600 hover:text-yellow-900">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="deleteSetting({{ $setting->id }})"
                                                class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-cog text-3xl mb-3 text-gray-300"></i>
                                <p>Belum ada pengaturan untuk kategori {{ $category }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach

        @if ($pengaturan->isEmpty())
            <div class="bg-white rounded-lg shadow card-shadow p-8 text-center">
                <i class="fas fa-sliders-h text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Pengaturan</h3>
                <p class="text-gray-500 mb-4">Mulai dengan menambahkan pengaturan sistem pertama Anda</p>
                <button onclick="openAddModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Tambah Pengaturan Pertama
                </button>
            </div>
        @endif
    </div>

    <!-- Add/Edit Modal -->
    <div id="settingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">Tambah Pengaturan</h3>

                <form id="settingForm" method="POST">
                    @csrf
                    <div id="methodField"></div>

                    <div class="space-y-4">
                        <div>
                            <label for="key" class="block text-sm font-medium text-gray-700 mb-2">
                                Key <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="key" id="key" required
                                placeholder="contoh: pesantren_nama"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Gunakan format: kategori_nama (contoh: pesantren_nama)</p>
                        </div>

                        <div>
                            <label for="value" class="block text-sm font-medium text-gray-700 mb-2">
                                Value <span class="text-red-500">*</span>
                            </label>
                            <textarea name="value" id="value" required rows="3" placeholder="Nilai pengaturan"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi
                            </label>
                            <textarea name="description" id="description" rows="2" placeholder="Deskripsi pengaturan (opsional)"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
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
        // Open add modal
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Pengaturan';
            document.getElementById('settingForm').action = '{{ route('pengaturan.store') }}';
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('settingForm').reset();
            document.getElementById('settingModal').classList.remove('hidden');
        }

        // Edit setting
        function editSetting(id, key, value, description) {
            document.getElementById('modalTitle').textContent = 'Edit Pengaturan';
            document.getElementById('settingForm').action = `/pengaturan/${id}`;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            document.getElementById('key').value = key;
            document.getElementById('key').readOnly = true; // Prevent changing key
            document.getElementById('value').value = value;
            document.getElementById('description').value = description;

            document.getElementById('settingModal').classList.remove('hidden');
        }

        // Add quick setting
        function addQuickSetting(key, value, description) {
            document.getElementById('modalTitle').textContent = 'Tambah Pengaturan';
            document.getElementById('settingForm').action = '{{ route('pengaturan.store') }}';
            document.getElementById('methodField').innerHTML = '';

            document.getElementById('key').value = key;
            document.getElementById('key').readOnly = false;
            document.getElementById('value').value = value;
            document.getElementById('description').value = description;

            document.getElementById('settingModal').classList.remove('hidden');
        }

        // Close modal
        function closeModal() {
            document.getElementById('settingModal').classList.add('hidden');
            document.getElementById('key').readOnly = false; // Reset readonly state
        }

        // Delete setting
        function deleteSetting(id) {
            if (confirm('Yakin ingin menghapus pengaturan ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/pengaturan/${id}`;
                form.innerHTML = `
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                <input type="hidden" name="_method" value="DELETE">
            `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modal when clicking outside
        document.getElementById('settingModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endpush
