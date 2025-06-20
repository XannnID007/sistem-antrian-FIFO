<?php
// routes/web.php - UPDATED VERSION dengan Hak Akses yang Benar

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\BarangTitipanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\JamOperasionalController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PublicDisplayController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Public Display Routes (untuk tampilan antrian publik)
Route::get('/display', [PublicDisplayController::class, 'index'])->name('public.display');
Route::get('/display/antrian', [PublicDisplayController::class, 'antrian'])->name('public.antrian');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes - SEMUA HARUS LOGIN
Route::middleware(['auth'])->group(function () {

    // Dashboard - Available for all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/queue-status', [DashboardController::class, 'getQueueStatus'])->name('dashboard.queue-status');

    // Profile Routes - Available for all authenticated users
    Route::get('/profile', [UserController::class, 'profile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');

    // Routes khusus untuk Admin - MENGELOLA KUNJUNGAN, BARANG TITIPAN, DAN LAPORAN
    Route::middleware(['role:admin'])->group(function () {

        // Kunjungan Routes
        Route::prefix('kunjungan')->name('kunjungan.')->group(function () {
            Route::get('/', [KunjunganController::class, 'index'])->name('index');
            Route::get('/create', [KunjunganController::class, 'create'])->name('create');
            Route::post('/', [KunjunganController::class, 'store'])->name('store');
            Route::get('/antrian', [KunjunganController::class, 'antrian'])->name('antrian');
            Route::get('/{kunjungan}', [KunjunganController::class, 'show'])->name('show');
            Route::get('/{kunjungan}/struk', [KunjunganController::class, 'cetakStruk'])->name('struk');

            // Antrian Actions
            Route::post('/{kunjungan}/panggil', [KunjunganController::class, 'panggil'])->name('panggil');
            Route::post('/{kunjungan}/mulai', [KunjunganController::class, 'mulai'])->name('mulai');
            Route::post('/{kunjungan}/selesai', [KunjunganController::class, 'selesai'])->name('selesai');
            Route::post('/{kunjungan}/batal', [KunjunganController::class, 'batal'])->name('batal');
        });

        // Barang Titipan Routes
        Route::prefix('barang-titipan')->name('barang-titipan.')->group(function () {
            Route::get('/', [BarangTitipanController::class, 'index'])->name('index');
            Route::get('/create', [BarangTitipanController::class, 'create'])->name('create');
            Route::post('/', [BarangTitipanController::class, 'store'])->name('store');
            Route::get('/{barangTitipan}', [BarangTitipanController::class, 'show'])->name('show');
            Route::get('/{barangTitipan}/edit', [BarangTitipanController::class, 'edit'])->name('edit');
            Route::put('/{barangTitipan}', [BarangTitipanController::class, 'update'])->name('update');
            Route::delete('/{barangTitipan}', [BarangTitipanController::class, 'destroy'])->name('destroy');
            Route::get('/{barangTitipan}/struk', [BarangTitipanController::class, 'cetakStruk'])->name('struk');

            // Status Actions
            Route::post('/{barangTitipan}/serahkan', [BarangTitipanController::class, 'serahkan'])->name('serahkan');
            Route::post('/{barangTitipan}/ambil', [BarangTitipanController::class, 'ambil'])->name('ambil');
        });

        // Laporan Routes untuk Admin
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('index');
            Route::get('/kunjungan', [LaporanController::class, 'kunjungan'])->name('kunjungan');
            Route::get('/barang-titipan', [LaporanController::class, 'barangTitipan'])->name('barang-titipan');
            Route::get('/statistik', [LaporanController::class, 'statistik'])->name('statistik');
            Route::post('/export', [LaporanController::class, 'export'])->name('export');
        });
    });

    // Routes khusus untuk Pengasuh - DATA MASTER, PENGATURAN DAN LAPORAN LANJUTAN
    Route::middleware(['role:pengasuh'])->group(function () {

        // Data Master - Santri Management
        Route::resource('santri', SantriController::class);
        Route::post('/santri/{santri}/toggle-status', [SantriController::class, 'toggleStatus'])->name('santri.toggle-status');
        Route::get('/santri/export/excel', [SantriController::class, 'exportExcel'])->name('santri.export.excel');

        // Data Master - User Management
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

        // Pengaturan - Jam Operasional
        Route::resource('jam-operasional', JamOperasionalController::class, ['except' => ['create', 'show', 'edit']]);
        Route::post('/jam-operasional/{jamOperasional}/toggle-status', [JamOperasionalController::class, 'toggleStatus'])->name('jam-operasional.toggle-status');

        // Pengaturan - System Settings
        Route::resource('pengaturan', PengaturanController::class, ['except' => ['create', 'show', 'edit']]);

        // Laporan Lanjutan (Pengasuh only)
        Route::prefix('laporan-lanjutan')->name('laporan.advanced.')->group(function () {
            Route::get('/', [LaporanController::class, 'advanced'])->name('index');
            Route::get('/analitik', [LaporanController::class, 'analitik'])->name('analitik');
            Route::get('/trend', [LaporanController::class, 'trend'])->name('trend');
            Route::post('/backup', [LaporanController::class, 'backup'])->name('backup');
        });
    });
});

// API Routes untuk AJAX calls
Route::prefix('api')->middleware(['auth'])->group(function () {

    // API untuk pengasuh - santri search
    Route::middleware(['role:pengasuh'])->group(function () {
        Route::get('/santri/search', [SantriController::class, 'search'])->name('api.santri.search');
    });

    // API untuk admin saja
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/kunjungan/queue-status', [KunjunganController::class, 'getQueueStatus'])->name('api.kunjungan.queue-status');
        Route::get('/barang-titipan/search', [BarangTitipanController::class, 'search'])->name('api.barang-titipan.search');
    });

    // API untuk semua user yang login
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('api.dashboard.stats');
});

// HOME ROUTE - PENTING!
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->middleware('auth')->name('home');
