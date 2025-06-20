<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kunjungan;
use App\Models\Santri;
use App\Models\BarangTitipan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KunjunganController extends Controller
{
    public function index(Request $request)
    {
        $query = Kunjungan::with(['santri', 'admin']);

        // Filter by date range
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('waktu_daftar', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('waktu_daftar', '<=', $request->tanggal_selesai);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_antrian', 'like', "%{$search}%")
                    ->orWhere('nama_pengunjung', 'like', "%{$search}%")
                    ->orWhereHas('santri', function ($sq) use ($search) {
                        $sq->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        $kunjungan = $query->latest('waktu_daftar')->paginate(10);

        return view('kunjungan.index', compact('kunjungan'));
    }

    public function create()
    {
        $santri = Santri::where('is_active', true)->orderBy('nama')->get();
        return view('kunjungan.create', compact('santri'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'nama_pengunjung' => 'required|string|max:255',
            'hubungan' => 'required|string|max:100',
            'phone_pengunjung' => 'required|string|max:20',
            'alamat_pengunjung' => 'required|string',
            'catatan' => 'nullable|string',
            'barang_titipan' => 'nullable|array',
            'barang_titipan.*.nama_barang' => 'required_with:barang_titipan|string|max:255',
            'barang_titipan.*.jumlah' => 'required_with:barang_titipan|integer|min:1',
            'barang_titipan.*.deskripsi' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            // Create kunjungan
            $kunjungan = Kunjungan::create([
                'nomor_antrian' => Kunjungan::generateNomorAntrian(),
                'santri_id' => $request->santri_id,
                'nama_pengunjung' => $request->nama_pengunjung,
                'hubungan' => $request->hubungan,
                'phone_pengunjung' => $request->phone_pengunjung,
                'alamat_pengunjung' => $request->alamat_pengunjung,
                'status' => 'menunggu',
                'waktu_daftar' => now(),
                'catatan' => $request->catatan,
                'admin_id' => auth()->id(),
            ]);

            // Create barang titipan if any
            if ($request->filled('barang_titipan')) {
                foreach ($request->barang_titipan as $barang) {
                    if (!empty($barang['nama_barang'])) {
                        BarangTitipan::create([
                            'kode_barang' => BarangTitipan::generateKodeBarang(),
                            'kunjungan_id' => $kunjungan->id,
                            'nama_barang' => $barang['nama_barang'],
                            'deskripsi' => $barang['deskripsi'] ?? null,
                            'jumlah' => $barang['jumlah'],
                            'status' => 'dititipkan',
                            'waktu_dititipkan' => now(),
                            'admin_penerima' => auth()->id(),
                        ]);
                    }
                }
            }
        });

        return redirect()->route('kunjungan.antrian')
            ->with('success', 'Kunjungan berhasil didaftarkan dengan nomor antrian: ' . $kunjungan->nomor_antrian);
    }

    public function antrian()
    {
        $menunggu = Kunjungan::with(['santri'])
            ->where('status', 'menunggu')
            ->fifoOrder()
            ->get();

        $dipanggil = Kunjungan::with(['santri'])
            ->where('status', 'dipanggil')
            ->fifoOrder()
            ->get();

        $berlangsung = Kunjungan::with(['santri'])
            ->where('status', 'berlangsung')
            ->latest('waktu_mulai')
            ->get();

        return view('kunjungan.antrian', compact('menunggu', 'dipanggil', 'berlangsung'));
    }

    public function panggil(Kunjungan $kunjungan)
    {
        if ($kunjungan->status !== 'menunggu') {
            return response()->json(['error' => 'Kunjungan tidak dalam status menunggu'], 400);
        }

        $kunjungan->update([
            'status' => 'dipanggil',
            'waktu_panggil' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Nomor antrian {$kunjungan->nomor_antrian} telah dipanggil"
        ]);
    }

    public function mulai(Kunjungan $kunjungan)
    {
        if ($kunjungan->status !== 'dipanggil') {
            return response()->json(['error' => 'Kunjungan belum dipanggil'], 400);
        }

        $kunjungan->update([
            'status' => 'berlangsung',
            'waktu_mulai' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Kunjungan {$kunjungan->nomor_antrian} telah dimulai"
        ]);
    }

    public function selesai(Kunjungan $kunjungan)
    {
        if ($kunjungan->status !== 'berlangsung') {
            return response()->json(['error' => 'Kunjungan tidak sedang berlangsung'], 400);
        }

        $kunjungan->update([
            'status' => 'selesai',
            'waktu_selesai' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Kunjungan {$kunjungan->nomor_antrian} telah selesai"
        ]);
    }

    public function batal(Kunjungan $kunjungan)
    {
        if (in_array($kunjungan->status, ['selesai', 'dibatalkan'])) {
            return response()->json(['error' => 'Kunjungan tidak dapat dibatalkan'], 400);
        }

        $kunjungan->update(['status' => 'dibatalkan']);

        return response()->json([
            'success' => true,
            'message' => "Kunjungan {$kunjungan->nomor_antrian} telah dibatalkan"
        ]);
    }

    public function show(Kunjungan $kunjungan)
    {
        $kunjungan->load(['santri', 'admin', 'barangTitipan.adminPenerima']);
        return view('kunjungan.show', compact('kunjungan'));
    }

    public function cetakStruk(Kunjungan $kunjungan)
    {
        $kunjungan->load(['santri', 'barangTitipan']);
        return view('kunjungan.struk', compact('kunjungan'));
    }
}
