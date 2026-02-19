<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::where('role', 'manager')->first();

        Project::create([
            'name' => 'Project Alpha',
            'description' => 'Main project for task management',
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'owner_id' => $manager->id,
        ]);
    }
}
