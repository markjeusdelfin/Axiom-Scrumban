<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;
use DB;

class ProjectMemberSeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::first();
        $employees = User::where('role', 'employee')->get();

        foreach ($employees as $employee) {
            DB::table('project_members')->insert([
                'project_id' => $project->id,
                'user_id' => $employee->id,
                'role' => 'member',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
