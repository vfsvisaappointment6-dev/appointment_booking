<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Service;
use App\Models\PromoCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class AdminReportsController extends Controller
{
    /**
     * Show reports dashboard
     */
    public function index(Request $request)
    {
        $reportType = $request->get('type', 'overview');
        $dateFrom = $request->get('date_from', Carbon::now()->subMonth()->toDateString());
        $dateTo = $request->get('date_to', Carbon::now()->toDateString());

        $data = [
            'reportType' => $reportType,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ];

        switch ($reportType) {
            case 'revenue':
                $data = array_merge($data, $this->getRevenueReport($dateFrom, $dateTo));
                break;
            case 'bookings':
                $data = array_merge($data, $this->getBookingsReport($dateFrom, $dateTo));
                break;
            case 'customers':
                $data = array_merge($data, $this->getCustomersReport($dateFrom, $dateTo));
                break;
            case 'staff':
                $data = array_merge($data, $this->getStaffReport($dateFrom, $dateTo));
                break;
            case 'services':
                $data = array_merge($data, $this->getServicesReport($dateFrom, $dateTo));
                break;
            case 'payments':
                $data = array_merge($data, $this->getPaymentsReport($dateFrom, $dateTo));
                break;
            case 'promos':
                $data = array_merge($data, $this->getPromoReport($dateFrom, $dateTo));
                break;
            case 'reviews':
                $data = array_merge($data, $this->getReviewsReport($dateFrom, $dateTo));
                break;
            case 'overview':
            default:
                $data = array_merge($data, $this->getOverviewReport($dateFrom, $dateTo));
                break;
        }

        return view('admin.reports', $data);
    }

    /**
     * Overview Report - Executive Summary
     */
    private function getOverviewReport($dateFrom, $dateTo)
    {
        $bookings = Booking::whereBetween('date', [$dateFrom, $dateTo])->get();
        $payments = Payment::whereBetween('created_at', [$dateFrom, $dateTo])->get();
        $users = User::whereBetween('created_at', [$dateFrom, $dateTo])->get();
        $reviews = Review::whereBetween('created_at', [$dateFrom, $dateTo])->get();

        return [
            'totalBookings' => $bookings->count(),
            'completedBookings' => $bookings->where('status', 'completed')->count(),
            'cancelledBookings' => $bookings->where('status', 'cancelled')->count(),
            'totalRevenue' => $payments->where('status', 'success')->sum('amount'),
            'totalPayments' => $payments->count(),
            'newCustomers' => $users->where('role', 'customer')->count(),
            'newStaff' => $users->where('role', 'staff')->count(),
            'avgRating' => $reviews->avg('rating') ?? 0,
            'totalReviews' => $reviews->count(),
        ];
    }

    /**
     * Revenue Report - Detailed financial analysis
     */
    private function getRevenueReport($dateFrom, $dateTo)
    {
        $payments = Payment::whereBetween('created_at', [$dateFrom, $dateTo])->get();

        // Daily revenue data for chart
        $dailyRevenue = [];
        $startDate = Carbon::parse($dateFrom);
        $endDate = Carbon::parse($dateTo);

        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dailyRevenue[] = [
                'date' => $date->format('M d'),
                'revenue' => Payment::where('status', 'success')
                    ->whereDate('created_at', $date->toDateString())
                    ->sum('amount'),
            ];
        }

        // Revenue by payment method
        $revenueByMethod = [];
        foreach (['card', 'bank_transfer', 'cash', 'check'] as $method) {
            $revenueByMethod[$method] = Payment::where('payment_method', $method)
                ->where('status', 'success')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->sum('amount');
        }

        // Top booking values
        $topBookings = Booking::with('payment')
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->whereHas('payment', function ($q) {
                $q->where('status', 'success');
            })
            ->join('payments', 'bookings.booking_id', '=', 'payments.booking_id')
            ->orderBy('payments.amount', 'desc')
            ->limit(10)
            ->get(['bookings.*']);

        return [
            'totalRevenue' => $payments->where('status', 'success')->sum('amount'),
            'averageTransaction' => $payments->where('status', 'success')->avg('amount') ?? 0,
            'successfulPayments' => $payments->where('status', 'success')->count(),
            'failedPayments' => $payments->where('status', 'failed')->count(),
            'refundedAmount' => $payments->where('status', 'refunded')->sum('amount'),
            'dailyRevenue' => $dailyRevenue,
            'revenueByMethod' => $revenueByMethod,
            'topBookings' => $topBookings,
        ];
    }

    /**
     * Bookings Report - Booking trends and analysis
     */
    private function getBookingsReport($dateFrom, $dateTo)
    {
        $bookings = Booking::with(['service', 'customer', 'staff'])
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->get();

        // Bookings by status
        $bookingsByStatus = [
            'pending' => $bookings->where('status', 'pending')->count(),
            'confirmed' => $bookings->where('status', 'confirmed')->count(),
            'completed' => $bookings->where('status', 'completed')->count(),
            'cancelled' => $bookings->where('status', 'cancelled')->count(),
        ];

        // Top services by bookings
        $topServices = Service::whereBetween('created_at', [$dateFrom, $dateTo])
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->limit(10)
            ->get();

        // Daily booking data for chart
        $dailyBookings = [];
        $startDate = Carbon::parse($dateFrom);
        $endDate = Carbon::parse($dateTo);

        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dailyBookings[] = [
                'date' => $date->format('M d'),
                'count' => Booking::whereDate('date', $date->toDateString())->count(),
            ];
        }

        return [
            'totalBookings' => $bookings->count(),
            'bookingsByStatus' => $bookingsByStatus,
            'completionRate' => $bookings->count() > 0
                ? round(($bookings->where('status', 'completed')->count() / $bookings->count()) * 100, 2)
                : 0,
            'cancellationRate' => $bookings->count() > 0
                ? round(($bookings->where('status', 'cancelled')->count() / $bookings->count()) * 100, 2)
                : 0,
            'topServices' => $topServices,
            'dailyBookings' => $dailyBookings,
            'recentBookings' => $bookings->sortByDesc('created_at')->take(20),
        ];
    }

    /**
     * Customers Report - Customer growth and behavior
     */
    private function getCustomersReport($dateFrom, $dateTo)
    {
        $customers = User::where('role', 'customer')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();

        $allCustomers = User::where('role', 'customer')->get();

        // Customer booking frequency
        $topCustomers = User::where('role', 'customer')
            ->withCount(['bookings' => function ($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('date', [$dateFrom, $dateTo]);
            }])
            ->orderByDesc('bookings_count')
            ->limit(10)
            ->get();

        // Customer spending
        $topSpenders = User::where('role', 'customer')
            ->with(['bookings' => function ($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('date', [$dateFrom, $dateTo]);
            }])
            ->get()
            ->map(function ($customer) {
                $totalSpent = Payment::whereHas('booking', function ($q) use ($customer) {
                    $q->where('user_id', $customer->user_id);
                })
                ->where('status', 'success')
                ->sum('amount');
                return [
                    'customer' => $customer,
                    'totalSpent' => $totalSpent,
                ];
            })
            ->sortByDesc('totalSpent')
            ->take(10);

        return [
            'totalCustomers' => $allCustomers->count(),
            'newCustomers' => $customers->count(),
            'activeCustomers' => User::where('role', 'customer')
                ->whereHas('bookings', function ($q) use ($dateFrom, $dateTo) {
                    $q->whereBetween('date', [$dateFrom, $dateTo]);
                })
                ->count(),
            'topCustomers' => $topCustomers,
            'topSpenders' => collect($topSpenders),
            'avgBookingsPerCustomer' => $customers->count() > 0
                ? Booking::whereIn('user_id', $customers->pluck('user_id'))->count() / $customers->count()
                : 0,
        ];
    }

    /**
     * Staff Report - Staff performance metrics
     */
    private function getStaffReport($dateFrom, $dateTo)
    {
        $allStaff = User::where('role', 'staff')->get();
        $staffIds = $allStaff->pluck('user_id')->toArray();

        $staffPerformance = $allStaff->map(function ($staff) use ($dateFrom, $dateTo) {
            $bookings = Booking::where('staff_id', $staff->user_id)
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->get();

            $revenue = Payment::whereHas('booking', function ($q) use ($staff, $dateFrom, $dateTo) {
                $q->where('staff_id', $staff->user_id)
                  ->whereBetween('date', [$dateFrom, $dateTo]);
            })
            ->where('status', 'success')
            ->sum('amount');

            $rating = Review::whereHas('booking', function ($q) use ($staff) {
                $q->where('staff_id', $staff->user_id);
            })->avg('rating') ?? 0;

            return [
                'staff' => $staff,
                'bookingsCount' => $bookings->count(),
                'completedBookings' => $bookings->where('status', 'completed')->count(),
                'revenue' => $revenue,
                'rating' => round($rating, 2),
            ];
        })->sortByDesc('bookingsCount');

        return [
            'totalStaff' => $allStaff->count(),
            'staffPerformance' => $staffPerformance,
            'topPerformers' => $staffPerformance->take(5),
            'avgRating' => $allStaff->count() > 0
                ? Review::whereHas('booking', function ($q) use ($staffIds) {
                    $q->whereIn('staff_id', $staffIds);
                })->avg('rating') ?? 0
                : 0,
        ];
    }

    /**
     * Services Report - Service performance
     */
    private function getServicesReport($dateFrom, $dateTo)
    {
        $services = Service::withCount(['bookings' => function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('date', [$dateFrom, $dateTo]);
        }])
        ->get();

        $servicePerformance = $services->map(function ($service) use ($dateFrom, $dateTo) {
            $bookings = $service->bookings()
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->get();

            $revenue = Payment::whereHas('booking', function ($q) use ($service, $dateFrom, $dateTo) {
                $q->where('service_id', $service->service_id)
                  ->whereBetween('date', [$dateFrom, $dateTo]);
            })
            ->where('status', 'success')
            ->sum('amount');

            $avgRating = Review::whereHas('booking', function ($q) use ($service, $dateFrom, $dateTo) {
                $q->where('service_id', $service->service_id)
                  ->whereBetween('date', [$dateFrom, $dateTo]);
            })->avg('rating') ?? 0;

            return [
                'service' => $service,
                'bookings' => $bookings->count(),
                'revenue' => $revenue,
                'avgRating' => round($avgRating, 2),
                'revenue_per_booking' => $bookings->count() > 0 ? $revenue / $bookings->count() : 0,
            ];
        })->sortByDesc('bookings');

        return [
            'totalServices' => $services->count(),
            'servicePerformance' => $servicePerformance,
            'topServices' => $servicePerformance->take(5),
            'totalServiceRevenue' => $services->sum('bookings_count'),
        ];
    }

    /**
     * Payments Report - Payment analysis
     */
    private function getPaymentsReport($dateFrom, $dateTo)
    {
        $payments = Payment::whereBetween('created_at', [$dateFrom, $dateTo])->get();

        return [
            'totalPayments' => $payments->count(),
            'successfulPayments' => $payments->where('status', 'success')->count(),
            'failedPayments' => $payments->where('status', 'failed')->count(),
            'refundedPayments' => $payments->where('status', 'refunded')->count(),
            'pendingPayments' => $payments->where('status', 'pending')->count(),
            'totalRevenue' => $payments->where('status', 'success')->sum('amount'),
            'failedAmount' => $payments->where('status', 'failed')->sum('amount'),
            'refundedAmount' => $payments->where('status', 'refunded')->sum('amount'),
            'successRate' => $payments->count() > 0
                ? round(($payments->where('status', 'success')->count() / $payments->count()) * 100, 2)
                : 0,
            'averagePayment' => $payments->avg('amount') ?? 0,
            'paymentsByMethod' => $payments->groupBy('payment_method')->map->count(),
        ];
    }

    /**
     * Promo Codes Report - Marketing effectiveness
     */
    private function getPromoReport($dateFrom, $dateTo)
    {
        $promos = PromoCode::with('usages')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();

        $promoPerformance = $promos->map(function ($promo) {
            $usages = $promo->usages;
            $discountGiven = $usages->sum('discount_amount');
            $bookingsWithPromo = $usages->count();

            return [
                'code' => $promo->code,
                'discount' => $promo->discount_value,
                'usage_count' => $bookingsWithPromo,
                'total_discount_given' => $discountGiven,
            ];
        });

        return [
            'totalPromos' => $promos->count(),
            'activePromos' => $promos->where('is_active', true)->count(),
            'totalDiscountGiven' => $promos->sum(function ($p) {
                return $p->usages->sum('discount_amount');
            }),
            'promoPerformance' => $promoPerformance,
        ];
    }

    /**
     * Reviews Report - Customer satisfaction
     */
    private function getReviewsReport($dateFrom, $dateTo)
    {
        $reviews = Review::whereBetween('created_at', [$dateFrom, $dateTo])->get();

        $ratingDistribution = [
            5 => $reviews->where('rating', 5)->count(),
            4 => $reviews->where('rating', 4)->count(),
            3 => $reviews->where('rating', 3)->count(),
            2 => $reviews->where('rating', 2)->count(),
            1 => $reviews->where('rating', 1)->count(),
        ];

        $topReviews = $reviews->sortByDesc('rating')->take(5);
        $recentReviews = $reviews->sortByDesc('created_at')->take(10);

        return [
            'totalReviews' => $reviews->count(),
            'averageRating' => round($reviews->avg('rating') ?? 0, 2),
            'ratingDistribution' => $ratingDistribution,
            'topReviews' => $topReviews,
            'recentReviews' => $recentReviews,
        ];
    }

    /**
     * Export report as CSV
     */
    public function exportCsv(Request $request)
    {
        $reportType = $request->get('type', 'overview');
        $dateFrom = $request->get('date_from', Carbon::now()->subMonth()->toDateString());
        $dateTo = $request->get('date_to', Carbon::now()->toDateString());

        $filename = "report_{$reportType}_" . now()->format('Y-m-d_H-i-s') . ".csv";

        return response()->stream(function () use ($reportType, $dateFrom, $dateTo) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            $this->generateCsv($handle, $reportType, $dateFrom, $dateTo);

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=$filename",
        ]);
    }

    /**
     * Return a printable HTML view of the report (open in new tab for print/save-as-PDF)
     */
    public function printable(Request $request)
    {
        $reportType = $request->get('type', 'overview');
        $dateFrom = $request->get('date_from', Carbon::now()->subMonth()->toDateString());
        $dateTo = $request->get('date_to', Carbon::now()->toDateString());

        $data = [
            'reportType' => $reportType,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ];

        switch ($reportType) {
            case 'revenue':
                $data = array_merge($data, $this->getRevenueReport($dateFrom, $dateTo));
                break;
            case 'bookings':
                $data = array_merge($data, $this->getBookingsReport($dateFrom, $dateTo));
                break;
            case 'customers':
                $data = array_merge($data, $this->getCustomersReport($dateFrom, $dateTo));
                break;
            case 'staff':
                $data = array_merge($data, $this->getStaffReport($dateFrom, $dateTo));
                break;
            case 'services':
                $data = array_merge($data, $this->getServicesReport($dateFrom, $dateTo));
                break;
            case 'payments':
                $data = array_merge($data, $this->getPaymentsReport($dateFrom, $dateTo));
                break;
            case 'promos':
                $data = array_merge($data, $this->getPromoReport($dateFrom, $dateTo));
                break;
            case 'reviews':
                $data = array_merge($data, $this->getReviewsReport($dateFrom, $dateTo));
                break;
            case 'overview':
            default:
                $data = array_merge($data, $this->getOverviewReport($dateFrom, $dateTo));
                break;
        }

        // Use a minimal printable view; the browser's Print -> Save as PDF can be used
        return view('admin.reports.printable', $data);
    }

    /**
     * Generate CSV content
     */
    private function generateCsv($handle, $reportType, $dateFrom, $dateTo)
    {
        $headers = match ($reportType) {
            'revenue' => ['Date', 'Revenue', 'Payment Method'],
            'bookings' => ['Service', 'Total Bookings', 'Confirmed', 'Completed'],
            'customers' => ['Customer', 'Email', 'Total Bookings', 'Total Spent'],
            'staff' => ['Staff Member', 'Total Bookings', 'Total Revenue', 'Rating'],
            'services' => ['Service Name', 'Total Bookings', 'Revenue', 'Rating'],
            'payments' => ['Payment ID', 'Amount', 'Status', 'Method', 'Date'],
            'promos' => ['Promo Code', 'Discount', 'Type', 'Usage Count'],
            'reviews' => ['Customer', 'Rating', 'Comment', 'Date'],
            default => ['Metric', 'Value'],
        };

        fputcsv($handle, $headers);

        $data = $this->getReportDataForExport($reportType, $dateFrom, $dateTo);

        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
    }

    /**
     * Get report data formatted for export
     */
    private function getReportDataForExport($reportType, $dateFrom, $dateTo)
    {
        $rows = [];

        switch ($reportType) {
            case 'revenue':
                $payments = Payment::where('status', 'success')
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->get()
                    ->groupBy('payment_method');

                foreach ($payments as $method => $items) {
                    $rows[] = [
                        Carbon::now()->toDateString(),
                        $items->sum('amount'),
                        $method,
                    ];
                }
                break;

            case 'bookings':
                $services = Service::withCount('bookings')->get();
                foreach ($services as $service) {
                    $rows[] = [
                        $service->name,
                        $service->bookings_count,
                        Booking::where('service_id', $service->id)->where('status', 'confirmed')->count(),
                        Booking::where('service_id', $service->id)->where('status', 'completed')->count(),
                    ];
                }
                break;

            case 'customers':
                $customers = User::where('role', 'customer')
                    ->withCount('bookings')
                    ->get();

                foreach ($customers as $customer) {
                    $spent = Payment::where('status', 'success')
                        ->whereHas('booking', function ($q) use ($customer) {
                            $q->where('user_id', $customer->id);
                        })
                        ->sum('amount');

                    $rows[] = [
                        $customer->name,
                        $customer->email,
                        $customer->bookings_count,
                        $spent,
                    ];
                }
                break;

            case 'staff':
                $staff = User::where('role', 'staff')
                    ->withCount('staffBookings')
                    ->get();

                foreach ($staff as $member) {
                    $revenue = Payment::where('status', 'success')
                        ->whereHas('booking', function ($q) use ($member) {
                            $q->where('staff_id', $member->id);
                        })
                        ->sum('amount');

                    $rating = Review::whereHas('booking', function ($q) use ($member) {
                        $q->where('staff_id', $member->id);
                    })->avg('rating') ?? 0;

                    $rows[] = [
                        $member->name,
                        $member->staff_bookings_count,
                        $revenue,
                        round($rating, 2),
                    ];
                }
                break;

            case 'services':
                $services = Service::withCount('bookings')->get();
                foreach ($services as $service) {
                    $revenue = Payment::where('status', 'success')
                        ->whereHas('booking', function ($q) use ($service) {
                            $q->where('service_id', $service->id);
                        })
                        ->sum('amount');

                    $rating = Review::whereHas('booking', function ($q) use ($service) {
                        $q->where('service_id', $service->id);
                    })->avg('rating') ?? 0;

                    $rows[] = [
                        $service->name,
                        $service->bookings_count,
                        $revenue,
                        round($rating, 2),
                    ];
                }
                break;

            case 'payments':
                $payments = Payment::whereBetween('created_at', [$dateFrom, $dateTo])->get();
                foreach ($payments as $payment) {
                    $rows[] = [
                        $payment->id,
                        $payment->amount,
                        $payment->status,
                        $payment->payment_method,
                        $payment->created_at->toDateString(),
                    ];
                }
                break;

            case 'promos':
                $promos = PromoCode::all();
                foreach ($promos as $promo) {
                    $rows[] = [
                        $promo->code,
                        $promo->discount_value,
                        $promo->discount_type,
                        $promo->usage_count ?? 0,
                    ];
                }
                break;

            case 'reviews':
                $reviews = Review::whereBetween('created_at', [$dateFrom, $dateTo])->get();
                foreach ($reviews as $review) {
                    $rows[] = [
                        $review->user->name ?? 'N/A',
                        $review->rating,
                        substr($review->comment ?? '', 0, 50),
                        $review->created_at->toDateString(),
                    ];
                }
                break;

            default:
                $rows[] = ['Overview Report', Carbon::now()->toDateString()];
        }

        return $rows;
    }
}
