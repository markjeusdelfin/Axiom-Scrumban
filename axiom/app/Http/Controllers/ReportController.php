<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function workload()
    {
        $workload = User::withCount([
            'tasks as total_tasks',
            'tasks as overdue_tasks' => fn($q) => $q
                ->where('due_date', '<', now())
                ->whereNotIn('status', ['completed']),
            'tasks as in_progress_tasks' => fn($q) => $q->where('status', 'in_progress'),
        ])->get();

        return inertia('Reports/Workload', compact('workload'));
    }

    public function analytics()
    {
        $totalTasks     = Task::count();
        $completedTasks = Task::where('status', 'completed')->count();
        $overdueTasks   = Task::where('due_date', '<', now())->where('status', '!=', 'completed')->count();

        $byStatus = Task::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $byPriority = Task::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->pluck('count', 'priority');

        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

        $recentActivity = Task::where('updated_at', '>=', now()->subDays(7))
            ->with('project', 'assignee')
            ->latest('updated_at')
            ->limit(20)
            ->get();

        // Get team members with workload calculation
        $teamMembers = User::whereNotNull('id')
            ->with(['tasks' => function($q) {
                $q->whereIn('status', ['not_started', 'in_progress', 'blocked']);
            }])
            ->get()
            ->map(function($user) {
                $activeTasks = $user->tasks->count();
                $overdueTasks = $user->tasks->filter(fn($t) => $t->due_date < now())->count();
                $totalCapacity = 10; // Assume 10 tasks is 100% capacity
                $workload = min(100, round(($activeTasks / $totalCapacity) * 100));
                
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'task_count' => $activeTasks,
                    'overdue' => $overdueTasks,
                    'workload' => $workload,
                ];
            });

        $teamStats = [
            'utilization' => round($totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0, 1),
            'lateTasks' => $overdueTasks,
        ];

        // Per-project completion metrics
        $projectsCompletion = Project::withCount([
            'tasks as total_tasks',
            'tasks as completed_tasks' => fn($q) => $q->where('status', 'completed'),
        ])->get()->map(function($p) {
            $total = $p->total_tasks ?? 0;
            $done = $p->completed_tasks ?? 0;
            return [
                'id' => $p->id,
                'name' => $p->name,
                'total_tasks' => $total,
                'completed_tasks' => $done,
                'completion_rate' => $total > 0 ? round(($done / $total) * 100, 1) : 0,
            ];
        });

        return inertia('Reports/Analytics', compact(
            'totalTasks', 'completedTasks', 'overdueTasks',
            'byStatus', 'byPriority', 'completionRate', 'recentActivity',
            'teamMembers', 'teamStats', 'projectsCompletion'
        ));
    }
}
