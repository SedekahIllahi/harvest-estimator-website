<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmerKalkulatorController extends Controller
{
    /**
     * Tampilkan halaman kalkulator
     */
    public function index()
    {
        $latestPrices = \App\Models\CommodityPrice::orderBy('effective_date', 'desc')
            ->get()
            ->unique('crop_name');

        // Pass it to the view
        return view('farmer.kalkulator', compact('latestPrices'));;
    }

    /**
     * Hitung kebutuhan pupuk berdasarkan luas lahan
     * (Contoh sederhana, bisa ditambah rumus lain)
     */
    public function hitungPupuk(Request $request)
    {
        $request->validate([
            'luas_lahan' => 'required|numeric|min:0.01',
            'dosis_per_hektar' => 'required|numeric|min:0',
        ]);

        $totalPupuk = $request->luas_lahan * $request->dosis_per_hektar;

        return back()->with('hasil_pupuk', "Total pupuk yang dibutuhkan: " . number_format($totalPupuk, 2) . " kg");
    }

    /**
     * Hitung estimasi keuntungan berdasarkan luas lahan dan harga jual
     */
    public function hitungKeuntungan(Request $request)
    {
        $request->validate([
            'luas_lahan' => 'required|numeric|min:0.01',
            'estimasi_hasil_ton_per_hektar' => 'required|numeric|min:0',
            'harga_per_kg' => 'required|numeric|min:0',
        ]);

        $totalHasilKg = $request->luas_lahan * $request->estimasi_hasil_ton_per_hektar * 1000;
        $totalKeuntungan = $totalHasilKg * $request->harga_per_kg;

        return back()->with('hasil_keuntungan', [
            'total_hasil_kg' => number_format($totalHasilKg, 0, ',', '.'),
            'total_keuntungan' => 'Rp ' . number_format($totalKeuntungan, 0, ',', '.')
        ]);
    }
}
