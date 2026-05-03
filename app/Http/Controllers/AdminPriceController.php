<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Http\Request;

class AdminPriceController extends Controller
{
    public function index()
    {
        $prices = Price::all();
        return view('admin.prices.index', compact('prices'));
    }

    public function update(Request $request, Price $price)
    {
        $request->validate([
            'price_per_kg' => 'required|numeric|min:0',
            'conversion_factor' => 'required|numeric|min:0.1'
        ]);

        $price->update([
            'price_per_kg' => $request->price_per_kg,
            'conversion_factor' => $request->conversion_factor
        ]);

        return back()->with('success', "Harga dan faktor konversi untuk {$price->commodity} berhasil diupdate.");
    }
}