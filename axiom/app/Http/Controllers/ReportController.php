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
            ->limit(10)
            ->get();

        return inertia('Reports/Analytics', compact(
            'totalTasks', 'completedTasks', 'overdueTasks',
            'byStatus', 'byPriority', 'completionRate', 'recentActivity'
        ));
    }
}
