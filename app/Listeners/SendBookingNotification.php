<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Models\Notification;

class SendBookingNotification
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
    public function handle(BookingCreated $event): void
    {
        $booking = $event->booking;

        // Notify customer about booking confirmation
        Notification::create([
            'notification_id' => \Illuminate\Support\Str::uuid(),
            'user_id' => $booking->user_id,
            'type' => 'sms',
            'message' => "Your booking for {$booking->service->name} on {$booking->date} has been confirmed.",
            'status' => 'sent',
        ]);

        // Notify staff about new booking assignment
        if ($booking->staff_id) {
            Notification::create([
                'notification_id' => \Illuminate\Support\Str::uuid(),
                'user_id' => $booking->staff_id,
                'type' => 'sms',
                'message' => "You have been assigned a new booking for {$booking->service->name} on {$booking->date}.",
                'status' => 'sent',
            ]);
        }
    }
}
