<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use DB;

class TaskDependencySeeder extends Seeder
{
    public function run(): void
    {
        $tasks = Task::take(2)->get();

        if ($tasks->count() === 2) {
            DB::table('task_dependencies')->insert([
                'task_id' => $tasks[1]->id,
                'depends_on_task_id' => $tasks[0]->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
