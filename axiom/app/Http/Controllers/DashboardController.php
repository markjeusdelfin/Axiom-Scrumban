<?php

namespace App\Http\Controllers;

use App\Services\TaskStatisticsService;
use App\Services\EmployeeWorkloadService;
use App\Services\TaskFilteringService;
use Inertia\Inertia;
use Illuminate\Http\Request;

/**
 * DashboardController - Orchestrates dashboard data
 * Follows: Single Responsibility Principle (orchestration only)
 * Follows: Dependency Inversion (uses injected services)
 */
class DashboardController extends Controller
{
    public function __construct(
        private TaskStatisticsService $statisticsService,
        private EmployeeWorkloadService $workloadService,
        private TaskFilteringService $filteringService
    ) {}

    /**
     * Display main dashboard
     */
    public function index()
    {
        $statistics = $this->statisticsService->getTaskStatistics();
        $statusBreakdown = $this->statisticsService->getStatusBreakdown();
        $priorityBreakdown = $this->statisticsService->getTasksByPriority();
        $workloadSummary = $this->workloadService->getCapacitySummary();
        $criticalTasks = $this->filteringService->getCriticalTasks();
        $upcomingTasks = $this->filteringService->getUpcomingTasks(7);
        $filterOptions = $this->filteringService->getFilterOptions();

        return Inertia::render('Dashboard/Index', [
            'statistics' => $statistics,
            'statusBreakdown' => $statusBreakdown,
            'priorityBreakdown' => $priorityBreakdown,
            'workloadSummary' => $workloadSummary,
            'criticalTasks' => $criticalTasks,
            'upcomingTasks' => $upcomingTasks,
            'filterOptions' => $filterOptions,
        ]);
    }

    /**
     * Display workload details for all employees
     */
    public function workload()
    {
        $employeeWorkloads = $this->workloadService->getAllEmployeeWorkload();
        $bottlenecks = $this->workloadService->getBottlenecks();
        $capacitySummary = $this->workloadService->getCapacitySummary();
        $filterOptions = $this->filteringService->getFilterOptions();

        return Inertia::render('Dashboard/Workload', [
            'employeeWorkloads' => $employeeWorkloads,
            'bottlenecks' => $bottlenecks,
            'capacitySummary' => $capacitySummary,
            'filterOptions' => $filterOptions,
        ]);
    }

    /**
     * Display task list with filtering
     */
    public function tasks(Request $request)
    {
        $filters = $request->validate([
            'assigned_to' => 'nullable|integer',
            'status' => 'nullable|array',
            'priority' => 'nullable|array',
            'project_id' => 'nullable|integer',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'overdue_only' => 'nullable|boolean',
            'sort_by' => 'nullable|string|in:due_date,priority,progress,title,assignee',
            'sort_direction' => 'nullable|string|in:asc,desc',
        ]);

        $tasks = $this->filteringService->filterTasks($filters);
        $filterOptions = $this->filteringService->getFilterOptions();

        return Inertia::render('Dashboard/Tasks', [
            'tasks' => $tasks,
            'filterOptions' => $filterOptions,
            'filters' => $filters,
        ]);
    }

    /**
     * Get dashboard data for API consumption
     */
    public function getStatics()
    {
        return response()->json([
            'statistics' => $this->statisticsService->getTaskStatistics(),
            'statusBreakdown' => $this->statisticsService->getStatusBreakdown(),
            'priorityBreakdown' => $this->statisticsService->getTasksByPriority(),
            'highPriorityTasks' => $this->statisticsService->getHighPriorityTasks(5),
            'timestamp' => now(),
        ]);
    }

    /**
     * Get workload data for API consumption
     */
    public function getWorkloadStatics()
    {
        return response()->json([
            'employeeWorkloads' => $this->workloadService->getAllEmployeeWorkload(),
            'bottlenecks' => $this->workloadService->getBottlenecks(),
            'capacitySummary' => $this->workloadService->getCapacitySummary(),
            'timestamp' => now(),
        ]);
    }
}
