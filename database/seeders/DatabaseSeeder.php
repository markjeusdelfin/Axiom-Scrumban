<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tasks;
use App\Models\Sprints;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a base Organization and Board UUID
        $orgId = Str::uuid();
        $boardId = Str::uuid();

        // 2. Create an Active Sprint
        $sprint = Sprints::create([
            'id' => Str::uuid(),
            'board_id' => $boardId,
            'organization_id' => $orgId,
            'name' => 'Q1 Main Sprint',
            'start_date' => now(),
            'end_date' => now()->addDays(14),
            'status' => 'active',
        ]);

        // 3. Create Employees with different workloads to show bottlenecks
        $team = [
            ['name' => 'Alex Rivera', 'count' => 3],
            ['name' => 'Sam Chen', 'count' => 9], // This will show as 'Overloaded'
            ['name' => 'Jordan Smith', 'count' => 5],
        ];

        foreach ($team as $member) {
            $user = User::create([
                'name' => $member['name'],
                'email' => strtolower(str_replace(' ', '.', $member['name'])) . '@example.com',
                'password' => bcrypt('password'),
            ]);

            // 4. Assign tasks to the user within the sprint
            for ($i = 0; $i < $member['count']; $i++) {
                Tasks::create([
                    'id' => Str::uuid(),
                    'column_id' => Str::uuid(), // Represents the current status/column
                    'organization_id' => $orgId,
                    'sprint_id' => $sprint->id,
                    'user_id' => $user->id,
                    'title' => 'Complete Dashboard task #' . ($i + 1),
                    'description' => 'Working on the summary view implementation.',
                    'position' => $i,
                    'metadata' => json_encode(['priority' => 'medium']),
                ]);
            }
        }
    }
}
