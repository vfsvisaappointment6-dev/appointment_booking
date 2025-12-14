<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ChatMessage;
use App\Models\StaffProfile;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    /**
     * Display the staff dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Unauthorized');
        }

        $userId = $user->user_id ?? $user->id;
        $staffProfile = StaffProfile::where('user_id', $userId)->first();

        // Get staff's bookings with eager loading
        $upcomingBookings = Booking::where('staff_id', $userId)
            ->where('date', '>=', now()->toDateString())
            ->where('status', 'confirmed')
            ->with(['service', 'customer'])
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        $todayBookings = Booking::where('staff_id', $userId)
            ->where('date', '=', now()->toDateString())
            ->where('status', 'confirmed')
            ->with(['service', 'customer'])
            ->orderBy('time')
            ->get();

        $completedBookings = Booking::where('staff_id', $userId)
            ->where('status', 'completed')
            ->count();

        $totalEarnings = Booking::where('staff_id', $userId)
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->count() * 50; // Placeholder earnings calculation

        $unreadMessages = ChatMessage::where('receiver_id', $userId)
            ->where('seen', false)
            ->count();

        // Recent reviews for this staff member with booking relationship
        $recentReviews = Auth::user()->receivedReviews()
            ->with('booking.customer')
            ->latest()
            ->take(5)
            ->get();

        // Get customers for messaging
        $recentCustomers = Booking::where('staff_id', $userId)
            ->with('customer')
            ->latest('created_at')
            ->limit(100)
            ->get()
            ->unique('user_id')
            ->take(5)
            ->pluck('customer');

        return view('staff.dashboard', [
            'user' => $user,
            'staffProfile' => $staffProfile,
            'upcomingBookings' => $upcomingBookings,
            'todayBookings' => $todayBookings,
            'completedBookings' => $completedBookings,
            'totalEarnings' => $totalEarnings,
            'unreadMessages' => $unreadMessages,
            'recentReviews' => $recentReviews,
            'recentCustomers' => $recentCustomers,
        ]);
    }
}
