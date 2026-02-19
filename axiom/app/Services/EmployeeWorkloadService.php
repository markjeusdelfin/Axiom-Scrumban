<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Support\Collection;

/**
 * EmployeeWorkloadService - Calculates employee workload and capacity
 * Follows: Single Responsibility Principle (workload calculations only)
 * Follows: Dependency Inversion (depends on TaskRepository abstraction)
 */
class EmployeeWorkloadService
{
    public function __construct(private TaskRepository $taskRepository)
    {
    }

    /**
     * Get workload for all employees
     */
    public function getAllEmployeeWorkload(): array
    {
        $employees = User::whereNotIn('role', ['admin'])->get();

        return $employees->map(function ($employee) {
            return $this->getEmployeeWorkloadDetails($employee);
        })->values()->all();
    }

    /**
     * Get detailed workload for a specific employee
     */
    public function getEmployeeWorkloadDetails(User $employee): array
    {
        $tasks = $this->taskRepository->getTasksByUser($employee);

        $activeTasks = $tasks->whereNotIn('status', ['completed']);
        $overdueTasks = $activeTasks->filter(fn($t) => $t->due_date < now());

        $totalHours = 0;
        foreach ($tasks as $task) {
            $totalHours += $task->timeLogs->sum('hours');
        }

        return [
            'id' => $employee->id,
            'name' => $employee->name,
            'email' => $employee->email,
            'role' => $employee->role,
            'total_tasks' => $tasks->count(),
            'active_tasks' => $activeTasks->count(),
            'completed_tasks' => $tasks->where('status', 'completed')->count(),
            'overdue_tasks' => $overdueTasks->count(),
            'in_progress' => $tasks->where('status', 'in_progress')->count(),
            'blocked_tasks' => $tasks->where('status', 'blocked')->count(),
            'average_priority' => $this->calculateAveragePriority($tasks),
            'workload_percentage' => $this->calculateWorkloadPercentage($activeTasks),
            'total_logged_hours' => round($totalHours, 2),
            'is_overloaded' => $activeTasks->count() > 5 || $overdueTasks->count() > 0,
            'bottleneck_level' => $this->calculateBottleneckLevel($activeTasks, $overdueTasks),
        ];
    }

    /**
     * Get employees with critical workload (bottlenecks)
     */
    public function getBottlenecks(): array
    {
        $allWorkload = $this->getAllEmployeeWorkload();

        return array_filter($allWorkload, function ($workload) {
            return $workload['is_overloaded'] || $workload['bottleneck_level'] === 'critical';
        });
    }

    /**
     * Get employee capacity summary
     */
    public function getCapacitySummary(): array
    {
        $allWorkload = $this->getAllEmployeeWorkload();

        $healthyLoad = array_filter($allWorkload, fn($w) => $w['bottleneck_level'] === 'healthy');
        $moderateLoad = array_filter($allWorkload, fn($w) => $w['bottleneck_level'] === 'moderate');
        $criticalLoad = array_filter($allWorkload, fn($w) => $w['bottleneck_level'] === 'critical');

        return [
            'total_employees' => count($allWorkload),
            'healthy_capacity' => count($healthyLoad),
            'moderate_capacity' => count($moderateLoad),
            'critical_capacity' => count($criticalLoad),
            'average_workload' => round(array_sum(array_column($allWorkload, 'active_tasks')) / max(count($allWorkload), 1), 2),
        ];
    }

    /**
     * Calculate bottleneck level for an employee
     */
    private function calculateBottleneckLevel(Collection $activeTasks, Collection $overdueTasks): string
    {
        if ($overdueTasks->count() > 2 || $activeTasks->count() > 8) {
            return 'critical';
        }

        if ($overdueTasks->count() > 0 || $activeTasks->count() > 5) {
            return 'moderate';
        }

        return 'healthy';
    }

    /**
     * Calculate workload percentage (0-100)
     */
    private function calculateWorkloadPercentage(Collection $activeTasks): int
    {
        $workload = $activeTasks->count();

        // Assume 10 active tasks is 100% capacity
        return min(100, round(($workload / 10) * 100));
    }

    /**
     * Calculate average priority (1 = high, 2 = medium, 3 = low)
     */
    private function calculateAveragePriority(Collection $tasks): string
    {
        if ($tasks->isEmpty()) {
            return 'none';
        }

        $priorityMap = ['high' => 1, 'medium' => 2, 'low' => 3];
        $sum = 0;
        $count = 0;

        foreach ($tasks as $task) {
            $sum += $priorityMap[$task->priority] ?? 2;
            $count++;
        }

        $average = $count > 0 ? round($sum / $count) : 2;

        return match ($average) {
            1 => 'high',
            2 => 'medium',
            3 => 'low',
            default => 'medium',
        };
    }

    /**
     * Get workload trends over time
     */
    public function getWorkloadTrend(User $employee, int $days = 30): array
    {
        $tasks = $this->taskRepository->getTasksByUser($employee);

        $trend = [];
        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $activeTasks = $tasks->filter(function ($task) use ($date) {
                $createdAt = $this->getDatetime($task->created_at);
                $completedAt = $task->completed_at ? $this->getDatetime($task->completed_at) : null;
                
                return $createdAt->format('Y-m-d') <= $date
                    && ($completedAt === null || $completedAt->format('Y-m-d') >= $date);
            });

            $trend[] = [
                'date' => $date,
                'active_tasks' => $activeTasks->count(),
            ];
        }

        return $trend;
    }

    /**
     * Convert to Carbon instance if needed
     */
    private function getDatetime($value)
    {
        if (is_null($value)) {
            return null;
        }
        if ($value instanceof \DateTime) {
            return $value;
        }
        return \Carbon\Carbon::parse($value);
    }
}
