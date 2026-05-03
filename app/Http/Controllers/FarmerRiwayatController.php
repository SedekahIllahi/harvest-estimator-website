<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ubinan;
use Carbon\Carbon;

class FarmerRiwayatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil semua ubinan dari lahan milik petani, diurutkan terbaru
        $ubinans = Ubinan::whereHas('land', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('land')->orderBy('projected_harvest_date', 'desc')->get();

        $now = Carbon::now();
        $currentMonth = $now->format('Y-m');
        $lastMonth = $now->copy()->subMonth()->format('Y-m');

        $currentMonthItems = [];
        $lastMonthItems = [];
        $olderItems = [];

        foreach ($ubinans as $ubinan) {
            $date = $ubinan->projected_harvest_date ? Carbon::parse($ubinan->projected_harvest_date) : $ubinan->created_at;
            $monthKey = $date->format('Y-m');

            // Tentukan nama tanaman: bisa dari land->crop, atau dari nama lahan (default Padi)
            $cropName = $ubinan->land->crop ?? $this->guessCropFromLandName($ubinan->land->nickname);
            $icon = $this->getIcon($cropName);

            $item = [
                'id'          => $ubinan->id,
                'name'        => $cropName . ' – ' . ($ubinan->land->nickname ?? 'Lahan'),
                'date'        => $date->format('d M Y'),
                'location'    => $ubinan->land->location ?? 'Blok ' . ($ubinan->land->id % 100),
                'amount_ton'  => round($ubinan->estimated_yield_kg / 1000, 1),
                'status'      => $this->mapStatus($ubinan->status),
                'tag_class'   => $this->getTagClass($ubinan->status),
                'icon'        => $icon,
            ];

            if ($monthKey == $currentMonth) {
                $currentMonthItems[] = $item;
            } elseif ($monthKey == $lastMonth) {
                $lastMonthItems[] = $item;
            } else {
                $olderItems[] = $item;
            }
        }

        return view('farmer.riwayat', compact('currentMonthItems', 'lastMonthItems', 'olderItems'));
    }

    private function mapStatus($status)
    {
        return match ($status) {
            'harvested' => 'Sukses',
            'pending'   => 'Proses',
            'failed'    => 'Gagal',
            default     => 'Proses',
        };
    }

    private function getTagClass($status)
    {
        return match ($status) {
            'harvested' => 'sukses',
            'pending'   => 'proses',
            'failed'    => 'gagal',
            default     => 'proses',
        };
    }

    private function getIcon($cropName)
    {
        $crop = strtolower($cropName);
        if (str_contains($crop, 'padi')) return '🌾';
        if (str_contains($crop, 'jagung')) return '🌽';
        if (str_contains($crop, 'kedelai')) return '🫘';
        if (str_contains($crop, 'singkong')) return '🍠';
        if (str_contains($crop, 'tebu')) return '🎋';
        if (str_contains($crop, 'sawit')) return '🌴';
        return '🌱';
    }

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