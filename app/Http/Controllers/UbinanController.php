<?php

namespace App\Http\Controllers;

use App\Models\Land;
use App\Models\Ubinan;
use App\Services\HarvestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UbinanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan daftar ubinan (riwayat).
     * - Petani: hanya ubinan dari lahan miliknya sendiri.
     * - Bapak Dukuh: semua ubinan.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isBapakDukuh()) {
            $ubinans = Ubinan::with('land.user')->orderBy('created_at', 'desc')->get();
        } else {
            $ubinans = Ubinan::whereHas('land', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with('land')->orderBy('created_at', 'desc')->get();
        }

        return view('ubinans.index', compact('ubinans'));
    }

    /**
     * Form tambah ubinan.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->isBapakDukuh()) {
            $lands = Land::with('user')->get();
        } else {
            $lands = Land::where('user_id', $user->id)->get();
        }

        return view('ubinans.create', compact('lands'));
    }

    /**
     * Simpan data ubinan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'land_id' => 'required|exists:lands,id',
            'sample_weight_kg' => 'required|numeric|min:0.01|max:999.99',
            'weather_note' => 'nullable|string|max:255',
        ]);

        $land = Land::findOrFail($request->land_id);

        // Otorisasi: petani hanya bisa tambah ubinan untuk lahan miliknya
        if (Auth::user()->isFarmer() && $land->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk lahan ini.');
        }

        // Hitung estimasi hasil (ton) menggunakan HarvestService
        $estimatedTons = HarvestService::calculateTotalYield(
            $request->sample_weight_kg,
            $land->area_size
        );

        Ubinan::create([
            'land_id' => $request->land_id,
            'sample_weight_kg' => $request->sample_weight_kg,
            'estimated_yield_tons' => $estimatedTons,
            'weather_note' => $request->weather_note,
        ]);

        return redirect()->route('ubinans.index')
            ->with('success', 'Data ubinan berhasil disimpan.');
    }

    /**
     * Detail satu ubinan (opsional).
     */
    public function show($id)
    {
        $ubinan = Ubinan::with('land.user')->findOrFail($id);
        $user = Auth::user();

        if ($user->isFarmer() && $ubinan->land->user_id !== $user->id) {
            abort(403);
        }

        return view('ubinans.show', compact('ubinan'));
    }

    /**
     * Form edit ubinan.
     */
    public function edit($id)
    {
        $ubinan = Ubinan::with('land')->findOrFail($id);
        $user = Auth::user();

        if ($user->isFarmer() && $ubinan->land->user_id !== $user->id) {
            abort(403);
        }

        $lands = ($user->isBapakDukuh()) ? Land::with('user')->get() : Land::where('user_id', $user->id)->get();

        return view('ubinans.edit', compact('ubinan', 'lands'));
    }

    /**
     * Update ubinan.
     */
    public function update(Request $request, $id)
    {
        $ubinan = Ubinan::findOrFail($id);
        $user = Auth::user();

        if ($user->isFarmer() && $ubinan->land->user_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'land_id' => 'required|exists:lands,id',
            'sample_weight_kg' => 'required|numeric|min:0.01',
            'weather_note' => 'nullable|string|max:255',
        ]);

        $land = Land::findOrFail($request->land_id);
        $estimatedTons = HarvestService::calculateTotalYield(
            $request->sample_weight_kg,
            $land->area_size
        );

        $ubinan->update([
            'land_id' => $request->land_id,
            'sample_weight_kg' => $request->sample_weight_kg,
            'estimated_yield_tons' => $estimatedTons,
            'weather_note' => $request->weather_note,
        ]);

        return redirect()->route('ubinans.index')
            ->with('success', 'Data ubinan berhasil diperbarui.');
    }

    /**
     * Hapus ubinan.
     */
    public function destroy($id)
    {
        $ubinan = Ubinan::findOrFail($id);
        $user = Auth::user();

        if ($user->isFarmer() && $ubinan->land->user_id !== $user->id) {
            abort(403);
        }

        $ubinan->delete();

        return redirect()->route('ubinans.index')
            ->with('success', 'Data ubinan berhasil dihapus.');
    }
}