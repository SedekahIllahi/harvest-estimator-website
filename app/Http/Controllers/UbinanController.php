<?php

namespace App\Http\Controllers;

use App\Models\Land;
use App\Models\Ubinan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UbinanController extends Controller
{
    /**
     * Display the list of Ubinan estimates.
     * Smart routing: Admins see everything, Farmers see only theirs.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // ADMIN VIEW: See all estimates + all lands for the dropdown
            $ubinans = Ubinan::with(['land.user'])->latest()->get();
            $lands = Land::with('user')->get(); 
            return view('admin.ubinans.index', compact('ubinans', 'lands'));
        } else {
            // FARMER VIEW: See only their own estimates
            $ubinans = Ubinan::whereHas('land', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with('land')->latest()->get();
            
            // Assuming your friend made a 'ubinans.index' view for the farmer side
            return view('ubinans.index', compact('ubinans'));
        }
    }

    /**
     * Store a newly created Ubinan calculation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'land_id' => 'required|exists:lands,id',
            'sample_weight_kg' => 'required|numeric|min:0.1',
            'projected_harvest_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $land = Land::findOrFail($validated['land_id']);
        $user = Auth::user();

        // SECURITY: If a farmer is submitting this, make sure it's actually their land
        if ($user->isFarmer() && $land->user_id !== $user->id) {
            abort(403, 'Unauthorized. You do not own this land.');
        }

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
            'status' => 'pending', // Defaults to pending
        ]);

        return back()->with('success', 'Estimasi panen berhasil dihitung dan disimpan!');
    }

    /**
     * Admin ONLY: Update the status of a harvest (pending -> harvested/failed)
     */
    public function updateStatus(Request $request, Ubinan $ubinan)
    {
        $user = Auth::user();
        
        // Block farmers from changing official statuses
        if (!$user->isBapakDukuh()) {
            abort(403, 'Hanya admin yang dapat mengubah status.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,harvested,failed'
        ]);

        $ubinan->update(['status' => $validated['status']]);

        return back()->with('success', 'Status panen diperbarui!');
    }

    /**
     * Delete an Ubinan record.
     */
    public function destroy($id)
    {
        $ubinan = Ubinan::findOrFail($id);
        $user = Auth::user();

        // Check if a farmer is trying to delete someone else's record
        if ($user->isFarmer() && $ubinan->land->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $ubinan->delete();

        return back()->with('success', 'Data ubinan berhasil dihapus.');
    }
}