<?php

namespace App\Http\Controllers;

use App\Models\Ubinan;
use App\Models\Land;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Route;

class UbinanController
{
    public function store(Request $request)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->to('/login')->with('error', 'You must be logged in to save a ubinan record.');
        }

        // Validate the input
        $request->validate([
            'land_id' => 'required|exists:lands,id',
            'sample_weight_kg' => 'required|decimal:5,2',
            'weather_note' => 'nullable|string|max:255'
        ]);

        // Create a new ubinan record
        $ubinan = Ubinan::create([
            'land_id' => $request->land_id,
            'sample_weight_kg' => $request->sample_weight_kg,
            'weather_note' => $request->weather_note or null,
            'estimated_yield_tons' => null // We'll calculate this later
        ]);

        // Return success response
        return redirect()->back()->with('success', 'Ubinan record saved successfully!');
    }
}
