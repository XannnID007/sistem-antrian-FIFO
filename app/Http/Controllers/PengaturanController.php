<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaturan;
use Illuminate\Support\Facades\Auth;

class PengaturanController extends Controller
{
    /**
     * Display pengaturan
     */
    public function index()
    {
        $pengaturan = Pengaturan::all()->groupBy(function ($item) {
            // Group by category (prefix before underscore)
            $parts = explode('_', $item->key);
            return $parts[0] ?? 'umum';
        });

        return view('pengaturan.index', compact('pengaturan'));
    }

    /**
     * Store new setting
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:pengaturan,key',
            'value' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $pengaturan = Pengaturan::create($request->all());

        activity()
            ->causedBy(Auth::user())
            ->performedOn($pengaturan)
            ->log('Created setting: ' . $pengaturan->key);

        return redirect()->route('pengaturan.index')
            ->with('success', 'Pengaturan berhasil ditambahkan.');
    }

    /**
     * Update setting
     */
    public function update(Request $request, Pengaturan $pengaturan)
    {
        $request->validate([
            'value' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $oldValue = $pengaturan->value;
        $pengaturan->update($request->only(['value', 'description']));

        activity()
            ->causedBy(Auth::user())
            ->performedOn($pengaturan)
            ->log("Updated setting {$pengaturan->key}: {$oldValue} → {$pengaturan->value}");

        return redirect()->route('pengaturan.index')
            ->with('success', 'Pengaturan berhasil diperbarui.');
    }

    /**
     * Delete setting
     */
    public function destroy(Pengaturan $pengaturan)
    {
        activity()
            ->causedBy(Auth::user())
            ->performedOn($pengaturan)
            ->log('Deleted setting: ' . $pengaturan->key);

        $pengaturan->delete();

        return redirect()->route('pengaturan.index')
            ->with('success', 'Pengaturan berhasil dihapus.');
    }
}
