<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Alex Rivera',
                'email' => 'alex.rivera@example.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Sam Chen',
                'email' => 'sam.chen@example.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Jordan Smith',
                'email' => 'jordan.smith@example.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Casey Morgan',
                'email' => 'casey.morgan@example.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Parker Lee',
                'email' => 'parker.lee@example.com',
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
