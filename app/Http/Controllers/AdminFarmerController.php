<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Land;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminFarmerController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate the massive payload coming from the frontend map form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone', // Prevent duplicate numbers
            'pin' => 'required|string|min:4',
            'sawah_name' => 'required|string|max:255',
            'area_hectares' => 'required|numeric|min:0.0001',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        try {
            // 2. The Database Transaction (If one fails, they both fail)
            DB::transaction(function () use ($validated) {
                
                // A. Spawn the Farmer
                $farmer = User::create([
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'password' => Hash::make($validated['pin']), // Always hash the PIN!
                    'role' => 'farmer',
                ]);

                // B. Spawn the Land and attach it to the Farmer's new ID
                Land::create([
                    'user_id' => $farmer->id,
                    'nickname' => $validated['sawah_name'],
                    'area_size' => $validated['area_hectares'],
                    'lat' => $validated['lat'],
                    'lng' => $validated['lng'],
                ]);
                
            });

            // 3. Success! Kick them back with a green message
            return back()->with('success', 'Petani dan lahan berhasil didaftarkan ke sistem!');

        } catch (\Exception $e) {
            // If the database crashes, show the error safely
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }
}