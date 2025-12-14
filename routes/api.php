<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\PromoCodeUsageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StaffProfileController;
use App\Http\Controllers\UserController;

/**
 * API Routes
 *
 * Authentication required by default (Sanctum)
 * Public endpoints available below
 */

// Public Authentication Endpoints
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

// Protected API Routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Auth endpoints
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me', [AuthController::class, 'me']);

    // Booking endpoints with custom actions
    Route::apiResource('bookings', BookingController::class);
    Route::post('bookings/{booking}/complete', [BookingController::class, 'complete']);
    Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel']);

    // Payment endpoints with processing
    Route::apiResource('payments', PaymentController::class);
    Route::post('payments/{payment}/process', [PaymentController::class, 'process']);

    // Promo validation endpoint
    Route::post('payment/validate-promo', [PaymentController::class, 'validatePromo'])->name('payment.validate-promo');
    // Review endpoints
    Route::apiResource('reviews', ReviewController::class);

    // Services
    Route::apiResource('services', ServiceController::class);

    // Chat messages
    Route::apiResource('chat-messages', ChatMessageController::class);

    Route::get('messages', function (Request $request) {
        $partnerId = $request->query('partner_id');
        $currentUserId = Auth::user()->user_id;

        // Mark all received messages as seen FIRST
        $updateCount = \App\Models\ChatMessage::where('receiver_id', $currentUserId)
            ->where('sender_id', $partnerId)
            ->where('seen', false)
            ->update(['seen' => true]);

        Log::info('Messages marked as seen', [
            'current_user' => $currentUserId,
            'partner' => $partnerId,
            'updated_count' => $updateCount
        ]);

        // Then fetch all messages
        $messages = \App\Models\ChatMessage::between($currentUserId, $partnerId)
            ->with(['sender', 'receiver'])
            ->get();

        return response()->json([
            'messages' => $messages,
            'marked_as_seen' => $updateCount
        ]);
    });

    // Notifications
    Route::apiResource('notifications', NotificationController::class);

    // Staff profiles
    Route::apiResource('staff-profiles', StaffProfileController::class);

    // Users (for admin/user management)
    Route::apiResource('users', UserController::class);

    // Promo codes
    Route::apiResource('promo-codes', PromoCodeController::class);

    // Promo code usages (read-only)
    Route::get('promo-code-usages', [PromoCodeUsageController::class, 'index']);
    Route::get('promo-code-usages/{promoCodeUsage}', [PromoCodeUsageController::class, 'show']);

    // Audit logs (read-only)
    Route::get('audit-logs', [AuditLogController::class, 'index']);
    Route::get('audit-logs/{auditLog}', [AuditLogController::class, 'show']);
});
