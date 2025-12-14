<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $email = 'eli@gmail.com';

        $user = User::where('email', $email)->first();
        if (!$user) {
            User::create([
                'user_id' => \Illuminate\Support\Str::uuid(),
                'name' => 'Eli Admin',
                'email' => $email,
                'phone' => '0000000000',
                'password' => Hash::make('eli@admin'),
                'role' => 'admin',
            ]);
        }
    }
}
