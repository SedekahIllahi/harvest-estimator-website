<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Land;

class FarmerLandController extends Controller
{
    public function index()
    {
        $lands = Land::where('user_id', Auth::id())->get();
        return view('farmer.lands.index', compact('lands'));
    }

    public function show(Land $land)
    {
        // Pastikan lahan milik petani yang login
        if ($land->user_id !== Auth::id()) {
            abort(403);
        }
        return view('farmer.lands.show', compact('land'));
    }

    // Petani bisa mengedit nickname lahan atau batas? Mungkin hanya melihat, tapi boleh edit nama
    public function edit(Land $land)
    {
        if ($land->user_id !== Auth::id()) {
            abort(403);
        }
        return view('farmer.lands.edit', compact('land'));
    }

    public function update(Request $request, Land $land)
    {
        if ($land->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'nickname' => 'required|string|max:255',
            // area_size, boundaries tidak diubah oleh petani untuk keamanan
        ]);

        $land->update($validated);
        return redirect()->route('farmer.lands.index')->with('success', 'Nama lahan berhasil diupdate.');
    }
}