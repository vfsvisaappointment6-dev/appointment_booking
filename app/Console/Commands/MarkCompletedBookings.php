<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class MarkCompletedBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:mark-completed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically mark past confirmed/rescheduled bookings as completed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Find all confirmed/rescheduled bookings whose date/time has passed
        $completedCount = Booking::whereIn('status', ['confirmed', 'rescheduled'])
            ->where(function($query) use ($now) {
                $query->where('date', '<', $now->toDateString())
                    ->orWhere(function($q) use ($now) {
                        $q->where('date', '=', $now->toDateString())
                          ->whereRaw('CAST(time as TIME) < ?', [$now->toTimeString()]);
                    });
            })
            ->update(['status' => 'completed']);

        $this->info("Marked {$completedCount} bookings as completed.");

        return Command::SUCCESS;
    }
}
