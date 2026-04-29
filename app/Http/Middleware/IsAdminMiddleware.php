<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Assuming your users table has a 'role' column. 
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // Boot them out if they aren't an admin
        abort(403, 'Waduh, bukan salah login bang');
        // OR redirect them: return redirect()->route('dashboard');
    }
}