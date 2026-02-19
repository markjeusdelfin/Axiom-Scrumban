<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use DB;

class TaskCommentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('task_comments')->insert([
            'task_id' => Task::first()->id,
            'user_id' => User::first()->id,
            'body' => 'Initial task comment.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
