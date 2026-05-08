<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Land;
use App\Models\Ubinan;
use App\Models\CommodityPrice;
use Carbon\Carbon;

class FarmerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. TOTAL LUAS LAHAN
        // Jumlahkan semua 'area_size' dari lahan milik petani ini
        $totalLuas = $user->lands()->sum('area_size') ?? 0;

        // 2. HARGA JUAL HARI INI
        // Ambil harga terbaru dari database
        $latestPrices = \App\Models\CommodityPrice::latest()->get()->unique('crop_name');

        // Map them into the format the Blade slider needs
        $cropPrices = $latestPrices->map(function ($item) {
            return (object) [
                'name' => $item->crop_name,
                'current_price' => $item->price_per_kg,
                'last_price' => $item->price_per_kg // Temporary fallback so the math doesn't crash
            ];
        })->values();

        // 3. PERKIRAAN PANEN
        // Ambil semua ID lahan milik petani ini
        $landIds = $user->lands()->pluck('id');

        // Jumlahkan estimated_yield_kg dari tabel ubinan yang belum gagal
        $totalEstimasiKg = \App\Models\Ubinan::whereIn('land_id', $landIds)
            ->where('status', '!=', 'failed')
            ->sum('estimated_yield_kg');

        // Convert ke Ton agar lebih rapi di UI
        $estimasiRange = $totalEstimasiKg > 0
            ? number_format($totalEstimasiKg / 1000, 2) . ' Ton'
            : '0 Ton';

        // 4. RIWAYAT HASIL UBINAN (YIELD HISTORY)
        // Ambil maksimal 5 data panen terakhir dari lahan milik petani ini
        $riwayatPanen = \App\Models\Ubinan::whereIn('land_id', $landIds)
            ->whereNotNull('projected_harvest_date')
            // ->where('status', 'completed') // <-- Uncomment ini jika pakai sistem status selesai
            ->orderBy('projected_harvest_date', 'asc')
            ->take(5)
            ->get();

        $chartLabels = [];
        $chartValues = [];

        // Jika petani belum punya data historis sama sekali
        if ($riwayatPanen->isEmpty()) {
            $chartLabels = ['Belum ada data'];
            $chartValues = [0];
        } else {
            foreach ($riwayatPanen as $panen) {
                // Ubah format tanggal jadi pendek (Contoh: "Mar 26")
                $chartLabels[] = \Carbon\Carbon::parse($panen->projected_harvest_date)->translatedFormat('M y');
                $chartValues[] = $panen->estimated_yield_kg;
            }
        }

        // 5. RINGKASAN PANEN
        // FIX: Grab the first price from the collection to use as a baseline, default to 0 if empty
        $basePrice = $latestPrices->first()->price_per_kg ?? 0;
        $estimasiPendapatan = $totalEstimasiKg * $basePrice;

        $totalPanenData = [
            'flag' => '🇮🇩',
            'country' => 'Indonesia',
            'sales' => number_format($totalEstimasiKg, 0, ',', '.'),
            'value' => 'Rp ' . number_format($estimasiPendapatan, 0, ',', '.'),
            'bounce' => 'Data Baru'
        ];

        // 6. KIRIM SEMUANYA KE VIEW (Satu kali saja, di paling bawah!)
        return view('farmer.dashboard', compact(
            'totalLuas',
            'cropPrices',
            'estimasiRange',
            'chartLabels',
            'chartValues',
            'totalPanenData'
        ));
    }

    /**
     * Engine Cuaca Pintar via Open-Meteo (Gratis, Tanpa API Key)
     */
    private function getWeatherForecast($user, $pendingUbinans)
    {
        $response = ['temp' => 'Menunggu Data', 'delta' => 'Tentukan lokasi lahan terlebih dahulu'];

        // Ambil koordinat lahan pertama milik user
        $firstLand = Land::where('user_id', $user->id)->whereNotNull('lat')->first();
        if (!$firstLand) return $response;

        // Cache cuaca selama 2 jam biar nggak di-banned API-nya karena spam
        $cacheKey = 'weather_' . $firstLand->id;
        $forecast = Cache::remember($cacheKey, 120, function () use ($firstLand) {
            try {
                // Request 14-day forecast
                $url = "https://api.open-meteo.com/v1/forecast?latitude={$firstLand->lat}&longitude={$firstLand->lng}&daily=weathercode,temperature_2m_max,temperature_2m_min,precipitation_probability_max&timezone=Asia%2FJakarta&forecast_days=14";
                $res = Http::timeout(3)->get($url);
                return $res->successful() ? $res->json() : null;
            } catch (\Exception $e) {
                return null; // Silent fail kalau koneksi ngadat
            }
        });

        if (!$forecast) {
            return ['temp' => 'Cuaca Offline', 'delta' => 'Gagal terhubung ke satelit.'];
        }

        // Cari jadwal panen terdekat
        $nearestHarvest = $pendingUbinans->where('projected_harvest_date', '>=', now())
            ->sortBy('projected_harvest_date')
            ->first();

        // Cuaca hari ini
        $todayMax = $forecast['daily']['temperature_2m_max'][0] ?? 30;
        $todayMin = $forecast['daily']['temperature_2m_min'][0] ?? 24;
        $todayCode = $forecast['daily']['weathercode'][0] ?? 0;

        $response['temp'] = $this->translateWeatherCode($todayCode) . ", " . round(($todayMax + $todayMin) / 2) . "°C";

        // Logic Rekomendasi Panen
        if ($nearestHarvest) {
            $harvestDate = Carbon::parse($nearestHarvest->projected_harvest_date);
            $daysAway = now()->diffInDays($harvestDate, false);

            if ($daysAway >= 0 && $daysAway < 14) {
                // Cek persentase hujan di hari panen
                $rainProb = $forecast['daily']['precipitation_probability_max'][intval($daysAway)] ?? 0;

                if ($rainProb > 50) {
                    $response['delta'] = "⚠️ Awas: " . $harvestDate->format('d M') . " peluang hujan {$rainProb}%. Pertimbangkan geser jadwal.";
                } else {
                    $response['delta'] = "✅ " . $harvestDate->format('d M') . " cerah. Ideal untuk panen!";
                }
            } else {
                $response['delta'] = "Panen " . $harvestDate->format('d M') . ". Prediksi cuaca belum tersedia.";
            }
        } else {
            $response['delta'] = "Belum ada jadwal panen terdekat.";
        }

        return $response;
    }

    /**
     * Terjemahan Kode Cuaca WMO ke Bahasa Indonesia
     */
    private function translateWeatherCode($code)
    {
        if ($code == 0) return 'Cerah';
        if ($code >= 1 && $code <= 3) return 'Cerah Berawan';
        if ($code >= 45 && $code <= 48) return 'Berkabut';
        if ($code >= 51 && $code <= 67) return 'Hujan Ringan';
        if ($code >= 80 && $code <= 82) return 'Hujan Deras';
        if ($code >= 95) return 'Badai Petir';
        return 'Berawan';
    }
}
