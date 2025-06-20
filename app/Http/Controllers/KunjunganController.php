<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kunjungan;
use App\Models\Santri;
use App\Models\BarangTitipan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KunjunganController extends Controller
{
    // METHOD STORE - FORCE JAKARTA TIME
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

        try {
            DB::transaction(function () use ($request, &$kunjungan) {
                // FORCE JAKARTA TIMEZONE
                $jakartaTime = Carbon::now('Asia/Jakarta');

                // Create kunjungan with explicit timezone
                $kunjungan = new Kunjungan();
                $kunjungan->nomor_antrian = Kunjungan::generateNomorAntrian();
                $kunjungan->santri_id = $request->santri_id;
                $kunjungan->nama_pengunjung = $request->nama_pengunjung;
                $kunjungan->hubungan = $request->hubungan;
                $kunjungan->phone_pengunjung = $request->phone_pengunjung;
                $kunjungan->alamat_pengunjung = $request->alamat_pengunjung;
                $kunjungan->status = 'menunggu';
                $kunjungan->waktu_daftar = $jakartaTime; // This will use mutator
                $kunjungan->catatan = $request->catatan;
                $kunjungan->admin_id = Auth::id();
                $kunjungan->save();

                // Create barang titipan if any
                if ($request->filled('barang_titipan')) {
                    foreach ($request->barang_titipan as $barang) {
                        if (!empty($barang['nama_barang'])) {
                            $barangTitipan = new BarangTitipan();
                            $barangTitipan->kode_barang = BarangTitipan::generateKodeBarang();
                            $barangTitipan->kunjungan_id = $kunjungan->id;
                            $barangTitipan->nama_barang = $barang['nama_barang'];
                            $barangTitipan->deskripsi = $barang['deskripsi'] ?? null;
                            $barangTitipan->jumlah = $barang['jumlah'];
                            $barangTitipan->status = 'dititipkan';
                            $barangTitipan->waktu_dititipkan = $jakartaTime;
                            $barangTitipan->admin_penerima = Auth::id();
                            $barangTitipan->save();
                        }
                    }
                }

                // Log activity
                activity()
                    ->causedBy(Auth::user())
                    ->performedOn($kunjungan)
                    ->log('Created new kunjungan: ' . $kunjungan->nomor_antrian);
            });

            return redirect()->route('kunjungan.antrian')
                ->with('success', 'Kunjungan berhasil didaftarkan dengan nomor antrian: ' . $kunjungan->nomor_antrian);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mendaftarkan kunjungan: ' . $e->getMessage());
        }
    }

    // METHOD PANGGIL - FORCE JAKARTA TIME
    public function panggil(Kunjungan $kunjungan)
    {
        if ($kunjungan->status !== 'menunggu') {
            return response()->json(['error' => 'Kunjungan tidak dalam status menunggu'], 400);
        }

        // FORCE JAKARTA TIMEZONE
        $jakartaTime = Carbon::now('Asia/Jakarta');

        $kunjungan->status = 'dipanggil';
        $kunjungan->waktu_panggil = $jakartaTime; // This will use mutator
        $kunjungan->save();

        activity()
            ->causedBy(Auth::user())
            ->performedOn($kunjungan)
            ->log('Called kunjungan: ' . $kunjungan->nomor_antrian);

        return response()->json([
            'success' => true,
            'message' => "Nomor antrian {$kunjungan->nomor_antrian} telah dipanggil"
        ]);
    }

    // METHOD MULAI - FORCE JAKARTA TIME
    public function mulai(Kunjungan $kunjungan)
    {
        if ($kunjungan->status !== 'dipanggil') {
            return response()->json(['error' => 'Kunjungan belum dipanggil'], 400);
        }

        // FORCE JAKARTA TIMEZONE
        $jakartaTime = Carbon::now('Asia/Jakarta');

        $kunjungan->status = 'berlangsung';
        $kunjungan->waktu_mulai = $jakartaTime; // This will use mutator
        $kunjungan->save();

        activity()
            ->causedBy(Auth::user())
            ->performedOn($kunjungan)
            ->log('Started kunjungan: ' . $kunjungan->nomor_antrian);

        return response()->json([
            'success' => true,
            'message' => "Kunjungan {$kunjungan->nomor_antrian} telah dimulai"
        ]);
    }

    // METHOD SELESAI - FORCE JAKARTA TIME
    public function selesai(Kunjungan $kunjungan)
    {
        if ($kunjungan->status !== 'berlangsung') {
            return response()->json(['error' => 'Kunjungan tidak sedang berlangsung'], 400);
        }

        // FORCE JAKARTA TIMEZONE
        $jakartaTime = Carbon::now('Asia/Jakarta');

        $kunjungan->status = 'selesai';
        $kunjungan->waktu_selesai = $jakartaTime; // This will use mutator
        $kunjungan->save();

        activity()
            ->causedBy(Auth::user())
            ->performedOn($kunjungan)
            ->log('Finished kunjungan: ' . $kunjungan->nomor_antrian);

        return response()->json([
            'success' => true,
            'message' => "Kunjungan {$kunjungan->nomor_antrian} telah selesai"
        ]);
    }

    // METHOD getQueueStatus - FORCE JAKARTA TIME
    public function getQueueStatus()
    {
        $queue = Kunjungan::with(['santri'])
            ->whereIn('status', ['menunggu', 'dipanggil', 'berlangsung'])
            ->fifoOrder()
            ->get()
            ->map(function ($kunjungan) {
                // FORCE JAKARTA TIMEZONE
                $now = Carbon::now('Asia/Jakarta');

                return [
                    'id' => $kunjungan->id,
                    'nomor_antrian' => $kunjungan->nomor_antrian,
                    'nama_pengunjung' => $kunjungan->nama_pengunjung,
                    'nama_santri' => $kunjungan->santri->nama,
                    'status' => $kunjungan->status,
                    'waktu_daftar' => $kunjungan->waktu_daftar->format('H:i'),
                    'waktu_tunggu' => $kunjungan->waktu_daftar->diffInMinutes($now) . ' menit',
                ];
            });

        return response()->json($queue);
    }

    // REST OF THE METHODS REMAIN THE SAME...
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

    public function batal(Kunjungan $kunjungan)
    {
        if (in_array($kunjungan->status, ['selesai', 'dibatalkan'])) {
            return response()->json(['error' => 'Kunjungan tidak dapat dibatalkan'], 400);
        }

        $kunjungan->update(['status' => 'dibatalkan']);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($kunjungan)
            ->log('Cancelled kunjungan: ' . $kunjungan->nomor_antrian);

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
