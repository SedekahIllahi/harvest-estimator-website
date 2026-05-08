<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Land;

class FarmerProfileController extends Controller
{
    // Tampilkan halaman profil
    public function index()
    {
        $farmer = Auth::user();
        $lands = Land::where('user_id', $farmer->id)->get();

        // Hitung total luas lahan dalam meter persegi
        $totalAreaHectare = $lands->sum('area_size');
        $totalAreaSqm = $totalAreaHectare * 10000;

        // Ambil lahan pertama untuk menentukan tanaman utama
        $mainLand = $lands->first();
        $crop = $mainLand ? ($mainLand->crop ?? $this->guessCropFromLandName($mainLand->nickname)) : 'Belum ada lahan';

        return view('farmer.profile', compact('farmer', 'totalAreaSqm', 'crop'));
    }

    // Tampilkan form edit profil
    public function edit()
    {
        $farmer = Auth::user();
        return view('farmer.edit-profile', compact('farmer'));
    }

    // Proses update profil (nama & telepon)
    public function update(Request $request)
    {
        $farmer = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone,' . $farmer->id,
        ]);

        $farmer->update($validated);

        return redirect()->route('farmer.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    // Tampilkan form ganti PIN
    public function showPinForm()
    {
        return view('farmer.change-pin');
    }

    // Proses ganti PIN
    public function updatePin(Request $request)
    {
        $request->validate([
            'current_pin' => 'required|string',
            'new_pin' => 'required|string|min:4|confirmed',
        ]);

        $farmer = Auth::user();

        if (!Hash::check($request->current_pin, $farmer->password)) {
            return back()->withErrors(['current_pin' => 'PIN saat ini salah.']);
        }

        $farmer->update([
            'password' => Hash::make($request->new_pin)
        ]);

        return redirect()->route('farmer.profile')->with('success', 'PIN berhasil diubah.');
    }

    // Helper untuk menebak jenis tanaman dari nama lahan
    private function guessCropFromLandName($landName)
    {
        $landName = strtolower($landName);
        if (str_contains($landName, 'padi')) return 'Padi';
        if (str_contains($landName, 'jagung')) return 'Jagung';
        if (str_contains($landName, 'kedelai')) return 'Kedelai';
        if (str_contains($landName, 'singkong')) return 'Singkong';
        if (str_contains($landName, 'tebu')) return 'Tebu';
        if (str_contains($landName, 'sawit')) return 'Kelapa Sawit';
        return 'Padi';
    }
}