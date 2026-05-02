<?php

namespace App\Http\Controllers;

use App\Models\Land;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandController extends Controller
{
    // 1. Just fetch the logged-in farmer's land. No Admin checks here.
    public function index()
    {
        $lands = Land::where('user_id', Auth::id())->with('user')->get();
        return view('lands.index', compact('lands'));
    }

    public function create()
    {
        return view('lands.create');
    }

    // 2. Protect the store method
    public function store(Request $request)
    {
        $request->validate([
            'nickname' => 'required|string|max:255',
            'area_size' => 'required|numeric|min:0',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            // Notice we don't validate 'boundaries' here. Farmers can just drop a pin for now, 
            // and the admin can draw the actual polygon later on the master map.
        ]);

        Land::create([
            'user_id' => Auth::id(),
            'nickname' => $request->nickname,
            'area_size' => $request->area_size,
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);

        return redirect()->route('lands.index')->with('success', 'Lahan berhasil ditambahkan.');
    }

    // 3. Strict security: Automatically 404 if a farmer tries to view someone else's land
    public function show($id)
    {
        $land = Land::where('id', $id)->where('user_id', Auth::id())->with('ubinans')->firstOrFail();
        return view('lands.show', compact('land'));
    }

    public function edit($id)
    {
        $land = Land::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('lands.edit', compact('land'));
    }

    public function update(Request $request, $id)
    {
        $land = Land::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'nickname' => 'required|string|max:255',
            'area_size' => 'required|numeric|min:0',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        // Only update these fields so we don't accidentally overwrite the admin's polygon boundaries
        $land->update($request->only(['nickname', 'area_size', 'lat', 'lng']));

        return redirect()->route('lands.index')->with('success', 'Lahan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $land = Land::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $land->delete();
        
        return redirect()->route('lands.index')->with('success', 'Lahan berhasil dihapus.');
    }
}