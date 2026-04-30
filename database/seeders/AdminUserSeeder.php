<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@scanyuk.com',
                'role' => User::ROLE_ADMIN,
                'password' => 'password123'
            ],
            [
                'name' => 'CS ScanYuk',
                'email' => 'cs@scanyuk.com',
                'role' => 'live_chat_admin', 
                'password' => 'password123'
            ],
            [
                'name' => 'User Free',
                'email' => 'free@scanyuk.com',
                'role' => User::ROLE_FREE,
                'password' => 'password123'
            ],
            [
                'name' => 'User Starter',
                'email' => 'starter@scanyuk.com',
                'role' => User::ROLE_STARTER,
                'password' => 'password123'
            ],
            [
                'name' => 'User Pro',
                'email' => 'pro@scanyuk.com',
                'role' => User::ROLE_PROFESSIONAL,
                'password' => 'password123'
            ],
            [
                'name' => 'User Business',
                'email' => 'business@scanyuk.com',
                'role' => User::ROLE_BUSINESS,
                'password' => 'password123'
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'role' => $userData['role'],
                    'password' => $userData['password'], 
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}