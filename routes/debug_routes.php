<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware(['auth', \App\Http\Middleware\UpdateLastActivity::class])->group(function () {
    Route::post('/debug/last-activity/set', function () {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        $user->last_activity = now();
        $user->saveQuietly();
        return response()->json(['success' => true, 'last_activity' => $user->last_activity]);
    });
});
