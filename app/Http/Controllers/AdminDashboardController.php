<?php

namespace App\Http\Controllers;

use App\Models\Land;
use App\Models\User;
use App\Models\Ubinan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:bapak_dukuh'); // hanya admin desa
    }

    /**
     * Tampilkan dashboard admin.
     */
    public function index()
    {
        // 1. Agregasi seluruh desa
        $totalFarmers = User::where('role', 'farmer')->count();
        $totalLands = Land::count();
        
        // 2. Proyeksi panen desa (total estimasi dari ubinan terbaru per lahan)
        $villageProjectionTons = $this->calculateVillageProjection();
        
        // 3. Data untuk grafik atau pemetaan
        $recentUbinans = Ubinan::with('land.user')->orderBy('created_at', 'desc')->limit(10)->get();
        
        // 4. Daftar farmer (untuk ditampilkan di tabel)
        $farmers = User::where('role', 'farmer')->with('lands')->get();
        
        return view('admin.dashboard', compact(
            'totalFarmers',
            'totalLands',
            'villageProjectionTons',
            'recentUbinans',
            'farmers'
        ));
    }
    
    /**
     * Hitung total proyeksi panen seluruh desa.
     * Ambil ubinan terbaru per lahan, jumlahkan estimated_yield_tons.
     */
    private function calculateVillageProjection()
    {
        $total = 0;
        $lands = Land::all();
        foreach ($lands as $land) {
            $latest = Ubinan::where('land_id', $land->id)
                ->orderBy('created_at', 'desc')
                ->first();
            if ($latest) {
                $total += $latest->estimated_yield_tons;
            }
        }
        return round($total, 2);
    }
    
    /**
     * Data untuk peta desa (Leaflet).
     * Endpoint API JSON, bisa dipanggil dari frontend.
     */
    public function mapData()
    {
        $lands = Land::with('user')
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->get(['id', 'nickname', 'lat', 'lng', 'user_id', 'area_size']);
        
        $features = [];
        foreach ($lands as $land) {
            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float) $land->lng, (float) $land->lat]
                ],
                'properties' => [
                    'id' => $land->id,
                    'name' => $land->nickname,
                    'farmer' => $land->user->name ?? 'Tidak diketahui',
                    'area_ha' => $land->area_size,
                ]
            ];
        }
        
        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features
        ]);
    }
    
    /**
     * Daftar farmer dalam bentuk JSON (untuk datatable).
     */
    public function farmersList()
    {
        $farmers = User::where('role', 'farmer')
            ->with(['lands' => function ($q) {
                $q->withCount('ubinans');
            }])
            ->get()
            ->map(function ($farmer) {
                $totalLandArea = $farmer->lands->sum('area_size');
                $totalEstimatedTons = 0;
                foreach ($farmer->lands as $land) {
                    $latest = $land->ubinans()->latest()->first();
                    if ($latest) $totalEstimatedTons += $latest->estimated_yield_tons;
                }
                return [
                    'id' => $farmer->id,
                    'name' => $farmer->name,
                    'phone_number' => $farmer->phone_number,
                    'total_lands' => $farmer->lands->count(),
                    'total_land_area_ha' => $totalLandArea,
                    'total_projected_yield_tons' => round($totalEstimatedTons, 2),
                ];
            });
        
        return response()->json(['data' => $farmers]);
    }
}