<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangTitipan;
use App\Models\Kunjungan;
use Illuminate\Support\Facades\Auth;

class BarangTitipanController extends Controller
{
    /**
     * Display barang titipan listing
     */
    public function index(Request $request)
    {
        $query = BarangTitipan::with(['kunjungan.santri', 'adminPenerima']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                    ->orWhere('nama_barang', 'like', "%{$search}%")
                    ->orWhereHas('kunjungan', function ($kq) use ($search) {
                        $kq->where('nama_pengunjung', 'like', "%{$search}%")
                            ->orWhereHas('santri', function ($sq) use ($search) {
                                $sq->where('nama', 'like', "%{$search}%");
                            });
                    });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('waktu_dititipkan', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('waktu_dititipkan', '<=', $request->tanggal_selesai);
        }

        $barangTitipan = $query->latest('waktu_dititipkan')->paginate(15);

        return view('barang-titipan.index', compact('barangTitipan'));
    }

    /**
     * Show create barang titipan form
     */
    public function create()
    {
        $kunjunganAktif = Kunjungan::with('santri')
            ->whereIn('status', ['berlangsung', 'dipanggil'])
            ->get();

        return view('barang-titipan.create', compact('kunjunganAktif'));
    }

    /**
     * Store new barang titipan
     */
    public function store(Request $request)
    {
        $request->validate([
            'kunjungan_id' => 'required|exists:kunjungan,id',
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        $barangTitipan = BarangTitipan::create([
            'kode_barang' => BarangTitipan::generateKodeBarang(),
            'kunjungan_id' => $request->kunjungan_id,
            'nama_barang' => $request->nama_barang,
            'deskripsi' => $request->deskripsi,
            'jumlah' => $request->jumlah,
            'status' => 'dititipkan',
            'waktu_dititipkan' => now(),
            'admin_penerima' => Auth::id(),
            'catatan' => $request->catatan,
        ]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($barangTitipan)
            ->log('Created barang titipan: ' . $barangTitipan->kode_barang);

        return redirect()->route('barang-titipan.index')
            ->with('success', 'Barang titipan berhasil didaftarkan dengan kode: ' . $barangTitipan->kode_barang);
    }

    /**
     * Display barang titipan details
     */
    public function show(BarangTitipan $barangTitipan)
    {
        $barangTitipan->load(['kunjungan.santri', 'adminPenerima', 'adminPenyerah']);
        return view('barang-titipan.show', compact('barangTitipan'));
    }

    /**
     * Show edit barang titipan form
     */
    public function edit(BarangTitipan $barangTitipan)
    {
        if ($barangTitipan->status !== 'dititipkan') {
            return back()->with('error', 'Hanya barang dengan status "dititipkan" yang dapat diedit.');
        }

        return view('barang-titipan.edit', compact('barangTitipan'));
    }

    /**
     * Update barang titipan
     */
    public function update(Request $request, BarangTitipan $barangTitipan)
    {
        if ($barangTitipan->status !== 'dititipkan') {
            return back()->with('error', 'Hanya barang dengan status "dititipkan" yang dapat diedit.');
        }

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        $barangTitipan->update([
            'nama_barang' => $request->nama_barang,
            'deskripsi' => $request->deskripsi,
            'jumlah' => $request->jumlah,
            'catatan' => $request->catatan,
        ]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($barangTitipan)
            ->log('Updated barang titipan: ' . $barangTitipan->kode_barang);

        return redirect()->route('barang-titipan.index')
            ->with('success', 'Data barang titipan berhasil diperbarui.');
    }

    /**
     * Delete barang titipan
     */
    public function destroy(BarangTitipan $barangTitipan)
    {
        if ($barangTitipan->status !== 'dititipkan') {
            return back()->with('error', 'Hanya barang dengan status "dititipkan" yang dapat dihapus.');
        }

        activity()
            ->causedBy(Auth::user())
            ->performedOn($barangTitipan)
            ->log('Deleted barang titipan: ' . $barangTitipan->kode_barang);

        $barangTitipan->delete();

        return redirect()->route('barang-titipan.index')
            ->with('success', 'Data barang titipan berhasil dihapus.');
    }

    /**
     * Serahkan barang ke santri
     */
    public function serahkan(BarangTitipan $barangTitipan)
    {
        if ($barangTitipan->status !== 'dititipkan') {
            return response()->json(['error' => 'Barang tidak dalam status dititipkan'], 400);
        }

        $barangTitipan->update([
            'status' => 'diserahkan',
            'waktu_diserahkan' => now(),
            'admin_penyerah' => Auth::id(),
        ]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($barangTitipan)
            ->log('Serahkan barang titipan: ' . $barangTitipan->kode_barang);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diserahkan ke santri.'
        ]);
    }

    /**
     * Tandai barang sudah diambil
     */
    public function ambil(BarangTitipan $barangTitipan)
    {
        if ($barangTitipan->status !== 'diserahkan') {
            return response()->json(['error' => 'Barang belum diserahkan ke santri'], 400);
        }

        $barangTitipan->update([
            'status' => 'diambil',
            'waktu_diambil' => now(),
        ]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($barangTitipan)
            ->log('Barang titipan diambil: ' . $barangTitipan->kode_barang);

        return response()->json([
            'success' => true,
            'message' => 'Barang ditandai sudah diambil.'
        ]);
    }

    /**
     * Cetak struk barang titipan
     */
    public function cetakStruk(BarangTitipan $barangTitipan)
    {
        $barangTitipan->load(['kunjungan.santri', 'adminPenerima']);
        return view('barang-titipan.struk', compact('barangTitipan'));
    }

    /**
     * Search barang titipan for AJAX
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $barang = BarangTitipan::with(['kunjungan.santri'])
            ->where(function ($q) use ($query) {
                $q->where('kode_barang', 'like', "%{$query}%")
                    ->orWhere('nama_barang', 'like', "%{$query}%");
            })
            ->take(10)
            ->get();

        return response()->json($barang);
    }
}
