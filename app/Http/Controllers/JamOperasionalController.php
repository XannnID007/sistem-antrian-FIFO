<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JamOperasional;
use Illuminate\Support\Facades\Auth;

class JamOperasionalController extends Controller
{
    /**
     * Display jam operasional
     */
    public function index()
    {
        $jamOperasional = JamOperasional::orderByRaw("
            CASE hari 
                WHEN 'senin' THEN 1
                WHEN 'selasa' THEN 2
                WHEN 'rabu' THEN 3
                WHEN 'kamis' THEN 4
                WHEN 'jumat' THEN 5
                WHEN 'sabtu' THEN 6
                WHEN 'minggu' THEN 7
            END
        ")->get();

        return view('jam-operasional.index', compact('jamOperasional'));
    }

    /**
     * Store new jam operasional
     */
    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu|unique:jam_operasional,hari',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
            'is_active' => 'boolean',
        ]);

        $jamOperasional = JamOperasional::create([
            'hari' => $request->hari,
            'jam_buka' => $request->jam_buka,
            'jam_tutup' => $request->jam_tutup,
            'is_active' => $request->boolean('is_active', true),
        ]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($jamOperasional)
            ->log('Created jam operasional: ' . $jamOperasional->hari);

        return redirect()->route('jam-operasional.index')
            ->with('success', 'Jam operasional berhasil ditambahkan.');
    }

    /**
     * Update jam operasional
     */
    public function update(Request $request, JamOperasional $jamOperasional)
    {
        $request->validate([
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
            'is_active' => 'boolean',
        ]);

        $jamOperasional->update([
            'jam_buka' => $request->jam_buka,
            'jam_tutup' => $request->jam_tutup,
            'is_active' => $request->boolean('is_active'),
        ]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($jamOperasional)
            ->log('Updated jam operasional: ' . $jamOperasional->hari);

        return redirect()->route('jam-operasional.index')
            ->with('success', 'Jam operasional berhasil diperbarui.');
    }

    /**
     * Delete jam operasional
     */
    public function destroy(JamOperasional $jamOperasional)
    {
        activity()
            ->causedBy(Auth::user())
            ->performedOn($jamOperasional)
            ->log('Deleted jam operasional: ' . $jamOperasional->hari);

        $jamOperasional->delete();

        return redirect()->route('jam-operasional.index')
            ->with('success', 'Jam operasional berhasil dihapus.');
    }

    /**
     * Toggle status jam operasional
     */
    public function toggleStatus(JamOperasional $jamOperasional)
    {
        $jamOperasional->update(['is_active' => !$jamOperasional->is_active]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($jamOperasional)
            ->log(($jamOperasional->is_active ? 'Activated' : 'Deactivated') . ' jam operasional: ' . $jamOperasional->hari);

        return response()->json([
            'success' => true,
            'message' => 'Status jam operasional berhasil diubah.',
            'is_active' => $jamOperasional->is_active
        ]);
    }
}
