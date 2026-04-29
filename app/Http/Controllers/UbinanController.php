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

class UbinanController extends Controller
{
    public function index()
    {
        // Fetch all estimates, eager loading the land and the farmer who owns it
        $ubinans = Ubinan::with(['land.user'])->latest()->get();
        // We'll need the lands for the "Create New Estimate" dropdown
        $lands = Land::with('user')->get(); 
        
        return view('admin.ubinans.index', compact('ubinans', 'lands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
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
            'sample_weight_kg' => 'required|numeric|min:0.1',
            'projected_harvest_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $land = Land::findOrFail($validated['land_id']);

        // --- THE MATH ENGINE ---
        // 1 Hectare = 10,000 sq meters. Sample plot = 6.25 sq meters.
        $total_sq_meters = $land->area_size * 10000;
        $multiplier = $total_sq_meters / 6.25;
        $estimated_total_kg = $multiplier * $validated['sample_weight_kg'];

        Ubinan::create([
            'land_id' => $land->id,
            'sample_weight_kg' => $validated['sample_weight_kg'],
            'estimated_yield_kg' => $estimated_total_kg,
            'projected_harvest_date' => $validated['projected_harvest_date'],
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Estimasi panen berhasil dihitung dan disimpan!');
    }

    public function updateStatus(Request $request, Ubinan $ubinan)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,harvested,failed'
        ]);

        $ubinan->update(['status' => $validated['status']]);

        return back()->with('success', 'Status panen diperbarui!');
    }
}