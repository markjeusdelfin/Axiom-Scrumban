<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScrumbanController extends Controller
{
    public function getSummary()
    {
        try {
            // 1. Fetch Users and calculate their 'load'
            $team = DB::table('users')
                ->select('users.id', 'users.name', DB::raw('count(tasks.id) as task_count'))
                ->leftJoin('tasks', 'users.id', '=', 'tasks.user_id')
                ->groupBy('users.id', 'users.name')
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'task_count' => $user->task_count,
                        'load' => min(100, ($user->task_count / 5) * 100)
                    ];
                });

            // 2. Calculate Late Tasks - tasks in sprints that have ended
            $lateTasks = DB::table('tasks')
                ->join('sprints', 'tasks.sprint_id', '=', 'sprints.id')
                ->where('sprints.end_date', '<', now())
                ->where('sprints.status', '!=', 'completed')
                ->count();

            // 3. Sprint Momentum (Tasks per sprint for the last 6 sprints)
            $momentum = DB::table('sprints')
                ->leftJoin('tasks', 'sprints.id', '=', 'tasks.sprint_id')
                ->select('sprints.id', 'sprints.name', DB::raw('count(tasks.id) as total_tasks'))
                ->groupBy('sprints.id', 'sprints.name')
                ->orderBy('sprints.created_at', 'desc')
                ->limit(6)
                ->get()
                ->sortBy('created_at')
                ->pluck('total_tasks')
                ->values()
                ->toArray();

            // Pad momentum data to 6 values
            while (count($momentum) < 6) {
                array_unshift($momentum, 0);
            }

            // 4. Priority Distribution - safe handling of JSONB metadata
            $priorityHigh = DB::table('tasks')
                ->where('metadata', '!=', null)
                ->whereRaw("metadata->>'priority' = 'high'")
                ->count();
            $priorityMed = DB::table('tasks')
                ->where('metadata', '!=', null)
                ->whereRaw("metadata->>'priority' = 'medium'")
                ->count();
            $priorityLow = DB::table('tasks')
                ->where('metadata', '!=', null)
                ->whereRaw("metadata->>'priority' = 'low'")
                ->count();

            $priorityData = [$priorityHigh, $priorityMed, $priorityLow];

            // Calculate average utilization
            $avgUtilization = $team->count() > 0 ? round($team->avg('load') ?? 0) : 0;

            return response()->json([
                'success' => true,
                'stats' => [
                    'utilization' => $avgUtilization,
                    'lateTasks' => $lateTasks,
                    'momentum' => array_slice($momentum, -6),
                    'priorityData' => $priorityData
                ],
                'team' => $team->values()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'stats' => [
                    'utilization' => 0,
                    'lateTasks' => 0,
                    'momentum' => [0, 0, 0, 0, 0, 0],
                    'priorityData' => [0, 0, 0]
                ],
                'team' => []
            ], 500);
        }
    }
}
