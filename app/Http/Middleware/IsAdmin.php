<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Require authenticated user with explicit role 'admin'
        if (!$user || ($user->role ?? null) !== 'admin') {
            abort(403);
        }

        return $next($request);
    }
}
