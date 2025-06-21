<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Kunjungan Santri</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #2D8659 0%, #1D5F3F 25%, #164A33 50%, #0F3625 75%, #0A2118 100%);
            position: relative;
            overflow: hidden;
        }

        .gradient-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.05"><circle cx="30" cy="30" r="2"/></g></svg>');
            animation: float 20s ease-in-out infinite;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow:
                0 25px 50px -12px rgba(0, 0, 0, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        .input-focus:focus {
            border-color: #2D8659;
            box-shadow: 0 0 0 3px rgba(45, 134, 89, 0.1);
            transform: translateY(-1px);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #2D8659 0%, #1D5F3F 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(45, 134, 89, 0.3);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(45, 134, 89, 0.4);
            background: linear-gradient(135deg, #1D5F3F 0%, #164A33 100%);
        }

        .logo-container {
            filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.3));
        }

        .geometric-pattern {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0.1;
            background-image:
                radial-gradient(circle at 25% 25%, #ffffff 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, #ffffff 2px, transparent 2px);
            background-size: 60px 60px;
            animation: patternMove 15s linear infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateX(0px) translateY(0px);
            }

            33% {
                transform: translateX(30px) translateY(-30px);
            }

            66% {
                transform: translateX(-20px) translateY(20px);
            }
        }

        @keyframes patternMove {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(60px, 60px);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 1s ease-out;
        }

        .slide-in-left {
            animation: slideInLeft 1s ease-out;
        }

        .input-group {
            position: relative;
            transition: all 0.3s ease;
        }

        .input-group:focus-within .input-icon {
            color: #2D8659;
            transform: scale(1.1);
        }

        .input-icon {
            transition: all 0.3s ease;
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .floating-shapes::before,
        .floating-shapes::after {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .floating-shapes::before {
            width: 200px;
            height: 200px;
            top: -100px;
            right: -100px;
            animation: floatRotate 20s linear infinite;
        }

        .floating-shapes::after {
            width: 150px;
            height: 150px;
            bottom: -75px;
            left: -75px;
            animation: floatRotate 25s linear infinite reverse;
        }

        @keyframes floatRotate {
            0% {
                transform: rotate(0deg) translateX(50px) rotate(0deg);
            }

            100% {
                transform: rotate(360deg) translateX(50px) rotate(-360deg);
            }
        }

        .notification-enter {
            animation: notificationSlide 0.5s ease-out;
        }

        @keyframes notificationSlide {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .islamic-pattern {
            background-image: url('data:image/svg+xml,<svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.03"><path d="M20 20m-8 0a8 8 0 1 1 16 0a8 8 0 1 1-16 0M20 20m-4 0a4 4 0 1 1 8 0a4 4 0 1 1-8 0"/></g></svg>');
        }
    </style>

    @stack('styles')
</head>

<body class="min-h-screen gradient-bg flex items-center justify-center p-4 relative">

    <!-- Background Elements -->
    <div class="geometric-pattern"></div>
    <div class="floating-shapes"></div>
    <div class="islamic-pattern absolute inset-0"></div>

    <!-- Main Container -->
    <div class="w-full max-w-sm space-y-6 relative z-10">

        <!-- Header Section -->
        <div class="text-center fade-in-up">
            <!-- Logo -->
            <div class="logo-container mx-auto mb-4">
                <div class="relative">
                    <div class="w-20 h-20 mx-auto mb-3 rounded-full bg-white p-2 shadow-xl">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo Pesantren"
                            class="w-full h-full object-cover rounded-full"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-full h-full bg-gradient-to-br from-green-600 to-green-800 rounded-full flex items-center justify-center"
                            style="display: none;">
                            <i class="fas fa-mosque text-white text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Login Form -->
        <div class="glass-card rounded-2xl shadow-xl p-6 fade-in-up" style="animation-delay: 0.3s">

            <!-- Flash Messages -->
            @if (session('success'))
                <div
                    class="mb-6 bg-green-50 border-l-4 border-green-400 text-green-700 px-4 py-3 rounded-r-lg notification-enter">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3 text-green-500"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="mb-6 bg-red-50 border-l-4 border-red-400 text-red-700 px-4 py-3 rounded-r-lg notification-enter">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Form Header -->
            <div class="text-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Masuk ke Akun</h3>
                <p class="text-gray-600 mt-1 text-sm">Masukkan kredensial Anda untuk melanjutkan</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Username Field -->
                <div class="input-group">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user input-icon text-gray-400"></i>
                        </div>
                        <input type="text" name="username" id="username" required autocomplete="username"
                            value="{{ old('username') }}"
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none input-focus transition-all duration-300 bg-gray-50 @error('username') border-red-500 bg-red-50 @enderror"
                            placeholder="Masukkan username Anda">
                    </div>
                    @error('username')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="input-group">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock input-icon text-gray-400"></i>
                        </div>
                        <input type="password" name="password" id="password" required autocomplete="current-password"
                            class="w-full pl-10 pr-12 py-2.5 border border-gray-300 rounded-lg focus:outline-none input-focus transition-all duration-300 bg-gray-50 @error('password') border-red-500 bg-red-50 @enderror"
                            placeholder="Masukkan password Anda">
                        <button type="button" onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-green-600 transition-colors">
                            <i id="password-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember"
                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Ingat saya
                        </label>
                    </div>
                    <div class="text-sm">
                        <span class="text-gray-500">Lupa password?</span>
                    </div>
                </div>

                <!-- Login Button -->
                <div>
                    <button type="submit"
                        class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg text-sm font-medium text-white btn-gradient focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        id="login-btn">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        <span id="btn-text">Masuk</span>
                        <div id="btn-loading" class="hidden ml-2">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                        </div>
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center fade-in-up" style="animation-delay: 0.6s">
            <p class="text-green-200 text-sm">
                © {{ date('Y') }} Pondok Pesantren Salafiyah Al-Jawahir
            </p>
            <p class="text-green-300 text-xs mt-1">
                Sistem Kunjungan Santri v1.0
            </p>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay"
        class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
        <div class="glass-card rounded-2xl p-8 flex flex-col items-center space-y-4">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
            <span class="text-gray-700 font-medium">Sedang masuk...</span>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }

        // Demo credential fill
        function fillDemo(role) {
            if (role === 'pengasuh') {
                document.getElementById('username').value = 'admin';
                document.getElementById('password').value = 'password';
            } else if (role === 'admin') {
                document.getElementById('username').value = 'staff';
                document.getElementById('password').value = 'password';
            }

            // Add visual feedback
            const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');
            inputs.forEach(input => {
                input.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    input.style.transform = 'scale(1)';
                }, 200);
            });
        }

        // Enhanced form submit with loading state
        document.querySelector('form').addEventListener('submit', function(e) {
            const loginBtn = document.getElementById('login-btn');
            const btnText = document.getElementById('btn-text');
            const btnLoading = document.getElementById('btn-loading');
            const loadingOverlay = document.getElementById('loading-overlay');

            // Update button state
            loginBtn.disabled = true;
            btnText.textContent = 'Memproses...';
            btnLoading.classList.remove('hidden');

            // Show overlay after short delay
            setTimeout(() => {
                loadingOverlay.classList.remove('hidden');
            }, 500);
        });

        // Auto focus and animations
        document.addEventListener('DOMContentLoaded', function() {
            // Focus on username field
            document.getElementById('username').focus();

            // Add entrance animations to form elements
            const formElements = document.querySelectorAll('.input-group');
            formElements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    element.style.transition = 'all 0.6s ease';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, (index + 1) * 200);
            });
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const form = document.querySelector('form');
                const activeElement = document.activeElement;

                if (activeElement.id === 'username') {
                    document.getElementById('password').focus();
                    e.preventDefault();
                } else if (activeElement.id === 'password') {
                    form.submit();
                }
            }
        });

        // Auto hide flash messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('.notification-enter');
            alerts.forEach(function(alert) {
                alert.style.transition = 'all 0.5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100%)';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);

        // Enhanced input interactions
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', function() {
                this.parentElement.parentElement.classList.remove('focused');
            });
        });

        // Background animation control
        let animationSpeed = 1;

        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                animationSpeed = 0.5;
            } else {
                animationSpeed = 1;
            }

            document.documentElement.style.setProperty('--animation-speed', animationSpeed);
        });

        // Logo error handling with fallback
        document.addEventListener('DOMContentLoaded', function() {
            const logoImg = document.querySelector('img[alt="Logo Pesantren"]');
            if (logoImg) {
                logoImg.addEventListener('error', function() {
                    this.style.display = 'none';
                    this.nextElementSibling.style.display = 'flex';
                });
            }
        });

        // Progressive enhancement - Add ripple effect to button
        document.getElementById('login-btn').addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });

        // Add custom ripple styles
        const style = document.createElement('style');
        style.textContent = `
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                pointer-events: none;
            }
            
            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            
            .focused .input-icon {
                color: #2D8659 !important;
                transform: scale(1.1);
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>
