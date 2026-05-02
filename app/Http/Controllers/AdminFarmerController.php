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
            'phone' => 'required|string|unique:users,phone',
            'pin' => 'required|string|min:4',
            'sawah_name' => 'required|string|max:255',
            'area_hectares' => 'required|numeric|min:0.0001',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'boundaries' => 'required|string',
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
                    'boundaries' => json_decode($validated['boundaries']), 
                ]);
            });

            // 3. Success! Kick them back with a green message
            return back()->with('success', 'Petani dan lahan berhasil didaftarkan ke sistem!');

        } catch (\Exception $e) {
            // If the database crashes, show the error safely
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function index()
    {
        // Fetch farmers, ordered by newest first, 15 per page.
        // We eager load the 'lands' relationship so we can show how many fields they own in the table.
        $farmers = User::where('role', 'farmer')
                        ->with('lands') 
                        ->latest()
                        ->paginate(15);

        return view('admin.farmers.index', compact('farmers'));
    }

    public function edit(User $farmer)
    {
        // Serve the edit view and pass the specific farmer's data to it
        return view('admin.farmers.edit', compact('farmer'));
    }

    public function update(Request $request, User $farmer)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // This is the magic trick: require it to be unique in the 'users' table, 
            // EXCEPT for the ID of the farmer we are currently updating.
            'phone' => 'required|string|unique:users,phone,' . $farmer->id,
        ]);

        // Update the farmer in the database
        $farmer->update($validated);

        // Send them back to the list with a high-five
        return redirect()->route('admin.farmers.index')->with('success', "Farmer {$farmer->name}'s profile has been updated.");
    }

    public function resetPin(User $farmer)
    {
        // Reset their password to a default, like '123456'
        $farmer->update([
            'password' => Hash::make('123456')
        ]);

        return back()->with('success', "PIN for {$farmer->name} has been reset to 123456.");
    }

    public function destroy(User $farmer)
    {
        // Hard delete. 
        $farmer->delete();

        return back()->with('success', 'Farmer removed from the system.');
    }
}