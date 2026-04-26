<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Login via phone number and PIN
    public function login(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'pin' => 'required|string|min:4|max:6',
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user || !Hash::check($request->pin, $user->pin)) {
            throw ValidationException::withMessages([
                'phone_number' => ['Nomor telepon atau PIN salah.'],
            ]);
        }

        // Hapus token lama jika ada (opsional)
        $user->tokens()->delete();

        // Buat token baru
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone_number' => $user->phone_number,
                'role' => $user->role,
            ],
            'token' => $token,
            'role' => $user->role,
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    // Get authenticated user
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user(),
        ]);
    }

    // Register new user (hanya untuk bapak_dukuh, bisa ditambahkan middleware nanti)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone_number' => 'required|string|unique:users',
            'pin' => 'required|string|min:4|max:6',
            'role' => 'in:farmer,bapak_dukuh',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'pin' => $request->pin,
            'role' => $request->role ?? 'farmer',
        ]);

        return response()->json([
            'success' => true,
            'user' => $user,
        ], 201);
    }
}