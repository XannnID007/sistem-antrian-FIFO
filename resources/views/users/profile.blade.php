@extends('layouts.admin')

@section('title', 'Profil')

@section('breadcrumb')
    <li class="flex items-center">
        <i class="fas fa-home text-gray-400 mr-2"></i>
        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-gray-600">Profil</span>
    </li>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow card-shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
                <p class="text-gray-600 mt-1">Kelola informasi profil dan keamanan akun Anda</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Update Profile -->
                <div class="bg-white rounded-lg shadow card-shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Profil</h3>
                    </div>
                    <form action="{{ route('profile.update') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" required
                                    value="{{ old('name', $user->name) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" required
                                    value="{{ old('email', $user->email) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Telepon
                                </label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                    placeholder="Contoh: 08123456789"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat
                                </label>
                                <textarea name="address" id="address" rows="3" placeholder="Alamat lengkap"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-200">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="bg-white rounded-lg shadow card-shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Ubah Password</h3>
                    </div>
                    <form action="{{ route('profile.password') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password Saat Ini <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="current_password" id="current_password" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('current_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password Baru <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password" id="password" required
                                    placeholder="Minimal 6 karakter"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Konfirmasi Password Baru <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    placeholder="Ulangi password baru"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-200">
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition-colors">
                                <i class="fas fa-key mr-2"></i>Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
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
                        <p class="text-xs text-gray-400 mt-1">@{{ $user - > username }}</p>

                        <div class="mt-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="bg-white rounded-lg shadow card-shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Akun</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Username</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->username }}</p>
                        </div>
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
                            <label class="block text-sm font-medium text-gray-700">Bergabung Sejak</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d F Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Terakhir Diperbarui</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Access & Permissions -->
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

                <!-- Security Tips -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-yellow-900 mb-2">
                        <i class="fas fa-shield-alt mr-2"></i>Tips Keamanan
                    </h4>
                    <ul class="text-sm text-yellow-800 space-y-1">
                        <li>• Gunakan password yang kuat (minimal 8 karakter)</li>
                        <li>• Jangan bagikan akun dengan orang lain</li>
                        <li>• Selalu logout setelah selesai menggunakan sistem</li>
                        <li>• Ubah password secara berkala</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
