<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UpdateLastActivity
{
    /**
     * Handle an incoming request and update authenticated user's last_activity.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Update last_activity after the request is processed (auth is loaded)
        if (Auth::check()) {
            $user = Auth::user();
            $now = now();
            $last = $user->last_activity ? \Illuminate\Support\Carbon::parse($user->last_activity) : null;

            // Update only if more than 30 seconds have passed to reduce writes
            if (! $last || $now->diffInSeconds($last) > 30) {
                $user->last_activity = $now;
                $user->saveQuietly();
            }
        }

        return $response;
    }
}
