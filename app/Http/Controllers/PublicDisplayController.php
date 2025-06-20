<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kunjungan;
use App\Models\Pengaturan;
use Carbon\Carbon;

class PublicDisplayController extends Controller
{
    /**
     * Display public antrian page
     */
    public function index()
    {
        $currentQueue = $this->getCurrentQueue();
        $waitingQueue = $this->getWaitingQueue();
        $estimatedWaitTime = $this->calculateEstimatedWaitTime();
        $pesantrenInfo = $this->getPesantrenInfo();

        return view('public.display', compact(
            'currentQueue',
            'waitingQueue',
            'estimatedWaitTime',
            'pesantrenInfo'
        ));
    }

    /**
     * Get antrian data for AJAX calls
     */
    public function antrian()
    {
        $data = [
            'current_queue' => $this->getCurrentQueue(),
            'waiting_queue' => $this->getWaitingQueue(),
            'estimated_wait_time' => $this->calculateEstimatedWaitTime(),
            'current_time' => Carbon::now()->format('H:i:s'),
            'current_date' => Carbon::now()->format('d F Y'),
        ];

        return response()->json($data);
    }

    /**
     * Get current queue (dipanggil & berlangsung)
     */
    private function getCurrentQueue()
    {
        return Kunjungan::with(['santri'])
            ->whereIn('status', ['dipanggil', 'berlangsung'])
            ->fifoOrder()
            ->get()
            ->map(function ($kunjungan) {
                return [
                    'nomor_antrian' => $kunjungan->nomor_antrian,
                    'nama_pengunjung' => $kunjungan->nama_pengunjung,
                    'nama_santri' => $kunjungan->santri->nama,
                    'status' => $kunjungan->status,
                    'waktu_panggil' => $kunjungan->waktu_panggil ? $kunjungan->waktu_panggil->format('H:i') : null,
                    'waktu_mulai' => $kunjungan->waktu_mulai ? $kunjungan->waktu_mulai->format('H:i') : null,
                ];
            });
    }

    /**
     * Get waiting queue
     */
    private function getWaitingQueue()
    {
        return Kunjungan::with(['santri'])
            ->where('status', 'menunggu')
            ->fifoOrder()
            ->take(10)
            ->get()
            ->map(function ($kunjungan) {
                return [
                    'nomor_antrian' => $kunjungan->nomor_antrian,
                    'nama_pengunjung' => $kunjungan->nama_pengunjung,
                    'nama_santri' => $kunjungan->santri->nama,
                    'waktu_daftar' => $kunjungan->waktu_daftar->format('H:i'),
                ];
            });
    }

    /**
     * Calculate estimated wait time
     */
    private function calculateEstimatedWaitTime()
    {
        $waitingCount = Kunjungan::where('status', 'menunggu')->count();
        $avgDuration = (int) Pengaturan::get('avg_visit_duration', 15); // default 15 minutes

        return $waitingCount * $avgDuration;
    }

    /**
     * Get pesantren information
     */
    private function getPesantrenInfo()
    {
        return [
            'nama' => Pengaturan::get('pesantren_nama', 'Pondok Pesantren Salafiyah Al-Jawahir'),
            'alamat' => Pengaturan::get('pesantren_alamat', ''),
            'telepon' => Pengaturan::get('pesantren_telepon', ''),
            'jam_operasional' => Pengaturan::get('jam_operasional_display', 'Senin - Minggu: 08:00 - 16:00'),
        ];
    }
}
