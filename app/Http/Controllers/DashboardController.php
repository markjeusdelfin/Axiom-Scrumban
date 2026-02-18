<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sprints;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $sprintId = $request->query('sprint_id');
        $userId = $request->query('user_id');
        $status = $request->query('status');
        $sortBy = $request->query('sort', 'created_at'); // Default sort

        // 1. Fetch Sprints & Users for dropdown filters
        $sprints = \App\Models\Sprints::orderBy('start_date', 'desc')->get();
        $allUsers = \App\Models\User::all();

        // 2. Filtered Stats for Charts
        $statusCounts = \App\Models\Tasks::query()
            ->when($sprintId, fn($q) => $q->where('sprint_id', $sprintId))
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->selectRaw('count(*) as count, column_id')
            ->groupBy('column_id')
            ->get();

        // 3. Employee Workload with dynamic filtering
        $employees = \App\Models\User::withCount(['tasks as active_tasks' => function ($query) use ($sprintId, $status) {
            if ($sprintId) $query->where('sprint_id', $sprintId);
            if ($status) $query->where('column_id', $status);
        }])
            ->orderBy($sortBy === 'workload' ? 'active_tasks' : 'name', 'desc')
            ->get();

        return view('dashboard.summary', compact('sprints', 'allUsers', 'employees', 'statusCounts'));
    }
}
