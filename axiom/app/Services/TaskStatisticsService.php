<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use Illuminate\Support\Collection;

/**
 * TaskStatisticsService - Calculates task statistics and aggregations
 * Follows: Single Responsibility Principle (statistics only)
 * Follows: Dependency Inversion (depends on TaskRepository abstraction)
 */
class TaskStatisticsService
{
    public function __construct(private TaskRepository $taskRepository)
    {
    }

    /**
     * Get comprehensive task statistics
     */
    public function getTaskStatistics(): array
    {
        $allTasks = $this->taskRepository->getAllWithRelations();

        return [
            'total_tasks' => $allTasks->count(),
            'completed_tasks' => $allTasks->where('status', 'completed')->count(),
            'in_progress_tasks' => $allTasks->where('status', 'in_progress')->count(),
            'not_started_tasks' => $allTasks->where('status', 'not_started')->count(),
            'blocked_tasks' => $allTasks->where('status', 'blocked')->count(),
            'overdue_tasks' => $this->countOverdueTasks($allTasks),
            'completion_rate' => $this->calculateCompletionRate($allTasks),
            'average_progress' => $this->calculateAverageProgress($allTasks),
        ];
    }

    /**
     * Get task distribution by status
     */
    public function getTasksByStatus(): array
    {
        $tasks = $this->taskRepository->getAllWithRelations();

        return [
            'not_started' => $tasks->where('status', 'not_started')->count(),
            'in_progress' => $tasks->where('status', 'in_progress')->count(),
            'blocked' => $tasks->where('status', 'blocked')->count(),
            'completed' => $tasks->where('status', 'completed')->count(),
        ];
    }

    /**
     * Get task distribution by priority
     */
    public function getTasksByPriority(): array
    {
        $tasks = $this->taskRepository->getAllWithRelations();

        return [
            'high' => $tasks->where('priority', 'high')->count(),
            'medium' => $tasks->where('priority', 'medium')->count(),
            'low' => $tasks->where('priority', 'low')->count(),
        ];
    }

    /**
     * Count overdue tasks
     */
    private function countOverdueTasks(Collection $tasks): int
    {
        return $tasks->filter(function ($task) {
            return $task->due_date < now() && $task->status !== 'completed';
        })->count();
    }

    /**
     * Calculate overall completion rate
     */
    private function calculateCompletionRate(Collection $tasks): float
    {
        if ($tasks->isEmpty()) {
            return 0;
        }

        $completed = $tasks->where('status', 'completed')->count();
        return round(($completed / $tasks->count()) * 100, 2);
    }

    /**
     * Calculate average progress percentage
     */
    private function calculateAverageProgress(Collection $tasks): float
    {
        if ($tasks->isEmpty()) {
            return 0;
        }

        return round($tasks->avg('progress_percent'), 2);
    }

    /**
     * Get tasks by status with details
     */
    public function getStatusBreakdown(): array
    {
        $tasks = $this->taskRepository->getAllWithRelations();
        $breakdown = [];

        $statuses = ['not_started', 'in_progress', 'blocked', 'completed'];

        foreach ($statuses as $status) {
            $statusTasks = $tasks->where('status', $status);
            $breakdown[$status] = [
                'count' => $statusTasks->count(),
                'percentage' => $tasks->count() > 0 ? round(($statusTasks->count() / $tasks->count()) * 100, 2) : 0,
            ];
        }

        return $breakdown;
    }

    /**
     * Get high-priority tasks
     */
    public function getHighPriorityTasks(int $limit = 10): array
    {
        return $this->taskRepository->getAllWithRelations()
            ->where('priority', 'high')
            ->whereNotIn('status', ['completed'])
            ->sortBy('due_date')
            ->take($limit)
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'assignee' => $task->assignee?->name,
                    'due_date' => $this->formatDate($task->due_date),
                    'status' => $task->status,
                    'project' => $task->project?->name,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * Format date string
     */
    private function formatDate($value): ?string
    {
        if (is_null($value)) {
            return null;
        }
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d');
        }
        return \Carbon\Carbon::parse($value)->format('Y-m-d');
    }
}
