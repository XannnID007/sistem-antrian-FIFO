<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kunjungan;
use App\Models\Santri;
use App\Models\BarangTitipan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
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
        $type = $request->get('type', 'kunjungan');
        $format = $request->get('format', 'excel');

        // This would implement actual export functionality
        // For now, return a placeholder response
        return response()->json([
            'message' => 'Export functionality needs to be implemented',
            'type' => $type,
            'format' => $format
        ]);
    }

    /**
     * Advanced reports (Pengasuh only)
     */
    public function advanced()
    {
        // Advanced analytics for pengasuh
        return view('laporan.advanced');
    }

    /**
     * Analytics dashboard
     */
    public function analitik()
    {
        // Detailed analytics
        return view('laporan.analitik');
    }

    /**
     * Trend analysis
     */
    public function trend()
    {
        // Trend analysis
        return view('laporan.trend');
    }

    /**
     * Backup data
     */
    public function backup()
    {
        // Database backup functionality
        return response()->json(['message' => 'Backup functionality needs to be implemented']);
    }
}
