<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class CleanupTestUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:test-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete test users (email contains "test" or ends with "@example.com") and related records';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Searching for test users...');

        $users = User::where('email', 'ilike', '%test%')
            ->orWhere('email', 'ilike', '%@example.com')
            ->orWhere('name', 'ilike', '%test%')
            ->get();

        if ($users->isEmpty()) {
            $this->info('No test users found.');
            return 0;
        }

        $userIds = $users->pluck('user_id')->all();

        $this->info('Found ' . count($users) . ' test user(s):');
        foreach ($users as $u) {
            $this->line(" - {$u->user_id}  {$u->email}  ({$u->name})");
        }

        // Gather counts of related records
        $counts = [
            'bookings' => DB::table('bookings')->whereIn('user_id', $userIds)->orWhereIn('staff_id', $userIds)->count(),
            'payments' => DB::table('payments')->whereIn('booking_id', function ($q) use ($userIds) {
                $q->select('booking_id')->from('bookings')->whereIn('user_id', $userIds)->orWhereIn('staff_id', $userIds);
            })->count(),
            'chat_messages' => DB::table('chat_messages')->whereIn('sender_id', $userIds)->orWhereIn('receiver_id', $userIds)->count(),
            'reviews' => DB::table('reviews')->whereIn('customer_id', $userIds)->orWhereIn('staff_id', $userIds)->count(),
            'notifications' => DB::table('notifications')->whereIn('user_id', $userIds)->count(),
            'promo_code_usages' => DB::table('promo_code_usage')->whereIn('user_id', $userIds)->count(),
            'staff_profiles' => DB::table('staff_profiles')->whereIn('user_id', $userIds)->count(),
        ];

        $this->info('Related records counts:');
        foreach ($counts as $k => $v) {
            $this->line(" - {$k}: {$v}");
        }

        if (! $this->confirm('Proceed to delete these users and all related records? This cannot be undone.')) {
            $this->info('Aborted. No changes made.');
            return 1;
        }

        DB::transaction(function () use ($userIds) {
            // Deleting users will cascade in many tables due to foreign keys
            DB::table('users')->whereIn('user_id', $userIds)->delete();
        });

        $this->info('Deletion completed.');
        return 0;
    }
}
