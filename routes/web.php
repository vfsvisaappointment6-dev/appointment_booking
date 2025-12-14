<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\UpdateLastActivity;
use App\Http\Controllers\StaffDashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth as FacadesAuth;

Route::get('/', function () {
    return view('landing.index');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    // Web form POST handlers (session-based). These are simple closures
    // to make the existing Blade forms work during local development.
    Route::post('/register', function (StoreUserRequest $request) {
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'customer',
        ]);

        // Create staff profile if registering as staff
        if ($request->role === 'staff') {
            \App\Models\StaffProfile::create([
                'staff_profile_id' => \Illuminate\Support\Str::uuid(),
                'user_id' => $user->user_id,
                'specialty' => 'Professional Services',
                'bio' => '',
                'rating' => 0,
                'status' => 'active',
            ]);
        }

        Auth::login($user);

        return redirect()->intended('/dashboard')->with('welcome', 'Welcome to our service! Your account has been created successfully.');
    });

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        // Prevent admin users from authenticating via the public login endpoint.
        // Check the user by email first to avoid authenticating them under the `web` guard.
        try {
            $maybeUser = \App\Models\User::where('email', $request->email)->first();
            if ($maybeUser && ($maybeUser->role ?? null) === 'admin') {
                return back()->withErrors(['email' => 'Unauthorized'])->onlyInput('email');
            }
        } catch (\Throwable $e) {
            // If lookup fails for any reason, fall back to normal behaviour below.
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard')->with('login_success', 'You have been logged in successfully!');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
    });
});

// Load admin routes (separate file) — keep them outside the main auth group
require __DIR__ . '/admin.php';

// Load debug-only helper routes (do not commit to production)
require __DIR__ . '/debug_routes.php';

// Dashboard - requires authentication
Route::middleware(['auth', UpdateLastActivity::class])->group(function () {

    // User presence/status endpoint (used by chat UI polling)
    Route::get('/users/{userId}/status', function ($userId) {
        $user = \App\Models\User::find($userId);
        if (! $user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $now = \Illuminate\Support\Carbon::now();
        $last = $user->last_activity ? \Illuminate\Support\Carbon::parse($user->last_activity) : null;
        $online = $last ? $now->diffInSeconds($last) <= 60 : false;

        return response()->json([
            'online' => $online,
            'last_activity' => $last?->toIso8601String(),
            'last_seen_human' => $last ? $last->diffForHumans() : 'Never',
        ]);
    })->name('users.status');

    // DEBUG: show raw last_activity for current user and optional partner (auth only)
    Route::get('/debug/last-activity/{user?}', function (?\App\Models\User $user = null) {
        $me = Auth::user();
        return response()->json([
            'me' => [
                'id' => $me?->user_id,
                'last_activity' => $me?->last_activity,
            ],
            'partner' => $user ? [
                'id' => $user->user_id,
                'last_activity' => $user->last_activity,
            ] : null,
        ]);
    })->name('debug.last_activity');
    Route::get('/dashboard', function () {
        $user = Auth::user();
        // Redirect staff and admin users to their respective dashboards
        if (($user->role ?? null) === 'staff') {
            return app(StaffDashboardController::class)->index();
        }

        if (($user->role ?? null) === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('bookings.dashboard');
    })->name('dashboard');

    Route::get('/staff-dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');

    // Staff-only routes
    Route::middleware('staff')->group(function () {
        Route::get('/staff/bookings', function () {
            return view('staff.bookings');
        })->name('staff.bookings');

        Route::get('/staff/earnings', function () {
            return view('staff.earnings');
        })->name('staff.earnings');

        Route::get('/staff/availability', function () {
            return view('staff.availability');
        })->name('staff.availability');

        Route::put('/staff/availability', function (Request $request) {
            try {
                $user = Auth::user();
                $staffProfile = $user->staffProfile;

                if (!$staffProfile) {
                    return redirect()->back()->with('error', 'Staff profile not found');
                }

                // Update status
                if ($request->has('status')) {
                    $staffProfile->update(['status' => $request->status]);
                }

                // Update schedule (Monday-Sunday)
                if ($request->has('schedule')) {
                    $schedule = json_encode($request->schedule);
                    $staffProfile->update(['schedule' => $schedule]);
                }

                // Add blocked time
                if ($request->has('block_start_date')) {
                    \App\Models\BlockedTime::create([
                        'staff_profile_id' => $staffProfile->staff_profile_id,
                        'start_date' => $request->block_start_date,
                        'end_date' => $request->block_end_date,
                        'reason' => $request->block_reason
                    ]);
                }

                $message = $request->action === 'block_time' ? 'Time blocked successfully' : 'Availability updated successfully';
                return redirect()->back()->with('success', $message);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
            }
        })->name('staff.availability.update');

        Route::delete('/staff/availability/blocked-time/{blockedTimeId}', function ($blockedTimeId) {
            try {
                $blockedTime = \App\Models\BlockedTime::findOrFail($blockedTimeId);

                // Verify it belongs to the logged-in user
                if ($blockedTime->staffProfile->user_id !== Auth::user()->user_id) {
                    return redirect()->back()->with('error', 'Unauthorized action');
                }

                $blockedTime->delete();
                return redirect()->back()->with('success', 'Blocked time removed successfully');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error removing blocked time: ' . $e->getMessage());
            }
        })->name('staff.availability.delete-blocked-time');
    });

    // Web page routes for bookings (render Blade views)
    Route::get('/bookings', function () {
        return view('bookings.index');
    })->name('bookings.index');

    Route::get('/bookings/create/{service}', function ($serviceId) {
        $service = \App\Models\Service::findOrFail($serviceId);
        $staff = \App\Models\User::where('role', 'staff')->with('staffProfile')->get();
        return view('bookings.create', compact('service', 'staff'));
    })->name('bookings.create');

    Route::post('/bookings', function (Request $request) {
        try {
            $request->validate([
                'service_id' => 'required|exists:services,service_id',
                'staff_id' => 'required|exists:users,user_id',
                'date' => 'required|date|after:today',
                'time' => 'required|date_format:H:i',
            ]);

            // Verify the selected staff member is active
            $staff = \App\Models\User::with('staffProfile')->findOrFail($request->staff_id);
            if (!$staff->staffProfile || $staff->staffProfile->status !== 'active') {
                return redirect()->back()->with('error', 'The selected staff member is not available for booking at this time.');
            }

            // Check if the time slot is already booked for this staff member
            $existingBooking = \App\Models\Booking::where('staff_id', $request->staff_id)
                ->where('date', $request->date)
                ->where('time', $request->time)
                ->whereIn('status', ['confirmed', 'pending', 'rescheduled'])
                ->exists();

            if ($existingBooking) {
                return redirect()->back()->with('error', 'The selected time slot is already booked. Please choose a different time.');
            }

            $booking = \App\Models\Booking::create([
                'booking_id' => \Illuminate\Support\Str::uuid(),
                'user_id' => Auth::id(),
                'staff_id' => $request->staff_id,
                'service_id' => $request->service_id,
                'date' => $request->date,
                'time' => $request->time,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            return redirect()->route('bookings.show', $booking->booking_id)->with('success', 'Booking created successfully! Please proceed with payment.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating booking: ' . $e->getMessage());
        }
    })->name('bookings.store');

    Route::get('/bookings/{booking}', function (Booking $booking) {
        $booking->load(['service', 'staff', 'payment']);
        return view('bookings.show', compact('booking'));
    })->name('bookings.show');

    // Booking actions: reschedule, cancel, receipt
    Route::get('/bookings/{booking}/reschedule', function ($bookingId) {
        $booking = Booking::where('booking_id', $bookingId)->firstOrFail();
        return view('bookings.reschedule', compact('booking'));
    })->name('bookings.reschedule');

    Route::post('/bookings/{booking}/reschedule', function (Request $request, $bookingId) {
        $request->validate([
            'date' => 'required|date|after:today',
            'time' => 'required|date_format:H:i',
        ]);

        $booking = Booking::where('booking_id', $bookingId)->firstOrFail();

        // Check for conflicts with other confirmed, pending, or rescheduled bookings
        $conflict = Booking::where('staff_id', $booking->staff_id)
            ->where('date', $request->date)
            ->where('time', $request->time)
            ->whereIn('status', ['confirmed', 'pending', 'rescheduled'])
            ->where('booking_id', '!=', $booking->booking_id)
            ->exists();

        if ($conflict) {
            return redirect()->back()->with('error', 'Selected time is unavailable. Please choose a different time.');
        }

        $booking->update([
            'date' => $request->date,
            'time' => $request->time,
            'status' => 'rescheduled',
        ]);

        return redirect()->route('bookings.show', $booking->booking_id)->with('success', 'Booking rescheduled successfully');
    })->name('bookings.reschedule.submit');

    Route::post('/bookings/{booking}/cancel', function (Request $request, $bookingId) {
        $booking = Booking::where('booking_id', $bookingId)->firstOrFail();

        // Authorization: only the booking owner or the staff assigned can cancel
        if (Auth::id() !== $booking->user_id && Auth::id() !== $booking->staff_id) {
            return redirect()->back()->with('error', 'You are not authorized to cancel this booking');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('bookings.show', $booking->booking_id)->with('success', 'Booking cancelled successfully');
    })->name('bookings.cancel');

    Route::patch('/bookings/{booking}/complete', function (Booking $booking) {
        // Authorization: only the assigned staff can complete the booking
        if (Auth::id() !== $booking->staff_id) {
            return redirect()->back()->with('error', 'You are not authorized to complete this booking');
        }

        // Can only complete if past date and confirmed status
        if (!$booking->date->isPast() || $booking->status !== 'confirmed') {
            return redirect()->back()->with('error', 'This booking cannot be marked as completed');
        }

        $booking->update(['status' => 'completed']);

        return redirect()->back()->with('success', 'Booking marked as completed successfully');
    })->name('bookings.complete');

    Route::get('/bookings/{booking}/receipt', function ($bookingId) {
        $booking = Booking::where('booking_id', $bookingId)->with(['service', 'staff', 'customer'])->firstOrFail();

        $html = view('bookings.receipt', compact('booking'))->render();

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="receipt_' . $booking->booking_id . '.html"',
        ]);
    })->name('bookings.receipt');

    // Services web pages
    Route::get('/services', function () {
        $services = \App\Models\Service::all();
        return view('services.index', compact('services'));
    })->name('services.index');

    Route::get('/services/{service}', function (\App\Models\Service $service) {
        return view('services.show', compact('service'));
    })->name('services.show');

    // Placeholder pages for UI navigation (temporary)
    Route::get('/reviews', function () { return view('reviews.index'); })->name('reviews.index');
    Route::get('/reviews/create', function () { return view('reviews.create'); })->name('reviews.create');
    Route::post('/reviews', function (\Illuminate\Http\Request $request) {
        \App\Models\Review::create([
            'booking_id' => $request->booking_id,
            'customer_id' => Auth::id(),
            'staff_id' => \App\Models\Booking::find($request->booking_id)->staff_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);
        return redirect()->route('reviews.index')->with('success', 'Review published successfully!');
    })->name('reviews.store');
    Route::get('/reviews/{review}/edit', function (\App\Models\Review $review) {
        return view('reviews.create', ['review' => $review]);
    })->name('reviews.edit');
    Route::delete('/reviews/{review}', function (\App\Models\Review $review) {
        $review->delete();
        return redirect()->route('reviews.index')->with('success', 'Review deleted successfully!');
    })->name('reviews.destroy');
    Route::get('/messages', function () { return view('messages.index'); })->name('messages.index');
    // AJAX endpoint for fetching messages using session auth (used by the Blade JS)
    Route::get('/messages/fetch', function (Illuminate\Http\Request $request) {
        $partnerId = $request->query('partner_id');
        $currentUserId = Auth::user()->user_id;

        // Mark as seen
        $updateCount = \App\Models\ChatMessage::where('receiver_id', $currentUserId)
            ->where('sender_id', $partnerId)
            ->where('seen', false)
            ->update(['seen' => true]);

        $messages = \App\Models\ChatMessage::between($currentUserId, $partnerId)
            ->with(['sender', 'receiver'])
            ->get();

        return response()->json([
            'messages' => $messages,
            'marked_as_seen' => $updateCount,
        ]);
    })->name('messages.fetch');

    // API endpoint to fetch booked times for a staff member on a given date
    Route::get('/api/staff/{staffId}/booked-times/{date}', function ($staffId, $date) {
        $bookings = \App\Models\Booking::where('staff_id', $staffId)
            ->where('date', $date)
            ->whereIn('status', ['confirmed', 'pending', 'rescheduled'])
            ->get(['time', 'status']);

        return response()->json([
            'booked_times' => $bookings->pluck('time')->map(fn($time) => $time->format('H:i'))->toArray(),
        ]);
    })->name('api.staff.booked-times');

    Route::get('/messages/{user}', function (\App\Models\User $user) {
        $messages = \App\Models\ChatMessage::between(Auth::id(), $user->user_id)->get();
        return view('messages.show', ['partner' => $user, 'messages' => $messages]);
    })->name('messages.show');
    Route::post('/messages/send', function (\Illuminate\Http\Request $request) {
        $message = \App\Models\ChatMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'seen' => false,
        ]);

        // Return JSON for AJAX requests or redirect for form submissions
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('success', 'Message sent successfully!');
    })->name('messages.send');
    Route::get('/notifications', function () { return view('placeholder', ['title' => 'Notifications']); })->name('notifications.index');
    Route::get('/profile/edit', function () {
        $user = Auth::user();
        if ($user->role === 'staff') {
            return view('staff.profile-edit');
        }
        return view('customer.account-settings');
    })->name('profile.edit');

    // Customer account settings
    Route::get('/account-settings', function () {
        return view('customer.account-settings');
    })->name('account-settings');

    // Profile update routes
    Route::put('/profile/personal', function (Request $request) {
        try {
            $user = Auth::user();
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
            return redirect()->back()->with('success', 'Personal information updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    })->name('profile.update-personal');

    Route::put('/profile/picture', function (Request $request) {
        try {
            $request->validate([
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $user = Auth::user();

            // Delete old picture if exists
            if ($user->profile_picture && \Illuminate\Support\Facades\Storage::exists($user->profile_picture)) {
                \Illuminate\Support\Facades\Storage::delete($user->profile_picture);
            }

            // Store new picture
            if ($request->hasFile('profile_picture')) {
                $path = $request->file('profile_picture')->store('profile-pictures', 'public');
                $user->update(['profile_picture' => $path]);
            }

            return redirect()->back()->with('success', 'Profile picture updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating profile picture: ' . $e->getMessage());
        }
    })->name('profile.picture');

    Route::put('/profile/professional', function (Request $request) {
        try {
            $request->validate([
                'status' => 'required|in:active,inactive,on-leave,busy',
            ]);

            $staffProfile = Auth::user()->staffProfile;
            if (!$staffProfile) {
                return redirect()->back()->with('error', 'Staff profile not found');
            }
            $staffProfile->update([
                'specialty' => $request->specialty,
                'bio' => $request->bio,
                'status' => $request->status,
            ]);
            return redirect()->back()->with('success', 'Professional information updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    })->name('profile.update-professional');

    Route::put('/profile/password', function (Request $request) {
        try {
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:8|confirmed',
            ]);

            $user = Auth::user();
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->with('error', 'Current password is incorrect');
            }

            $user->update(['password' => Hash::make($request->password)]);
            return redirect()->back()->with('success', 'Password updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating password: ' . $e->getMessage());
        }
    })->name('profile.update-password');

    // Aliases for customer account settings (point to existing routes)
    Route::put('/account/password', function (Request $request) {
        try {
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:8|confirmed',
            ]);

            $user = Auth::user();
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->with('error', 'Current password is incorrect');
            }

            $user->update(['password' => Hash::make($request->password)]);
            return redirect()->back()->with('success', 'Password updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating password: ' . $e->getMessage());
        }
    })->name('profile.password');

    Route::put('/account/preferences', function (Request $request) {
        // Store preferences in session or user metadata (optional)
        // For now, just return success
        return redirect()->back()->with('success', 'Preferences updated successfully');
    })->name('profile.preferences');

    Route::delete('/account', function (Request $request) {
        try {
            $user = Auth::user();
            Auth::logout();
            $user->delete();
            return redirect('/')->with('success', 'Account deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting account: ' . $e->getMessage());
        }
    })->name('profile.delete');

    // Staff profile detail route used by components (URL generation only)
    Route::get('/staff-profiles/{staff}', function ($staff) { return view('placeholder', ['title' => 'Staff Profile']); })->name('staff-profiles.show');

    // Web logout (form action in navbar)
    Route::post('/logout', function () {
        $user = FacadesAuth::user();
        if ($user) {
            // mark as offline immediately
            $user->last_activity = null;
            $user->saveQuietly();
        }
        FacadesAuth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/')->with('logout_success', 'You have been logged out successfully.');
    })->name('logout');

    // Payment routes
    // Promo validation (web) — used by Blade AJAX with session CSRF
    Route::post('/payment/validate-promo', [\App\Http\Controllers\PaymentController::class, 'validatePromo'])->name('payment.validate-promo');

    Route::get('/payment/{booking}', function (Booking $booking) {
        return view('payment.checkout', compact('booking'));
    })->name('payment.checkout');

    Route::post('/payment/{booking}/initialize', function (Request $request, Booking $booking) {
        $paystackSecretKey = config('services.paystack.secret_key') ?? env('PAYSTACK_SECRET_KEY');

        if (!$paystackSecretKey) {
            return redirect()->back()->with('error', 'Paystack configuration is missing');
        }

        $curl = curl_init();
        $reference = 'booking_' . $booking->booking_id . '_' . time();

        // Allow applied_discount from the form (sent as kobo-adjusted on server)
        $appliedDiscount = floatval($request->input('applied_discount', 0));
        $finalAmount = max(0.01, $booking->service->price - $appliedDiscount);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'amount' => round($finalAmount * 100), // Paystack expects amount in kobo
                'email' => Auth::user()->email,
                'reference' => $reference,
                'callback_url' => route('payment.callback'),
                'metadata' => [
                    'booking_id' => $booking->booking_id,
                    'user_id' => Auth::id(),
                    'service_id' => $booking->service_id,
                    'applied_discount' => number_format($appliedDiscount, 2),
                    'promo_code' => $request->input('promo_code') ?? null,
                ]
            ]),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $paystackSecretKey,
                "Content-Type: application/json",
                "Cache-Control: no-cache"
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if($err){
            return redirect()->back()->with('error', 'Connection Error: ' . $err);
        }

        $result = json_decode($response);
        if(!$result->status){
            return redirect()->back()->with('error', $result->message);
        }

        return redirect()->to($result->data->authorization_url);
    })->name('payment.initialize');

    Route::get('/payment/callback/paystack', function (Request $request) {
        $reference = $request->get('reference');
        $paystackSecretKey = config('services.paystack.secret_key') ?? env('PAYSTACK_SECRET_KEY');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . $reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $paystackSecretKey,
                "Content-Type: application/json",
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($response);

        if(!$result->status){
            return redirect()->route('bookings.index')->with('error', 'Payment verification failed');
        }

        // Update booking payment status
        $metadata = $result->data->metadata;
        $booking = Booking::find($metadata->booking_id);

        if($booking && $result->data->status === 'success'){
            $booking->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
            ]);

            // Store payment record (map fields to DB schema)
            \App\Models\Payment::create([
                'payment_id' => \Illuminate\Support\Str::uuid(),
                'booking_id' => $booking->booking_id,
                'amount' => $result->data->amount / 100, // Convert back from kobo
                'method' => 'paystack',
                'transaction_id' => $result->data->id ?? null,
                'reference' => $reference,
                'payment_method' => 'paystack',
                'status' => 'success',
            ]);

            // If a promo was applied (passed via metadata), record usage and increment counters
            try {
                if (!empty($metadata->promo_code) && isset($metadata->applied_discount)) {
                    $appliedDiscount = floatval($metadata->applied_discount);
                    $promoCode = \App\Models\PromoCode::where('code', strtoupper($metadata->promo_code))->first();
                    if ($promoCode) {
                        // Create a promo usage record
                        \App\Models\PromoCodeUsage::create([
                            'promo_code_usage_id' => \Illuminate\Support\Str::uuid(),
                            'promo_code_id' => $promoCode->promo_code_id,
                            'user_id' => $booking->user_id,
                            'booking_id' => $booking->booking_id,
                            'used_at' => now(),
                            'discount_amount' => $appliedDiscount,
                        ]);

                        // Increment times_used safely
                        $promoCode->increment('times_used');
                    }
                }
            } catch (\Exception $e) {
                // Log but don't fail the happy-path payment confirmation
                \Log::warning('Failed to record promo usage: ' . $e->getMessage());
            }

            return redirect()->route('bookings.show', $booking->booking_id)->with('success', 'Payment successful! Your booking is confirmed.');
        }

        return redirect()->route('bookings.index')->with('error', 'Payment could not be verified');
    })->name('payment.callback');
});
