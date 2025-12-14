<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Service;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Core Metrics
        $bookingsToday = Booking::whereDate('date', $today)->count();
        $bookingsThisMonth = Booking::whereDate('date', '>=', $thisMonth)->count();
        $totalUsers = User::count();
        $pendingPayments = Payment::where('status', 'pending')->count();

        // Revenue Metrics
        $totalRevenue = Payment::where('status', 'success')->sum('amount');
        $monthlyRevenue = Payment::where('status', 'success')
            ->whereDate('created_at', '>=', $thisMonth)
            ->sum('amount');
        $lastMonthRevenue = Payment::where('status', 'success')
            ->whereBetween('created_at', [$lastMonth, $thisMonth])
            ->sum('amount');

        // Booking Metrics
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();

        // User Metrics
        $staffCount = User::where('role', 'staff')->count();
        $customerCount = User::where('role', 'customer')->count();
        $newUsersThisMonth = User::whereDate('created_at', '>=', $thisMonth)->count();

        // Payment Metrics
        $failedPayments = Payment::where('status', 'failed')->count();
        $refundedPayments = Payment::where('status', 'refunded')->count();
        $successPayments = Payment::where('status', 'success')->count();

        // Service & Review Metrics
        $services = Service::count();
        $averageRating = Review::avg('rating') ?? 0;
        $totalReviews = Review::count();

        // Performance Metrics
        $avgBookingValue = $completedBookings > 0
            ? Payment::where('status', 'success')->avg('amount')
            : 0;

        $paymentSuccessRate = Payment::count() > 0
            ? round((Payment::where('status', 'success')->count() / Payment::count()) * 100, 2)
            : 0;

        // Charts Data - Last 7 days
        $lastSevenDays = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $lastSevenDays[] = [
                'date' => $date->format('M d'),
                'fullDate' => $date->toDateString(),
                'bookings' => Booking::whereDate('date', $date)->count(),
                'revenue' => Payment::where('status', 'success')->whereDate('created_at', $date)->sum('amount'),
            ];
        }

        // Top Staff
        $topStaff = User::where('role', 'staff')
            ->withCount('staffBookings')
            ->orderByDesc('staff_bookings_count')
            ->limit(5)
            ->get();

        // Top Services
        $topServices = Service::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->limit(5)
            ->get();

        // Growth Metrics - Compare this month to previous month
        $previousMonthStart = $lastMonth;
        $previousMonthEnd = $lastMonth->copy()->endOfMonth();
        $previousMonthBookings = Booking::whereBetween('date', [$previousMonthStart, $previousMonthEnd])->count();

        $bookingGrowth = $previousMonthBookings > 0
            ? round((($bookingsThisMonth - $previousMonthBookings) / $previousMonthBookings) * 100, 1)
            : 0;

        $revenueGrowth = $lastMonthRevenue > 0
            ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        $stats = [
            // Today
            'bookings_today' => $bookingsToday,

            // Month
            'bookings_this_month' => $bookingsThisMonth,
            'monthly_revenue' => $monthlyRevenue,
            'last_month_revenue' => $lastMonthRevenue,
            'new_users_this_month' => $newUsersThisMonth,

            // Users
            'total_users' => $totalUsers,
            'staff_count' => $staffCount,
            'customer_count' => $customerCount,

            // Revenue
            'total_revenue' => $totalRevenue,
            'avg_booking_value' => $avgBookingValue,

            // Bookings
            'confirmed_bookings' => $confirmedBookings,
            'completed_bookings' => $completedBookings,
            'cancelled_bookings' => $cancelledBookings,
            'pending_bookings' => $pendingBookings,

            // Payments
            'pending_payments' => $pendingPayments,
            'success_payments' => $successPayments,
            'failed_payments' => $failedPayments,
            'refunded_payments' => $refundedPayments,
            'payment_success_rate' => $paymentSuccessRate,

            // Services & Reviews
            'services' => $services,
            'average_rating' => round($averageRating, 2),
            'total_reviews' => $totalReviews,

            // Growth
            'booking_growth' => $bookingGrowth,
            'revenue_growth' => $revenueGrowth,

            // Charts
            'last_seven_days' => $lastSevenDays,
            'top_staff' => $topStaff,
            'top_services' => $topServices,
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
