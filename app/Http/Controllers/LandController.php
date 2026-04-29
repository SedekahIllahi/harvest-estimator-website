<?php

namespace App\Http\Controllers;

use App\Models\Land;
use App\Models\User;
use App\Services\HarvestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // membutuhkan login
    }

    /**
     * Menampilkan daftar lahan milik user (petani) atau semua lahan jika admin.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->isBapakDukuh()) {
            $lands = Land::with('user')->get();
        } else {
            $lands = Land::where('user_id', $user->id)->with('user')->get();
        }
        return view('lands.index', compact('lands'));
    }

    /**
     * Form tambah lahan.
     */
    public function create()
    {
        return view('lands.create');
    }

    /**
     * Menyimpan lahan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nickname' => 'required|string|max:255',
            'area_size' => 'required|numeric|min:0',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
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

    /**
     * Detail lahan.
     */
    public function show($id)
    {
        $land = Land::with('user', 'ubinans')->findOrFail($id);
        $user = Auth::user();
        if ($user->isFarmer() && $land->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        return view('lands.show', compact('land'));
    }

    /**
     * Form edit lahan.
     */
    public function edit($id)
    {
        $land = Land::findOrFail($id);
        $user = Auth::user();
        if ($user->isFarmer() && $land->user_id !== $user->id) {
            abort(403);
        }
        return view('lands.edit', compact('land'));
    }

    /**
     * Update lahan.
     */
    public function update(Request $request, $id)
    {
        $land = Land::findOrFail($id);
        $user = Auth::user();
        if ($user->isFarmer() && $land->user_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'nickname' => 'required|string|max:255',
            'area_size' => 'required|numeric|min:0',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        $land->update($request->only(['nickname', 'area_size', 'lat', 'lng']));

        return redirect()->route('lands.index')->with('success', 'Lahan berhasil diperbarui.');
    }

    /**
     * Hapus lahan.
     */
    public function destroy($id)
    {
        $land = Land::findOrFail($id);
        $user = Auth::user();
        if ($user->isFarmer() && $land->user_id !== $user->id) {
            abort(403);
        }
        $land->delete();
        return redirect()->route('lands.index')->with('success', 'Lahan berhasil dihapus.');
    }
}