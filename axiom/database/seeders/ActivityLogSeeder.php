<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use DB;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('activity_logs')->insert([
            'user_id' => User::first()->id,
            'subject_type' => Task::class,
            'subject_id' => Task::first()->id,
            'action' => 'created',
            'changes' => json_encode(['status' => 'not_started']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
