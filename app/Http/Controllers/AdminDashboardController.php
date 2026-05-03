<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Price; // tambahkan ini

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Ambil semua data harga komoditas untuk modal price
        $prices = Price::all();

        // Hitung statistik dinamis (contoh dari database, bukan mock)
        // Karena masih ada mock, kita hitung dari model jika ada data
        $total_farmers = \App\Models\User::where('role', 'farmer')->count();
        $active_fields = \App\Models\Land::count(); // semua lahan dianggap aktif
        $est_harvest_tons = \App\Models\Land::sum('area_size') * 5; // asumsi 5 ton per hektar

        // Ambil harga terbaru untuk ditampilkan di card (misal harga padi)
        $latest_price_record = Price::where('slug', 'padi')->first();
        $latest_price = $latest_price_record ? 'Rp ' . number_format($latest_price_record->price_per_kg, 0, ',', '.') . '/kg' : 'Rp 12,000/kg';

        $stats = [
            'total_farmers' => $total_farmers,
            'active_fields' => $active_fields,
            'est_harvest_tons' => round($est_harvest_tons, 1),
            'latest_price' => $latest_price
        ];

        // Data untuk tabel recent mappings (masih mock, bisa diambil dari database nanti)
        $recent_mappings = [
            ['farmer' => 'Pak Budi', 'location' => 'Block A', 'area_ha' => 2.5, 'crop' => 'Rice'],
            ['farmer' => 'Bu Siti', 'location' => 'Block C', 'area_ha' => 1.2, 'crop' => 'Corn'],
        ];

        return view('admin.dashboard', compact('stats', 'recent_mappings', 'prices'));
    }
}