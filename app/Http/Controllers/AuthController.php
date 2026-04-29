<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller // Make sure it extends Controller!
{
    public function login(Request $request)
    {
        // 1. Validate the phone and PIN (No emails allowed)
        $credentials = $request->validate([
            'phone' => ['required', 'string'],
            'password' => ['required', 'string'], // This is their PIN
        ]);

        // 2. Attempt to authenticate the user
        // The 'true' at the end turns on "Remember Me" so they stay logged in
        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();

            // Optional: Route them based on who they are
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            // Redirect to your sandbox or farmer dashboard
            return redirect()->intended('/dashboard');
        }

        // 3. If login fails, kick them back to the login page with an error
        return back()->withErrors([
            'phone' => 'Nomor HP atau PIN salah, Pak.',
        ])->onlyInput('phone');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        // Nuke the session for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}