<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Land;
use App\Models\Price;

class FarmerDashboardController extends Controller
{
    public function index()
    {
        $farmer = Auth::user();
        $lands = Land::where('user_id', $farmer->id)->get();

        $totalLahan = $lands->count();
        $totalLuas = $lands->sum('area_size'); // hektar

        // Estimasi panen: misal 5 ton per hektar (bisa disesuaikan per komoditas nanti)
        $estimasiPanenTon = $totalLuas * 5;
        $estimasiRange = round($estimasiPanenTon * 0.9, 1) . ' – ' . round($estimasiPanenTon * 1.1, 1) . ' Ton';

        // Harga jual terbaru (ambil dari model Price jika ada, atau statis)
        $latestPrice = Price::latest()->first();
        $hargaJual = $latestPrice ? 'Rp ' . number_format($latestPrice->price_per_kg, 0, ',', '.') . '/kg' : 'Rp 12.000/kg';
        $hargaNumeric = $latestPrice ? $latestPrice->price_per_kg : 12000;

        // Data untuk chart penjualan (contoh data statis, nanti bisa dari model Harvest)
        $chartLabels = ['Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt'];
        $chartValues = [1250, 1420, 1380, 1600, 1750, 1890]; // kg (mock)

        // Cuaca mock (dikirim sebagai variabel terpisah, bukan array)
        $weatherTemp = '28°C';
        $weatherDelta = 'Cerah';

        // Data untuk card "Total Panen Ringkasan"
        $totalPanenData = [
            'flag' => '🇮🇩',
            'country' => 'Indonesia',
            'sales' => number_format(array_sum($chartValues), 0, ',', '.'),
            'value' => 'Rp ' . number_format(array_sum($chartValues) * $hargaNumeric, 0, ',', '.'),
            'bounce' => '12%'
        ];

        return view('farmer.dashboard', compact(
            'totalLuas', 
            'hargaJual', 
            'estimasiRange', 
            'chartLabels', 
            'chartValues', 
            'totalPanenData'
        ));
    }
}