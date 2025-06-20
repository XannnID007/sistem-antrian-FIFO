<?php
// routes/web.php
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

// Public Routes
Route::get('/', function () {
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

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/queue-status', [DashboardController::class, 'getQueueStatus'])->name('dashboard.queue-status');

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

    // Laporan Routes
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/kunjungan', [LaporanController::class, 'kunjungan'])->name('kunjungan');
        Route::get('/barang-titipan', [LaporanController::class, 'barangTitipan'])->name('barang-titipan');
        Route::get('/statistik', [LaporanController::class, 'statistik'])->name('statistik');
        Route::post('/export', [LaporanController::class, 'export'])->name('export');
    });

    // Profile Routes
    Route::get('/profile', [UserController::class, 'profile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');

    // Routes khusus untuk Pengasuh
    Route::middleware(['role:pengasuh'])->group(function () {

        // Santri Management
        Route::prefix('santri')->name('santri.')->group(function () {
            Route::get('/', [SantriController::class, 'index'])->name('index');
            Route::get('/create', [SantriController::class, 'create'])->name('create');
            Route::post('/', [SantriController::class, 'store'])->name('store');
            Route::get('/{santri}', [SantriController::class, 'show'])->name('show');
            Route::get('/{santri}/edit', [SantriController::class, 'edit'])->name('edit');
            Route::put('/{santri}', [SantriController::class, 'update'])->name('update');
            Route::delete('/{santri}', [SantriController::class, 'destroy'])->name('destroy');
            Route::post('/{santri}/toggle-status', [SantriController::class, 'toggleStatus'])->name('toggle-status');
            Route::get('/export/excel', [SantriController::class, 'exportExcel'])->name('export.excel');
        });

        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
        });

        // Jam Operasional
        Route::prefix('jam-operasional')->name('jam-operasional.')->group(function () {
            Route::get('/', [JamOperasionalController::class, 'index'])->name('index');
            Route::post('/', [JamOperasionalController::class, 'store'])->name('store');
            Route::put('/{jamOperasional}', [JamOperasionalController::class, 'update'])->name('update');
            Route::delete('/{jamOperasional}', [JamOperasionalController::class, 'destroy'])->name('destroy');
            Route::post('/{jamOperasional}/toggle-status', [JamOperasionalController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Pengaturan System
        Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
            Route::get('/', [PengaturanController::class, 'index'])->name('index');
            Route::post('/', [PengaturanController::class, 'store'])->name('store');
            Route::put('/{pengaturan}', [PengaturanController::class, 'update'])->name('update');
            Route::delete('/{pengaturan}', [PengaturanController::class, 'destroy'])->name('destroy');
        });

        // Advanced Reports (Pengasuh only)
        Route::prefix('laporan-lanjutan')->name('laporan.advanced.')->group(function () {
            Route::get('/', [LaporanController::class, 'advanced'])->name('index');
            Route::get('/analitik', [LaporanController::class, 'analitik'])->name('analitik');
            Route::get('/trend', [LaporanController::class, 'trend'])->name('trend');
            Route::post('/backup', [LaporanController::class, 'backup'])->name('backup');
        });
    });
});
