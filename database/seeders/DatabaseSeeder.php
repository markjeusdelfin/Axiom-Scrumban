<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with comprehensive test data.
     * 
     * Creates:
     * - 5 team members with different roles
     * - 6 sprints (5 completed, 1 active)
     * - 21 tasks distributed across team members
     * - Realistic workload distribution showing bottlenecks
     */
    public function run(): void
    {
        // Run seeders in order
        $this->call([
            UserSeeder::class,
            SprintSeeder::class,
            TaskSeeder::class,
        ]);

        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('   - Created 5 team members');
        $this->command->info('   - Created 6 sprints');
        $this->command->info('   - Created 21 tasks with realistic distribution');
        $this->command->info('   - Note: Sam Chen is overloaded with 9 tasks (90%+ utilization)');
    }
}
