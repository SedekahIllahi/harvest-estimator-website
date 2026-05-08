<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validate the phone and PIN
        $credentials = $request->validate([
            'phone' => ['required', 'string'],
            'password' => ['required', 'string'], // This is their PIN
        ]);

        // 2. Attempt to authenticate the user
        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // 3. THE TRAFFIC COP (Fixed)
            // Use your model methods and force the redirect so 'intended' doesn't hijack it
            if ($user->isAdmin()) {
                return redirect('/admin/dashboard');
            }

            if ($user->isFarmer()) {
                return redirect('/dashboard');
            }

            // Fallback just in case someone slips through without a role
            abort(403, 'Role tidak dikenali oleh sistem.');
        }

        // 4. Failed login
        return back()->withErrors([
            'phone' => 'Nomor HP atau PIN salah, Pak.',
        ])->onlyInput('phone');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
