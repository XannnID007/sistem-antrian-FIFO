<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kunjungan;
use App\Models\Santri;
use App\Models\BarangTitipan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Statistics
        $stats = [
            'total_kunjungan_hari_ini' => Kunjungan::whereDate('waktu_daftar', $today)->count(),
            'total_antrian_menunggu' => Kunjungan::where('status', 'menunggu')->count(),
            'total_kunjungan_berlangsung' => Kunjungan::where('status', 'berlangsung')->count(),
            'total_santri_aktif' => Santri::where('is_active', true)->count(),
            'total_barang_titipan' => BarangTitipan::whereIn('status', ['dititipkan', 'diserahkan'])->count(),
            'total_kunjungan_bulan_ini' => Kunjungan::where('waktu_daftar', '>=', $thisMonth)->count(),
        ];

        // Recent visits
        $recentVisits = Kunjungan::with(['santri', 'admin'])
            ->latest('waktu_daftar')
            ->take(5)
            ->get();

        // Current queue (FIFO order)
        $currentQueue = Kunjungan::with(['santri'])
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->fifoOrder()
            ->take(10)
            ->get();

        // Daily statistics for chart (last 7 days)
        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyStats[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('D'),
                'kunjungan' => Kunjungan::whereDate('waktu_daftar', $date)->count(),
                'selesai' => Kunjungan::whereDate('waktu_selesai', $date)->count(),
            ];
        }

        // Average waiting time (in minutes)
        $avgWaitingTime = Kunjungan::whereNotNull('waktu_panggil')
            ->whereDate('waktu_daftar', $today)
            ->get()
            ->avg(function ($kunjungan) {
                return $kunjungan->waktu_tunggu;
            });

        // Peak hours analysis
        $peakHours = Kunjungan::whereDate('waktu_daftar', $today)
            ->select(DB::raw('HOUR(waktu_daftar) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->take(3)
            ->get();

        return view('dashboard', compact(
            'stats',
            'recentVisits',
            'currentQueue',
            'dailyStats',
            'avgWaitingTime',
            'peakHours'
        ));
    }

    public function getQueueStatus()
    {
        $queue = Kunjungan::with(['santri'])
            ->whereIn('status', ['menunggu', 'dipanggil', 'berlangsung'])
            ->fifoOrder()
            ->get()
            ->map(function ($kunjungan) {
                return [
                    'id' => $kunjungan->id,
                    'nomor_antrian' => $kunjungan->nomor_antrian,
                    'nama_pengunjung' => $kunjungan->nama_pengunjung,
                    'nama_santri' => $kunjungan->santri->nama,
                    'status' => $kunjungan->status,
                    'waktu_daftar' => $kunjungan->waktu_daftar->format('H:i'),
                    'waktu_tunggu' => $kunjungan->waktu_daftar->diffInMinutes(now()) . ' menit',
                ];
            });

        return response()->json($queue);
    }
}
