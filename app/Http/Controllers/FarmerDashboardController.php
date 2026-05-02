<?php

namespace App\Http\Controllers;

use App\Models\Land;
use App\Models\Ubinan;
use App\Services\HarvestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class FarmerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:farmer')->only('index'); // pastikan hanya farmer
    }

    /**
     * Tampilkan dashboard petani.
     */
    public function index()
    {
        $user = Auth::user();
        
        // 1. Total estimasi panen dari semua lahan milik petani
        $totalEstimatedTons = 0;
        $lands = Land::where('user_id', $user->id)->get();
        
        foreach ($lands as $land) {
            // Ambil ubinan terbaru per lahan (atau rata-rata? kita pakai yang terbaru)
            $latestUbinan = Ubinan::where('land_id', $land->id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($latestUbinan) {
                // estimated_yield_tons sudah dalam total ton untuk lahan itu
                $totalEstimatedTons += $latestUbinan->estimated_yield_tons;
            }
        }
        
        // 2. Ambil data cuaca (contoh dari OpenWeather, perlu API key)
        $weather = $this->getWeatherForUser($user);
        
        // 3. Data tambahan untuk grafik atau riwayat (opsional)
        $recentUbinans = Ubinan::whereHas('land', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('land')->orderBy('created_at', 'desc')->limit(5)->get();
        
        return view('farmer.dashboard', compact('totalEstimatedTons', 'weather', 'recentUbinans'));
    }
    
    /**
     * Ambil data cuaca berdasarkan GPS lahan pertama atau default.
     */
    private function getWeatherForUser($user)
    {
        // Cari lahan yang punya koordinat
        $land = Land::where('user_id', $user->id)
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->first();
        
        $lat = $land->lat ?? -6.200000;  // default Jakarta
        $lon = $land->lng ?? 106.816666;
        
        // Simpan di cache selama 10 menit agar tidak hit API terus
        $cacheKey = "weather_{$lat}_{$lon}";
        if (cache()->has($cacheKey)) {
            return cache($cacheKey);
        }
        
        // Panggil OpenWeather API (ganti dengan API key Anda)
        $apiKey = env('OPENWEATHER_API_KEY', '');
        if ($apiKey) {
            $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'id'
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                $weather = [
                    'condition' => $data['weather'][0]['description'] ?? '',
                    'temp' => $data['main']['temp'] ?? null,
                    'humidity' => $data['main']['humidity'] ?? null,
                    'icon' => $data['weather'][0]['icon'] ?? '',
                ];
                cache([$cacheKey => $weather], now()->addMinutes(10));
                return $weather;
            }
        }
        
        // Fallback jika tidak ada API key
        return ['condition' => 'Tidak tersedia', 'temp' => null];
    }
}