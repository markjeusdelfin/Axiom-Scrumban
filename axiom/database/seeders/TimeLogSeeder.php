<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use DB;

class TimeLogSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('time_logs')->insert([
            'task_id' => Task::first()->id,
            'user_id' => User::where('role', 'employee')->first()->id,
            'hours' => 3.50,
            'description' => 'Database design work',
            'logged_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
