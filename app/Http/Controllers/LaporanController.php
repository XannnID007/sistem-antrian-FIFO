<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kunjungan;
use App\Models\Santri;
use App\Models\BarangTitipan;
use App\Services\ExportService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    protected $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    /**
     * Display laporan dashboard
     */
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Summary statistics
        $summary = [
            'total_kunjungan_hari_ini' => Kunjungan::whereDate('waktu_daftar', $today)->count(),
            'total_kunjungan_bulan_ini' => Kunjungan::where('waktu_daftar', '>=', $thisMonth)->count(),
            'total_santri_aktif' => Santri::where('is_active', true)->count(),
            'total_barang_titipan_aktif' => BarangTitipan::whereIn('status', ['dititipkan', 'diserahkan'])->count(),
        ];

        return view('laporan.index', compact('summary'));
    }

    /**
     * Laporan kunjungan
     */
    public function kunjungan(Request $request)
    {
        $tanggalMulai = $request->get('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggalSelesai = $request->get('tanggal_selesai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $query = Kunjungan::with(['santri', 'admin'])
            ->whereDate('waktu_daftar', '>=', $tanggalMulai)
            ->whereDate('waktu_daftar', '<=', $tanggalSelesai);

        // Filter by status if specified
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $kunjungan = $query->latest('waktu_daftar')->get();

        // Statistics
        $stats = [
            'total' => $kunjungan->count(),
            'selesai' => $kunjungan->where('status', 'selesai')->count(),
            'dibatalkan' => $kunjungan->where('status', 'dibatalkan')->count(),
            'rata_rata_tunggu' => $kunjungan->where('status', 'selesai')->avg('waktu_tunggu') ?? 0,
        ];

        return view('laporan.kunjungan', compact('kunjungan', 'stats', 'tanggalMulai', 'tanggalSelesai'));
    }

    /**
     * Laporan barang titipan
     */
    public function barangTitipan(Request $request)
    {
        $tanggalMulai = $request->get('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggalSelesai = $request->get('tanggal_selesai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $query = BarangTitipan::with(['kunjungan.santri', 'adminPenerima'])
            ->whereDate('waktu_dititipkan', '>=', $tanggalMulai)
            ->whereDate('waktu_dititipkan', '<=', $tanggalSelesai);

        // Filter by status if specified
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $barangTitipan = $query->latest('waktu_dititipkan')->get();

        // Statistics
        $stats = [
            'total' => $barangTitipan->count(),
            'dititipkan' => $barangTitipan->where('status', 'dititipkan')->count(),
            'diserahkan' => $barangTitipan->where('status', 'diserahkan')->count(),
            'diambil' => $barangTitipan->where('status', 'diambil')->count(),
        ];

        return view('laporan.barang-titipan', compact('barangTitipan', 'stats', 'tanggalMulai', 'tanggalSelesai'));
    }

    /**
     * Laporan statistik
     */
    public function statistik(Request $request)
    {
        $periode = $request->get('periode', '30'); // days
        $tanggalMulai = Carbon::now()->subDays($periode);

        // Daily statistics
        $dailyStats = [];
        for ($i = $periode - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyStats[] = [
                'tanggal' => $date->format('Y-m-d'),
                'hari' => $date->format('D'),
                'kunjungan' => Kunjungan::whereDate('waktu_daftar', $date)->count(),
                'selesai' => Kunjungan::whereDate('waktu_selesai', $date)->count(),
                'barang_titipan' => BarangTitipan::whereDate('waktu_dititipkan', $date)->count(),
            ];
        }

        // Peak hours (last 30 days)
        $peakHours = Kunjungan::where('waktu_daftar', '>=', $tanggalMulai)
            ->select(DB::raw('HOUR(waktu_daftar) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->get();

        // Top santri by visits
        $topSantri = Santri::withCount(['kunjungan' => function ($query) use ($tanggalMulai) {
            $query->where('waktu_daftar', '>=', $tanggalMulai);
        }])
            ->having('kunjungan_count', '>', 0)
            ->orderBy('kunjungan_count', 'desc')
            ->take(10)
            ->get();

        return view('laporan.statistik', compact('dailyStats', 'peakHours', 'topSantri', 'periode'));
    }

    /**
     * Export laporan
     */
    public function export(Request $request)
    {
        try {
            $type = $request->get('type', 'kunjungan');
            $format = $request->get('format', 'excel');
            $tanggalMulai = $request->get('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $tanggalSelesai = $request->get('tanggal_selesai', Carbon::now()->endOfMonth()->format('Y-m-d'));
            $status = $request->get('status');

            // Validate parameters
            $request->validate([
                'type' => 'required|in:kunjungan,barang-titipan,all',
                'format' => 'required|in:excel,pdf,csv',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
            ]);

            $exportResult = null;

            // Export based on type and format
            if ($type === 'kunjungan') {
                switch ($format) {
                    case 'excel':
                        $exportResult = $this->exportService->exportKunjunganExcel($tanggalMulai, $tanggalSelesai, $status);
                        break;
                    case 'pdf':
                        $exportResult = $this->exportService->exportKunjunganPdf($tanggalMulai, $tanggalSelesai, $status);
                        break;
                    case 'csv':
                        $exportResult = $this->exportService->exportKunjunganCsv($tanggalMulai, $tanggalSelesai, $status);
                        break;
                }
            } elseif ($type === 'barang-titipan') {
                switch ($format) {
                    case 'excel':
                        $exportResult = $this->exportService->exportBarangTitipanExcel($tanggalMulai, $tanggalSelesai, $status);
                        break;
                    case 'pdf':
                        $exportResult = $this->exportService->exportBarangTitipanPdf($tanggalMulai, $tanggalSelesai, $status);
                        break;
                    case 'csv':
                        $exportResult = $this->exportService->exportBarangTitipanCsv($tanggalMulai, $tanggalSelesai, $status);
                        break;
                }
            }

            if (!$exportResult) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format export tidak didukung'
                ], 400);
            }

            // Return file download
            return response()->download(
                $exportResult['file'],
                $exportResult['filename'],
                [
                    'Content-Type' => $exportResult['mime'],
                    'Content-Disposition' => 'attachment; filename="' . $exportResult['filename'] . '"'
                ]
            )->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat export: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Advanced reports (Pengasuh only)
     */
    public function advanced()
    {
        // Advanced analytics for pengasuh
        $monthlyStats = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyStats[] = [
                'bulan' => $date->format('F Y'),
                'kunjungan' => Kunjungan::whereYear('waktu_daftar', $date->year)
                    ->whereMonth('waktu_daftar', $date->month)
                    ->count(),
                'selesai' => Kunjungan::whereYear('waktu_selesai', $date->year)
                    ->whereMonth('waktu_selesai', $date->month)
                    ->count(),
                'barang_titipan' => BarangTitipan::whereYear('waktu_dititipkan', $date->year)
                    ->whereMonth('waktu_dititipkan', $date->month)
                    ->count(),
            ];
        }

        return view('laporan.advanced', compact('monthlyStats'));
    }

    /**
     * Analytics dashboard
     */
    public function analitik()
    {
        // Detailed analytics
        $analytics = [
            'conversion_rate' => $this->calculateConversionRate(),
            'average_wait_time' => $this->calculateAverageWaitTime(),
            'peak_days' => $this->getPeakDays(),
            'santri_activity' => $this->getSantriActivity()
        ];

        return view('laporan.analitik', compact('analytics'));
    }

    /**
     * Trend analysis
     */
    public function trend()
    {
        // Trend analysis data
        $trends = [
            'monthly_growth' => $this->calculateMonthlyGrowth(),
            'seasonal_patterns' => $this->getSeasonalPatterns(),
            'forecast' => $this->generateForecast()
        ];

        return view('laporan.trend', compact('trends'));
    }

    /**
     * Backup data
     */
    public function backup()
    {
        try {
            // Create database backup
            $backupPath = storage_path('app/backups/');
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $fullPath = $backupPath . $filename;

            // Execute mysqldump command
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.host'),
                config('database.connections.mysql.database'),
                $fullPath
            );

            exec($command);

            if (file_exists($fullPath)) {
                return response()->download($fullPath, $filename)->deleteFileAfterSend(true);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat backup database'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper methods for analytics
     */
    private function calculateConversionRate()
    {
        $totalKunjungan = Kunjungan::count();
        $selesaiKunjungan = Kunjungan::where('status', 'selesai')->count();

        return $totalKunjungan > 0 ? ($selesaiKunjungan / $totalKunjungan) * 100 : 0;
    }

    private function calculateAverageWaitTime()
    {
        return Kunjungan::where('status', 'selesai')
            ->whereNotNull('waktu_tunggu')
            ->avg('waktu_tunggu') ?? 0;
    }

    private function getPeakDays()
    {
        return Kunjungan::select(DB::raw('DAYNAME(waktu_daftar) as day'), DB::raw('COUNT(*) as count'))
            ->groupBy('day')
            ->orderBy('count', 'desc')
            ->take(3)
            ->get();
    }

    private function getSantriActivity()
    {
        return Santri::withCount('kunjungan')
            ->orderBy('kunjungan_count', 'desc')
            ->take(10)
            ->get();
    }

    private function calculateMonthlyGrowth()
    {
        $thisMonth = Kunjungan::whereMonth('waktu_daftar', now()->month)->count();
        $lastMonth = Kunjungan::whereMonth('waktu_daftar', now()->subMonth()->month)->count();

        return $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0;
    }

    private function getSeasonalPatterns()
    {
        return Kunjungan::select(DB::raw('MONTH(waktu_daftar) as month'), DB::raw('COUNT(*) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    private function generateForecast()
    {
        // Simple linear forecast based on last 6 months
        $data = Kunjungan::select(DB::raw('YEAR(waktu_daftar) as year'), DB::raw('MONTH(waktu_daftar) as month'), DB::raw('COUNT(*) as count'))
            ->where('waktu_daftar', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Calculate simple trend
        $counts = $data->pluck('count')->toArray();
        if (count($counts) >= 2) {
            $trend = (end($counts) - reset($counts)) / count($counts);
            $nextMonth = end($counts) + $trend;
            return max(0, round($nextMonth));
        }

        return 0;
    }
}
