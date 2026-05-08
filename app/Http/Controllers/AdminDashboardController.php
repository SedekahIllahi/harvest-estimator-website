<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Land;
use App\Models\CommodityPrice;
use App\Models\Ubinan;

class AdminDashboardController extends Controller
{
public function index()
    {
        $total_farmers = User::where('role', 'farmer')->count();
        $active_fields = Land::count(); 
        
        // [NEW] Hitung total luas lahan fisik (Hektar)
        $total_area = Land::sum('area_size'); 
        
        // [NEW] Hitung antrean Ubinan yang butuh validasi admin (Alerts)
        $pending_alerts = Ubinan::where('status', 'pending')->count();

        // Estimasi panen sementara (Asumsi 5 ton per hektar)
        $est_harvest_tons = $total_area * 5; 

        // Ambil harga terbaru khusus untuk Padi (sebagai benchmark di dashboard)
        $latest_price_record = CommodityPrice::where('crop_name', 'Padi')
                                            ->orderBy('effective_date', 'desc')
                                            ->first();
                                            
        $latest_price = $latest_price_record ? 'Rp ' . number_format($latest_price_record->price_per_kg, 0, ',', '.') . '/kg' : 'Belum ada data';

        $existingCrops = CommodityPrice::select('crop_name')->distinct()->pluck('crop_name');

        // Array stats yang sudah terhubung penuh ke UI baru
        $stats = [
            'total_farmers' => $total_farmers,
            'active_fields' => $active_fields,
            'total_area' => $total_area,           // <-- Dikirim ke UI
            'est_harvest_tons' => round($est_harvest_tons, 1),
            'pending_alerts' => $pending_alerts,   // <-- Dikirim ke UI
            'latest_price' => $latest_price
        ];

        $recent_mappings = [
            ['farmer' => 'Pak Budi', 'location' => 'Block A', 'area_ha' => 2.5, 'crop' => 'Rice'],
            ['farmer' => 'Bu Siti', 'location' => 'Block C', 'area_ha' => 1.2, 'crop' => 'Corn'],
        ];

        // Grab the most recent price for EACH crop to show in the admin table
        $latest_prices = CommodityPrice::orderBy('effective_date', 'desc')
                                        ->get()
                                        ->unique('crop_name');

        return view('admin.dashboard', compact('stats', 'recent_mappings', 'latest_prices', 'existingCrops'));
    }
}