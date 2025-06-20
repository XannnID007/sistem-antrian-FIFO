<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Santri;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use App\Exports\SantriExport;
use App\Models\BarangTitipan;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\KunjunganExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exports\BarangTitipanExport;
use Maatwebsite\Excel\Facades\Excel;

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
     * Export laporan - FIXED VERSION
     */
    public function export(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'type' => 'required|in:kunjungan,barang-titipan,santri',
                'format' => 'required|in:excel,pdf,csv',
                'tanggal_mulai' => 'sometimes|date',
                'tanggal_selesai' => 'sometimes|date|after_or_equal:tanggal_mulai',
                'status' => 'sometimes|string'
            ]);

            $type = $request->get('type');
            $format = $request->get('format');
            $tanggalMulai = $request->get('tanggal_mulai');
            $tanggalSelesai = $request->get('tanggal_selesai');
            $status = $request->get('status');

            // Generate filename
            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "laporan_{$type}_{$timestamp}";

            switch ($type) {
                case 'kunjungan':
                    return $this->exportKunjungan($format, $filename, $tanggalMulai, $tanggalSelesai, $status);

                case 'barang-titipan':
                    return $this->exportBarangTitipan($format, $filename, $tanggalMulai, $tanggalSelesai, $status);

                case 'santri':
                    return $this->exportSantri($format, $filename, $status);

                default:
                    return response()->json(['error' => 'Invalid export type'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat export: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export Kunjungan
     */
    private function exportKunjungan($format, $filename, $tanggalMulai = null, $tanggalSelesai = null, $status = null)
    {
        switch ($format) {
            case 'excel':
                return Excel::download(
                    new KunjunganExport($tanggalMulai, $tanggalSelesai, $status),
                    $filename . '.xlsx'
                );

            case 'csv':
                return Excel::download(
                    new KunjunganExport($tanggalMulai, $tanggalSelesai, $status),
                    $filename . '.csv',
                    \Maatwebsite\Excel\Excel::CSV
                );

            case 'pdf':
                return $this->exportKunjunganPdf($filename, $tanggalMulai, $tanggalSelesai, $status);

            default:
                throw new \Exception('Format tidak didukung');
        }
    }

    /**
     * Export Barang Titipan
     */
    private function exportBarangTitipan($format, $filename, $tanggalMulai = null, $tanggalSelesai = null, $status = null)
    {
        switch ($format) {
            case 'excel':
                return Excel::download(
                    new BarangTitipanExport($tanggalMulai, $tanggalSelesai, $status),
                    $filename . '.xlsx'
                );

            case 'csv':
                return Excel::download(
                    new BarangTitipanExport($tanggalMulai, $tanggalSelesai, $status),
                    $filename . '.csv',
                    \Maatwebsite\Excel\Excel::CSV
                );

            case 'pdf':
                return $this->exportBarangTitipanPdf($filename, $tanggalMulai, $tanggalSelesai, $status);

            default:
                throw new \Exception('Format tidak didukung');
        }
    }

    /**
     * Export Santri
     */
    private function exportSantri($format, $filename, $status = null)
    {
        switch ($format) {
            case 'excel':
                return Excel::download(
                    new SantriExport($status),
                    $filename . '.xlsx'
                );

            case 'csv':
                return Excel::download(
                    new SantriExport($status),
                    $filename . '.csv',
                    \Maatwebsite\Excel\Excel::CSV
                );

            case 'pdf':
                return $this->exportSantriPdf($filename, $status);

            default:
                throw new \Exception('Format tidak didukung');
        }
    }

    /**
     * Export Kunjungan to PDF
     */
    private function exportKunjunganPdf($filename, $tanggalMulai = null, $tanggalSelesai = null, $status = null)
    {
        $query = Kunjungan::with(['santri', 'admin']);

        if ($tanggalMulai) {
            $query->whereDate('waktu_daftar', '>=', $tanggalMulai);
        }

        if ($tanggalSelesai) {
            $query->whereDate('waktu_daftar', '<=', $tanggalSelesai);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $kunjungan = $query->latest('waktu_daftar')->get();

        $data = [
            'kunjungan' => $kunjungan,
            'tanggalMulai' => $tanggalMulai ? Carbon::parse($tanggalMulai)->format('d/m/Y') : 'Semua',
            'tanggalSelesai' => $tanggalSelesai ? Carbon::parse($tanggalSelesai)->format('d/m/Y') : 'Semua',
            'status' => $status ? ucfirst($status) : 'Semua Status',
            'total' => $kunjungan->count()
        ];

        $pdf = PDF::loadView('laporan.pdf.kunjungan', $data);
        return $pdf->download($filename . '.pdf');
    }

    /**
     * Export Barang Titipan to PDF
     */
    private function exportBarangTitipanPdf($filename, $tanggalMulai = null, $tanggalSelesai = null, $status = null)
    {
        $query = BarangTitipan::with(['kunjungan.santri', 'adminPenerima']);

        if ($tanggalMulai) {
            $query->whereDate('waktu_dititipkan', '>=', $tanggalMulai);
        }

        if ($tanggalSelesai) {
            $query->whereDate('waktu_dititipkan', '<=', $tanggalSelesai);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $barangTitipan = $query->latest('waktu_dititipkan')->get();

        $data = [
            'barangTitipan' => $barangTitipan,
            'tanggalMulai' => $tanggalMulai ? Carbon::parse($tanggalMulai)->format('d/m/Y') : 'Semua',
            'tanggalSelesai' => $tanggalSelesai ? Carbon::parse($tanggalSelesai)->format('d/m/Y') : 'Semua',
            'status' => $status ? ucfirst($status) : 'Semua Status',
            'total' => $barangTitipan->count()
        ];

        $pdf = PDF::loadView('laporan.pdf.barang-titipan', $data);
        return $pdf->download($filename . '.pdf');
    }

    /**
     * Export Santri to PDF
     */
    private function exportSantriPdf($filename, $status = null)
    {
        $query = Santri::withCount('kunjungan');

        if ($status !== null) {
            $isActive = $status === 'active';
            $query->where('is_active', $isActive);
        }

        $santri = $query->latest()->get();

        $data = [
            'santri' => $santri,
            'status' => $status ? ($status === 'active' ? 'Aktif' : 'Tidak Aktif') : 'Semua Status',
            'total' => $santri->count()
        ];

        $pdf = PDF::loadView('laporan.pdf.santri', $data);
        return $pdf->download($filename . '.pdf');
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
}
