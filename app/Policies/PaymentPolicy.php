<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    /**
     * Determine if user can view any payments
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if user can view a payment
     */
    public function view(User $user, Payment $payment): bool
    {
        return $user->role === 'admin' || $user->user_id === $payment->booking->user_id;
    }

    /**
     * Determine if user can create a payment
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can make a payment
    }

    /**
     * Determine if user can update a payment
     */
    public function update(User $user, Payment $payment): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if user can delete a payment
     */
    public function delete(User $user, Payment $payment): bool
    {
        return $user->role === 'admin' && $payment->status === 'pending';
    }
}
