<?php

namespace App\Http\Controllers;

use App\Models\CommodityPrice;
use Illuminate\Http\Request;

class CommodityPriceController extends Controller
{
    /**
     * Tampilkan halaman manajemen harga (History & Form Tambah)
     */
    public function index()
    {
        // Get all history, ordered by newest first
        $priceHistory = CommodityPrice::orderBy('effective_date', 'desc')->get();

        // Get just the absolute latest price for each crop (useful for dashboard widgets)
        $latestPrices = $priceHistory->unique('crop_name');

        $existingCrops = CommodityPrice::select('crop_name')->distinct()->pluck('crop_name');

        return view('admin.prices.index', compact('latestPrices', 'priceHistory', 'existingCrops'));
    }

    /**
     * Simpan harga baru (Append-only / History tracking)
     */
    public function store(Request $request)
    {
        // 1. Validation Upgrade
        // We tell Laravel: "new_crop_name is ONLY required if they selected 'NEW' in the dropdown"
        $request->validate([
            'crop_name' => 'required|string|max:255',
            'new_crop_name' => 'required_if:crop_name,NEW|nullable|string|max:255',
            'price_per_kg' => 'required|numeric|min:0',
        ]);

        // 2. The Logic Swap
        // If they selected 'NEW', grab what they typed in the text input.
        // Otherwise, just use the normal dropdown value.
        $actualCropName = $request->crop_name === 'NEW'
            ? $request->new_crop_name
            : $request->crop_name;

        // 3. Save it to the database
        CommodityPrice::create([
            'crop_name' => $actualCropName,
            'price_per_kg' => $request->price_per_kg,
            'effective_date' => now(),
        ]);

        return back()->with('success', "Harga {$actualCropName} terbaru berhasil dicatat!");
    }

    /**
     * Hapus data harga komoditas
     */
    public function destroy($id)
    {
        $price = CommodityPrice::findOrFail($id);
        $cropName = $price->crop_name; // Save the name before deleting for the message

        $price->delete();

        return back()->with('success', "Data harga {$cropName} berhasil dihapus dari sistem!");
    }
}
