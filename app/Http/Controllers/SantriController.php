<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Santri;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SantriController extends Controller
{
    /**
     * Display santri listing
     */
    public function index(Request $request)
    {
        $query = Santri::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhere('nama_wali', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        // Filter by tahun masuk
        if ($request->filled('tahun_masuk')) {
            $query->where('tahun_masuk', $request->tahun_masuk);
        }

        // Filter by jenis kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        $santri = $query->latest()->paginate(15);
        $tahunOptions = Santri::distinct()->pluck('tahun_masuk')->sort()->values();

        return view('santri.index', compact('santri', 'tahunOptions'));
    }

    /**
     * Show create santri form
     */
    public function create()
    {
        return view('santri.create');
    }

    /**
     * Store new santri
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:santri',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'alamat' => 'required|string',
            'nama_wali' => 'required|string|max:255',
            'phone_wali' => 'required|string|max:20',
            'kamar' => 'nullable|string|max:20',
            'tahun_masuk' => 'required|integer|min:2020|max:' . (date('Y') + 1),
        ]);

        $santri = Santri::create($request->all());

        activity()
            ->causedBy(Auth::user())
            ->performedOn($santri)
            ->log('Created new santri: ' . $santri->nama);

        return redirect()->route('santri.index')
            ->with('success', 'Data santri berhasil ditambahkan.');
    }

    /**
     * Display santri details
     */
    public function show(Santri $santri)
    {
        $santri->load(['kunjungan' => function ($query) {
            $query->with('admin')->latest()->take(10);
        }]);

        return view('santri.show', compact('santri'));
    }

    /**
     * Show edit santri form
     */
    public function edit(Santri $santri)
    {
        return view('santri.edit', compact('santri'));
    }

    /**
     * Update santri
     */
    public function update(Request $request, Santri $santri)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:santri,nim,' . $santri->id,
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'alamat' => 'required|string',
            'nama_wali' => 'required|string|max:255',
            'phone_wali' => 'required|string|max:20',
            'kamar' => 'nullable|string|max:20',
            'tahun_masuk' => 'required|integer|min:2020|max:' . (date('Y') + 1),
        ]);

        $santri->update($request->all());

        activity()
            ->causedBy(Auth::user())
            ->performedOn($santri)
            ->log('Updated santri: ' . $santri->nama);

        return redirect()->route('santri.index')
            ->with('success', 'Data santri berhasil diperbarui.');
    }

    /**
     * Delete santri
     */
    public function destroy(Santri $santri)
    {
        // Check if santri has kunjungan
        if ($santri->kunjungan()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus santri yang memiliki riwayat kunjungan.');
        }

        activity()
            ->causedBy(Auth::user())
            ->performedOn($santri)
            ->log('Deleted santri: ' . $santri->nama);

        $santri->delete();

        return redirect()->route('santri.index')
            ->with('success', 'Data santri berhasil dihapus.');
    }

    /**
     * Toggle santri status
     */
    public function toggleStatus(Santri $santri)
    {
        $santri->update(['is_active' => !$santri->is_active]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($santri)
            ->log(($santri->is_active ? 'Activated' : 'Deactivated') . ' santri: ' . $santri->nama);

        return response()->json([
            'success' => true,
            'message' => 'Status santri berhasil diubah.',
            'is_active' => $santri->is_active
        ]);
    }

    /**
     * Search santri for AJAX
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $santri = Santri::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('nama', 'like', "%{$query}%")
                    ->orWhere('nim', 'like', "%{$query}%");
            })
            ->take(10)
            ->get(['id', 'nama', 'nim', 'kamar']);

        return response()->json($santri);
    }

    /**
     * Export santri to Excel
     */
    public function exportExcel(Request $request)
    {
        // This would require installing maatwebsite/excel package
        // For now, return a simple response
        return response()->json(['message' => 'Export feature requires Excel package installation']);
    }
}
