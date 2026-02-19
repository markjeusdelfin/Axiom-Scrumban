<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::first();
        $employee = User::where('role', 'employee')->first();

        Task::create([
            'project_id' => $project->id,
            'assigned_to' => $employee->id,
            'title' => 'Design Database Schema',
            'description' => 'Create ERD and migrations',
            'status' => 'in_progress',
            'priority' => 'high',
            'progress_percent' => 40,
            'start_date' => now(),
            'due_date' => now()->addWeek(),
        ]);
    }
}
