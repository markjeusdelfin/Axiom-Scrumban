<?php

namespace Database\Seeders;

use App\Models\Sprints;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SprintSeeder extends Seeder
{
    public function run(): void
    {
        $orgId = Str::uuid();
        $boardId = Str::uuid();

        // Create 6 sprints with different statuses
        $sprints = [
            [
                'name' => 'Sprint 1 - Foundation',
                'start_date' => now()->subDays(90),
                'end_date' => now()->subDays(75),
                'status' => 'completed',
            ],
            [
                'name' => 'Sprint 2 - Features',
                'start_date' => now()->subDays(75),
                'end_date' => now()->subDays(60),
                'status' => 'completed',
            ],
            [
                'name' => 'Sprint 3 - Dashboard',
                'start_date' => now()->subDays(60),
                'end_date' => now()->subDays(45),
                'status' => 'completed',
            ],
            [
                'name' => 'Sprint 4 - Optimization',
                'start_date' => now()->subDays(45),
                'end_date' => now()->subDays(30),
                'status' => 'completed',
            ],
            [
                'name' => 'Sprint 5 - Polish',
                'start_date' => now()->subDays(30),
                'end_date' => now()->subDays(15),
                'status' => 'completed',
            ],
            [
                'name' => 'Sprint 6 - Current',
                'start_date' => now()->subDays(15),
                'end_date' => now()->addDays(14),
                'status' => 'active',
            ],
        ];

        foreach ($sprints as $sprint) {
            Sprints::firstOrCreate(
                ['name' => $sprint['name']],
                [
                    'id' => Str::uuid(),
                    'board_id' => $boardId,
                    'organization_id' => $orgId,
                    'start_date' => $sprint['start_date'],
                    'end_date' => $sprint['end_date'],
                    'status' => $sprint['status'],
                ]
            );
        }
    }
}
