<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Sistem Kunjungan Santri</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }

        .active-menu {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 0.5rem;
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }

        .submenu.open {
            max-height: 500px;
        }

        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .notification-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .7;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar"
            class="sidebar-transition bg-white w-64 min-h-screen shadow-lg border-r border-gray-200 fixed lg:relative z-30">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center">
                        <i class="fas fa-mosque text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-800">Al-Jawahir</h1>
                        <p class="text-xs text-gray-500">Sistem Kunjungan</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="p-4 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                    class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 transition-colors 
                          {{ request()->routeIs('dashboard') ? 'active-menu' : 'text-gray-700' }}">
                    <i class="fas fa-chart-line w-5"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Kunjungan Menu -->
                <div class="menu-group">
                    <button onclick="toggleSubmenu('kunjungan')"
                        class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-gray-100 transition-colors text-gray-700">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-users w-5"></i>
                            <span>Kunjungan</span>
                        </div>
                        <i class="fas fa-chevron-down transition-transform" id="kunjungan-icon"></i>
                    </button>
                    <div id="kunjungan-submenu" class="submenu ml-8 mt-2 space-y-1">
                        <a href="{{ route('kunjungan.create') }}"
                            class="block p-2 rounded text-sm hover:bg-gray-100 transition-colors
                                  {{ request()->routeIs('kunjungan.create') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                            <i class="fas fa-plus w-4 mr-2"></i>Daftar Kunjungan
                        </a>
                        <a href="{{ route('kunjungan.antrian') }}"
                            class="block p-2 rounded text-sm hover:bg-gray-100 transition-colors
                                  {{ request()->routeIs('kunjungan.antrian') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                            <i class="fas fa-list w-4 mr-2"></i>Kelola Antrian
                        </a>
                        <a href="{{ route('kunjungan.index') }}"
                            class="block p-2 rounded text-sm hover:bg-gray-100 transition-colors
                                  {{ request()->routeIs('kunjungan.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                            <i class="fas fa-history w-4 mr-2"></i>Riwayat Kunjungan
                        </a>
                    </div>
                </div>

                <!-- Barang Titipan -->
                <a href="{{ route('barang-titipan.index') }}"
                    class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 transition-colors 
                          {{ request()->routeIs('barang-titipan.*') ? 'active-menu' : 'text-gray-700' }}">
                    <i class="fas fa-box w-5"></i>
                    <span>Barang Titipan</span>
                </a>

                @if (auth()->user()->isPengasuh())
                    <!-- Data Master (Only for Pengasuh) -->
                    <div class="menu-group">
                        <button onclick="toggleSubmenu('master')"
                            class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-gray-100 transition-colors text-gray-700">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-database w-5"></i>
                                <span>Data Master</span>
                            </div>
                            <i class="fas fa-chevron-down transition-transform" id="master-icon"></i>
                        </button>
                        <div id="master-submenu" class="submenu ml-8 mt-2 space-y-1">
                            <a href="{{ route('santri.index') }}"
                                class="block p-2 rounded text-sm hover:bg-gray-100 transition-colors
                                  {{ request()->routeIs('santri.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                                <i class="fas fa-graduation-cap w-4 mr-2"></i>Data Santri
                            </a>
                            <a href="{{ route('users.index') }}"
                                class="block p-2 rounded text-sm hover:bg-gray-100 transition-colors
                                  {{ request()->routeIs('users.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                                <i class="fas fa-user-cog w-4 mr-2"></i>Kelola User
                            </a>
                        </div>
                    </div>

                    <!-- Pengaturan (Only for Pengasuh) -->
                    <div class="menu-group">
                        <button onclick="toggleSubmenu('pengaturan')"
                            class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-gray-100 transition-colors text-gray-700">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-cog w-5"></i>
                                <span>Pengaturan</span>
                            </div>
                            <i class="fas fa-chevron-down transition-transform" id="pengaturan-icon"></i>
                        </button>
                        <div id="pengaturan-submenu" class="submenu ml-8 mt-2 space-y-1">
                            <a href="{{ route('jam-operasional.index') }}"
                                class="block p-2 rounded text-sm hover:bg-gray-100 transition-colors
                                  {{ request()->routeIs('jam-operasional.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                                <i class="fas fa-clock w-4 mr-2"></i>Jam Operasional
                            </a>
                            <a href="{{ route('pengaturan.index') }}"
                                class="block p-2 rounded text-sm hover:bg-gray-100 transition-colors
                                  {{ request()->routeIs('pengaturan.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                                <i class="fas fa-sliders-h w-4 mr-2"></i>Pengaturan Umum
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Laporan -->
                <a href="{{ route('laporan.index') }}"
                    class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 transition-colors 
                          {{ request()->routeIs('laporan.*') ? 'active-menu' : 'text-gray-700' }}">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span>Laporan</span>
                </a>
            </nav>
        </div>

        <!-- Overlay for mobile -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black opacity-50 z-20 lg:hidden hidden"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200 fixed w-full lg:w-auto lg:relative z-10">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <!-- Mobile menu button -->
                        <button onclick="toggleSidebar()" class="lg:hidden text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bars text-xl"></i>
                        </button>

                        <!-- Breadcrumb -->
                        <nav class="hidden sm:flex" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    </div>

                    <!-- Right side -->
                    <div class="flex items-center space-x-4">

                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center space-x-3 text-gray-700 hover:text-gray-900 focus:outline-none">
                                <div
                                    class="w-8 h-8 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-medium">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="hidden sm:block text-left">
                                    <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                                </div>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>

                            <!-- Dropdown -->
                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95">

                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-3"></i>Profil
                                </a>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-3"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 pt-20 lg:pt-6 bg-gray-50">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                        role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3"
                            onclick="this.parentElement.style.display='none'">
                            <i class="fas fa-times cursor-pointer"></i>
                        </span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                        role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3"
                            onclick="this.parentElement.style.display='none'">
                            <i class="fas fa-times cursor-pointer"></i>
                        </span>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="mb-6 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative"
                        role="alert">
                        <span class="block sm:inline">{{ session('warning') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3"
                            onclick="this.parentElement.style.display='none'">
                            <i class="fas fa-times cursor-pointer"></i>
                        </span>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Custom JavaScript -->
    <script>
        // Toggle Sidebar for Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Toggle Submenu
        function toggleSubmenu(menuId) {
            const submenu = document.getElementById(menuId + '-submenu');
            const icon = document.getElementById(menuId + '-icon');

            submenu.classList.toggle('open');
            icon.classList.toggle('rotate-180');
        }

        // Auto hide mobile sidebar when clicking overlay
        document.getElementById('sidebar-overlay').addEventListener('click', function() {
            toggleSidebar();
        });

        // Auto hide flash messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.display = 'none';
            });
        }, 5000);

        // Real-time clock
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID');
            const dateString = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const clockElement = document.getElementById('real-time-clock');
            if (clockElement) {
                clockElement.innerHTML = `${timeString}<br><small class="text-xs">${dateString}</small>`;
            }
        }

        // Update clock every second
        setInterval(updateClock, 1000);
        updateClock(); // Initial call

        // CSRF Token Setup for AJAX
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };

        // Global AJAX Setup
        if (typeof $ !== 'undefined') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }
    </script>

    @stack('scripts')
</body>

</html>
