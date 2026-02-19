<?php

namespace App\Services;

use App\Repositories\TaskRepository;

/**
 * TaskFilteringService - Handles all filtering logic for tasks
 * Follows: Single Responsibility Principle (filtering only)
 * Follows: Dependency Inversion (depends on TaskRepository abstraction)
 */
class TaskFilteringService
{
    public function __construct(private TaskRepository $taskRepository) {}

    /**
     * Apply filters to tasks
     */
    public function filterTasks(array $filters): array
    {
        $tasks = $this->taskRepository->getFiltered($filters);

        // Sort if requested
        if (isset($filters['sort_by'])) {
            $tasks = $this->sortTasks($tasks, $filters['sort_by'], $filters['sort_direction'] ?? 'asc');
        }

        // Map to simple array format
        return $tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'project_id' => $task->project_id,
                'project_name' => $task->project?->name,
                'assignee_id' => $task->assigned_to,
                'assignee_name' => $task->assignee?->name,
                'status' => $task->status,
                'priority' => $task->priority,
                'progress_percent' => $task->progress_percent,
                'start_date' => $this->formatDate($task->start_date),
                'due_date' => $this->formatDate($task->due_date),
                'is_overdue' => $task->due_date && $this->getDatetime($task->due_date) < now() && $task->status !== 'completed',
                'completed_at' => $this->formatDate($task->completed_at),
                'created_at' => $this->formatDateTime($task->created_at),
                'updated_at' => $this->formatDateTime($task->updated_at),
            ];
        })->values()->all();
    }

    /**
     * Get available filter options
     */
    public function getFilterOptions(): array
    {
        return [
            'statuses' => ['not_started', 'in_progress', 'blocked', 'completed'],
            'priorities' => ['high', 'medium', 'low'],
            'employees' => $this->getEmployeeOptions(),
        ];
    }

    /**
     * Sort tasks by specified field
     */
    public function sortTasks($tasks, string $sortBy, string $direction = 'asc')
    {
        $reverse = $direction === 'desc';

        return match ($sortBy) {
            'due_date' => $tasks->sortBy('due_date', SORT_REGULAR, $reverse),
            'priority' => $tasks->sort(function ($a, $b) use ($reverse) {
                $priorityMap = ['high', 'medium', 'low'];
                $aIdx = array_search($a->priority, $priorityMap);
                $bIdx = array_search($b->priority, $priorityMap);
                $result = $aIdx <=> $bIdx;
                return $reverse ? -$result : $result;
            }),
            'progress' => $tasks->sortBy('progress_percent', SORT_REGULAR, $reverse),
            'title' => $tasks->sortBy('title', SORT_REGULAR, $reverse),
            'assignee' => $tasks->sortBy(fn($t) => $t->assignee?->name, SORT_REGULAR, $reverse),
            default => $tasks->sortBy('created_at', SORT_REGULAR, $reverse),
        };
    }

    /**
     * Get employee options for filtering
     */
    private function getEmployeeOptions(): array
    {
        return \App\Models\User::where('role', '!=', 'admin')
            ->select('id', 'name')
            ->get()
            ->map(fn($user) => ['id' => $user->id, 'name' => $user->name])
            ->all();
    }

    /**
     * Convert string to datetime if needed
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

    /**
     * Format datetime string
     */
    private function formatDateTime($value): ?string
    {
        if (is_null($value)) {
            return null;
        }
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d H:i');
        }
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i');
    }

    /**
     * Get high priority tasks that need attention
     */
    public function getCriticalTasks(): array
    {
        return $this->filterTasks([
            'priority' => ['high'],
            'status' => ['not_started', 'in_progress', 'blocked'],
            'overdue_only' => false,
            'sort_by' => 'due_date',
            'sort_direction' => 'asc',
        ]);
    }

    /**
     * Get overdue tasks
     */
    public function getOverdueTasks(): array
    {
        return $this->filterTasks([
            'overdue_only' => true,
            'sort_by' => 'due_date',
            'sort_direction' => 'asc',
        ]);
    }

    /**
     * Get tasks due soon (within next 7 days)
     */
    public function getUpcomingTasks(int $daysAhead = 7): array
    {
        $tasks = $this->taskRepository->getAllWithRelations();

        $today = now();
        $ahead = now()->addDays($daysAhead);

        $upcoming = $tasks->filter(function ($task) use ($today, $ahead) {
            return $task->due_date &&
                $task->due_date >= $today &&
                $task->due_date <= $ahead &&
                $task->status !== 'completed';
        });

        return $this->sortTasks($upcoming, 'due_date', 'asc')
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'project_name' => $task->project?->name,
                    'assignee_name' => $task->assignee?->name,
                    'due_date' => $this->formatDate($task->due_date),
                    'priority' => $task->priority,
                    'progress_percent' => $task->progress_percent,
                ];
            })
            ->values()
            ->all();
    }
}
