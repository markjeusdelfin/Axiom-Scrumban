<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;

/**
 * TaskRepository - Handles all data retrieval for tasks
 * Follows: Single Responsibility Principle (data access only)
 * Follows: Dependency Inversion (abstracts data access from business logic)
 */
class TaskRepository
{
    /**
     * Get all tasks with relationships
     */
    public function getAllWithRelations(): Collection
    {
        return Task::with(['project', 'assignee', 'timeLogs'])
            ->get();
    }

    /**
     * Get tasks assigned to a specific user
     */
    public function getTasksByUser(User $user): Collection
    {
        return Task::where('assigned_to', $user->id)
            ->with(['project', 'assignee'])
            ->get();
    }

    /**
     * Get overdue tasks
     */
    public function getOverdueTasks(): Collection
    {
        return Task::where('due_date', '<', now())
            ->whereNotIn('status', ['completed'])
            ->with(['project', 'assignee'])
            ->get();
    }

    /**
     * Get tasks by status
     */
    public function getTasksByStatus(string $status): Collection
    {
        return Task::where('status', $status)
            ->with(['project', 'assignee', 'timeLogs'])
            ->get();
    }

    /**
     * Get tasks by priority
     */
    public function getTasksByPriority(string $priority): Collection
    {
        return Task::where('priority', $priority)
            ->with(['project', 'assignee'])
            ->get();
    }

    /**
     * Get filtered tasks based on criteria
     */
    public function getFiltered(array $filters): Collection
    {
        $query = Task::query();

        if (isset($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        if (isset($filters['status']) && !empty($filters['status'])) {
            $query->whereIn('status', (array) $filters['status']);
        }

        if (isset($filters['priority']) && !empty($filters['priority'])) {
            $query->whereIn('priority', (array) $filters['priority']);
        }

        if (isset($filters['project_id'])) {
            $query->where('project_id', $filters['project_id']);
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('due_date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('due_date', '<=', $filters['date_to']);
        }

        if (isset($filters['overdue_only']) && $filters['overdue_only']) {
            $query->where('due_date', '<', now())
                ->whereNotIn('status', ['completed']);
        }

        return $query->with(['project', 'assignee', 'timeLogs'])
            ->get();
    }

    /**
     * Get tasks with time logs aggregation
     */
    public function getWithTimeLogsMetrics(): Collection
    {
        return Task::with(['assignee', 'project', 'timeLogs'])
            ->get();
    }
}
