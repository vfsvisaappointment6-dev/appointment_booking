<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * Determine if user can view any reviews
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if user can view a review
     */
    public function view(User $user, Review $review): bool
    {
        return true; // Reviews are public
    }

    /**
     * Determine if user can create a review
     */
    public function create(User $user): bool
    {
        return $user->role !== 'admin'; // Customers can review, staff cannot
    }

    /**
     * Determine if user can update a review
     */
    public function update(User $user, Review $review): bool
    {
        return $user->user_id === $review->customer_id;
    }

    /**
     * Determine if user can delete a review
     */
    public function delete(User $user, Review $review): bool
    {
        return $user->role === 'admin' || $user->user_id === $review->customer_id;
    }
}
