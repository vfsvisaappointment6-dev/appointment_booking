<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    /**
     * Determine if user can view any bookings
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if user can view a booking
     */
    public function view(User $user, Booking $booking): bool
    {
        return $user->user_id === $booking->user_id || $user->user_id === $booking->staff_id || $user->role === 'admin';
    }

    /**
     * Determine if user can create a booking
     */
    public function create(User $user): bool
    {
        return $user->role !== 'admin'; // Non-admins can book appointments
    }

    /**
     * Determine if user can update a booking
     */
    public function update(User $user, Booking $booking): bool
    {
        return $user->user_id === $booking->user_id || $user->user_id === $booking->staff_id || $user->role === 'admin';
    }

    /**
     * Determine if user can delete a booking
     */
    public function delete(User $user, Booking $booking): bool
    {
        return $user->role === 'admin' || ($user->user_id === $booking->user_id && !in_array($booking->status, ['completed', 'cancelled']));
    }
}
