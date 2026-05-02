<?php

namespace App\Http\Controllers;

use App\Models\Land;
use App\Models\User;
use Illuminate\Http\Request;

class AdminLandController extends Controller
{
    public function index()
    {
        // Get all lands with their owners
        $lands = Land::with('user')->get();
        // Get all farmers for the "Reassign" dropdown
        $farmers = User::where('role', 'farmer')->orderBy('name')->get();

        return view('admin.mapping', compact('lands', 'farmers'));
    }

    public function update(Request $request, Land $land)
    {
        $validated = $request->validate([
            'nickname' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'area_size' => 'required|numeric',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'boundaries' => 'required|string', // JSON string from map
        ]);

        // Decode boundaries so it stores as a proper array in the JSON column
        $validated['boundaries'] = json_decode($request->boundaries);

        $land->update($validated);

        return back()->with('success', 'Lahan ' . $land->nickname . ' berhasil diperbarui!');
    }

    public function destroy(Land $land)
    {
        $land->delete();
        return back()->with('success', 'Land record removed.');
    }
}
