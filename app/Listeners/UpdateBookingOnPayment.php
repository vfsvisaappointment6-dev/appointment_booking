<?php

namespace App\Listeners;

use App\Events\PaymentProcessed;

class UpdateBookingOnPayment
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
    public function handle(PaymentProcessed $event): void
    {
        // Update booking payment status to 'paid' if payment successful
        if ($event->payment->status === 'success') {
            $event->payment->booking->update(['payment_status' => 'paid']);
        }
    }
}
