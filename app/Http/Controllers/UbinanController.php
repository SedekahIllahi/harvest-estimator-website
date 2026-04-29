<?php

namespace App\Http\Controllers;

use App\Models\Land;
use App\Models\Ubinan;
use Illuminate\Http\Request;

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