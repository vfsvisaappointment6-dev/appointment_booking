<?php

namespace App\Listeners;

use App\Events\ReviewSubmitted;

class UpdateStaffRating
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ReviewSubmitted $event): void
    {
        // Update staff profile rating based on new review
        if ($event->review->staff_id && $event->review->staff) {
            $staffProfile = $event->review->staff->staffProfile;
            if ($staffProfile) {
                $averageRating = $event->review->staff->receivedReviews()->avg('rating');
                $staffProfile->update(['rating' => $averageRating]);
            }
        }
    }
}
