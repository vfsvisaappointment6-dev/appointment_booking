<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminAuthController;

// Public admin auth routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Redirect /admin to /admin/login
    Route::get('/', function () {
        return redirect()->route('admin.login');
    });

    // Admin login routes (anyone can access, but controller validates admin role)
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.submit');

    // Logout must use the admin guard
    Route::middleware('auth:admin')->post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected admin routes using EnsureAdminAuthenticated middleware
    Route::middleware(['web', 'admin'])->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Users
        Route::get('users', [\App\Http\Controllers\AdminUserController::class, 'index'])->name('users.index');
        Route::get('users/{id}', [\App\Http\Controllers\AdminUserController::class, 'show'])->name('users.show');
        Route::get('users/{id}/edit', [\App\Http\Controllers\AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('users/{id}', [\App\Http\Controllers\AdminUserController::class, 'update'])->name('users.update');
        Route::delete('users/{id}', [\App\Http\Controllers\AdminUserController::class, 'destroy'])->name('users.destroy');

        // Bookings
        Route::get('bookings', [\App\Http\Controllers\AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{id}', [\App\Http\Controllers\AdminBookingController::class, 'show'])->name('bookings.show');
        Route::put('bookings/{id}', [\App\Http\Controllers\AdminBookingController::class, 'update'])->name('bookings.update');
        Route::delete('bookings/{id}', [\App\Http\Controllers\AdminBookingController::class, 'destroy'])->name('bookings.destroy');

        // Payments
        Route::get('payments', [\App\Http\Controllers\AdminPaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{id}', [\App\Http\Controllers\AdminPaymentController::class, 'show'])->name('payments.show');
        Route::put('payments/{id}', [\App\Http\Controllers\AdminPaymentController::class, 'update'])->name('payments.update');
        Route::delete('payments/{id}', [\App\Http\Controllers\AdminPaymentController::class, 'destroy'])->name('payments.destroy');

        // Reports
        Route::get('reports', [\App\Http\Controllers\AdminReportsController::class, 'index'])->name('reports');
        Route::get('reports/export/csv', [\App\Http\Controllers\AdminReportsController::class, 'exportCsv'])->name('reports.export-csv');
        Route::get('reports/print', [\App\Http\Controllers\AdminReportsController::class, 'printable'])->name('reports.print');

        // Promo Codes
        Route::get('promos', [\App\Http\Controllers\AdminPromoCodeController::class, 'index'])->name('promos.index');
        Route::get('promos/create', [\App\Http\Controllers\AdminPromoCodeController::class, 'create'])->name('promos.create');
        Route::post('promos', [\App\Http\Controllers\AdminPromoCodeController::class, 'store'])->name('promos.store');
        Route::get('promos/{promo}/edit', [\App\Http\Controllers\AdminPromoCodeController::class, 'edit'])->name('promos.edit')->where('promo', '[a-f0-9\-]{36}');
        Route::put('promos/{promo}', [\App\Http\Controllers\AdminPromoCodeController::class, 'update'])->name('promos.update')->where('promo', '[a-f0-9\-]{36}');
        Route::delete('promos/{promo}', [\App\Http\Controllers\AdminPromoCodeController::class, 'destroy'])->name('promos.destroy')->where('promo', '[a-f0-9\-]{36}');
        Route::patch('promos/{promo}/activate', [\App\Http\Controllers\AdminPromoCodeController::class, 'activate'])->name('promos.activate')->where('promo', '[a-f0-9\-]{36}');
        Route::patch('promos/{promo}/deactivate', [\App\Http\Controllers\AdminPromoCodeController::class, 'deactivate'])->name('promos.deactivate')->where('promo', '[a-f0-9\-]{36}');

        // Services
        Route::get('services', [\App\Http\Controllers\AdminServiceController::class, 'index'])->name('services.index');
        Route::get('services/create', [\App\Http\Controllers\AdminServiceController::class, 'create'])->name('services.create');
        Route::post('services', [\App\Http\Controllers\AdminServiceController::class, 'store'])->name('services.store');
        Route::get('services/{service}', [\App\Http\Controllers\AdminServiceController::class, 'show'])->name('services.show')->where('service', '[a-f0-9\-]{36}');
        Route::get('services/{service}/edit', [\App\Http\Controllers\AdminServiceController::class, 'edit'])->name('services.edit')->where('service', '[a-f0-9\-]{36}');
        Route::put('services/{service}', [\App\Http\Controllers\AdminServiceController::class, 'update'])->name('services.update')->where('service', '[a-f0-9\-]{36}');
        Route::delete('services/{service}', [\App\Http\Controllers\AdminServiceController::class, 'destroy'])->name('services.destroy')->where('service', '[a-f0-9\-]{36}');
    });
});
